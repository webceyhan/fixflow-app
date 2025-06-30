<?php

namespace App\Models;

use App\Enums\DeviceStatus;
use App\Enums\DeviceType;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Device extends Model
{
    /**
     * @use HasFactory<\Database\Factories\DeviceFactory>
     */
    use HasFactory;

    /**
     * The model's default values for attributes.
     *
     * @var array
     */
    protected $attributes = [
        'type' => DeviceType::Other,
        'status' => DeviceStatus::Received,
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'model',
        'brand',
        'serial_number',
        'purchase_date',
        'warranty_expire_date',
        'type',
        'status',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'purchase_date' => 'date',
        'warranty_expire_date' => 'date',
        'type' => DeviceType::class,
        'status' => DeviceStatus::class,
    ];

    // ACCESSORS ///////////////////////////////////////////////////////////////////////////////////

    // RELATIONS ///////////////////////////////////////////////////////////////////////////////////

    /**
     * Get the customer that owns the device.
     */
    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
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

    // METHODS /////////////////////////////////////////////////////////////////////////////////////

    /**
     * Determine if the device has valid warranty.
     */
    public function hasWarranty(): bool
    {
        return $this->warranty_expire_date > now();
    }
}
