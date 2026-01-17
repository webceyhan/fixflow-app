<?php

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
