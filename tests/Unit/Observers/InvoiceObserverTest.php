<?php

use App\Models\Invoice;
use App\Observers\InvoiceObserver;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->observer = new InvoiceObserver;
});

it('does nothing when subtotal components were not changed', function () {
    // Arrange
    $invoice = mock(Invoice::class);

    $invoice->shouldReceive('wasChanged')
        ->once()
        ->with(['task_total', 'order_total'])
        ->andReturn(false);

    // invoice should not be loaded or modified when no relevant changes
    $invoice->shouldNotReceive('load');
    $invoice->shouldNotReceive('hasPercentageAdjustments');

    // Act
    $this->observer->updated($invoice);
});

it('does nothing when subtotal changed but no percentage adjustments exist', function () {
    // Arrange
    $invoice = mock(Invoice::class);

    $invoice->shouldReceive('wasChanged')
        ->once()
        ->with(['task_total', 'order_total'])
        ->andReturn(true);

    $invoice->shouldReceive('load')
        ->once()
        ->with('adjustments')
        ->andReturnSelf();

    $invoice->shouldReceive('hasPercentageAdjustments')
        ->once()
        ->andReturn(false);

    // should not recalculate when no percentage adjustments
    $invoice->shouldNotReceive('fillAdjustmentAmounts');

    // Act
    $this->observer->updated($invoice);
});

it('updates adjustment amounts when subtotal changed and percentage adjustments exist', function () {
    // Arrange
    $invoice = mock(Invoice::class);

    $invoice->shouldReceive('wasChanged')
        ->once()
        ->with(['task_total', 'order_total'])
        ->andReturn(true);

    $invoice->shouldReceive('load')
        ->once()
        ->with('adjustments')
        ->andReturnSelf();

    $invoice->shouldReceive('hasPercentageAdjustments')
        ->once()
        ->andReturn(true);

    $invoice->shouldReceive('fillAdjustmentAmounts')
        ->once()
        ->andReturnSelf();

    $invoice->shouldReceive('saveQuietly')
        ->once()
        ->andReturn(true);

    // Act
    $this->observer->updated($invoice);
});
