<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    /**
     * This is a workaround to allow using the `mock` helper in tests.
     * It allows us to call mock methods directly on the test case.
     */
    public function __call($name, $arguments)
    {
        $callback = $this->{$name} ?? null;

        if (is_callable($callback)) {
            return $callback(...$arguments);
        }
    }
}
