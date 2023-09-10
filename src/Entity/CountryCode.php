<?php

namespace App\Entity;

/**
 * The 'CountryCode' class extends the 'BaseEntity' class ensuring correct data types.
 * 
 * Provides methods for setting and getting property values, 
 * as well as converting the object to an array representation.
 */
class CountryCode extends BaseEntity {

    private $prefix;

    /**
     * The '__construct' method initializes properties with corresponding values, either defaults or passed as arguments.
     * 
     * Creates an array with valid columns/keys using the table array of the configuration file and the parent constructor. 
     *     
     * @param string $prefix Prefix for the table key in the configuration file (e.g. ebay_ for an ebay table).
     *  
     * @return void
     */
    public function __construct(string $prefix) {

        $this->prefix = $prefix;
        parent::__construct($this->prefix);
    }
}
