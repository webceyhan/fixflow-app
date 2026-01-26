<?php

use App\Models\Ticket;
use App\Models\User;

use function Pest\Laravel\actingAs;

it('shows the tickets list to authenticated users', function () {
    // Arrange
    $viewer = User::factory()->create();
    $tickets = Ticket::factory()->count(3)->create();

    // Act
    $response = actingAs($viewer)->get(route('tickets.index'));

    // Assert
    $response->assertSuccessful();
    $response->assertInertia(fn ($page) => $page
        ->component('tickets/Index')
        ->has('tickets.data', 3)
        ->has('tickets.links')
        ->has('filter')
    );
});

it('allows searching tickets by title', function () {
    // Arrange
    $uid = uniqid(); // parallel-safe
    $viewer = User::factory()->create();
    Ticket::factory()->create(['title' => "{$uid} Screen Repair"]);
    Ticket::factory()->create(['title' => "{$uid} Screen Replacement"]);
    Ticket::factory()->create(['title' => 'Battery Issue']);

    // Act
    $response = actingAs($viewer)->get(route('tickets.index', ['filter' => ['search' => $uid]]));

    // Assert
    $response->assertSuccessful();
    $response->assertInertia(fn ($page) => $page
        ->component('tickets/Index')
        ->has('tickets.data', 2)
    );
});

it('allows filtering tickets by priority', function () {
    // Arrange
    $viewer = User::factory()->create();
    Ticket::factory()->count(2)->create(['priority' => 'low']);
    Ticket::factory()->count(3)->create(['priority' => 'high']);

    // Act
    $response = actingAs($viewer)->get(route('tickets.index', ['filter' => ['priority' => 'low']]));

    // Assert
    $response->assertSuccessful();
    $response->assertInertia(fn ($page) => $page
        ->component('tickets/Index')
        ->has('tickets.data', 2)
    );
});

it('allows filtering tickets by status', function () {
    // Arrange
    $viewer = User::factory()->create();
    Ticket::factory()->count(2)->create(['status' => 'created']);
    Ticket::factory()->count(3)->create(['status' => 'in_progress']);

    // Act
    $response = actingAs($viewer)->get(route('tickets.index', ['filter' => ['status' => 'created']]));

    // Assert
    $response->assertSuccessful();
    $response->assertInertia(fn ($page) => $page
        ->component('tickets/Index')
        ->has('tickets.data', 2)
    );
});

it('allows filtering tickets by assignee - me', function () {
    // Arrange
    $viewer = User::factory()->create();
    $otherUser = User::factory()->create();
    Ticket::factory()->count(2)->forAssignee($viewer)->create();
    Ticket::factory()->count(3)->forAssignee($otherUser)->create();

    // Act
    $response = actingAs($viewer)->get(route('tickets.index', ['filter' => ['assignee' => 'me']]));

    // Assert
    $response->assertSuccessful();
    $response->assertInertia(fn ($page) => $page
        ->component('tickets/Index')
        ->has('tickets.data', 2)
    );
});

it('allows filtering tickets by assignee - none', function () {
    // Arrange
    $viewer = User::factory()->create();
    $assignee = User::factory()->create();
    Ticket::factory()->count(2)->unassigned()->create();
    Ticket::factory()->count(3)->forAssignee($assignee)->create();

    // Act
    $response = actingAs($viewer)->get(route('tickets.index', ['filter' => ['assignee' => 'none']]));

    // Assert
    $response->assertSuccessful();
    $response->assertInertia(fn ($page) => $page
        ->component('tickets/Index')
        ->has('tickets.data', 2)
    );
});

it('allows sorting tickets by title', function () {
    // Arrange
    $viewer = User::factory()->create();
    Ticket::factory()->create(['title' => 'Battery Issue']);
    Ticket::factory()->create(['title' => 'Screen Repair']);
    Ticket::factory()->create(['title' => 'Audio Problem']);

    // Act
    $response = actingAs($viewer)->get(route('tickets.index', ['sort' => 'title']));

    // Assert
    $response->assertSuccessful();
    $response->assertInertia(fn ($page) => $page
        ->component('tickets/Index')
        ->where('tickets.data.0.title', 'Audio Problem')
        ->where('tickets.data.1.title', 'Battery Issue')
        ->where('tickets.data.2.title', 'Screen Repair')
    );
});

it('allows sorting tickets by created_at descending by default', function () {
    // Arrange
    $viewer = User::factory()->create();
    $ticket1 = Ticket::factory()->create(['created_at' => now()->subDays(3)]);
    $ticket2 = Ticket::factory()->create(['created_at' => now()->subDays(1)]);
    $ticket3 = Ticket::factory()->create(['created_at' => now()->subDays(2)]);

    // Act
    $response = actingAs($viewer)->get(route('tickets.index'));

    // Assert
    $response->assertSuccessful();
    $response->assertInertia(fn ($page) => $page
        ->component('tickets/Index')
        ->where('tickets.data.0.id', $ticket2->id)
        ->where('tickets.data.1.id', $ticket3->id)
        ->where('tickets.data.2.id', $ticket1->id)
    );
});
