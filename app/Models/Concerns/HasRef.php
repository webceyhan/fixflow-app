<?php

namespace App\Models\Concerns;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;

/**
 * @template TModel of Model
 *
 * @mixin TModel
 */
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
     *
     * @return Attribute<string, never>
     */
    public function ref(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->refPrefix.'-'.str_pad((string) $this->id, 6, '0', STR_PAD_LEFT)
        );
    }
}
