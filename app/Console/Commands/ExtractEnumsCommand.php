<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class ExtractEnumsCommand extends Command
{
    const JSON_PATH = 'resources/js/data/enums.json';

    const CONSTANTS_PATH = 'resources/js/constants/enums.ts';

    const TYPES_PATH = 'resources/js/types/enums.d.ts';

    protected $signature = 'extract:enums {--path= : Path to enums directory (default: app/Enums)} {--ts : Generate TypeScript files instead of JSON}';

    protected $description = 'Extract PHP enums to JSON (default) or TypeScript';

    public function handle(): void
    {
        $shouldOutputTs = $this->option('ts');
        $path = $this->option('path') ?? app_path('Enums');
        $enumData = $this->extractEnumData($path);

        $shouldOutputTs
            ? $this->extractAsTypeScript($enumData)
            : $this->extractAsJson($enumData);

        $enumCount = count($enumData);
        $output = $shouldOutputTs ? 'TypeScript' : 'JSON';
        $this->info("Extracted {$enumCount} enums as {$output}");
    }

    // HELPERS /////////////////////////////////////////////////////////////////////////////////////

    protected function getEnums(string $enumsPath): Collection
    {
        $namespace = $this->pathToNamespace($enumsPath);

        return collect(File::allFiles($enumsPath))
            ->map(fn ($file) => $namespace.'\\'.$file->getFilenameWithoutExtension())
            ->filter(fn ($class) => $this->isValidEnum($class));
    }

    protected function extractEnumData(string $enumsPath): array
    {
        return $this->getEnums($enumsPath)
            ->mapWithKeys(fn ($enumClass) => [
                class_basename($enumClass) => collect($enumClass::cases())
                    ->mapWithKeys(fn ($case) => [$case->name => $case->value])->toArray(),
            ])
            ->toArray();
    }

    protected function pathToNamespace(string $path): string
    {
        $relativePath = str_replace(base_path().'/', '', $path);

        return str_replace('/', '\\', ucfirst($relativePath));
    }

    protected function isValidEnum(string $class): bool
    {
        return enum_exists($class);
    }

    protected function writeFile(string $relativePath, string $content): void
    {
        $path = base_path($relativePath);

        File::ensureDirectoryExists(dirname($path));
        File::put($path, $content);
    }

    protected function extractAsJson(array $enumData): void
    {
        $content = $this->formatAsJson($enumData);

        $this->writeFile(self::JSON_PATH, $content);
        $this->info('JSON extracted to: '.self::JSON_PATH);
    }

    protected function extractAsTypeScript(array $enumData): void
    {
        $headline = "// Auto-generated from PHP enums - DO NOT EDIT MANUALLY\n\n";

        $content = collect($enumData)->map($this->formatAsConstant(...))->join("\n\n");
        $this->writeFile(self::CONSTANTS_PATH, $headline.$content);
        $this->info('Constants extracted to: '.self::CONSTANTS_PATH);

        $content = collect($enumData)->map($this->formatAsType(...))->join("\n\n");
        $this->writeFile(self::TYPES_PATH, $headline.$content);
        $this->info('Types exported to: '.self::TYPES_PATH);
    }

    // FORMATTERS //////////////////////////////////////////////////////////////////////////////////

    protected function formatAsJson(array $data): string
    {
        return json_encode($data, JSON_UNESCAPED_SLASHES);
    }

    protected function formatAsConstant(array $data, string $key): string
    {
        $constantName = Str::of($key)->snake()->upper();
        $constantBody = $this->formatAsJson($data);

        return "export const {$constantName} = {$constantBody} as const;";
    }

    protected function formatAsType(array $data, string $key): string
    {
        $values = collect($data)->values()->map(fn ($value) => "'{$value}'")->join('|');

        return "export type {$key} = {$values};";
    }
}
