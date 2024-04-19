<?php

use App\Enums\OrderStatus;
use App\Models\Order;

it('can determine if order is completed', function (OrderStatus $status) {
    $order = Order::factory()->ofStatus($status)->create();

    expect($order->isCompleted())->toBeTrue();
})->with(OrderStatus::completedCases());

describe('scopes', function () {
    beforeEach(function () {
        collect(OrderStatus::cases())->each(function (OrderStatus $status) {
            Order::factory()->ofStatus($status)->create();
        });
    });

    it('can filter orders by pending scope', function () {
        expect(Order::pending()->count())->toBe(2);
        expect(Order::pending()->first()->isCompleted())->toBeFalse();
    });

    it('can filter orders by completed scope', function () {
        expect(Order::completed()->count())->toBe(2);
        expect(Order::completed()->first()->isCompleted())->toBeTrue();
    });
});
