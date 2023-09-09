<?php

namespace Tests\Unit\Entity;

use App\Entity\ListingStatus;
use Codeception\Test\Unit;

/**
 * The 'ListingStatusTest' is a unit test class for testing the 'ListingStatus' class.
 * 
 * Tests the functionality of creating an instance and getting/setting the class-related properties.
 */
class ListingStatusTest extends Unit {

    protected $tester;
    private $keyArray;
    private $listingStatus;
    private $listingStatusWithout;

    /**
     * Sets up the necessary environment for running tests by 
     * creating an array of keys and different instances of 'ListingStatus'.
     */
    protected function _before() {

        $this->keyArray = ['id', 'status_code', 'status_description'];

        $this->listingStatus = new ListingStatus($this->keyArray, 'Completed', 'The listing has closed.', 2);
        $this->listingStatusWithout = new ListingStatus($this->keyArray, 'Active', 'The listing is open.', null);
    }

    /**
     * Tests whether the 'ListingStatus' instance is created correctly with and without id.
     */
    public function testListingStatusCreation() {

        // Assert that an instance of 'ListingStatus' was created
        $this->assertInstanceOf(ListingStatus::class, $this->listingStatus);
        $this->assertInstanceOf(ListingStatus::class, $this->listingStatusWithout);
    }

    /**
     * Tests whether the getters of the 'ListingStatus' class return the correct values with and without id.
     */
    public function testListingStatusGetters() {

        // Assert that the getters return the expected value
        $this->assertEquals(2, $this->listingStatus->getId());
        $this->assertEquals('Completed', $this->listingStatus->getStatusCode());
        $this->assertEquals('The listing has closed.', $this->listingStatus->getStatusDescription());

        $this->assertEquals(null, $this->listingStatusWithout->getId());
        $this->assertEquals('Active', $this->listingStatusWithout->getStatusCode());
        $this->assertEquals('The listing is open.', $this->listingStatusWithout->getStatusDescription());
    }

    /**
     * Tests whether the setters in the 'ListingStatus' class modify the properties correctly.
     */
    public function testListingStatusSetters() {

        // Act 
        $this->listingStatus->setId(1);
        $this->listingStatus->setStatusCode('Active');
        $this->listingStatus->setStatusDescription('The listing is open.');

        // Assert that the correct values of the changes are returned
        $this->assertEquals(1, $this->listingStatus->getId());
        $this->assertEquals('Active', $this->listingStatus->getStatusCode());
        $this->assertEquals('The listing is open.', $this->listingStatus->getStatusDescription());
    }

    /**
     * Tests the 'toArray' method of the 'ListingStatus' class whether
     * it converts a ListingStatus object to the correct array with and without id.
     */
    public function testListingStatusToArrayConversion() {

        $expectedArray = [
            'id' => 2,
            'status_code' => 'Completed',
            'status_description' => 'The listing has closed.',
        ];

        $expectedArrayWithout = [
            'id' => null,
            'status_code' => 'Active',
            'status_description' => 'The listing is open.',
        ];

        // Assert
        $this->assertEquals($expectedArray, $this->listingStatus->toArray());
        $this->assertEquals($expectedArrayWithout, $this->listingStatusWithout->toArray());
    }

    /**
     * Tests the 'toArray' method of the 'ListingStatus' class whether
     * an exception is thrown when trying to convert an object to an array
     * with an invalid key.
     */
    public function testListingStatusToArrayThrowsExceptionForInvalidKey() {

        // Arrange: Add an invalid key to the keyArray
        $invalidKeyArray = ['id', 'listing_status', 'status_description'];
        $listingStatusWithInvalidKey = new ListingStatus($invalidKeyArray, '2000', 'New');

        // Assert
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage("Invalid Request: Getter for 'listing_status' does not exist in App\Entity\ListingStatus.");

        // Act
        $listingStatusWithInvalidKey->toArray();
    }
}
