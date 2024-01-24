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
     * @param string $table Represents the table name to work with.
     * @param array $configTables Each table as key contains its corresponding valid columns (a.o.) as value.
     *  
     * @return void
     */
    public function __construct(string $table, array $configTables) {

        parent::__construct($table, $configTables);
    }
}
