<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use ReflectionClass;

class ExtractModelsCommand extends Command
{
    const JSON_PATH = 'resources/js/data/models.json';

    const TYPES_PATH = 'resources/js/types/models.d.ts';

    protected $signature = 'extract:models {--path= : Path to models directory (default: app/Models)} {--ts : Generate TypeScript types instead of JSON}';

    protected $description = 'Extract PHP models to JSON (default) or TypeScript';

    public function handle(): void
    {
        $shouldOutputTs = $this->option('ts');
        $path = $this->option('path') ?? app_path('Models');
        $modelData = $this->extractModelData($path);

        $shouldOutputTs
            ? $this->extractAsTypeScript($modelData)
            : $this->extractAsJson($modelData);

        $modelCount = count($modelData);
        $output = $shouldOutputTs ? 'TypeScript' : 'JSON';
        $this->info("Extracted {$modelCount} models as {$output}");
    }

    // HELPERS /////////////////////////////////////////////////////////////////////////////////////

    protected function getModels(string $modelsPath): Collection
    {
        $namespace = $this->pathToNamespace($modelsPath);

        return collect(File::allFiles($modelsPath))
            ->map(fn ($file) => $namespace.'\\'.$file->getFilenameWithoutExtension())
            ->filter(fn ($class) => $this->isValidModel($class))
            ->map(fn ($class) => class_basename($class));
    }

    protected function isValidModel(string $class): bool
    {
        return class_exists($class)
            && is_subclass_of($class, Model::class)
            && ! (new ReflectionClass($class))->isAbstract();
    }

    protected function pathToNamespace(string $path): string
    {
        $relativePath = str_replace(base_path().'/', '', $path);

        return str_replace('/', '\\', ucfirst($relativePath));
    }

    protected function extractModelData(string $modelsPath): array
    {
        $models = $this->getModels($modelsPath);
        $modelData = [];

        foreach ($models as $modelName) {
            try {
                // Run model:show --json for each model
                $exitCode = Artisan::call('model:show', [
                    'model' => $modelName,
                    '--json' => true,
                ]);

                if ($exitCode === 0) {
                    $jsonOutput = Artisan::output();
                    $modelInfo = json_decode($jsonOutput, true);
                    $modelData[$modelName] = $this->processModelInfo($modelInfo);
                }
            } catch (\Throwable $e) {
                $this->warn("Error extracting model {$modelName}: {$e->getMessage()}");
            }
        }

        return $modelData;
    }

    protected function processModelInfo(array $modelInfo): array
    {
        $processedData = [
            'table' => $modelInfo['table'],
            'attributes' => [],
            'relations' => [],
        ];

        // Process attributes
        foreach ($modelInfo['attributes'] as $attribute) {
            // Skip hidden attributes
            if ($attribute['hidden'] ?? false) {
                continue;
            }

            $processedData['attributes'][$attribute['name']] = [
                'type' => $this->parseAttributeType($attribute, $modelInfo['class']) ?? 'any',
                'required' => ! $attribute['nullable'] ?? false,
                'readonly' => ! $attribute['fillable'] ?? false,
                'cast' => $attribute['cast'] ?? null,
            ];
        }

        // Process relations
        foreach ($modelInfo['relations'] as $relation) {
            // Convert relation name from camelCase to snake_case
            $relationName = Str::snake($relation['name']);

            $processedData['relations'][$relationName] = [
                'type' => $relation['type'],
                'related' => class_basename($relation['related']),
            ];
        }

        return $processedData;
    }

    protected function parseAttributeType(array $attribute, string $modelClass): ?string
    {
        // Extract attribute array
        [
            'name' => $name,
            'type' => $type,
            'cast' => $cast,
        ] = $attribute;

        if (! $cast) {
            // Map database column types to JSON types
            return $this->mapToJsonType($type ?? '');
        }

        // Skip attribute casts
        if ($cast === 'attribute') {
            return $this->parseAttributeTypeFromDocBlock($name, $modelClass);
        }

        // Skip enum casts
        if (str_starts_with($cast, 'App\Enums\\')) {
            return class_basename($cast);
        }

        // Normalize and map to JSON type
        return $this->mapToJsonType(explode(':', $cast)[0] ?? $cast);
    }

