<?php

declare(strict_types=1);

use App\Enums\AdjustmentReason;
use App\Enums\AdjustmentType;
use App\Models\Adjustment;
use App\Models\User;

use function Pest\Laravel\actingAs;

it('shows the edit form to authenticated users', function () {
    // Arrange
    $manager = User::factory()->manager()->create();
    $adjustment = Adjustment::factory()->create();

    // Act
    $response = actingAs($manager)->get(route('adjustments.edit', $adjustment));

    // Assert
    $response->assertSuccessful();
    $response->assertInertia(fn ($page) => $page
        ->component('adjustments/Edit')
        ->has('adjustment')
        ->where('adjustment.id', $adjustment->id)
        ->has('invoice')
    );
});

it('validates required fields when updating an adjustment', function () {
    // Arrange
    $manager = User::factory()->manager()->create();
    $adjustment = Adjustment::factory()->create();

    // Act
    $response = actingAs($manager)->put(route('adjustments.update', $adjustment), [
        'amount' => '',
        'reason' => '',
    ]);

    // Assert
    $response->assertSessionHasErrors(['amount']);
});

it('validates amount must be numeric and positive', function () {
    // Arrange
    $manager = User::factory()->manager()->create();
    $adjustment = Adjustment::factory()->create();

    // Act
    $response = actingAs($manager)->put(route('adjustments.update', $adjustment), [
        'amount' => -10,
        'reason' => AdjustmentReason::Compensation->value,
    ]);

    // Assert
    $response->assertSessionHasErrors(['amount']);

    // Act
    $response = actingAs($manager)->put(route('adjustments.update', $adjustment), [
        'amount' => 'invalid',
        'reason' => AdjustmentReason::Compensation->value,
    ]);

    // Assert
    $response->assertSessionHasErrors(['amount']);
});

it('updates an adjustment successfully', function () {
    // Arrange
    $manager = User::factory()->manager()->create();
    $adjustment = Adjustment::factory()->create();
    $data = [
        'amount' => 150,
        'is_percent' => true,
        'note' => 'Updated discount note',
        'reason' => AdjustmentReason::Loyalty->value,
    ];

    // Act
    $response = actingAs($manager)->put(route('adjustments.update', $adjustment), $data);

    // Assert
    $response->assertRedirect(route('invoices.show', $adjustment->invoice));

    $adjustment->refresh();
    expect($adjustment->amount)->toBe(150.0)
        ->and($adjustment->is_percent)->toBe(true)
        ->and($adjustment->note)->toBe('Updated discount note')
        ->and($adjustment->reason)->toBe(AdjustmentReason::Loyalty)
        ->and($adjustment->type)->toBe(AdjustmentType::Discount);
});

it('auto-syncs type from reason when updating', function () {
    // Arrange
    $manager = User::factory()->manager()->create();
    $adjustment = Adjustment::factory()->create([
        'reason' => AdjustmentReason::Compensation,
        'type' => AdjustmentType::Discount,
    ]);
    $data = [
        'amount' => 50,
        'reason' => AdjustmentReason::RushService->value,
    ];

    // Act
    actingAs($manager)->put(route('adjustments.update', $adjustment), $data);

    // Assert
    $adjustment->refresh();
    expect($adjustment->reason)->toBe(AdjustmentReason::RushService)
        ->and($adjustment->type)->toBe(AdjustmentType::Fee);
});

it('syncs invoice amounts after updating an adjustment', function () {
    // Arrange
    $manager = User::factory()->manager()->create();
    $adjustment = Adjustment::factory()->create();
    $adjustment->invoice->update([
        'task_total' => 200,
        'order_total' => 100,
        'discount_amount' => 50,
    ]);
    $data = [
        'amount' => 75,
        'is_percent' => false,
        'reason' => AdjustmentReason::Compensation->value,
    ];

    // Act
    actingAs($manager)->put(route('adjustments.update', $adjustment), $data);

    // Assert
    $adjustment->invoice->refresh();
    expect($adjustment->invoice->discount_amount)->toBe(75.0);
});

it('recalculates percentage-based adjustment when updating', function () {
    // Arrange
    $manager = User::factory()->manager()->create();
    $adjustment = Adjustment::factory()->create([
        'amount' => 50,
        'is_percent' => false,
        'reason' => AdjustmentReason::Compensation,
    ]);
    $adjustment->invoice->update([
        'task_total' => 200,
        'order_total' => 100,
    ]);
    $data = [
        'amount' => 20,
        'is_percent' => true,
        'reason' => AdjustmentReason::Compensation->value,
    ];

    // Act
    actingAs($manager)->put(route('adjustments.update', $adjustment), $data);

    // Assert
    $adjustment->invoice->refresh();
    // 20% of (200 + 100) = 60
    expect($adjustment->invoice->discount_amount)->toBe(60.0);
});

it('can update only specific fields', function () {
    // Arrange
    $manager = User::factory()->manager()->create();
    $adjustment = Adjustment::factory()->create();
    $originalNote = $adjustment->note;
    $originalReason = $adjustment->reason;
    $data = [
        'amount' => 200,
        'is_percent' => $adjustment->is_percent,
        'note' => $originalNote,
        'reason' => $originalReason->value,
    ];

    // Act
    actingAs($manager)->put(route('adjustments.update', $adjustment), $data);

    // Assert
    $adjustment->refresh();
    expect($adjustment->amount)->toBe(200.0)
        ->and($adjustment->note)->toBe($originalNote)
        ->and($adjustment->reason)->toBe($originalReason);
});

it('redirects to invoice show page after successful update', function () {
    // Arrange
    $manager = User::factory()->manager()->create();
    $adjustment = Adjustment::factory()->create();
    $data = [
        'amount' => 100,
        'reason' => AdjustmentReason::Compensation->value,
    ];

    // Act
    $response = actingAs($manager)->put(route('adjustments.update', $adjustment), $data);

    // Assert
    $response->assertRedirect(route('invoices.show', $adjustment->invoice));
});
