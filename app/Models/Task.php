<?php

namespace App\Models;

use App\Enums\TaskType;
use Database\Factories\TaskFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

/**
 * @property int $id
 * @property string $description
 * @property float $cost
 * @property bool $is_billable
 * @property TaskType $type
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * 
 * @method static TaskFactory factory(int $count = null, array $state = [])
 * @method static Builder|static billable()
 * @method static Builder|static ofType(TaskType $type)
 */
class Task extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'description',
        'cost',
        'is_billable',
        'type',
    ];

    /**
     * The model's default values for attributes.
     *
     * @var array
     */
    protected $attributes = [
        'cost' => 0,
        'is_billable' => true,
        'type' => TaskType::Repair,
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'cost' => 'float',
            'is_billable' => 'boolean',
            'type' => TaskType::class,
        ];
    }

    // SCOPES //////////////////////////////////////////////////////////////////////////////////////

    /**
     * Scope a query to only include billable tasks.
     */
    public function scopeBillable(Builder $query): void
    {
        $query->where('is_billable', true);
    }

    /**
     * Scope a query to only include tasks of a given type.
     */
    public function scopeOfType(Builder $query, TaskType $type): void
    {
        $query->where('type', $type->value);
    }
}
