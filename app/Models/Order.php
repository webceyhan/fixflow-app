<?php

namespace App\Models;

use App\Enums\OrderStatus;
use Database\Factories\OrderFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

/**
 * @property int $id
 * @property string $name
 * @property string|null $url
 * @property int $quantity
 * @property float $cost
 * @property bool $is_billable
 * @property OrderStatus $status
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * 
 * @method static OrderFactory factory(int $count = null, array $state = [])
 * @method static Builder|static billable()
 * @method static Builder|static ofStatus(OrderStatus $status)
 */
class Order extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'url',
        'quantity',
        'cost',
        'is_billable',
        'status',
    ];

    /**
     * The model's default values for attributes.
     *
     * @var array
     */
    protected $attributes = [
        'quantity' => 1,
        'cost' => 0,
        'is_billable' => true,
        'status' => OrderStatus::New,
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
            'status' => OrderStatus::class,
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
     * Scope a query to only include tasks of a given status.
     */
    public function scopeOfStatus(Builder $query, OrderStatus $status): void
    {
        $query->where('status', $status->value);
    }
}
