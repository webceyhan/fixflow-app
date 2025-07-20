<?php

namespace App\Models;

use App\Enums\AdjustmentReason;
use App\Enums\AdjustmentType;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Adjustment extends Model
{
    /**
     * @use HasFactory<\Database\Factories\AdjustmentFactory>
     */
    use HasFactory;

    /**
     * The model's default values for attributes.
     *
     * @var array
     */
    protected $attributes = [
        'type' => AdjustmentType::Bonus,
        'reason' => AdjustmentReason::Welcome,
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'amount',
        'percentage',
        'note',
        'type',
        'reason',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'amount' => 'float',
        'percentage' => 'float',
        'type' => AdjustmentType::class,
        'reason' => AdjustmentReason::class,
    ];

    // ACCESSORS ///////////////////////////////////////////////////////////////////////////////////

    // RELATIONS ///////////////////////////////////////////////////////////////////////////////////

    /**
     * Get the invoice associated with the adjustment.
     */
    public function invoice(): BelongsTo
    {
        return $this->belongsTo(Invoice::class);
    }

    // SCOPES //////////////////////////////////////////////////////////////////////////////////////

    /**
     * Scope to filter adjustments by type.
     */
    public function scopeOfType($query, AdjustmentType $type)
    {
        return $query->where('type', $type);
    }

    /**
     * Scope to filter adjustments by reason.
     */
    public function scopeOfReason($query, AdjustmentReason $reason)
    {
        return $query->where('reason', $reason);
    }

    // METHODS /////////////////////////////////////////////////////////////////////////////////////

    /**
     * Check if the adjustment has a fixed amount (percentage is null).
     */
    public function isFixed(): bool
    {
        return $this->percentage === null;
    }

    /**
     * Check if the adjustment is an addition (fee only).
     */
    public function isAddition(): bool
    {
        return $this->type->isAdditive();
    }

    /**
     * Get the effective amount that will be applied to the invoice.
     * Handles both fixed amounts and percentage-based calculations.
     */
    public function getEffectiveAmount(float $subtotal): float
    {
        // Apply sign correction for adjustments
        $signature = $this->isAddition() ? 1 : -1;

        if ($this->isFixed()) {
            return abs($this->amount) * $signature;
        }

        // For percentage-based adjustments, calculate from percentage
        $amount = ($subtotal * abs($this->percentage)) / 100;

        return $amount * $signature;
    }
}
