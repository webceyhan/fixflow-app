<?php

use App\Models\Ticket;
use App\Models\User;
use App\Support\QueryBuilder\Filters\AssigneeFilter;
use Illuminate\Foundation\Testing\RefreshDatabase;

use function Pest\Laravel\actingAs;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->currentUser = User::factory()->create();
    $this->otherUser = User::factory()->create();

    actingAs($this->currentUser);

    $this->myTicket = Ticket::factory()->forAssignee($this->currentUser)->create();
    $this->otherTicket = Ticket::factory()->forAssignee($this->otherUser)->create();
    $this->unassignedTicket = Ticket::factory()->unassigned()->create();
});

it('filters by me', function () {
    // Arrange
    $filter = new AssigneeFilter;
    $query = Ticket::query();

    // Act
    $filter($query, 'me', 'assignee');
    $results = $query->get();

    // Assert
    expect($results)->toHaveCount(1)
        ->and($results->first()->id)->toBe($this->myTicket->id);
});

it('filters by others', function () {
    // Arrange
    $filter = new AssigneeFilter;
    $query = Ticket::query();

    // Act
    $filter($query, 'others', 'assignee');
    $results = $query->get();

    // Assert
    expect($results)->toHaveCount(1)
        ->and($results->first()->id)->toBe($this->otherTicket->id);
});

it('filters by none', function () {
    // Arrange
    $filter = new AssigneeFilter;
    $query = Ticket::query();

    // Act
    $filter($query, 'none', 'assignee');
    $results = $query->get();

    // Assert
    expect($results)->toHaveCount(1)
        ->and($results->first()->id)->toBe($this->unassignedTicket->id);
});

it('filters by specific user id', function () {
    // Arrange
    $filter = new AssigneeFilter;
    $query = Ticket::query();

    // Act
    $filter($query, (string) $this->otherUser->id, 'assignee');
    $results = $query->get();

    // Assert
    expect($results)->toHaveCount(1)
        ->and($results->first()->id)->toBe($this->otherTicket->id);
});

it('filters by numeric user id', function () {
    // Arrange
    $filter = new AssigneeFilter;
    $query = Ticket::query();

    // Act
    $filter($query, $this->currentUser->id, 'assignee');
    $results = $query->get();

    // Assert
    expect($results)->toHaveCount(1)
        ->and($results->first()->id)->toBe($this->myTicket->id);
});

it('returns empty when filtering by non-existent user id', function () {
    // Arrange
    $filter = new AssigneeFilter;
    $query = Ticket::query();

    // Act
    $filter($query, '99999', 'assignee');

    // Assert
    expect($query->get())->toHaveCount(0);
});
