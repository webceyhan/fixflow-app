<?php

namespace App\Models;

use App\Enums\TransactionMethod;
use App\Enums\TransactionType;
use App\Models\Concerns\HasType;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Transaction extends Model
{
    /** @use HasFactory<\Database\Factories\TransactionFactory> */
    use HasFactory,
        HasType;

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
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'invoice_id',
        'amount',
        'note',
        'method',
        'type',
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
     */
    public function invoice(): BelongsTo
    {
        return $this->belongsTo(Invoice::class);
    }

    // SCOPES //////////////////////////////////////////////////////////////////////////////////////

    /**
     * Scope a query to only include transactions of a given method.
     */
    public function scopeOfMethod($query, TransactionMethod $method): void
    {
        $query->where('method', $method);
    }
}
