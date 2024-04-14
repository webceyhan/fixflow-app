<?php

namespace App\Models;

use App\Enums\TransactionMethod;
use App\Enums\TransactionType;
use Database\Factories\TransactionFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

/**
 * @property int $id
 * @property int $invoice_id
 * @property float $amount
 * @property string|null $note
 * @property TransactionMethod $method
 * @property TransactionType $type
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * 
 * @property-read Invoice $invoice
 * 
 * @method static TransactionFactory factory(int $count = null, array $state = [])
 * @method static Builder|static ofMethod(TransactionMethod $method)
 * @method static Builder|static ofType(TransactionType $type)
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
        'type',
    ];

    /**
     * The model's default values for attributes.
     *
     * @var array
     */
    protected $attributes = [
        'amount' => 0,
        'method' => TransactionMethod::Cash,
        'type' => TransactionType::Payment,
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
            'type' => TransactionType::class,
        ];
    }

    // RELATIONS ///////////////////////////////////////////////////////////////////////////////////

    public function invoice(): BelongsTo
    {
        return $this->belongsTo(Invoice::class);
    }

    // SCOPES //////////////////////////////////////////////////////////////////////////////////////

    /**
     * Scope a query to only include transactions of a given method.
     */
    public function scopeOfMethod(Builder $query, TransactionMethod $method): void
    {
        $query->where('method', $method->value);
    }

    /**
     * Scope a query to only include transactions of a given type.
     */
    public function scopeOfType(Builder $query, TransactionType $type): void
    {
        $query->where('type', $type->value);
    }
}
