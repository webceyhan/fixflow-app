<?php

use App\Models\Invoice;
use App\Observers\InvoiceObserver;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->observer = new InvoiceObserver;

    // Helpers

    $this->mockInvoice = function (bool $sync = false) {
        $invoice = mock(Invoice::class);

        $invoice->shouldReceive('load')->with('adjustments')->andReturnSelf();
        $invoice->shouldReceive('hasPercentageAdjustments')->andReturn($sync);

        if ($sync) {
            $invoice->shouldReceive('fillAdjustmentAmounts')->once()->andReturnSelf();
            $invoice->shouldReceive('saveQuietly')->once()->andReturn(true);
        }

        return $invoice;
    };

    $this->mockInvoiceWithUpdates = function (bool $subTotalChanged = false, bool $hasPercentageAdjustments = false) {
        $invoice = $this->mockInvoice(sync: $subTotalChanged && $hasPercentageAdjustments);

        $invoice->shouldReceive('wasChanged')
            ->once()
            ->with(['task_total', 'order_total'])
            ->andReturn($subTotalChanged);

        return $invoice;
    };
});

it('does nothing when subtotal components were not changed', function () {
    // Arrange
    $invoice = $this->mockInvoiceWithUpdates();

    // Act
    $this->observer->updated($invoice);
});

it('does nothing when subtotal changed but no percentage adjustments exist', function () {
    // Arrange
    $invoice = $this->mockInvoiceWithUpdates(subTotalChanged: true);

    // Act
    $this->observer->updated($invoice);
});

it('updates adjustment amounts when subtotal changed and percentage adjustments exist', function () {
    // Arrange
    $invoice = $this->mockInvoiceWithUpdates(subTotalChanged: true, hasPercentageAdjustments: true);

    // Act
    $this->observer->updated($invoice);
});
