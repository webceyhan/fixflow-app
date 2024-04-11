<?php

use App\Enums\UserRole;
use App\Enums\UserStatus;
use App\Models\Ticket;
use App\Models\User;
use Illuminate\Support\Carbon;

it('can initialize user', function () {
    $user = new User();

    expect($user->id)->toBeNull();
    expect($user->name)->toBeNull();
    expect($user->email)->toBeNull();
    expect($user->phone)->toBeNull();
    expect($user->password)->toBeNull();
    expect($user->remember_token)->toBeNull();
    expect($user->role)->toBe(UserRole::Technician);
    expect($user->status)->toBe(UserStatus::Active);
    expect($user->created_at)->toBeNull();
    expect($user->updated_at)->toBeNull();
    expect($user->email_verified_at)->toBeNull();
});

it('can create user', function () {
    $user = User::factory()->create();

    expect($user->id)->toBeInt();
    expect($user->name)->toBeString();
    expect($user->email)->toBeString();
    expect($user->phone)->toBeString();
    expect($user->password)->toBeString();
    expect($user->remember_token)->toBeString();
    expect($user->role)->toBe(UserRole::Technician);
    expect($user->status)->toBe(UserStatus::Active);
    expect($user->created_at)->toBeInstanceOf(Carbon::class);
    expect($user->updated_at)->toBeInstanceOf(Carbon::class);
    expect($user->email_verified_at)->toBeInstanceOf(Carbon::class);
});

it('can create user without phone number', function () {
    $user = User::factory()->withoutPhone()->create();

    expect($user->phone)->toBeNull();
});

it('can create user of role', function (UserRole $role) {
    $user = User::factory()->ofRole($role)->create();

    expect($user->role)->toBe($role);
})->with(UserRole::cases());

it('can create user of status', function (UserStatus $status) {
    $user = User::factory()->ofStatus($status)->create();

    expect($user->status)->toBe($status);
})->with(UserStatus::cases());

it('can create user unverified', function () {
    $user = User::factory()->unverified()->create();

    expect($user->email_verified_at)->toBeNull();
});

it('can update user', function () {
    $user = User::factory()->create();

    $user->update([
        'name' => 'Bill Gates',
        'email' => 'bill.gates@mail.com',
        'phone' => '+1234567890',
        'role' => UserRole::Manager,
        'status' => UserStatus::OnLeave,
    ]);

    expect($user->name)->toBe('Bill Gates');
    expect($user->email)->toBe('bill.gates@mail.com');
    expect($user->phone)->toBe('+1234567890');
    expect($user->role)->toBe(UserRole::Manager);
    expect($user->status)->toBe(UserStatus::OnLeave);
});

it('can delete user', function () {
    $user = User::factory()->create();

    $user->delete();

    expect(User::find($user->id))->toBeNull();
});

// Assigned Tickets ////////////////////////////////////////////////////////////////////////////////

it('can have many assigned tickets', function () {
    $user = User::factory()->hasAssignedTickets(2)->create();

    expect($user->assignedTickets)->toHaveCount(2);
});

it('can delete user while assigned tickets remain unassigned', function () {
    $user = User::factory()->hasAssignedTickets(2)->create();

    $user->delete();

    expect(User::find($user->id))->toBeNull();
    expect(Ticket::count())->toBe(2);
    expect(Ticket::pluck('assignee_id'))->each->toBeNull();
});

// Role ////////////////////////////////////////////////////////////////////////////////////////////

it('can determine if role is admin', function (UserRole $role) {
    $user = User::factory()->ofRole($role)->create();

    expect($user->role)->toBe($role);
    expect($user->role->isAdmin())->toBe($role->isAdmin());
})->with(UserRole::cases());

it('can filter users by role scope', function () {
    User::factory(3)->create();
    User::factory(2)->asManager()->create();
    User::factory(1)->asAdmin()->create();

    expect(User::technicians()->count())->toBe(3);
    expect(User::managers()->count())->toBe(2);
    expect(User::admins()->count())->toBe(1);
});

// Status //////////////////////////////////////////////////////////////////////////////////////////

it('can determine if status is active', function (UserStatus $status) {
    $user = User::factory()->ofStatus($status)->create();

    expect($user->status)->toBe($status);
    expect($user->status->isActive())->toBe($status->isActive());
})->with(UserStatus::cases());

it('can filter users by status scope', function (UserStatus $status) {
    User::factory()->ofStatus($status)->create();

    expect(User::ofStatus($status)->count())->toBe(1);
    expect(User::ofStatus($status)->first()->status)->toBe($status);
})->with(UserStatus::cases());