    protected function parseAttributeTypeFromDocBlock(string $attributeName, string $modelClass): ?string
    {
        try {
            $reflection = new ReflectionClass($modelClass);
            $method = $reflection->getMethod(Str::camel($attributeName));
            $docComment = $method?->getDocComment();

            // Skip if no doc comment is found
            if (! $docComment) {
                return null;
            }

            // Parse @return Attribute<type> from DocBlock
            if (preg_match('/@return\s+Attribute<(\w+)>/', $docComment, $matches)) {
                return $this->mapToJsonType($matches[1]);
            }
        } catch (\Throwable $e) {
            // If reflection fails, return null to fall back to 'mixed'
        }

        return null;
    }

    protected function mapToJsonType(string $type): ?string
    {
        return match (strtolower($type)) {
            // Database types
            'integer', 'int', 'bigint', 'smallint', 'tinyint', 'mediumint' => 'number',
            'numeric', 'decimal', 'float', 'double', 'real' => 'number',
            'varchar', 'char', 'text', 'mediumtext', 'longtext' => 'string',
            'date', 'datetime', 'timestamp', 'time' => 'string',
            'boolean', 'tinyint(1)' => 'boolean',
            'json', 'jsonb' => 'object',

            // PHP types
            'string' => 'string',
            'int', 'integer' => 'number',
            'real', 'float', 'double' => 'number',
            'bool', 'boolean' => 'boolean',
            'object', 'array', 'json' => 'object',
            'date', 'datetime', 'timestamp' => 'string',
            'collection' => 'array',
            'hashed' => 'string',
            'encrypted' => 'string',
            'mixed' => 'any',
            default => null
        };
    }

    protected function writeFile(string $relativePath, string $content): void
    {
        $path = base_path($relativePath);

        File::ensureDirectoryExists(dirname($path));
        File::put($path, $content);
    }

    protected function extractAsJson(array $modelData): void
    {
        $content = $this->formatAsJson($modelData);

        $this->writeFile(self::JSON_PATH, $content);
        $this->info('JSON extracted to: '.self::JSON_PATH);
    }

    protected function extractAsTypeScript(array $modelData): void
    {
        $content = $this->formatAsTypeScript($modelData);

        $this->writeFile(self::TYPES_PATH, $content);
        $this->info('Types exported to: '.self::TYPES_PATH);
    }

    // FORMATTERS //////////////////////////////////////////////////////////////////////////////////

    protected function formatAsJson(array $data): string
    {
        return json_encode($data, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);
    }

    protected function formatAsTypeScript(array $modelData): string
    {
        $content = "// Auto-generated from PHP models - DO NOT EDIT MANUALLY\n\n";

        // Add enum imports
        $enumTypes = $this->collectEnumTypes($modelData);

        if (! empty($enumTypes)) {
            $enumImportBody = implode(',', $enumTypes);
            $content .= "import type {{$enumImportBody}} from './enums';\n\n";
        }

        $baseAttributes = [
            'id' => 'number',
            'created_at' => 'string',
            'updated_at' => 'string',
        ];

        // Add base model interface with common attributes
        $baseModelBody = collect($baseAttributes)->map(fn ($attr, $name) => "{$name}:{$attr}")->join(';');
        $content .= "export interface Model {{$baseModelBody}}\n\n";

        // Add model interfaces
        foreach ($modelData as $modelName => $data) {
            // Add model attributes
            $modelBody = collect($data['attributes'])->map(function ($attr, $name) {
                // skip base model attributes
                if (in_array($name, ['id', 'created_at', 'updated_at'])) {
                    return;
                }

                $type = $attr['type'];
                $suffix = $attr['required'] ? '' : '?';

                return "{$name}{$suffix}:{$type};";
            })->filter()->join('');

            // Add relationships as optional properties
            if (! empty($data['relations'])) {
                $modelBody .= collect($data['relations'])->map(function ($relation, $name) {
                    $relatedType = $relation['related'];
                    $isArray = in_array($relation['type'], ['HasMany', 'HasManyThrough', 'MorphMany']);
                    $type = $isArray ? "{$relatedType}[]" : $relatedType;

                    // Relation name is already in snake_case from processModelInfo
                    return "{$name}?: {$type};";
                })->filter()->join('');
            }

            $content .= "export interface {$modelName} extends Model {{$modelBody}}\n\n";
        }

        return $content;
    }

    protected function collectEnumTypes(array $modelData): array
    {
        return collect($modelData)
            ->flatMap(fn ($data) => collect($data['attributes'])->pluck('cast'))
            ->filter(fn ($cast) => str_starts_with($cast, 'App\\Enums\\'))
            ->map(fn ($cast) => class_basename($cast))
            ->unique()
            ->toArray();
    }
}
