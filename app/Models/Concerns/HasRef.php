<?php

namespace App\Models\Concerns;

use Illuminate\Database\Eloquent\Casts\Attribute;

trait HasRef
{
    /**
     * Initialize the trait for an instance.
     */
    public function initializeHasRef(): void
    {
        $this->appends[] = 'ref';
    }

    /**
     * Get the formatted reference string for the model.
     */
    public function ref(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->refPrefix.'-'.str_pad($this->id, 6, '0', STR_PAD_LEFT)
        );
    }
}
