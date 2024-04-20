<?php

use App\Models\Ticket;
use App\Models\User;
use Inertia\Testing\AssertableInertia as Assert;

it('can view all tickets', function () {
    $user = User::factory()->create();
    $tickets = Ticket::factory(2)->create();

    $response = $this->actingAs($user)->get('/tickets');

    $response
        ->assertOk()
        ->assertInertia(
            fn (Assert $page) => $page
                ->component('Tickets/Index')
                ->has('tickets', 2)
                ->has(
                    'tickets.0',
                    fn (Assert $page) => $page
                        ->where('id', $tickets->first()->id)
                        ->where('assignee_id', $tickets->first()->assignee_id)
                        ->where('customer_id', $tickets->first()->customer_id)
                        ->where('description', $tickets->first()->description)
                        ->where('priority', $tickets->first()->priority->value)
                        ->where('status', $tickets->first()->status->value)
                        ->where('total_cost', (int)$tickets->first()->total_cost)
                        ->where('total_tasks_count', $tickets->first()->total_tasks_count)
                        ->where('pending_tasks_count', $tickets->first()->pending_tasks_count)
                        ->where('total_orders_count', $tickets->first()->total_orders_count)
                        ->where('pending_orders_count', $tickets->first()->pending_orders_count)
                        ->etc()
                )
        );
});

it('can view a ticket', function () {
    $user = User::factory()->create();
    $ticket = Ticket::factory()->create();

    $response = $this->actingAs($user)->get('/tickets/' . $ticket->id);

    $response
        ->assertOk()
        ->assertInertia(
            fn (Assert $page) => $page
                ->component('Tickets/Show')
                ->has(
                    'ticket',
                    fn (Assert $page) => $page
                        ->where('id', $ticket->id)
                        ->where('assignee_id', $ticket->assignee_id)
                        ->where('customer_id', $ticket->customer_id)
                        ->where('description', $ticket->description)
                        ->where('priority', $ticket->priority->value)
                        ->where('status', $ticket->status->value)
                        ->where('total_cost', (int)$ticket->total_cost)
                        ->where('total_tasks_count', $ticket->total_tasks_count)
                        ->where('pending_tasks_count', $ticket->pending_tasks_count)
                        ->where('total_orders_count', $ticket->total_orders_count)
                        ->where('pending_orders_count', $ticket->pending_orders_count)
                        ->etc()
                )
        );
});
