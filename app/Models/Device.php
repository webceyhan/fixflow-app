<?php

namespace App\Models;

use App\Enums\DeviceType;
use Database\Factories\DeviceFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

/**
 * @property int $id
 * @property string $model
 * @property string|null $brand
 * @property string|null $serial_number
 * @property Carbon|null $warranty_expire_date
 * @property DeviceType $type
 * @property Carbon $created_at
 * @property Carbon $updated_at
 *
 * @method static DeviceFactory factory(int $count = null, array $state = [])
 * @method static Builder|static withWarranty()
 * @method static Builder|static ofType(DeviceType $type)
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
    ];

    /**
     * The model's default values for attributes.
     *
     * @var array
     */
    protected $attributes = [
        'type' => DeviceType::Other,
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
        ];
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
}
