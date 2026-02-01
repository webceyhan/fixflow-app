<?php

use App\Enums\AdjustmentReason;
use App\Models\Adjustment;
use App\Models\User;

use function Pest\Laravel\actingAs;

it('shows the adjustments list to authenticated users', function () {
    // Arrange
    $manager = User::factory()->manager()->create();
    Adjustment::factory()->count(5)->create();

    // Act
    $response = actingAs($manager)->get(route('adjustments.index'));

    // Assert
    $response->assertSuccessful();
    $response->assertInertia(fn ($page) => $page
        ->component('adjustments/Index')
        ->has('adjustments.data', 5)
    );
});

it('allows searching adjustments', function () {
    // Arrange
    $manager = User::factory()->manager()->create();
    Adjustment::factory()->create(['note' => 'Special discount for loyal customer']);
    Adjustment::factory()->create(['note' => 'Rush fee']);

    // Act
    $response = actingAs($manager)->get(route('adjustments.index', ['filter' => ['search' => 'loyal']]));

    // Assert
    $response->assertSuccessful();
    $response->assertInertia(fn ($page) => $page->has('adjustments.data', 1));
});

it('allows filtering adjustments by type', function () {
    // Arrange
    $manager = User::factory()->manager()->create();
    Adjustment::factory()->count(3)->discount()->create();
    Adjustment::factory()->count(2)->fee()->create();

    // Act
    $response = actingAs($manager)->get(route('adjustments.index', ['filter' => ['type' => 'discount']]));

    // Assert
    $response->assertSuccessful();
    $response->assertInertia(fn ($page) => $page->has('adjustments.data', 3));
});

it('allows filtering adjustments by reason', function () {
    // Arrange
    $manager = User::factory()->manager()->create();
    Adjustment::factory()->count(2)->create(['reason' => AdjustmentReason::Welcome]);
    Adjustment::factory()->count(3)->create(['reason' => AdjustmentReason::RushService]);

    // Act
    $response = actingAs($manager)->get(route('adjustments.index', ['filter' => ['reason' => 'welcome']]));

    // Assert
    $response->assertSuccessful();
    $response->assertInertia(fn ($page) => $page->has('adjustments.data', 2));
});

it('allows sorting adjustments by amount', function () {
    // Arrange
    $manager = User::factory()->manager()->create();
    Adjustment::factory()->create(['amount' => 50]);
    Adjustment::factory()->create(['amount' => 100]);
    Adjustment::factory()->create(['amount' => 25]);

    // Act
    $response = actingAs($manager)->get(route('adjustments.index', ['sort' => 'amount']));

    // Assert
    $response->assertSuccessful();
    $response->assertInertia(fn ($page) => $page
        ->has('adjustments.data', 3)
        ->where('adjustments.data.0.amount', 25)
        ->where('adjustments.data.2.amount', 100)
    );
});

it('defaults to sorting by created_at descending', function () {
    // Arrange
    $manager = User::factory()->manager()->create();
    $first = Adjustment::factory()->create(['created_at' => now()->subDays(2)]);
    $second = Adjustment::factory()->create(['created_at' => now()->subDay()]);
    $third = Adjustment::factory()->create(['created_at' => now()]);

    // Act
    $response = actingAs($manager)->get(route('adjustments.index'));

    // Assert
    $response->assertSuccessful();
    $response->assertInertia(fn ($page) => $page
        ->has('adjustments.data', 3)
        ->where('adjustments.data.0.id', $third->id)
        ->where('adjustments.data.2.id', $first->id)
    );
});
