<?php

namespace App\Entity;

/**
 * The interface 'EntityInterface' defines that any class that implements the `EntityInterface`
 * must provide an implementation for the `toArray` method, which returns an array.
 */
interface EntityInterface {
    public function toArray(): array;
}
