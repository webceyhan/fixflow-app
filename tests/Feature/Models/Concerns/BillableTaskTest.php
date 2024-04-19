<?php

use App\Models\Task;

describe('scopes', function () {
    beforeEach(function () {
        Task::factory()->create();
        Task::factory()->free()->create();
    });

    it('can filter tasks by billable scope', function () {
        expect(Task::billable()->count())->toBe(1);
        expect(Task::billable()->first()->is_billable)->toBeTrue();
    });

    it('can filter tasks by free scope', function () {
        expect(Task::free()->count())->toBe(1);
        expect(Task::free()->first()->is_billable)->toBeFalse();
    });
});
