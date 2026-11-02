<?php

namespace App\Support\QueryBuilder\Filters;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Spatie\QueryBuilder\Filters\Filter;

/**
 * @template TModelClass of Model
 *
 * @implements Filter<TModelClass>
 */
class SearchFilter implements Filter
{
    /**
     * @param  array<string>  $searchables  Array of searchable columns or relationships
     */
    public function __construct(
        private array $searchables
    ) {}

    /**
     * Create a search filter for query builder.
     *
     * Supports searching across multiple columns and relationships.
     *
     * @param  Builder<TModelClass>  $query
     */
    public function __invoke(Builder $query, mixed $value, string $property): void
    {
        $query->where(function ($q) use ($value) {
            foreach ($this->searchables as $searchable) {
                [$relation, $columns] = $this->parseSearchable($searchable);

                if ($relation) {
                    $this->whereRelationLike($q, $relation, $columns, $value);
                } else {
                    foreach ($columns as $column) {
                        $this->whereLike($q, $column, $value);
                    }
                }
            }
        });
    }

    /**
     * Parse a searchable string into relation and columns.
     *
     * Examples:
     * - 'title' => [null, ['title']]
     * - 'customer.name' => ['customer', ['name']]
     * - 'customer.name,email' => ['customer', ['name', 'email']]
     *
     * @return array{0: ?string, 1: array<string>}
     */
    private function parseSearchable(string $searchable): array
    {
        if (str_contains($searchable, '.')) {
            $parts = explode('.', $searchable);
            $columnsPart = array_pop($parts);
            $relation = implode('.', $parts);
            $columns = explode(',', $columnsPart);

            return [$relation, $columns];
        }

        return [null, [$searchable]];
    }

    /**
     * Add an OR WHERE LIKE condition for a direct column search.
     *
     * @param  Builder<TModelClass>  $query
     */
    private function whereLike(Builder $query, string $column, string $value): void
    {
        $query->orWhere($column, 'like', "%{$value}%");
    }

    /**
     * Add an OR WHERE HAS condition for relationship column search.
     *
     * Searches across multiple columns in the related model using whereAny.
     *
     * @param  Builder<TModelClass>  $query
     * @param  array<string>  $columns
     */
    private function whereRelationLike(Builder $query, string $relation, array $columns, string $value): void
    {
        $query->orWhereHas($relation, fn ($q) => $q->whereAny($columns, 'like', "%{$value}%"));
    }
}
