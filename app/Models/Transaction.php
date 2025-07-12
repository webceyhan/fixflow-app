<?php

namespace App\Models;

use App\Enums\TransactionMethod;
use App\Enums\TransactionType;
use App\Models\Concerns\HasType;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Transaction extends Model
{
    /**
     * @use HasFactory<\Database\Factories\TransactionFactory>
     * @use HasType<\App\Enums\TransactionType>
     */
    use HasFactory, HasType;

    /**
     * The model's default values for attributes.
     *
     * @var array
     */
    protected $attributes = [
        'method' => TransactionMethod::Cash,
        'type' => TransactionType::Payment,
    ];

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
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'amount' => 'float',
        'method' => TransactionMethod::class,
    ];

    // RELATIONS ///////////////////////////////////////////////////////////////////////////////////

    /**
     * Get the invoice associated with the transaction.
     */
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
}
