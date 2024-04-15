<?php

use App\Enums\Priority;
use App\Enums\TicketStatus;
use App\Models\Customer;
use App\Models\Device;
use App\Models\Invoice;
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
    expect($ticket->total_cost)->toBe(0.0);
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
    expect($ticket->total_cost)->toBe(0.0);
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

// Invoice /////////////////////////////////////////////////////////////////////////////////////////

it('can have one invoice', function () {
    $ticket = Ticket::factory()->hasInvoice()->create();

    expect($ticket->invoice)->toBeInstanceOf(Invoice::class);
});

it('can delete ticket with invoice', function () {
    $ticket = Ticket::factory()->hasInvoice()->create();

    $ticket->delete();

    expect(Ticket::find($ticket->id))->toBeNull();
    expect(Invoice::count())->toBe(0);
});

// Status ////////////////////////////////////////////////////////////////////////////////////////

it('can filter tickets by status scope', function (TicketStatus $status) {
    Ticket::factory()->ofStatus($status)->create();

    expect(Ticket::ofStatus($status)->count())->toBe(1);
    expect(Ticket::ofStatus($status)->first()->status)->toBe($status);
})->with(TicketStatus::cases());

// Total Cost //////////////////////////////////////////////////////////////////////////////////////

it('can update ticket total_cost automatically', function () {
    $ticket = Ticket::factory()->create();
    $task = Task::factory()->forTicket($ticket)->create();
    $order = Order::factory()->forTicket($ticket)->create();

    // ignore non-billable tasks, orders
    Task::factory()->forTicket($ticket)->free()->create();
    Order::factory()->forTicket($ticket)->free()->create();

    $ticket->refresh();

    expect($ticket->total_cost)->toBe($task->cost + $order->cost);
});
