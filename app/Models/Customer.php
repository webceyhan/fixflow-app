<?php

namespace App\Models;

use Database\Factories\CustomerFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

/**
 * @property int $id
 * @property int|null $company_id
 * @property string $name
 * @property string|null $email
 * @property string|null $phone
 * @property string|null $address
 * @property string|null $note
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * 
 * @property-read Company|null $company
 *
 * @method static CustomerFactory factory(int $count = null, array $state = [])
 */
class Customer extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'phone',
        'address',
        'note',
    ];

    // RELATIONS ///////////////////////////////////////////////////////////////////////////////////

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }
}
