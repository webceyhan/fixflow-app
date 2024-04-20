<?php

use App\Models\Order;
use App\Models\User;
use Inertia\Testing\AssertableInertia as Assert;

it('can view all orders', function () {
    $user = User::factory()->create();
    $orders = Order::factory(2)->create();

    $response = $this->actingAs($user)->get('/orders');

    $response
        ->assertOk()
        ->assertInertia(
            fn (Assert $page) => $page
                ->component('Orders/Index')
                ->has('orders', 2)
                ->has(
                    'orders.0',
                    fn (Assert $page) => $page
                        ->where('id', $orders->first()->id)
                        ->where('ticket_id', $orders->first()->ticket_id)
                        ->where('name', $orders->first()->name)
                        ->where('url', $orders->first()->url)
                        ->where('quantity', $orders->first()->quantity)
                        ->where('cost', $orders->first()->cost)
                        ->where('is_billable', $orders->first()->is_billable)
                        ->where('status', $orders->first()->status->value)
                        ->etc()
                )
        );
});
