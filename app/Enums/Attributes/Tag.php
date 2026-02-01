<?php

namespace App\Enums\Attributes;

use Attribute;

#[Attribute]
final class Tag
{
    public function __construct(public readonly mixed $value) {}
}
