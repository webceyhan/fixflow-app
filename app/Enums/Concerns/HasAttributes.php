<?php

namespace App\Enums\Concerns;

use ReflectionAttribute;
use ReflectionClassConstant;

trait HasAttributes
{
    /**
     * Get the list of attributes for the enum case.
     */
    public function attributes(?string $filterName = null): array
    {
        return (new ReflectionClassConstant(static::class, $this->name))->getAttributes($filterName);
    }

    /**
     * Get the specified attribute for the enum case.
     */
    public function attribute(string $name): ?ReflectionAttribute
    {
        return $this->attributes($name)[0] ?? null;
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
}
