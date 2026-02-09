<?php

use App\Enums\DeviceType;

dataset('cellularDevices', [
    'phone' => [DeviceType::Phone, true],
    'tablet' => [DeviceType::Tablet, true],
    'wearable' => [DeviceType::Wearable, true],
]);

dataset('nonCellularDevices', [
    'laptop' => [DeviceType::Laptop, false],
    'desktop' => [DeviceType::Desktop, false],
    'other' => [DeviceType::Other, false],
]);

it('returns true for cellular devices', function (DeviceType $type, bool $expected) {
    // Act
    $result = $type->hasCellular();

    // Assert
    expect($result)->toBe($expected);
})->with('cellularDevices');

it('returns false for non-cellular devices', function (DeviceType $type, bool $expected) {
    // Act
    $result = $type->hasCellular();

    // Assert
    expect($result)->toBe($expected);
})->with('nonCellularDevices');
