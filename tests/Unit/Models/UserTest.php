<?php

use App\Enums\UserRole;
use App\Enums\UserStatus;
use App\Models\Concerns\HasActiveStatus;
use App\Models\Concerns\HasStatus;
use App\Models\Ticket;
use App\Models\User;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Notifications\Notifiable;
use Laravel\Fortify\TwoFactorAuthenticatable;

uses(RefreshDatabase::class);

// STRUCTURE TESTS /////////////////////////////////////////////////////////////////////////////////

testModelStructure(
    modelClass: User::class,
    concerns: [
        HasActiveStatus::class,
        HasStatus::class,
        Notifiable::class,
        TwoFactorAuthenticatable::class,
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
        'note',
        'role',
        'status',
        'email_verified_at',
    ],
    hiddens: [
        'password',
        'remember_token',
        'two_factor_secret',
        'two_factor_recovery_codes',
    ],
    casts: [
        'password' => 'hashed',
        'role' => UserRole::class,
        'status' => UserStatus::class,
        'email_verified_at' => 'datetime',
        'two_factor_confirmed_at' => 'datetime',
    ],
    relations: [
        'assignedTickets' => HasMany::class,
    ]
);

// RELATION TESTS //////////////////////////////////////////////////////////////////////////////////

it('has many assigned tickets relationship', function () {
    // Arrange
    $user = User::factory()->create();
    Ticket::factory()->count(2)->create(['assignee_id' => $user->id]);

    $otherUser = User::factory()->create();
    Ticket::factory()->create(['assignee_id' => $otherUser->id]); // Not this user's ticket

    // Assert
    expect($user->assignedTickets)->toHaveCount(2);
    expect($user->assignedTickets->first())->toBeInstanceOf(Ticket::class);
    expect($user->assignedTickets->first()->assignee_id)->toBe($user->id);
});

// SCOPE TESTS /////////////////////////////////////////////////////////////////////////////////////

it('can filter models by role scope', function (UserRole $role) {
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
    $technician = User::factory()->technician()->create();

    // Assert
    expect($admin->isAdmin())->toBeTrue();
    expect($manager->isAdmin())->toBeFalse();
    expect($technician->isAdmin())->toBeFalse();
});

it('can determine if user is manager', function () {
    // Arrange
    $admin = User::factory()->admin()->create();
    $manager = User::factory()->manager()->create();
    $technician = User::factory()->technician()->create();

    // Assert
    expect($admin->isManager())->toBeFalse();
    expect($manager->isManager())->toBeTrue();
    expect($technician->isManager())->toBeFalse();
});
