<?php

use App\Models\Order;

describe('scopes', function () {
    beforeEach(function () {
        Order::factory()->create();
        Order::factory()->free()->create();
    });

    it('can filter orders by billable scope', function () {
        expect(Order::billable()->count())->toBe(1);
        expect(Order::billable()->first()->is_billable)->toBeTrue();
    });

    it('can filter orders by free scope', function () {
        expect(Order::free()->count())->toBe(1);
        expect(Order::free()->first()->is_billable)->toBeFalse();
    });
});
