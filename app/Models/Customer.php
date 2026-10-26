<?php

namespace App\Models;

use App\Enums\CustomerStatus;
use App\Enums\CustomerType;
use App\Models\Concerns\HasActiveStatus;
use App\Models\Concerns\HasRef;
use App\Models\Concerns\HasStatus;
use App\Models\Concerns\HasType;
use Carbon\CarbonImmutable;
use Database\Factories\CustomerFactory;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;

/**
 * @property int $id
 * @property string $name
 * @property string|null $company
 * @property string|null $tax_number
 * @property string|null $email
 * @property string|null $phone
 * @property string|null $address
 * @property string|null $note
 * @property CustomerType $type
 * @property CustomerStatus $status
 * @property CarbonImmutable|null $created_at
 * @property CarbonImmutable|null $updated_at
 * @property-read Collection<int, Device> $devices
 * @property-read int|null $devices_count
 * @property-read mixed $ref
 * @property-read Collection<int, Ticket> $tickets
 * @property-read int|null $tickets_count
 */
class Customer extends Model
{
    /**
     * @use HasActiveStatus<static>
     * @use HasFactory<CustomerFactory>
     * @use HasRef<static>
     * @use HasStatus<static, CustomerStatus>
     * @use HasType<static, CustomerType>
     */
    use HasActiveStatus,
        HasFactory,
        HasRef,
        HasStatus,
        HasType;

    protected string $refPrefix = 'CUS';

    /**
     * The model's default values for attributes.
     *
     * @var array<string, mixed>
     */
    protected $attributes = [
        'type' => CustomerType::Individual,
        'status' => CustomerStatus::Active,
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'company',
        'tax_number',
        'email',
        'phone',
        'address',
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
        return [];
    }

    // ATTRIBUTES //////////////////////////////////////////////////////////////////////////////////

    // RELATIONSHIPS ///////////////////////////////////////////////////////////////////////////////

    /**
     * Get the devices for the customer.
     *
     * @return HasMany<Device, $this>
     */
    public function devices(): HasMany
    {
        return $this->hasMany(Device::class);
    }

    /**
     * Get the tickets associated with the customer via devices.
     *
     * @return HasManyThrough<Ticket, Device, $this>
     */
    public function tickets(): HasManyThrough
    {
        return $this->hasManyThrough(Ticket::class, Device::class);
    }

    // SCOPES //////////////////////////////////////////////////////////////////////////////////////

    // METHODS /////////////////////////////////////////////////////////////////////////////////////
}
