<?php

use App\Models\Order;
use Illuminate\Support\Carbon;

it('can initialize order', function () {
    $order = new Order();

    expect($order->id)->toBeNull();
    expect($order->name)->toBeNull();
    expect($order->url)->toBeNull();
    expect($order->quantity)->toBe(1);
    expect($order->cost)->toBe(0.0);
    expect($order->is_billable)->toBeTrue();
    expect($order->created_at)->toBeNull();
    expect($order->updated_at)->toBeNull();
});

it('can create order', function () {
    $order = Order::factory()->create();

    expect($order->id)->toBeInt();
    expect($order->name)->toBeString();
    expect($order->url)->toBeString();
    expect($order->quantity)->toBeInt();
    expect($order->cost)->toBeFloat();
    expect($order->is_billable)->toBeTrue();
    expect($order->created_at)->toBeInstanceOf(Carbon::class);
    expect($order->updated_at)->toBeInstanceOf(Carbon::class);
});

it('can create order as free', function () {
    $order = Order::factory()->free()->create();

    expect($order->is_billable)->toBeFalse();
});

it('can update order', function () {
    $order = Order::factory()->create();

    $order->update([
        'name' => 'Corsair Vengeance 16GB',
        'url' => 'https://www.corsair.com',
        'quantity' => 2,
        'cost' => 200,
        'is_billable' => false,
    ]);

    expect($order->name)->toBe('Corsair Vengeance 16GB');
    expect($order->url)->toBe('https://www.corsair.com');
    expect($order->quantity)->toBe(2);
    expect($order->cost)->toBe(200.0);
    expect($order->is_billable)->toBeFalse();
});

it('can delete order', function () {
    $order = Order::factory()->create();

    $order->delete();

    expect(Order::find($order->id))->toBeNull();
});

// Billable ////////////////////////////////////////////////////////////////////////////////////////

it('can filter orders by billable scope', function () {
    Order::factory()->create();
    Order::factory()->free()->create();

    expect(Order::billable()->count())->toBe(1);
    expect(Order::billable()->first()->is_billable)->toBeTrue();
});
