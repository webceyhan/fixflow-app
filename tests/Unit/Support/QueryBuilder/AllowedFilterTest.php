<?php

use App\Support\QueryBuilder\AllowedFilter;

it('creates search filter with searchables', function () {
    // Act
    $filter = AllowedFilter::search(['name', 'email']);

    // Assert
    expect($filter)->toBeInstanceOf(\Spatie\QueryBuilder\AllowedFilter::class)
        ->and($filter->getName())->toBe('search');
});

it('creates date filter with default column', function () {
    // Act
    $filter = AllowedFilter::date();

    // Assert
    expect($filter)->toBeInstanceOf(\Spatie\QueryBuilder\AllowedFilter::class)
        ->and($filter->getName())->toBe('created_at');
});

it('creates date filter with custom column', function () {
    // Act
    $filter = AllowedFilter::date('due_date');

    // Assert
    expect($filter)->toBeInstanceOf(\Spatie\QueryBuilder\AllowedFilter::class)
        ->and($filter->getName())->toBe('due_date');
});

it('creates assignee filter', function () {
    // Act
    $filter = AllowedFilter::assignee();

    // Assert
    expect($filter)->toBeInstanceOf(\Spatie\QueryBuilder\AllowedFilter::class)
        ->and($filter->getName())->toBe('assignee');
});
