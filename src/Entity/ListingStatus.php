<?php

namespace App\Entity;

/**
 * The 'ListingStatus' class extends the 'BaseEntity' class ensuring correct data types.
 * 
 * Provides methods for setting and getting property values, 
 * as well as converting the object to an array representation.
 */
class ListingStatus extends BaseEntity {

    /**
     * The '__construct' method initializes properties with corresponding values, either defaults or passed as arguments.
     * 
     * Creates an array with valid columns/keys using the table array of the configuration file and the parent constructor. 
     *     
     * @param string $prefix Prefix for the table key in the configuration file (e.g. ebay_ for an ebay table).
     * 
     * @return void
     */
    public function __construct(string $prefix, string $listingStatus) {

        parent::__construct($prefix);

        if (empty($listingStatus))
            throw new \InvalidArgumentException("Value for listing_status is mandatory and cannot be empty.");

        $this->listing_status = $listingStatus;
    }

    /**
     * The '__set' method sets the value for a property.
     * 
     * @param string $name Property to be set.
     * @param string $value Value to be set for the given property. 
     * 
     * @return void
     * @throws \Exception If an invalid property is tried to be set.
     */
    public function __set(string $name, string $value): void {

        parent::__set($name, $value);
    }
}
