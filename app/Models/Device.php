<?php

namespace App\Models;

use App\Enums\DeviceStatus;
use App\Enums\DeviceType;
use Database\Factories\DeviceFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;

/**
 * @property int $id
 * @property int $customer_id
 * @property string $model
 * @property string|null $brand
 * @property string|null $serial_number
 * @property Carbon|null $warranty_expire_date
 * @property DeviceType $type
 * @property DeviceStatus $status
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * 
 * @property-read Customer $customer
 * @property-read Collection<int, Ticket> $tickets
 *
 * @method static DeviceFactory factory(int $count = null, array $state = [])
 * @method static Builder|static withWarranty()
 * @method static Builder|static ofType(DeviceType $type)
 * @method static Builder|static ofStatus(DeviceStatus $status)
 */
class Device extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'model',
        'brand',
        'serial_number',
        'warranty_expire_date',
        'type',
        'status',
    ];

    /**
     * The model's default values for attributes.
     *
     * @var array
     */
    protected $attributes = [
        'type' => DeviceType::Other,
        'status' => DeviceStatus::CheckedIn,
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'warranty_expire_date' => 'date',
            'type' => DeviceType::class,
            'status' => DeviceStatus::class,
        ];
    }

    // RELATIONS ///////////////////////////////////////////////////////////////////////////////////

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function tickets(): HasMany
    {
        return $this->hasMany(Ticket::class);
    }

    // METHODS /////////////////////////////////////////////////////////////////////////////////////

    /**
     * Determine if the device has warranty.
     */
    public function hasWarranty(): bool
    {
        return $this->warranty_expire_date > now();
    }

    // SCOPES //////////////////////////////////////////////////////////////////////////////////////

    /**
     * Scope a query to only include devices with warranty.
     */
    public function scopeWithWarranty(Builder $query): void
    {
        $query->where('warranty_expire_date', '>', now());
    }

    /**
     * Scope a query to only include devices of a given type.
     */
    public function scopeOfType(Builder $query, DeviceType $type): void
    {
        $query->where('type', $type->value);
    }

    /**
     * Scope a query to only include devices of a given status.
     */
    public function scopeOfStatus(Builder $query, DeviceStatus $status): void
    {
        $query->where('status', $status->value);
    }
}
