<?php

use App\Models\Order;
use App\Models\User;

use function Pest\Laravel\actingAs;

it('deletes an order successfully', function () {
    // Arrange
    $manager = User::factory()->manager()->create();
    $order = Order::factory()->create();

    // Act
    $response = actingAs($manager)->delete(route('orders.destroy', $order));

    // Assert
    $response->assertRedirect(route('orders.index'));
    expect(Order::find($order->id))->toBeNull();
});
