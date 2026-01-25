<?php

use App\Models\Customer;
use App\Models\Device;
use App\Models\User;

use function Pest\Laravel\actingAs;

it('shows the devices list to authenticated users', function () {
    // Arrange
    $viewer = User::factory()->create();
    $devices = Device::factory()->count(3)->create();

    // Act
    $response = actingAs($viewer)->get(route('devices.index'));

    // Assert
    $response->assertSuccessful();
    $response->assertInertia(fn ($page) => $page
        ->component('devices/Index')
        ->has('devices.data', 3)
        ->has('devices.links')
        ->has('filter')
    );
});

it('allows searching devices by model', function () {
    // Arrange
    $uid = uniqid(); // parallel-safe
    $viewer = User::factory()->create();
    Device::factory()->create(['model' => "iPhone {$uid} 15"]);
    Device::factory()->create(['model' => "iPhone {$uid} 14"]);
    Device::factory()->create(['model' => 'Samsung Galaxy']);

    // Act
    $response = actingAs($viewer)->get(route('devices.index', ['filter' => ['search' => $uid]]));

    // Assert
    $response->assertSuccessful();
    $response->assertInertia(fn ($page) => $page
        ->component('devices/Index')
        ->has('devices.data', 2)
    );
});

it('allows searching devices by brand', function () {
    // Arrange
    $uid = uniqid(); // parallel-safe
    $viewer = User::factory()->create();
    Device::factory()->create(['brand' => "Apple {$uid}"]);
    Device::factory()->create(['brand' => "Apple {$uid}"]);
    Device::factory()->create(['brand' => 'Samsung']);

    // Act
    $response = actingAs($viewer)->get(route('devices.index', ['filter' => ['search' => $uid]]));

    // Assert
    $response->assertSuccessful();
    $response->assertInertia(fn ($page) => $page
        ->component('devices/Index')
        ->has('devices.data', 2)
    );
});

it('allows searching devices by imei', function () {
    // Arrange
    $uid = uniqid(); // parallel-safe
    $viewer = User::factory()->create();
    Device::factory()->create(['imei' => "99{$uid}12345"]);
    Device::factory()->create(['imei' => "99{$uid}12346"]);
    Device::factory()->create(['imei' => '123456789012345']);

    // Act
    $response = actingAs($viewer)->get(route('devices.index', ['filter' => ['search' => $uid]]));

    // Assert
    $response->assertSuccessful();
    $response->assertInertia(fn ($page) => $page
        ->component('devices/Index')
        ->has('devices.data', 2)
    );
});

it('allows filtering devices by type', function () {
    // Arrange
    $viewer = User::factory()->create();
    Device::factory()->count(2)->create(['type' => 'phone']);
    Device::factory()->count(3)->create(['type' => 'laptop']);

    // Act
    $response = actingAs($viewer)->get(route('devices.index', ['filter' => ['type' => 'phone']]));

    // Assert
    $response->assertSuccessful();
    $response->assertInertia(fn ($page) => $page
        ->component('devices/Index')
        ->has('devices.data', 2)
    );
});

it('allows filtering devices by status', function () {
    // Arrange
    $viewer = User::factory()->create();
    Device::factory()->count(2)->create(['status' => 'received']);
    Device::factory()->count(3)->create(['status' => 'in_service']);

    // Act
    $response = actingAs($viewer)->get(route('devices.index', ['filter' => ['status' => 'received']]));

    // Assert
    $response->assertSuccessful();
    $response->assertInertia(fn ($page) => $page
        ->component('devices/Index')
        ->has('devices.data', 2)
    );
});

it('allows filtering devices by customer', function () {
    // Arrange
    $viewer = User::factory()->create();
    $customer1 = Customer::factory()->create();
    $customer2 = Customer::factory()->create();

    Device::factory()->count(2)->create(['customer_id' => $customer1->id]);
    Device::factory()->count(3)->create(['customer_id' => $customer2->id]);

    // Act
    $response = actingAs($viewer)->get(route('devices.index', ['filter' => ['customer_id' => $customer1->id]]));

    // Assert
    $response->assertSuccessful();
    $response->assertInertia(fn ($page) => $page
        ->component('devices/Index')
        ->has('devices.data', 2)
    );
});

it('paginates devices correctly', function () {
    // Arrange
    $viewer = User::factory()->create();
    Device::factory()->count(20)->create();

    // Act
    $response = actingAs($viewer)->get(route('devices.index'));

    // Assert
    $response->assertSuccessful();
    $response->assertInertia(fn ($page) => $page
        ->component('devices/Index')
        ->has('devices.data', 15)
        ->has('devices.links')
    );
});

it('sorts devices by model', function () {
    // Arrange
    $viewer = User::factory()->create();
    Device::factory()->create(['model' => 'Zebra Device']);
    Device::factory()->create(['model' => 'Alpha Device']);
    Device::factory()->create(['model' => 'Beta Device']);

    // Act
    $response = actingAs($viewer)->get(route('devices.index', ['sort' => 'model']));

    // Assert
    $response->assertSuccessful();
    $response->assertInertia(fn ($page) => $page
        ->component('devices/Index')
        ->where('devices.data.0.model', 'Alpha Device')
    );
});

it('allows combining search and type filters', function () {
    // Arrange
    $uid = uniqid(); // parallel-safe
    $viewer = User::factory()->create();
    Device::factory()->create(['model' => "iPhone {$uid} 15", 'type' => 'phone']);
    Device::factory()->create(['model' => "iPhone {$uid} 14", 'type' => 'phone']);
    Device::factory()->create(['model' => 'MacBook Pro', 'type' => 'laptop']);

    // Act
    $response = actingAs($viewer)->get(route('devices.index', [
        'filter' => [
            'search' => $uid,
            'type' => 'phone',
        ],
    ]));

    // Assert
    $response->assertSuccessful();
    $response->assertInertia(fn ($page) => $page
        ->component('devices/Index')
        ->has('devices.data', 2)
    );
});
