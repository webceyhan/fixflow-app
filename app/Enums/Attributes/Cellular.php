<?php

namespace App\Enums\Attributes;

use Attribute;

/**
 * Indicates that a device type supports cellular connectivity.
 *
 * Devices with this attribute can have IMEI numbers and connect to mobile networks.
 */
#[Attribute]
final class Cellular {}
