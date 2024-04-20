<?php

use App\Models\Task;
use App\Models\User;
use Inertia\Testing\AssertableInertia as Assert;

it('can view all tasks', function () {
    $user = User::factory()->create();
    $tasks = Task::factory(2)->create();

    $response = $this->actingAs($user)->get('/tasks');

    $response
        ->assertOk()
        ->assertInertia(
            fn (Assert $page) => $page
                ->component('Tasks/Index')
                ->has('tasks', 2)
                ->has(
                    'tasks.0',
                    fn (Assert $page) => $page
                        ->where('id', $tasks->first()->id)
                        ->where('ticket_id', $tasks->first()->ticket_id)
                        ->where('description', $tasks->first()->description)
                        ->where('cost', $tasks->first()->cost)
                        ->where('is_billable', $tasks->first()->is_billable)
                        ->where('type', $tasks->first()->type->value)
                        ->where('status', $tasks->first()->status->value)
                        ->etc()
                )
        );
});
