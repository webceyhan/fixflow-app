<?php

namespace Database\Fixtures;

use Illuminate\Support\Facades\File;

abstract class Fixture
{
    /**
     * The fixture data file name (without extension).
     */
    protected static string $name;

    /**
     * Load fixture data from JSON file.
     */
    public static function load(string $name): array
    {
        return once(function () use ($name) {
            $path = database_path("fixtures/data/{$name}.json");

            return File::json($path, JSON_THROW_ON_ERROR);
        });
    }

    /**
     * Get all fixture data or a specific key.
     */
    public static function data(?string $key = null): array
    {
        $data = static::load(static::$name);

        return $key ? $data[$key] : $data;
    }
}
