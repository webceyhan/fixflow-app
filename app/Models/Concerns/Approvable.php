<?php

namespace App\Models\Concerns;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

/**
 * @template TModel of Model
 *
 * @mixin TModel
 */
trait Approvable
{
    private const APPROVED_AT = 'approved_at';

    /**
     * Initialize the trait for an instance.
     */
    public function initializeApprovable(): void
    {
        $this->casts[self::APPROVED_AT] = 'datetime';
    }

    /**
     * Determine if the model is approved.
     */
    public function isApproved(): bool
    {
        return $this->{self::APPROVED_AT} !== null;
    }

    // SCOPES //////////////////////////////////////////////////////////////////////////////////////

    /**
     * Scope a query to only include approved models.
     *
     * @param  Builder<TModel>  $query
     */
    public function scopeApproved(Builder $query): void
    {
        $query->whereNotNull(self::APPROVED_AT);
    }

    /**
     * Scope a query to only include unapproved models.
     *
     * @param  Builder<TModel>  $query
     */
    public function scopeUnapproved(Builder $query): void
    {
        $query->whereNull(self::APPROVED_AT);
    }
}
