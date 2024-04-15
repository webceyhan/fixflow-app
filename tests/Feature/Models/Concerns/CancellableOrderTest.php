<?php

use App\Enums\OrderStatus;
use App\Models\Order;

it('can determine if order is cancelled', function () {
    $order = Order::factory()->cancelled()->create();

    expect($order->isCancelled())->toBeTrue();
});

it('can cancel order', function () {
    $order = Order::factory()->create();

    expect($order->isCancelled())->toBeFalse();

    $order->cancel();
    $order->refresh();

    expect($order->isCancelled())->toBeTrue();
});

describe('scopes', function () {
    beforeEach(function () {
        collect(OrderStatus::cases())->each(function (OrderStatus $status) {
            Order::factory()->ofStatus($status)->create();
        });
    });

    it('can filter orders by cancelled scope', function () {
        expect(Order::cancelled()->count())->toBe(1);
        expect(Order::cancelled()->first()->isCancelled())->toBeTrue();
    });
});
