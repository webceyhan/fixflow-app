<?php

use App\Models\User;
use App\Support\QueryBuilder;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('extends spatie query builder', function () {
    // Arrange & Act
    $queryBuilder = QueryBuilder::for(User::class);

    // Assert
    expect($queryBuilder)->toBeInstanceOf(Spatie\QueryBuilder\QueryBuilder::class);
});

it('sets default sort to -created_at', function () {
    // Arrange & Act
    $queryBuilder = QueryBuilder::for(User::class);
    $sql = $queryBuilder->toSql();

    // Assert
    expect($sql)->toContain('order by');
});

it('sets default pagination to 10', function () {
    // Arrange
    User::factory()->count(15)->create();

    // Act
    $result = QueryBuilder::for(User::class)->paginate();

    // Assert
    expect($result->perPage())->toBe(10);
});

it('allows overriding per_page from request', function () {
    // Arrange
    User::factory()->count(25)->create();
    request()->merge(['per_page' => 20]);

    // Act
    $result = QueryBuilder::for(User::class)->paginate();

    // Assert
    expect($result->perPage())->toBe(20);
});

it('caps per_page to prevent oversized pagination queries', function () {
    // Arrange
    User::factory()->count(120)->create();
    request()->merge(['per_page' => 500]);

    // Act
    $result = QueryBuilder::for(User::class)->paginate();

    // Assert
    expect($result->perPage())->toBe(100);
});

it('falls back to default per_page when request value is invalid', function () {
    // Arrange
    User::factory()->count(25)->create();
    request()->merge(['per_page' => 0]);

    // Act
    $result = QueryBuilder::for(User::class)->paginate();

    // Assert
    expect($result->perPage())->toBe(10);
});

it('uses default per_page when not provided in request', function () {
    // Arrange
    User::factory()->count(15)->create();

    // Act
    $result = QueryBuilder::for(User::class)->paginate();

    // Assert
    expect($result->perPage())->toBe(10);
});
