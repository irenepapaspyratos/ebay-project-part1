<?php

namespace App\Entity;

/**
 * The 'SiteCode' class extends the 'BaseEntity' class ensuring correct data types.
 * 
 * Provides methods for setting and getting property values, 
 * as well as converting the object to an array representation.
 */
class SiteCode extends BaseEntity {

    /**
     * The '__construct' method initializes properties with corresponding values, either defaults or passed as arguments.
     * 
     * Creates an array with valid columns/keys using the table array of the configuration file and the parent constructor. 
     *     
     * @param string $prefix Prefix for the table key in the configuration file (e.g. ebay_ for an ebay table).
     * 
     * @return void
     */
    public function __construct(string $prefix, int $siteId) {

        parent::__construct($prefix);

        if (empty($siteId))
            throw new \InvalidArgumentException("Value for site_id is mandatory and cannot be empty.");

        $this->site_id = $siteId;
    }

    /**
     * The '__set' method sets the value for a property.
     * 
     * @param string $name Property to be set.
     * @param int|string $value Value to be set for the given property. 
     * 
     * @return void
     * @throws \Exception If an invalid property is tried to be set.
     */
    public function __set(string $name, int|string $value): void {

        parent::__set($name, $value);
    }
}
