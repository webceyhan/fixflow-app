<?php

use App\Models\Ticket;
use App\Support\QueryBuilder\Filters\DateFilter;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\QueryBuilder\Exceptions\InvalidFilterValue;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->today = Ticket::factory()->create(['created_at' => now()]);
    $this->yesterday = Ticket::factory()->create(['created_at' => now()->subDay()]);
    $this->lastWeek = Ticket::factory()->create(['created_at' => now()->subWeek()]);
    $this->lastMonth = Ticket::factory()->create(['created_at' => now()->subMonth()]);
});

it('filters by today preset', function () {
    // Arrange
    $filter = new DateFilter('created_at');
    $query = Ticket::query();

    // Act
    $filter($query, 'today', 'created_at');
    $results = $query->get();

    // Assert
    expect($results)->toHaveCount(1)
        ->and($results->first()->id)->toBe($this->today->id);
});

it('filters by yesterday preset', function () {
    // Arrange
    $filter = new DateFilter('created_at');
    $query = Ticket::query();

    // Act
    $filter($query, 'yesterday', 'created_at');
    $results = $query->get();

    // Assert
    expect($results)->toHaveCount(1)
        ->and($results->first()->id)->toBe($this->yesterday->id);
});

it('filters by last_week preset', function () {
    // Arrange
    $filter = new DateFilter('created_at');
    $query = Ticket::query();

    // Act
    $filter($query, 'last_week', 'created_at');
    $results = $query->get();

    // Assert
    expect($results->count())->toBeGreaterThanOrEqual(1)
        ->and($results->pluck('id'))->toContain($this->lastWeek->id);
});

it('filters by last_month preset', function () {
    // Arrange
    $filter = new DateFilter('created_at');
    $query = Ticket::query();

    // Act
    $filter($query, 'last_month', 'created_at');
    $results = $query->get();

    // Assert
    expect($results->count())->toBeGreaterThanOrEqual(1)
        ->and($results->pluck('id'))->toContain($this->lastMonth->id);
});

it('filters by date range with both from and to', function () {
    // Arrange
    $from = now()->subDays(2)->format('Y-m-d');
    $to = now()->format('Y-m-d');
    $filter = new DateFilter('created_at');
    $query = Ticket::query();

    // Act
    $filter($query, [$from, $to], 'created_at');
    $results = $query->get();

    // Assert
    expect($results->count())->toBeGreaterThanOrEqual(2)
        ->and($results->pluck('id'))->toContain($this->today->id, $this->yesterday->id);
});

it('filters by date range with only from date', function () {
    // Arrange
    $from = now()->subDays(2)->format('Y-m-d');
    $filter = new DateFilter('created_at');
    $query = Ticket::query();

    // Act
    $filter($query, [$from, null], 'created_at');
    $results = $query->get();

    // Assert
    expect($results->count())->toBeGreaterThanOrEqual(2)
        ->and($results->pluck('id'))->toContain($this->today->id, $this->yesterday->id);
});

it('filters by date range with only to date', function () {
    // Arrange
    $to = now()->subDays(2)->format('Y-m-d');
    $filter = new DateFilter('created_at');
    $query = Ticket::query();

    // Act
    $filter($query, [null, $to], 'created_at');
    $results = $query->get();

    // Assert
    expect($results->count())->toBeGreaterThanOrEqual(2)
        ->and($results->pluck('id'))->toContain($this->lastWeek->id, $this->lastMonth->id);
});

it('filters by indexed array from url', function () {
    // Arrange
    $from = now()->subDays(2)->format('Y-m-d');
    $to = now()->format('Y-m-d');
    $filter = new DateFilter('created_at');
    $query = Ticket::query();

    // Act
    // PHP converts "2024-01-01,2024-12-31" to [0 => '2024-01-01', 1 => '2024-12-31']
    $filter($query, [$from, $to], 'created_at');
    $results = $query->get();

    // Assert
    expect($results)->toHaveCount(2);
});

it('throws exception for invalid preset', function () {
    // Arrange
    $filter = new DateFilter('created_at');
    $query = Ticket::query();

    // Act & Assert
    $filter($query, 'invalid_preset', 'created_at');
})->throws(InvalidFilterValue::class);

it('throws exception for empty array', function () {
    // Arrange
    $filter = new DateFilter('created_at');
    $query = Ticket::query();

    // Act & Assert
    $filter($query, [], 'created_at');
})->throws(Exception::class);

it('throws exception for null value', function () {
    // Arrange
    $filter = new DateFilter('created_at');
    $query = Ticket::query();

    // Act & Assert
    $filter($query, null, 'created_at');
})->throws(InvalidFilterValue::class);

it('works with custom column name', function () {
    // Arrange
    $ticket1 = Ticket::factory()->create(['due_date' => now()]);
    $ticket2 = Ticket::factory()->create(['due_date' => now()->subWeek()]);
    $filter = new DateFilter('due_date');
    $query = Ticket::query();

    // Act
    $filter($query, 'today', 'due_date');
    $results = $query->get();

    // Assert
    expect($results->pluck('id'))->toContain($ticket1->id)
        ->not()->toContain($ticket2->id);
});
