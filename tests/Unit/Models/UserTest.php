<?php

use App\Enums\UserRole;
use App\Enums\UserStatus;
use App\Models\Concerns\Contactable;
use App\Models\Concerns\HasStatus;
use App\Models\Ticket;
use App\Models\User;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

// STRUCTURE TESTS /////////////////////////////////////////////////////////////////////////////////

testModelStructure(
    modelClass: User::class,
    concerns: [
        Contactable::class,
        HasStatus::class,
    ],
    defaults: [
        'role' => UserRole::Technician,
        'status' => UserStatus::Active,
    ],
    fillables: [
        'name',
        'email',
        'phone',
        'password',
        'role',
        'status',
    ],
    hiddens: [
        'password',
        'remember_token',
    ],
    casts: [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'role' => UserRole::class,
    ],
    relations: [
        'assignedTickets' => HasMany::class,
    ]
);

// RELATION TESTS //////////////////////////////////////////////////////////////////////////////////

it('has many assigned tickets relationship', function () {
    // Arrange
    $user = User::factory()->create();
    Ticket::factory()->count(2)->forAssignee($user)->create();

    // Assert
    expect($user->assignedTickets)->toHaveCount(2);
    expect($user->assignedTickets->first())->toBeInstanceOf(Ticket::class);
    expect($user->assignedTickets->first()->assignee_id)->toBe($user->id);
});

// SCOPE TESTS /////////////////////////////////////////////////////////////////////////////////////

it('can filter by role scope', function (UserRole $role) {
    // Arrange
    $user1 = User::factory()->ofRole($role)->create();
    $user2 = User::factory()->ofRole($role->next())->create();

    // Act
    $users = User::ofRole($role)->get();

    // Assert
    expect($users)->toHaveCount(1);
    expect($users->first()->id)->toBe($user1->id);
})->with(UserRole::cases());

// METHOD TESTS ////////////////////////////////////////////////////////////////////////////////////

it('can determine if user is admin', function () {
    // Arrange
    $admin = User::factory()->admin()->create();
    $manager = User::factory()->manager()->create();
    $technician = User::factory()->create(); // Default role is Technician

    // Assert
    expect($admin->isAdmin())->toBeTrue();
    expect($manager->isAdmin())->toBeFalse();
    expect($technician->isAdmin())->toBeFalse();
});

it('can determine if user is active', function () {
    // Arrange
    $activeUser = User::factory()->ofStatus(UserStatus::Active)->create();
    $suspendedUser = User::factory()->ofStatus(UserStatus::Suspended)->create();
    $terminatedUser = User::factory()->ofStatus(UserStatus::Terminated)->create();

    // Assert
    expect($activeUser->isActive())->toBeTrue();
    expect($suspendedUser->isActive())->toBeFalse();
    expect($terminatedUser->isActive())->toBeFalse();
});
