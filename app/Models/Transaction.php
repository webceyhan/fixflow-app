<?php

namespace App\Models;

use App\Enums\TransactionMethod;
use App\Enums\TransactionType;
use App\Models\Concerns\HasRef;
use App\Models\Concerns\HasType;
use Carbon\CarbonImmutable;
use Database\Factories\TransactionFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $id
 * @property int $invoice_id
 * @property float $amount
 * @property string|null $note
 * @property TransactionMethod $method
 * @property TransactionType $type
 * @property CarbonImmutable|null $created_at
 * @property CarbonImmutable|null $updated_at
 * @property-read Invoice $invoice
 * @property-read mixed $ref
 */
#[Fillable(['invoice_id', 'amount', 'note', 'method', 'type'])]
class Transaction extends Model
{
    /**
     * @use HasFactory<TransactionFactory>
     * @use HasRef<static>
     * @use HasType<static, TransactionType>
     * */
    use HasFactory,
        HasRef,
        HasType;

    protected string $refPrefix = 'TXN';

    /**
     * The model's default values for attributes.
     *
     * @var array<string, mixed>
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
        ];
    }

    // RELATIONSHIPS ///////////////////////////////////////////////////////////////////////////////

    /**
     * Get the invoice that owns the transaction.
     *
     * @return BelongsTo<Invoice, $this>
     */
    public function invoice(): BelongsTo
    {
        return $this->belongsTo(Invoice::class);
    }

    // SCOPES //////////////////////////////////////////////////////////////////////////////////////

    /**
     * Scope a query to only include transactions of a given method.
     *
     * @param  Builder<static>  $query
     */
    public function scopeOfMethod(Builder $query, TransactionMethod $method): void
    {
        $query->where('method', $method);
    }
}
