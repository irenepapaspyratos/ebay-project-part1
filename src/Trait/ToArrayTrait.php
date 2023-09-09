<?php

namespace App\Trait;

/**
 * The 'ToArrayTrait' trait provides a functionality to 
 * convert an object to an array representation 
 * based on keys provided by '$this->keyArray'.
 */
trait ToArrayTrait {

    /**
     * Converts the object of the actual class to an array 
     * based on given keys from '$this->keyArray' created by the constructor.
     * 
     * @return array<string,mixed> Array representation of the object.
     */
    public function toArray(): array {

        $output = [];
        foreach ($this->keyArray as $key) {

            // Convert keys from the keyArray to corresponding getter methods & validate existence
            $getter = 'get' . str_replace('_', '', ucwords($key, '_'));

            if (!method_exists($this, $getter))
                throw new \Exception("Invalid Request: Getter for '$key' does not exist in " . get_class($this) . '.');

            // Add element to output array
            $output[$key] = $this->$getter();
        }

        return $output;
    }
}
