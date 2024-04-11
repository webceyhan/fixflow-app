<?php

namespace App\Models;

use App\Enums\Priority;
use Database\Factories\TicketFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

/**
 * @property int $id
 * @property string $description
 * @property Priority $priority
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * 
 * @method static TicketFactory factory(int $count = null, array $state = [])
 * @method static Builder|static ofPriority(Priority $priority)
 * @method static Builder|static prioritized()
 */
class Ticket extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'description',
        'priority',
    ];

    /**
     * The model's default values for attributes.
     *
     * @var array
     */
    protected $attributes = [
        'priority' => Priority::Normal,
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'priority' => Priority::class,
        ];
    }

    // SCOPES //////////////////////////////////////////////////////////////////////////////////////

    /**
     * Scope a query to only include models of a given priority.
     */
    public function scopeOfPriority(Builder $query, Priority $priority): void
    {
        $query->where('priority', $priority->value);
    }

    /**
     * Scope a query to order models by priority from high to low.
     */
    public function scopePrioritized(Builder $query): void
    {
        $query->orderBy('priority', 'desc');
    }
}
