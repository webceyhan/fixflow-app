<?php

namespace App\Models;

use App\Casts\Progress;
use App\Enums\DeviceStatus;
use App\Models\Concerns\Contactable;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;

class Customer extends Model
{
    /**
     * @use HasFactory<\Database\Factories\CustomerFactory>
     */
    use Contactable, HasFactory;

    /**
     * The model's default values for attributes.
     *
     * @var array
     */
    protected $attributes = [
        'pending_devices_count' => 0,
        'complete_devices_count' => 0,
        'total_devices_count' => 0,
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'company',
        'vat_number',
        'email',
        'phone',
        'address',
        'note',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array<int, string>
     */
    protected $appends = [
        'device_progress',
    ];

    // ACCESSORS ///////////////////////////////////////////////////////////////////////////////////

    /**
     * Get the device progress percentage.
     */
    protected function deviceProgress(): Attribute
    {
        return Progress::using('pending_devices_count', 'complete_devices_count');
    }

    // RELATIONS ///////////////////////////////////////////////////////////////////////////////////

    /**
     * Get the devices associated with the customer.
     */
    public function devices(): HasMany
    {
        return $this->hasMany(Device::class);
    }

    /**
     * Get the tickets associated with the customer via devices.
     */
    public function tickets(): HasManyThrough
    {
        return $this->hasManyThrough(Ticket::class, Device::class);
    }

    // SCOPES //////////////////////////////////////////////////////////////////////////////////////

    // METHODS /////////////////////////////////////////////////////////////////////////////////////

    /**
     * Fill device counts for the customer.
     */
    public function fillDeviceCounts(): self
    {
        $deviceCounts = $this->devices()
            ->selectRaw('status, COUNT(*) as count')
            ->groupBy('status')
            ->pluck('count', 'status');

        $pendingDevicesCount = collect(DeviceStatus::pendingCases())
            ->map(fn ($case) => $deviceCounts[$case->value] ?? 0)
            ->sum();

        $completeDevicesCount = collect(DeviceStatus::completeCases())
            ->map(fn ($case) => $deviceCounts[$case->value] ?? 0)
            ->sum();

        $totalDevicesCount = $deviceCounts->sum();

        return $this->forceFill([
            'pending_devices_count' => $pendingDevicesCount,
            'complete_devices_count' => $completeDevicesCount,
            'total_devices_count' => $totalDevicesCount,
        ]);
    }
}
