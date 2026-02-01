<?php

use App\Console\Commands\ExtractEnumsCommand;
use Illuminate\Support\Facades\File;

use function Pest\Laravel\artisan;

// Constants
const ENUM_FIXTURES_PATH = 'tests/Fixtures/Enums';

it('has correct signature and description', function () {
    // Arrange
    $command = new ExtractEnumsCommand;

    // Act & Assert
    expect($command->getName())->toBe('extract:enums');
    expect($command->getDescription())->toBe('Extract PHP enums to JSON (default) or TypeScript');
});

it('extracts enums to JSON by default', function () {
    // Arrange
    $mock = File::partialMock();
    $mock->expects('ensureDirectoryExists')->once();
    $mock->expects('put')->once()->withArgs(function (string $path, string $contents) {
        expect($path)->toBe(base_path(ExtractEnumsCommand::JSON_PATH));

        $data = json_decode($contents, true);
        expect($data)->toBeArray();
        expect($data)->toHaveKey('TestStatus');
        expect($data['TestStatus'])->toBe(['Active' => 'active', 'Inactive' => 'inactive']);

        return true;
    });

    // Act & Assert
    artisan('extract:enums', ['--path' => ENUM_FIXTURES_PATH])
        ->expectsOutput('Extracted 1 enums as JSON')
        ->assertExitCode(0);
});

it('extracts enums with TypeScript flag', function () {
    // Arrange
    $mock = File::partialMock();
    $mock->expects('ensureDirectoryExists')->twice();
    $mock->expects('put')->twice()->withArgs(function (string $path, string $contents) {
        if (str_contains($path, 'constants')) {
            expect($contents)->toContain('export const TEST_STATUS =');
            expect($contents)->toContain('as const;');
        } else {
            expect($contents)->toContain('export type TestStatus =');
            expect($contents)->toContain("'active'|'inactive'");
        }

        return true;
    });

    // Act & Assert
    artisan('extract:enums', ['--path' => ENUM_FIXTURES_PATH, '--ts' => true])
        ->expectsOutput('Extracted 1 enums as TypeScript')
        ->assertExitCode(0);
});

it('handles empty enum discovery gracefully', function () {
    // Arrange
    $mock = File::partialMock();
    $mock->expects('ensureDirectoryExists')->once();
    $mock->expects('put')->once()->withArgs(function (string $path, string $contents) {
        expect($contents)->toBe('[]');

        return true;
    });

    // Act & Assert
    artisan('extract:enums', ['--path' => 'tests/Fixtures/EmptyDir'])
        ->expectsOutput('Extracted 0 enums as JSON')
        ->assertExitCode(0);
});

it('uses app/Enums as default path', function () {
    // Arrange
    $mock = File::partialMock();
    $mock->expects('ensureDirectoryExists')->once();
    $mock->expects('put')->once()->withArgs(function (string $path, string $contents) {
        $data = json_decode($contents, true);
        expect($data)->toBeArray();

        return true;
    });

    // Act & Assert
    artisan('extract:enums')
        ->assertExitCode(0);
});
