<?php

/*
|--------------------------------------------------------------------------
| Model Testing Helpers
|--------------------------------------------------------------------------
|
| This file contains helper functions and expectations specifically designed
| for testing Eloquent models in the FixFlow application. These helpers
| provide a clean and consistent API for testing model behavior.
|
*/

use Illuminate\Database\Eloquent\Attributes\ObservedBy;

/**
 * Define complete model structure tests with individual it() blocks.
 *
 * @param  string  $modelClass  The model class to test
 * @param  array  $concerns  Traits/concerns used by the model (optional)
 * @param  array  $observers  Observer classes (optional)
 * @param  array  $defaults  Default attributes (optional)
 * @param  array  $fillables  Fillable attributes (optional)
 * @param  array  $hiddens  Hidden attributes (optional)
 * @param  array  $casts  Cast attributes (optional)
 * @param  array  $relations  Relations with their types ['methodName' => Type::class] (optional)
 */
function testModelStructure(
    string $modelClass,
    array $concerns = [],
    array $observers = [],
    array $defaults = [],
    array $fillables = [],
    array $hiddens = [],
    array $casts = [],
    array $relations = []
): void {
    // Test concerns/traits
    it('uses correct concerns', function () use ($modelClass, $concerns) {
        expect($modelClass)->toUseConcerns($concerns);
    })->skip(empty($concerns), 'N/A');

    // Test observers
    it('uses correct observers', function () use ($modelClass, $observers) {
        expect($modelClass)->toUseObservers($observers);
    })->skip(empty($observers), 'N/A');

    // Test default attributes
    it('has correct default attributes', function () use ($modelClass, $defaults) {
        expect($modelClass)->toHaveDefaultAttributes($defaults);
    })->skip(empty($defaults), 'N/A');

    // Test fillable attributes
    it('has correct fillable attributes', function () use ($modelClass, $fillables) {
        expect($modelClass)->toHaveFillables($fillables);
    })->skip(empty($fillables), 'N/A');

    // Test hidden attributes
    it('has correct hidden attributes', function () use ($modelClass, $hiddens) {
        expect($modelClass)->toHideAttributes($hiddens);
    })->skip(empty($hiddens), 'N/A');

    // Test casts
    it('has correct attribute casts', function () use ($modelClass, $casts) {
        expect($modelClass)->toCastAttributes($casts);
    })->skip(empty($casts), 'N/A');

    // Test relations
    it('has correct relationship structures', function () use ($modelClass, $relations) {
        expect($modelClass)->toHaveRelations($relations);
    })->skip(empty($relations), 'N/A');
}

/**
 * Extend Pest expectations with model-specific assertions.
 */
expect()->extend('toHaveDefaultAttributes', function (array $attributes) {
    $defaults = (new $this->value)->getAttributes();

    return expect($defaults)->toMatchArray($attributes);
});

expect()->extend('toHaveFillables', function (array $attributes) {
    $fillables = (new $this->value)->getFillable();

    return expect($fillables)->toMatchArray($attributes);
});

expect()->extend('toHideAttributes', function (array $attributes) {
    $hidden = (new $this->value)->getHidden();

    return expect($hidden)->toMatchArray($attributes);
});

expect()->extend('toCastAttributes', function (array $attributes) {
    $casts = (new $this->value)->getCasts();

    return expect($casts)->toMatchArray($attributes);
});

expect()->extend('toUseConcerns', function (array $concerns) {
    $traits = class_uses_recursive($this->value);

    return expect($traits)->toContain(...$concerns);
});

expect()->extend('toHaveRelations', function (array $relations) {
    $reflection = new ReflectionClass($this->value);

    foreach ($relations as $relationName => $relationType) {
        expect($reflection->hasMethod($relationName))->toBeTrue();

        $returnType = $reflection->getMethod($relationName)->getReturnType();
        expect($returnType?->getName())->toBe($relationType);
    }

    return $this;
});

expect()->extend('toUseObservers', function (array $observers) {
    $reflection = new ReflectionClass($this->value);
    $attributes = $reflection->getAttributes();

    $observedBy = array_find($attributes, function ($attribute) {
        return $attribute->getName() === ObservedBy::class;
    });

    return expect($observedBy?->getArguments()[0])->toContain(...$observers);
});
