<?php

namespace Database\Fixtures;

use App\Models\Device;

class TicketFixture extends Fixture
{
    protected static string $name = 'tickets';

    /**
     * Return a closure that sets the title and note based on the device type.
     */
    public static function issueByDevice(Device $device): \Closure
    {
        $issues = static::data('issues');
        $typeIssues = $issues[$device->type->value];

        return fn (array $attributes) => [
            ...fake()->randomElement($typeIssues),
        ];
    }
}
