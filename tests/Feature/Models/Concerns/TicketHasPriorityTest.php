<?php

use App\Enums\Priority;
use App\Models\Ticket;

it('can filter tickets by priority scope', function (Priority $priority) {
    Ticket::factory()->ofPriority($priority)->create();

    expect(Ticket::ofPriority($priority)->count())->toBe(1);
    expect(Ticket::ofPriority($priority)->first()->priority)->toBe($priority);
})->with(Priority::cases());

it('can sort tickets by prioritized scope', function () {
    Ticket::factory()->create();
    Ticket::factory()->ofPriority(Priority::Low)->create();
    Ticket::factory()->ofPriority(Priority::High)->create();

    expect(Ticket::prioritized()->get()->map->priority->all())->toBe([
        Priority::High,
        Priority::Normal,
        Priority::Low,
    ]);
});
