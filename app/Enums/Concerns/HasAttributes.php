<?php

namespace App\Enums\Concerns;

use ReflectionAttribute;
use ReflectionEnumBackedCase;

trait HasAttributes
{
    /**
     * Get the specified attribute instance for the enum case.
     *
     * @template T of object
     *
     * @param  class-string<T>  $name
     * @return ?T
     */
    public function attribute(string $name): ?object
    {
        return ($this->attributes($name)[0] ?? null)?->newInstance();
    }

    /**
     * Check if the enum case has a specific attribute.
     */
    public function hasAttribute(string $name): bool
    {
        return ! empty($this->attributes($name));
    }

    /**
     * Get all enum cases that have a specific attribute.
     */
    public static function casesByAttribute(string $attribute): array
    {
        return array_filter(static::cases(), fn ($case) => $case->hasAttribute($attribute));
    }

    // HELPERS /////////////////////////////////////////////////////////////////////////////////////

    /**
     * Get the list of attributes for the enum case.
     *
     * @return array<int, ReflectionAttribute>
     */
    protected function attributes(?string $name = null): array
    {
        return (new ReflectionEnumBackedCase($this, $this->name))->getAttributes($name);
    }
}
