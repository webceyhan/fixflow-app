<?php

namespace App\Models;

use App\Enums\DeviceStatus;
use App\Enums\DeviceType;
use App\Models\Concerns\HasActiveStatus;
use App\Models\Concerns\HasRef;
use App\Models\Concerns\HasStatus;
use App\Models\Concerns\HasType;
use App\Models\Concerns\HasWarranty;
use Carbon\CarbonImmutable;
use Database\Factories\DeviceFactory;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property int $id
 * @property int $customer_id
 * @property string $model
 * @property string $brand
 * @property string|null $color
 * @property string|null $imei
 * @property string|null $serial_number
 * @property string|null $passcode
 * @property CarbonImmutable|null $purchase_date
 * @property CarbonImmutable|null $warranty_expire_date
 * @property string|null $note
 * @property DeviceType $type
 * @property DeviceStatus $status
 * @property CarbonImmutable|null $created_at
 * @property CarbonImmutable|null $updated_at
 * @property-read Customer $customer
 * @property-read mixed $ref
 * @property-read Collection<int, Ticket> $tickets
 * @property-read int|null $tickets_count
 */
class Device extends Model
{
    /**
     * @use HasActiveStatus<static>
     * @use HasFactory<DeviceFactory>
     * @use HasRef<static>
     * @use HasStatus<static, DeviceStatus>
     * @use HasType<static, DeviceType>
     * @use HasWarranty<static>
     */
    use HasActiveStatus,
        HasFactory,
        HasRef,
        HasStatus,
        HasType,
        HasWarranty;

    protected string $refPrefix = 'DVC';

    /**
     * The model's default values for attributes.
     *
     * @var array<string, mixed>
     */
    protected $attributes = [
        'type' => DeviceType::Other,
        'status' => DeviceStatus::Received,
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'customer_id',
        'model',
        'brand',
        'color',
        'imei',
        'serial_number',
        'passcode',
        'purchase_date',
        'warranty_expire_date',
        'note',
        'type',
        'status',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'passcode' => 'encrypted',
            'purchase_date' => 'date',
        ];
    }

    // ATTRIBUTES //////////////////////////////////////////////////////////////////////////////////

    // RELATIONSHIPS ///////////////////////////////////////////////////////////////////////////////

    /**
     * Get the customer that owns the device.
     *
     * @return BelongsTo<Customer, $this>
     */
    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    /**
     * Get the tickets for the device.
     *
     * @return HasMany<Ticket, $this>
     */
    public function tickets(): HasMany
    {
        return $this->hasMany(Ticket::class);
    }

    // SCOPES //////////////////////////////////////////////////////////////////////////////////////

    // METHODS /////////////////////////////////////////////////////////////////////////////////////
}
