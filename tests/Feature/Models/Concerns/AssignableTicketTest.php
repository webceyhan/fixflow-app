<?php

use App\Models\Ticket;
use App\Models\User;

it('can have an assignee', function () {
    $user = User::factory()->create();
    $ticket = Ticket::factory()->forAssignee($user)->create();

    expect($ticket->assignee)->toBeInstanceOf(User::class);
    expect($ticket->assignee->id)->toBe($user->id);
});

it('can determine if ticket is assignable', function () {
    $ticket = Ticket::factory()->create();

    expect($ticket->isAssignable())->toBeTrue();
    expect($ticket->assignee_id)->toBeNull();
});

it('can assign a user to ticket', function () {
    $user = User::factory()->create();
    $ticket = Ticket::factory()->create();

    $ticket->assignTo($user);

    expect($ticket->isAssignable())->toBeFalse();
    expect($ticket->assignee->id)->toBe($user->id);
});

it('can unassign a user from ticket', function () {
    $ticket = Ticket::factory()->assigned()->create();

    $ticket->unassign();

    expect($ticket->isAssignable())->toBeTrue();
    expect($ticket->assignee)->toBeNull();
});

describe('scopes', function () {
    beforeEach(function () {
        Ticket::factory()->create();
        Ticket::factory()->assigned()->create();
    });

    it('can filter tickets by assignable scope', function () {
        expect(Ticket::assignable()->count())->toBe(1);
        expect(Ticket::assignable()->first()->isAssignable())->toBeTrue();
    });

    it('can filter tickets by assigned scope', function () {
        expect(Ticket::assigned()->count())->toBe(1);
        expect(Ticket::assigned()->first()->isAssignable())->toBeFalse();
    });
});
