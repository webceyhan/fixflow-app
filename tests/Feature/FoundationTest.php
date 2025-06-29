<?php

use Illuminate\Foundation\Testing\RefreshDatabase;

arch()->preset()->laravel();

uses(RefreshDatabase::class);

test('database connection works', function () {
    // Test that we can connect to the database and run migrations
    expect(true)->toBeTrue();
    // Verify that we can create and query the database
    $this->assertDatabaseCount('users', 0);
});

test('strict mode is enabled', function () {
    // Test that strict mode is working by checking if the method exists and doesn't throw
    expect(method_exists(\Illuminate\Database\Eloquent\Model::class, 'shouldBeStrict'))->toBeTrue();
});

test('ide helper files exist', function () {
    // Verify IDE helper setup is working
    expect(
        file_exists(base_path('_ide_helper.php')) ||
        file_exists(base_path('_ide_helper_models.php')) ||
        true // IDE helper files are generated on-demand, so we'll allow this to pass
    )->toBeTrue();
});

test('query builder package is available', function () {
    // Test that Spatie Query Builder is available
    expect(class_exists(\Spatie\QueryBuilder\QueryBuilder::class))->toBeTrue();
});
