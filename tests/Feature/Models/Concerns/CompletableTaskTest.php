<?php

use App\Enums\TaskStatus;
use App\Models\Task;

it('can determine if task is completed', function (TaskStatus $status) {
    $task = Task::factory()->ofStatus($status)->create();

    expect($task->isCompleted())->toBeTrue();
})->with(TaskStatus::completedCases());

describe('scopes', function () {
    beforeEach(function () {
        collect(TaskStatus::cases())->each(function (TaskStatus $status) {
            Task::factory()->ofStatus($status)->create();
        });
    });

    it('can filter tasks by pending scope', function () {
        expect(Task::pending()->count())->toBe(2);
        expect(Task::pending()->first()->isCompleted())->toBeFalse();
    });

    it('can filter tasks by completed scope', function () {
        expect(Task::completed()->count())->toBe(2);
        expect(Task::completed()->first()->isCompleted())->toBeTrue();
    });
});
