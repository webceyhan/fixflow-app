<?php

namespace App\Models;

use App\Enums\AdjustmentReason;
use App\Enums\AdjustmentType;
use App\Models\Concerns\HasRef;
use App\Models\Concerns\HasType;
use Carbon\CarbonImmutable;
use Database\Factories\AdjustmentFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $id
 * @property int $invoice_id
 * @property float $amount
 * @property bool $is_percent
 * @property string|null $note
 * @property AdjustmentType $type
 * @property AdjustmentReason $reason
 * @property CarbonImmutable|null $created_at
 * @property CarbonImmutable|null $updated_at
 * @property-read Invoice $invoice
 * @property-read mixed $ref
 */
#[Fillable(['invoice_id', 'amount', 'is_percent', 'note', 'type', 'reason'])]
class Adjustment extends Model
{
    /**
     * @use HasFactory<AdjustmentFactory>
     * @use HasRef<static>
     * @use HasType<static, AdjustmentType>
     */
    use HasFactory,
        HasRef,
        HasType;

    protected string $refPrefix = 'ADJ';

    /**
     * The model's default values for attributes.
     *
     * @var array<string, mixed>
     */
    protected $attributes = [
        'amount' => 0,
        'is_percent' => false,
        'type' => AdjustmentType::Discount,
        'reason' => AdjustmentReason::Welcome,
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
            'is_percent' => 'boolean',
            'reason' => AdjustmentReason::class,
        ];
    }

    // RELATIONSHIPS ///////////////////////////////////////////////////////////////////////////////

    /**
     * Get the invoice that owns the adjustment.
     *
     * @return BelongsTo<Invoice, $this>
     */
    public function invoice(): BelongsTo
    {
        return $this->belongsTo(Invoice::class);
    }

    // SCOPES //////////////////////////////////////////////////////////////////////////////////////

    /**
     * Scope a query to only include adjustments of a given reason.
     *
     * @param  Builder<static>  $query
     */
    public function scopeOfReason(Builder $query, AdjustmentReason $reason): void
    {
        $query->where('reason', $reason);
    }
}
