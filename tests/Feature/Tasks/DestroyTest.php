<?php

use App\Models\Task;
use App\Models\User;

use function Pest\Laravel\actingAs;

it('deletes a task successfully', function () {
    // Arrange
    $manager = User::factory()->manager()->create();
    $task = Task::factory()->create();

    // Act
    $response = actingAs($manager)->delete(route('tasks.destroy', $task));

    // Assert
    $response->assertRedirect(route('tasks.index'));
    expect(Task::find($task->id))->toBeNull();
});
