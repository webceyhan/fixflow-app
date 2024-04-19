<?php

use App\Enums\TicketStatus;
use App\Models\Ticket;

it('can determine if ticket is completed', function (TicketStatus $status) {
    $ticket = Ticket::factory()->ofStatus($status)->create();

    expect($ticket->isCompleted())->toBeTrue();
})->with(TicketStatus::completedCases());

describe('scopes', function () {
    beforeEach(function () {
        collect(TicketStatus::cases())->each(function (TicketStatus $status) {
            Ticket::factory()->ofStatus($status)->create();
        });
    });

    it('can filter tickets by pending scope', function () {
        expect(Ticket::pending()->count())->toBe(3);
        expect(Ticket::pending()->first()->isCompleted())->toBeFalse();
    });

    it('can filter tickets by completed scope', function () {
        expect(Ticket::completed()->count())->toBe(2);
        expect(Ticket::completed()->first()->isCompleted())->toBeTrue();
    });
});
