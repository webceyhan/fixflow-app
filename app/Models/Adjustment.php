<?php

namespace App\Models;

use App\Enums\AdjustmentReason;
use App\Enums\AdjustmentType;
use App\Models\Concerns\HasRef;
use App\Models\Concerns\HasType;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Adjustment extends Model
{
    /** @use HasFactory<\Database\Factories\AdjustmentFactory> */
    use HasFactory,
        HasRef,
        HasType;

    protected string $refPrefix = 'ADJ';

    /**
     * The model's default values for attributes.
     *
     * @var array
     */
    protected $attributes = [
        'amount' => 0,
        'is_percent' => false,
        'type' => AdjustmentType::Discount,
        'reason' => AdjustmentReason::Welcome,
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'invoice_id',
        'amount',
        'is_percent',
        'note',
        'type',
        'reason',
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
     */
    public function invoice(): BelongsTo
    {
        return $this->belongsTo(Invoice::class);
    }

    // SCOPES //////////////////////////////////////////////////////////////////////////////////////

    /**
     * Scope a query to only include adjustments of a given reason.
     */
    public function scopeOfReason($query, AdjustmentReason $reason): void
    {
        $query->where('reason', $reason);
    }
}
