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
