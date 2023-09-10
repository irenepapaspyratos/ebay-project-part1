<?php

namespace Tests\Unit\Entity;

use App\Entity\ListingStatus;
use Codeception\Test\Unit;

/**
 * The 'ListingStatusTest' is a unit test class for testing the 'ListingStatus' entity.
 * 
 * Tests the functionality of creating an instance and getting/setting the entity-related properties.
 */
class ListingStatusTest extends Unit {

    protected $tester;
    private $listingStatus;
    private $prefix = 'ebay_';

    /**
     * Sets up the necessary environment for running tests by 
     * creating an instance of 'ListingStatus'.
     */
    protected function _before() {

        $this->listingStatus = new ListingStatus($this->prefix);
    }

    /**
     * Tests whether the 'ListingStatus' instance is created correctly.
     */
    public function testListingStatusCreation() {

        // Assert that an instance of 'ListingStatus' was created
        $this->assertInstanceOf(ListingStatus::class, $this->listingStatus);
    }

    /**
     * Tests whether the setters in the 'ListingStatus' entity modify the properties correctly.
     */
    public function testListingStatusSettersAndGetters() {

        // Act 
        $this->listingStatus->id = 5;
        $this->listingStatus->status_code = 'Active';
        $this->listingStatus->status_description = 'Active Listing';

        // Assert that the correct values of the changes are returned
        $this->assertEquals(5, $this->listingStatus->id);
        $this->assertEquals('Active', $this->listingStatus->status_code);
        $this->assertEquals('Active Listing', $this->listingStatus->status_description);
    }

    /**
     * Tests the 'toArray' method of the 'ListingStatus' entity whether
     * it converts a ListingStatus object to the correct array.
     */
    public function testListingStatusToArrayConversion() {

        // Act
        $this->listingStatus->id = 1;
        $this->listingStatus->status_code = 'Ended';
        $this->listingStatus->status_description = 'Ended Listing';

        $expectedArray = [
            'id' => 1,
            'status_code' => 'Ended',
            'status_description' => 'Ended Listing',
        ];

        // Assert that the correct array is returned
        $this->assertEquals($expectedArray, $this->listingStatus->toArray());
    }

    /**
     * Tests the getter of the 'ListingStatus' class whether
     * it throws an exception on a missing table key.
     */
    public function testListingStatusMissingTableKeyException() {

        // Assert that an exception of type `\Exception` is thrown containing the correct message 
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage("Columns for table 'wrongprefix_listing_status' not found.");

        // Act
        $listingStatus = new ListingStatus('wrongprefix_');
    }

    /**
     * Tests the setter of the 'ListingStatus' entity whether
     * it throws an exception on an invalid property.
     */
    public function testListingStatusSetterInvalidPropertyException() {

        // Assert that an exception of type `\Exception` is thrown containing the correct message 
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage("Invalid property: invalid_property");

        // Act
        $this->listingStatus->invalid_property = 'test';
    }

    /**
     * Tests the setter of the 'ListingStatus' entity whether
     * it throws an exception on an invalid property type.
     */
    public function testListingStatusSetterInvalidTypeException() {

        // Assert that an exception of type `\Exception` is thrown containing the correct message 
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage("Invalid type for 'status_code'. Expected string, got integer");

        // Act
        $this->listingStatus->status_code = 5;
    }

    /**
     * Tests the getter of the 'ListingStatus' entity whether
     * it throws an exception on an invalid property.
     */
    public function testListingStatusGetterInvalidPropertyException() {

        // Assert that an exception of type `\Exception` is thrown containing the correct message 
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage("Property 'invalid_property' not existing");

        // Act
        $value = $this->listingStatus->invalid_property;
    }
}
