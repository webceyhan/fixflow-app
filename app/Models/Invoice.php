<?php

namespace App\Models;

use App\Models\Concerns\HasDueDate;
use Database\Factories\InvoiceFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;

/**
 * @property int $id
 * @property int $ticket_id
 * @property float $total
 * @property bool $is_paid
 * @property Carbon $due_date
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property-read float $total_paid
 * @property-read float $total_refunded
 * 
 * @property-read Ticket $ticket
 * @property-read Collection<int, Transaction> $transactions
 * 
 * @method static InvoiceFactory factory(int $count = null, array $state = [])
 * @method static Builder|static unpaid()
 */
class Invoice extends Model
{
    use HasFactory, HasDueDate;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'total',
        'is_paid',
        'due_date',
    ];

    /**
     * The model's default values for attributes.
     *
     * @var array
     */
    protected $attributes = [
        'total' => 0,
        'is_paid' => false,
        'total_paid' => 0,
        'total_refunded' => 0,
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'total' => 'float',
            'is_paid' => 'boolean',
            'due_date' => 'date',
            'total_paid' => 'float',
            'total_refunded' => 'float',
        ];
    }

    // RELATIONS ///////////////////////////////////////////////////////////////////////////////////

    public function ticket(): BelongsTo
    {
        return $this->belongsTo(Ticket::class);
    }

    public function transactions(): HasMany
    {
        return $this->hasMany(Transaction::class);
    }

    // METHODS /////////////////////////////////////////////////////////////////////////////////////

    public function fillTotalPaid(): static
    {
        return $this->forceFill([
            'total_paid' => $this->transactions()->payments()->sum('amount'),
        ]);
    }

    public function fillTotalRefunded(): static
    {
        return $this->forceFill([
            'total_refunded' => $this->transactions()->refunds()->sum('amount'),
        ]);
    }

    // SCOPES //////////////////////////////////////////////////////////////////////////////////////

    /**
     * Scope a query to only include unpaid invoices.
     */
    public function scopeUnpaid(Builder $query): void
    {
        $query->where('is_paid', false);
    }
}
