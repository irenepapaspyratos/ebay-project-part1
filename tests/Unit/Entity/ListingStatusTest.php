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

    /**
     * Tests whether the 'ListingStatus' instance is created correctly without id.
     */
    public function testListingStatusCreationWithoutId() {

        // Act
        $listingStatus = new ListingStatus(null, 'Completed', 'The listing has closed.');

        // Assert that an instance of 'ListingStatus' was created
        $this->assertInstanceOf(ListingStatus::class, $listingStatus);
    }

    /**
     * Tests whether the 'ListingStatus' instance is created correctly with id.
     */
    public function testListingStatusCreationWithId() {

        // Act
        $listingStatus = new ListingStatus(2, 'Completed', 'The listing has closed.');

        // Assert that an instance of 'ListingStatus' was created
        $this->assertInstanceOf(ListingStatus::class, $listingStatus);
    }

    /**
     * Tests whether the getters of the 'ListingStatus' class return the correct values without id.
     */
    public function testListingStatusGettersWithoutId() {

        // Act
        $listingStatus = new ListingStatus(null, 'Completed', 'The listing has closed.');

        // Assert that the getters return the expected value
        $this->assertEquals(null, $listingStatus->getId());
        $this->assertEquals('Completed', $listingStatus->getCodeType());
        $this->assertEquals('The listing has closed.', $listingStatus->getDescription());
    }

    /**
     * Tests whether the getters of the 'ListingStatus' class return the correct values with id.
     */
    public function testListingStatusGettersWithId() {

        // Act
        $listingStatus = new ListingStatus(2, 'Completed', 'The listing has closed.');

        // Assert that the getters return the expected value
        $this->assertEquals(2, $listingStatus->getId());
        $this->assertEquals('Completed', $listingStatus->getCodeType());
        $this->assertEquals('The listing has closed.', $listingStatus->getDescription());
    }

    /**
     * Tests whether the setters in the 'ListingStatus' class modify the properties correctly.
     */
    public function testListingStatusSettersModifyProperties() {

        // Arrange
        $listingStatus = new ListingStatus(2, 'Completed', 'The listing has closed.');

        // Act 
        $listingStatus->setId(1);
        $listingStatus->setCodeType('Active');
        $listingStatus->setDescription('The listing is still active.');

        // Assert that the correct values of the changes are returned
        $this->assertEquals(1, $listingStatus->getId());
        $this->assertEquals('Active', $listingStatus->getCodeType());
        $this->assertEquals('The listing is still active.', $listingStatus->getDescription());
    }

    /**
     * Tests the 'toArray' method of the 'ListingStatus' class whether
     * it converts a ListingStatus object to the correct array without id.
     */
    public function testToArrayConversionWithoutId() {

        // Act
        $listingStatus = new ListingStatus(null, 'Completed', 'The listing has closed.');

        $expectedArray = [
            'id' => null,
            'code_type' => 'Completed',
            'description' => 'The listing has closed.',
        ];

        // Assert
        $this->assertEquals($expectedArray, $listingStatus->toArray());
    }

    /**
     * Tests the 'toArray' method of the 'ListingStatus' class whether
     * it converts a ListingStatus object to the correct array with id.
     */
    public function testToArrayConversionWithId() {

        // Act
        $listingStatus = new ListingStatus(1, 'Active', 'The listing is still active.');

        $expectedArray = [
            'id' => 1,
            'code_type' => 'Active',
            'description' => 'The listing is still active.',
        ];

        // Assert
        $this->assertEquals($expectedArray, $listingStatus->toArray());
    }
}
