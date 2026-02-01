<?php

declare(strict_types=1);

use App\Models\Adjustment;
use App\Models\User;

use function Pest\Laravel\actingAs;

it('shows the edit form when viewing an adjustment', function () {
    // Arrange
    $manager = User::factory()->manager()->create();
    $adjustment = Adjustment::factory()->create();

    // Act
    $response = actingAs($manager)->get(route('adjustments.show', $adjustment));

    // Assert
    $response->assertSuccessful();
    $response->assertInertia(fn ($page) => $page
        ->component('adjustments/Edit')
        ->has('adjustment')
        ->where('adjustment.id', $adjustment->id)
        ->has('invoice')
    );
});
