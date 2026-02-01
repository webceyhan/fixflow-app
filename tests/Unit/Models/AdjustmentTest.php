<?php

use App\Enums\AdjustmentReason;
use App\Enums\AdjustmentType;
use App\Models\Adjustment;
use App\Models\Concerns\HasType;
use App\Models\Invoice;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

// STRUCTURE TESTS /////////////////////////////////////////////////////////////////////////////////

testModelStructure(
    modelClass: Adjustment::class,
    concerns: [
        HasType::class,
    ],
    defaults: [
        'amount' => 0,
        'is_percent' => false,
        'type' => AdjustmentType::Discount,
        'reason' => AdjustmentReason::Welcome,
    ],
    fillables: [
        'invoice_id',
        'amount',
        'is_percent',
        'note',
        'type',
        'reason',
    ],
    hiddens: [
        // No hidden attributes
    ],
    casts: [
        'amount' => 'float',
        'is_percent' => 'boolean',
        'reason' => AdjustmentReason::class,
        'type' => AdjustmentType::class,
    ],
    relations: [
        'invoice' => BelongsTo::class,
    ]
);

// RELATION TESTS //////////////////////////////////////////////////////////////////////////////////

it('belongs to invoice relationship', function () {
    // Arrange
    $invoice = Invoice::factory()->create();
    $adjustment = Adjustment::factory()->forInvoice($invoice)->create();

    // Assert
    expect($adjustment->invoice)->toBeInstanceOf(Invoice::class);
    expect($adjustment->invoice->id)->toBe($invoice->id);
});

// SCOPE TESTS /////////////////////////////////////////////////////////////////////////////////////

it('scopes adjustments by reason', function () {
    // Arrange
    Adjustment::factory()->count(2)->create(['reason' => AdjustmentReason::Welcome]);
    Adjustment::factory()->count(3)->create(['reason' => AdjustmentReason::RushService]);

    // Act
    $welcomeAdjustments = Adjustment::ofReason(AdjustmentReason::Welcome)->get();

    // Assert
    expect($welcomeAdjustments)->toHaveCount(2);
    expect($welcomeAdjustments->first()->reason)->toBe(AdjustmentReason::Welcome);
});

// METHOD TESTS ////////////////////////////////////////////////////////////////////////////////////
