<?php

use App\Enums\TaskStatus;
use App\Models\Task;

it('can determine if task is cancelled', function () {
    $task = Task::factory()->cancelled()->create();

    expect($task->isCancelled())->toBeTrue();
});

it('can cancel task', function () {
    $task = Task::factory()->create();

    expect($task->isCancelled())->toBeFalse();

    $task->cancel();
    $task->refresh();

    expect($task->isCancelled())->toBeTrue();
});

describe('scopes', function () {
    beforeEach(function () {
        collect(TaskStatus::cases())->each(function (TaskStatus $status) {
            Task::factory()->ofStatus($status)->create();
        });
    });

    it('can filter tasks by cancelled scope', function () {
        expect(Task::cancelled()->count())->toBe(1);
        expect(Task::cancelled()->first()->isCancelled())->toBeTrue();
    });
});
