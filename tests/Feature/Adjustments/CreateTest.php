<?php

declare(strict_types=1);

use App\Enums\AdjustmentReason;
use App\Enums\AdjustmentType;
use App\Models\Invoice;
use App\Models\User;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\assertDatabaseHas;

it('shows the create form to authenticated users', function () {
    // Arrange
    $manager = User::factory()->manager()->create();
    $invoice = Invoice::factory()->create();

    // Act
    $response = actingAs($manager)->get(route('adjustments.create', $invoice));

    // Assert
    $response->assertSuccessful();
    $response->assertInertia(fn ($page) => $page
        ->component('adjustments/Create')
        ->has('invoice')
        ->where('invoice.id', $invoice->id)
    );
});

it('validates required fields when creating an adjustment', function () {
    // Arrange
    $manager = User::factory()->manager()->create();
    $invoice = Invoice::factory()->create();

    // Act
    $response = actingAs($manager)->post(route('adjustments.store', $invoice), []);

    // Assert
    $response->assertSessionHasErrors(['amount', 'reason']);
});

it('validates amount must be numeric and positive', function () {
    // Arrange
    $manager = User::factory()->manager()->create();
    $invoice = Invoice::factory()->create();

    // Act
    $response = actingAs($manager)->post(route('adjustments.store', $invoice), [
        'amount' => -10,
        'reason' => AdjustmentReason::Compensation->value,
    ]);

    // Assert
    $response->assertSessionHasErrors(['amount']);

    // Act
    $response = actingAs($manager)->post(route('adjustments.store', $invoice), [
        'amount' => 'invalid',
        'reason' => AdjustmentReason::Compensation->value,
    ]);

    // Assert
    $response->assertSessionHasErrors(['amount']);
});

it('validates reason must be a valid AdjustmentReason enum', function () {
    // Arrange
    $manager = User::factory()->manager()->create();
    $invoice = Invoice::factory()->create();

    // Act
    $response = actingAs($manager)->post(route('adjustments.store', $invoice), [
        'amount' => 50,
        'reason' => 'invalid_reason',
    ]);

    // Assert
    $response->assertSessionHasErrors(['reason']);
});

it('creates an adjustment with all fields', function () {
    // Arrange
    $manager = User::factory()->manager()->create();
    $invoice = Invoice::factory()->create();
    $data = [
        'amount' => 100,
        'is_percent' => false,
        'note' => 'Test discount note',
        'reason' => AdjustmentReason::Compensation->value,
    ];

    // Act
    $response = actingAs($manager)->post(route('adjustments.store', $invoice), $data);

    // Assert
    $response->assertRedirect(route('invoices.show', $invoice));
    assertDatabaseHas('adjustments', [
        'invoice_id' => $invoice->id,
        'amount' => 100,
        'is_percent' => false,
        'note' => 'Test discount note',
        'reason' => AdjustmentReason::Compensation->value,
        'type' => AdjustmentType::Discount->value,
    ]);
});

it('creates an adjustment with required fields only', function () {
    // Arrange
    $manager = User::factory()->manager()->create();
    $invoice = Invoice::factory()->create();
    $data = [
        'amount' => 50,
        'reason' => AdjustmentReason::Compensation->value,
    ];

    // Act
    $response = actingAs($manager)->post(route('adjustments.store', $invoice), $data);

    // Assert
    $response->assertRedirect(route('invoices.show', $invoice));
    assertDatabaseHas('adjustments', [
        'invoice_id' => $invoice->id,
        'amount' => 50,
        'is_percent' => false,
        'note' => null,
        'reason' => AdjustmentReason::Compensation->value,
        'type' => AdjustmentType::Discount->value,
    ]);
});

it('auto-syncs type from reason when creating', function () {
    // Arrange
    $manager = User::factory()->manager()->create();
    $invoice = Invoice::factory()->create();
    $discountData = [
        'amount' => 50,
        'reason' => AdjustmentReason::Compensation->value,
    ];
    $feeData = [
        'amount' => 25,
        'reason' => AdjustmentReason::RushService->value,
    ];

    // Act
    actingAs($manager)->post(route('adjustments.store', $invoice), $discountData);

    // Assert
    assertDatabaseHas('adjustments', [
        'invoice_id' => $invoice->id,
        'reason' => AdjustmentReason::Compensation->value,
        'type' => AdjustmentType::Discount->value,
    ]);

    // Act
    actingAs($manager)->post(route('adjustments.store', $invoice), $feeData);

    // Assert
    assertDatabaseHas('adjustments', [
        'invoice_id' => $invoice->id,
        'reason' => AdjustmentReason::RushService->value,
        'type' => AdjustmentType::Fee->value,
    ]);
});

it('syncs invoice discount amount after creating a discount adjustment', function () {
    // Arrange
    $manager = User::factory()->manager()->create();
    $invoice = Invoice::factory()->create([
        'task_total' => 200,
        'order_total' => 100,
    ]);
    $data = [
        'amount' => 30,
        'is_percent' => false,
        'reason' => AdjustmentReason::Compensation->value,
    ];

    // Act
    actingAs($manager)->post(route('adjustments.store', $invoice), $data);

    // Assert
    $invoice->refresh();
    expect($invoice->discount_amount)->toBe(30.0);
});

it('syncs invoice fee amount after creating a fee adjustment', function () {
    // Arrange
    $manager = User::factory()->manager()->create();
    $invoice = Invoice::factory()->create([
        'task_total' => 200,
        'order_total' => 100,
    ]);
    $data = [
        'amount' => 15,
        'is_percent' => false,
        'reason' => AdjustmentReason::RushService->value,
    ];

    // Act
    actingAs($manager)->post(route('adjustments.store', $invoice), $data);

    // Assert
    $invoice->refresh();
    expect($invoice->fee_amount)->toBe(15.0);
});

it('calculates percentage-based discount correctly', function () {
    // Arrange
    $manager = User::factory()->manager()->create();
    $invoice = Invoice::factory()->create([
        'task_total' => 200,
        'order_total' => 100,
    ]);
    $data = [
        'amount' => 10,
        'is_percent' => true,
        'reason' => AdjustmentReason::Compensation->value,
    ];

    // Act
    actingAs($manager)->post(route('adjustments.store', $invoice), $data);

    // Assert
    $invoice->refresh();
    // 10% of (200 + 100) = 30
    expect($invoice->discount_amount)->toBe(30.0);
});

it('redirects to invoice show page after successful creation', function () {
    // Arrange
    $manager = User::factory()->manager()->create();
    $invoice = Invoice::factory()->create();
    $data = [
        'amount' => 50,
        'reason' => AdjustmentReason::Compensation->value,
    ];

    // Act
    $response = actingAs($manager)->post(route('adjustments.store', $invoice), $data);

    // Assert
    $response->assertRedirect(route('invoices.show', $invoice));
});
