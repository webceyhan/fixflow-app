<?php

use App\Models\Adjustment;
use App\Models\Invoice;
use App\Observers\AdjustmentObserver;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->observer = new AdjustmentObserver;
});

it('updates invoice adjustment amounts on creation', function () {
    // Arrange
    $invoice = mock(Invoice::class);
    $adjustment = mock(Adjustment::class);

    $adjustment->shouldReceive('load')
        ->once()
        ->with('invoice')
        ->andReturnSelf();

    $adjustment->shouldReceive('getAttribute')
        ->with('invoice')
        ->once()
        ->andReturn($invoice);

    $invoice->shouldReceive('fillAdjustmentAmounts')
        ->once()
        ->andReturnSelf();

    $invoice->shouldReceive('saveQuietly')
        ->once()
        ->andReturn(true);

    // Act
    $this->observer->created($adjustment);
});

it('does nothing when amount or percentage was not changed', function () {
    // Arrange
    $adjustment = mock(Adjustment::class);

    $adjustment->shouldReceive('wasChanged')
        ->once()
        ->with(['amount', 'percentage'])
        ->andReturn(false);

    // adjustment should not be loaded or modified when no relevant changes
    $adjustment->shouldNotReceive('load');

    // Act
    $this->observer->updated($adjustment);
});

it('updates invoice adjustment amounts when amount or percentage was changed', function () {
    // Arrange
    $invoice = mock(Invoice::class);
    $adjustment = mock(Adjustment::class);

    $adjustment->shouldReceive('wasChanged')
        ->once()
        ->with(['amount', 'percentage'])
        ->andReturn(true);

    $adjustment->shouldReceive('load')
        ->once()
        ->with('invoice')
        ->andReturnSelf();

    $adjustment->shouldReceive('getAttribute')
        ->with('invoice')
        ->once()
        ->andReturn($invoice);

    $invoice->shouldReceive('fillAdjustmentAmounts')
        ->once()
        ->andReturnSelf();

    $invoice->shouldReceive('saveQuietly')
        ->once()
        ->andReturn(true);

    // Act
    $this->observer->updated($adjustment);
});

it('updates invoice adjustment amounts on deletion', function () {
    // Arrange
    $invoice = mock(Invoice::class);
    $adjustment = mock(Adjustment::class);

    $adjustment->shouldReceive('load')
        ->once()
        ->with('invoice')
        ->andReturnSelf();

    $adjustment->shouldReceive('getAttribute')
        ->with('invoice')
        ->once()
        ->andReturn($invoice);

    $invoice->shouldReceive('fillAdjustmentAmounts')
        ->once()
        ->andReturnSelf();

    $invoice->shouldReceive('saveQuietly')
        ->once()
        ->andReturn(true);

    // Act
    $this->observer->deleted($adjustment);
});
