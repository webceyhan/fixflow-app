<?php

use function Pest\Laravel\get;

it('returns a successful response', function () {
    // Act
    $response = get(route('home'));

    // Assert
    $response->assertOk();
});
