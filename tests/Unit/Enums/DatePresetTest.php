<?php

use App\Enums\DatePreset;
use Illuminate\Support\Carbon;

beforeEach(function () {
    // Freeze time to a fixed date for consistent test results
    Carbon::setTestNow(Carbon::create(2000, 1, 1));
});

dataset('datePresetRanges', [
    'today' => [DatePreset::Today, '2000-01-01', '2000-01-01'],
    'yesterday' => [DatePreset::Yesterday, '1999-12-31', '1999-12-31'],
    'last week' => [DatePreset::LastWeek, '1999-12-25', '2000-01-01'],
    'last month' => [DatePreset::LastMonth, '1999-12-01', '2000-01-01'],
    'last year' => [DatePreset::LastYear, '1999-01-01', '2000-01-01'],
]);

it('returns correct date range', function (DatePreset $preset, string $expectedFrom, string $expectedTo) {
    // Act
    [$from, $to] = $preset->range();

    // Assert
    expect($from)->toBe($expectedFrom)->and($to)->toBe($expectedTo);
})->with('datePresetRanges');
