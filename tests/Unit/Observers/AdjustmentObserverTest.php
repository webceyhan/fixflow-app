<?php

use App\Models\Adjustment;
use App\Models\Invoice;
use App\Observers\AdjustmentObserver;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->observer = new AdjustmentObserver;

    // Helpers

    $this->mockAdjustment = function (bool $syncInvoice = false) {
        $adjustment = mock(Adjustment::class);

        if ($syncInvoice) {
            $invoice = mock(Invoice::class);
            $invoice->shouldReceive('fillAdjustmentAmounts')->once()->andReturnSelf();
            $invoice->shouldReceive('saveQuietly')->once()->andReturn(true);

            $adjustment->shouldReceive('load')->once()->with('invoice')->andReturnSelf();
            $adjustment->shouldReceive('getAttribute')->once()->with('invoice')->andReturn($invoice);
        }

        return $adjustment;
    };

    $this->mockAdjustmentWithUpdates = function (bool $amountOrPercentageChanged = false) {
        $adjustment = $this->mockAdjustment(syncInvoice: $amountOrPercentageChanged);

        $adjustment->shouldReceive('wasChanged')
            ->once()
            ->with(['amount', 'percentage'])
            ->andReturn($amountOrPercentageChanged);

        return $adjustment;
    };
});

it('updates invoice adjustment-amounts on creation', function () {
    // Arrange
    $adjustment = $this->mockAdjustment(syncInvoice: true);

    // Act
    $this->observer->created($adjustment);
});

it('does nothing when amount or percentage was not changed', function () {
    // Arrange
    $adjustment = $this->mockAdjustmentWithUpdates();

    // Act
    $this->observer->updated($adjustment);
});

it('updates invoice adjustment-amounts when amount or percentage was changed', function () {
    // Arrange
    $adjustment = $this->mockAdjustmentWithUpdates(amountOrPercentageChanged: true);

    // Act
    $this->observer->updated($adjustment);
});

it('updates invoice adjustment-amounts on deletion', function () {
    // Arrange
    $adjustment = $this->mockAdjustment(syncInvoice: true);

    // Act
    $this->observer->deleted($adjustment);
});
