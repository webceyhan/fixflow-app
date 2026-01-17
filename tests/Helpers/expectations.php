<?php

use Illuminate\Database\Eloquent\Attributes\ObservedBy;

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
