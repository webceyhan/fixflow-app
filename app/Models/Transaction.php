<?php

namespace App\Models;

use App\Enums\TransactionMethod;
use Database\Factories\TransactionFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

/**
 * @property int $id
 * @property float $amount
 * @property string|null $note
 * @property TransactionMethod $method
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * 
 * @method static TransactionFactory factory(int $count = null, array $state = [])
 * @method static Builder|static ofMethod(TransactionMethod $method)
 */
class Transaction extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'amount',
        'note',
        'method',
    ];

    /**
     * The model's default values for attributes.
     *
     * @var array
     */
    protected $attributes = [
        'amount' => 0,
        'method' => TransactionMethod::Cash,
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'amount' => 'float',
            'method' => TransactionMethod::class,
        ];
    }

    // SCOPES //////////////////////////////////////////////////////////////////////////////////////

    /**
     * Scope a query to only include transactions of a given method.
     */
    public function scopeOfMethod(Builder $query, TransactionMethod $method): void
    {
        $query->where('method', $method->value);
    }
}
