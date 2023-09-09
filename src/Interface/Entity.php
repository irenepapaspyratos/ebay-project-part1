<?php

namespace App\Interface;

/**
 * The interface 'Entity' defines that any class that implements the `Entity`
 * must provide an implementation for the `toArray` method, which returns an array.
 */
interface Entity {

    public function toArray(): array;
}
