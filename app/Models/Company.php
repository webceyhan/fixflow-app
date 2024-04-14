<?php

namespace App\Models;

use App\Models\Concerns\Contactable;
use Database\Factories\CompanyFactory;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;

/**
 * @property int $id
 * @property string $name
 * @property string|null $email
 * @property string|null $phone
 * @property string|null $vat_number
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * 
 * @property-read Collection<int, Customer> $members
 *
 * @method static CompanyFactory factory(int $count = null, array $state = [])
 */
class Company extends Model
{
    use HasFactory, Contactable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'phone',
        'vat_number',
    ];

    // RELATIONS ///////////////////////////////////////////////////////////////////////////////////

    public function members(): HasMany
    {
        return $this->hasMany(Customer::class);
    }
}
