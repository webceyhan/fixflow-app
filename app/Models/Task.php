<?php

namespace App\Models;

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
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * 
 * @method static TaskFactory factory(int $count = null, array $state = [])
 * @method static Builder|static billable()
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
    ];

    /**
     * The model's default values for attributes.
     *
     * @var array
     */
    protected $attributes = [
        'cost' => 0,
        'is_billable' => true,
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
}
