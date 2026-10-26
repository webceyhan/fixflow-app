<?php

namespace App\Models\Concerns;

use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @template TModel of Model
 *
 * @mixin TModel
 */
trait Assignable
{
    private const ASSIGNEE_ID = 'assignee_id';

    /**
     * Determine if the model is assigned.
     */
    public function isAssigned(): bool
    {
        return $this->{self::ASSIGNEE_ID} !== null;
    }

    // RELATIONS ///////////////////////////////////////////////////////////////////////////////////

    /**
     * Get the assignee (user) for the model.
     *
     * @return BelongsTo<User, TModel>
     */
    public function assignee(): BelongsTo
    {
        return $this->belongsTo(User::class, self::ASSIGNEE_ID);
    }

    // SCOPES //////////////////////////////////////////////////////////////////////////////////////

    /**
     * Scope a query to only include models that are already assigned.
     *
     * @param  Builder<TModel>  $query
     */
    public function scopeAssigned(Builder $query): void
    {
        $query->whereNotNull(self::ASSIGNEE_ID);
    }

    /**
     * Scope a query to only include models that are unassigned.
     *
     * @param  Builder<TModel>  $query
     */
    public function scopeUnassigned(Builder $query): void
    {
        $query->whereNull(self::ASSIGNEE_ID);
    }
}
