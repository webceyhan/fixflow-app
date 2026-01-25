<?php

namespace App\Models;

use App\Enums\CustomerStatus;
use App\Enums\CustomerType;
use App\Models\Concerns\HasActiveStatus;
use App\Models\Concerns\HasStatus;
use App\Models\Concerns\HasType;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Customer extends Model
{
    /** @use HasFactory<\Database\Factories\CustomerFactory> */
    use HasActiveStatus, HasFactory, HasStatus, HasType;

    /**
     * The model's default values for attributes.
     *
     * @var array
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
     */
    public function devices(): HasMany
    {
        return $this->hasMany(Device::class);
    }

    // SCOPES //////////////////////////////////////////////////////////////////////////////////////

    // METHODS /////////////////////////////////////////////////////////////////////////////////////
}
