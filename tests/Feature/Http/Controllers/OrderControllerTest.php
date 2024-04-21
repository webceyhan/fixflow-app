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

it('can view an order', function () {
    $user = User::factory()->create();
    $order = Order::factory()->create();

    $response = $this->actingAs($user)->get('/orders/' . $order->id);

    $response
        ->assertOk()
        ->assertInertia(
            fn (Assert $page) => $page
                ->component('Orders/Show')
                ->has(
                    'order',
                    fn (Assert $page) => $page
                        ->where('id', $order->id)
                        ->where('ticket_id', $order->ticket_id)
                        ->where('name', $order->name)
                        ->where('url', $order->url)
                        ->where('quantity', $order->quantity)
                        ->where('cost', $order->cost)
                        ->where('is_billable', $order->is_billable)
                        ->where('status', $order->status->value)
                        ->etc()
                )
        );
});

it('can delete an order', function () {
    $user = User::factory()->create();
    $order = Order::factory()->create();

    $response = $this->actingAs($user)->delete('/orders/' . $order->id);

    $response->assertRedirect('/orders');

    $this->assertNull($order->fresh());
});
