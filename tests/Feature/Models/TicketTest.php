<?php

use App\Enums\Priority;
use App\Enums\TicketStatus;
use App\Models\Customer;
use App\Models\Device;
use App\Models\Order;
use App\Models\Task;
use App\Models\Ticket;
use App\Models\User;
use Illuminate\Support\Carbon;

it('can initialize ticket', function () {
    $ticket = new Ticket();

    expect($ticket->id)->toBeNull();
    expect($ticket->assignee_id)->toBeNull();
    expect($ticket->customer_id)->toBeNull();
    expect($ticket->description)->toBeNull();
    expect($ticket->priority)->toBe(Priority::Normal);
    expect($ticket->status)->toBe(TicketStatus::New);
    expect($ticket->created_at)->toBeNull();
    expect($ticket->updated_at)->toBeNull();
});

it('can create ticket', function () {
    $ticket = Ticket::factory()->create();

    expect($ticket->id)->toBeInt();
    expect($ticket->assignee_id)->toBeNull();
    expect($ticket->customer_id)->toBeInt();
    expect($ticket->description)->toBeString();
    expect($ticket->priority)->toBe(Priority::Normal);
    expect($ticket->status)->toBe(TicketStatus::New);
    expect($ticket->created_at)->toBeInstanceOf(Carbon::class);
    expect($ticket->updated_at)->toBeInstanceOf(Carbon::class);
});

it('can create ticket with assignee', function () {
    $ticket = Ticket::factory()->assigned()->create();

    expect($ticket->assignee->id)->toBe(User::first()->id);
});

it('can create ticket of priority', function () {
    $ticket = Ticket::factory()->ofPriority(Priority::High)->create();

    expect($ticket->priority)->toBe(Priority::High);
});

it('can create ticket of status', function () {
    $ticket = Ticket::factory()->ofStatus(TicketStatus::Closed)->create();

    expect($ticket->status)->toBe(TicketStatus::Closed);
});

it('can update ticket', function () {
    $ticket = Ticket::factory()->create();

    $ticket->update([
        'description' => 'Repair iPhone 13 Pro',
        'priority' => Priority::High,
        'status' => TicketStatus::InProgress,
    ]);

    expect($ticket->description)->toBe('Repair iPhone 13 Pro');
    expect($ticket->priority)->toBe(Priority::High);
    expect($ticket->status)->toBe(TicketStatus::InProgress);
});

it('can delete ticket', function () {
    $ticket = Ticket::factory()->create();

    $ticket->delete();

    expect(Ticket::find($ticket->id))->toBeNull();
});

// Assignee ////////////////////////////////////////////////////////////////////////////////////////

it('can have assignee', function () {
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

it('can assign ticket to a user', function () {
    $user = User::factory()->create();
    $ticket = Ticket::factory()->create();

    // assign
    $ticket->assignee()->associate($user)->save();

    expect($ticket->isAssignable())->toBeFalse();

    // unassign
    $ticket->assignee()->dissociate()->save();

    expect($ticket->isAssignable())->toBeTrue();
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

// Customer ////////////////////////////////////////////////////////////////////////////////////////

it('belongs to a customer', function () {
    $customer = Customer::factory()->create();
    $ticket = Ticket::factory()->forCustomer($customer)->create();

    expect($ticket->customer)->toBeInstanceOf(Customer::class);
    expect($ticket->customer->id)->toBe($customer->id);
});

// Device //////////////////////////////////////////////////////////////////////////////////////////

it('belongs to a device', function () {
    $device = Device::factory()->create();
    $ticket = Ticket::factory()->forDevice($device)->create();

    expect($ticket->device)->toBeInstanceOf(Device::class);
    expect($ticket->device->id)->toBe($device->id);
});

// Tasks ///////////////////////////////////////////////////////////////////////////////////////////

it('can have many tasks', function () {
    $ticket = Ticket::factory()->hasTasks(2)->create();

    expect($ticket->tasks)->toHaveCount(2);
});

it('can delete ticket with tasks', function () {
    $ticket = Ticket::factory()->hasTasks(2)->create();

    $ticket->delete();

    expect(Ticket::find($ticket->id))->toBeNull();
    expect(Task::count())->toBe(0);
});

// Orders //////////////////////////////////////////////////////////////////////////////////////////

it('can have many orders', function () {
    $ticket = Ticket::factory()->hasOrders(2)->create();

    expect($ticket->orders)->toHaveCount(2);
});

it('can delete ticket with orders', function () {
    $ticket = Ticket::factory()->hasOrders(2)->create();

    $ticket->delete();

    expect(Ticket::find($ticket->id))->toBeNull();
    expect(Order::count())->toBe(0);
});

// Priority ////////////////////////////////////////////////////////////////////////////////////////

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

// Status ////////////////////////////////////////////////////////////////////////////////////////

it('can filter tickets by status scope', function (TicketStatus $status) {
    Ticket::factory()->ofStatus($status)->create();

    expect(Ticket::ofStatus($status)->count())->toBe(1);
    expect(Ticket::ofStatus($status)->first()->status)->toBe($status);
})->with(TicketStatus::cases());
