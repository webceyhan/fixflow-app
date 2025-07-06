<?php

namespace App\Models\Concerns;

use Illuminate\Database\Eloquent\Builder;

trait Contactable
{
    private const EMAIL = 'email';

    private const PHONE = 'phone';

    /**
     * Determine if the model has an email address.
     */
    public function isMailable(): bool
    {
        return (bool) $this->{static::EMAIL};
    }

    /**
     * Determine if the model has a phone number.
     */
    public function isCallable(): bool
    {
        return (bool) $this->{static::PHONE};
    }

    /**
     * Determine if the model can be contacted via email or phone.
     */
    public function isContactable(): bool
    {
        return $this->isMailable() || $this->isCallable();
    }

    // SCOPES //////////////////////////////////////////////////////////////////////////////////////

    /**
     * Scope a query to only include models that have an email address.
     */
    public function scopeMailable(Builder $query): void
    {
        $query->whereNotNull(static::EMAIL);
    }

    /**
     * Scope a query to only include models that have a phone number.
     */
    public function scopeCallable(Builder $query): void
    {
        $query->whereNotNull(static::PHONE);
    }

    /**
     * Scope a query to only include models that are contactable.
     */
    public function scopeContactable(Builder $query): void
    {
        $query->where(function (Builder $query) {
            $query->whereNotNull(static::EMAIL)
                ->orWhereNotNull(static::PHONE);
        });
    }
}
