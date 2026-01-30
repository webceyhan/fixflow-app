<?php

use App\Models\Task;
use App\Models\User;

use function Pest\Laravel\actingAs;

it('shows the tasks list to authenticated users', function () {
    // Arrange
    $viewer = User::factory()->create();
    $tasks = Task::factory()->count(3)->create();

    // Act
    $response = actingAs($viewer)->get(route('tasks.index'));

    // Assert
    $response->assertSuccessful();
    $response->assertInertia(fn ($page) => $page
        ->component('tasks/Index')
        ->has('tasks.data', 3)
        ->has('tasks.links')
        ->has('filter')
    );
});

it('allows searching tasks', function () {
    // Arrange
    $uid = uniqid(); // parallel-safe
    $viewer = User::factory()->create();
    Task::factory()->count(2)->create(['note' => "{$uid} Screen repair task"]);
    Task::factory()->create(['note' => 'Battery replacement task']);

    // Act
    $response = actingAs($viewer)->get(route('tasks.index', ['filter' => ['search' => $uid]]));

    // Assert
    $response->assertSuccessful();
    $response->assertInertia(fn ($page) => $page
        ->component('tasks/Index')
        ->has('tasks.data', 2)
    );
});

it('allows filtering tasks by type', function () {
    // Arrange
    $viewer = User::factory()->create();
    Task::factory()->count(2)->create(['type' => 'repair']);
    Task::factory()->count(3)->create(['type' => 'diagnosis']);

    // Act
    $response = actingAs($viewer)->get(route('tasks.index', ['filter' => ['type' => 'repair']]));

    // Assert
    $response->assertSuccessful();
    $response->assertInertia(fn ($page) => $page
        ->component('tasks/Index')
        ->has('tasks.data', 2)
    );
});

it('allows filtering tasks by priority', function () {
    // Arrange
    $viewer = User::factory()->create();
    Task::factory()->count(2)->create(['priority' => 'low']);
    Task::factory()->count(3)->create(['priority' => 'high']);

    // Act
    $response = actingAs($viewer)->get(route('tasks.index', ['filter' => ['priority' => 'low']]));

    // Assert
    $response->assertSuccessful();
    $response->assertInertia(fn ($page) => $page
        ->component('tasks/Index')
        ->has('tasks.data', 2)
    );
});

it('allows filtering tasks by status', function () {
    // Arrange
    $viewer = User::factory()->create();
    Task::factory()->count(2)->create(['status' => 'created']);
    Task::factory()->count(3)->create(['status' => 'completed']);

    // Act
    $response = actingAs($viewer)->get(route('tasks.index', ['filter' => ['status' => 'created']]));

    // Assert
    $response->assertSuccessful();
    $response->assertInertia(fn ($page) => $page
        ->component('tasks/Index')
        ->has('tasks.data', 2)
    );
});

it('allows filtering tasks by assignee - me', function () {
    // Arrange
    $viewer = User::factory()->create();
    $otherUser = User::factory()->create();
    Task::factory()->count(2)->create(['assignee_id' => $viewer->id]);
    Task::factory()->count(3)->create(['assignee_id' => $otherUser->id]);

    // Act
    $response = actingAs($viewer)->get(route('tasks.index', ['filter' => ['assignee' => 'me']]));

    // Assert
    $response->assertSuccessful();
    $response->assertInertia(fn ($page) => $page
        ->component('tasks/Index')
        ->has('tasks.data', 2)
    );
});

it('allows filtering tasks by assignee - none', function () {
    // Arrange
    $viewer = User::factory()->create();
    $assignee = User::factory()->create();
    Task::factory()->count(2)->create(['assignee_id' => null]);
    Task::factory()->count(3)->create(['assignee_id' => $assignee->id]);

    // Act
    $response = actingAs($viewer)->get(route('tasks.index', ['filter' => ['assignee' => 'none']]));

    // Assert
    $response->assertSuccessful();
    $response->assertInertia(fn ($page) => $page
        ->component('tasks/Index')
        ->has('tasks.data', 2)
    );
});

it('allows filtering tasks by assignee - others', function () {
    // Arrange
    $viewer = User::factory()->create();
    $otherAssignee = User::factory()->create();
    Task::factory()->count(2)->create(['assignee_id' => $otherAssignee->id]);
    Task::factory()->count(3)->create(['assignee_id' => $viewer->id]);

    // Act
    $response = actingAs($viewer)->get(route('tasks.index', ['filter' => ['assignee' => 'others']]));

    // Assert
    $response->assertSuccessful();
    $response->assertInertia(fn ($page) => $page
        ->component('tasks/Index')
        ->has('tasks.data', 2)
    );
});

it('allows filtering tasks by created date', function () {
    // Arrange
    $viewer = User::factory()->create();
    Task::factory()->count(2)->create(['created_at' => now()]);
    Task::factory()->count(3)->create(['created_at' => now()->subDays(10)]);

    // Act
    $response = actingAs($viewer)->get(route('tasks.index', ['filter' => ['created_at' => 'today']]));

    // Assert
    $response->assertSuccessful();
    $response->assertInertia(fn ($page) => $page
        ->component('tasks/Index')
        ->has('tasks.data', 2)
    );
});
