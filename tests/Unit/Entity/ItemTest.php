<?php

namespace Tests\Unit\Entity;

use App\Entity\Item;
use Codeception\Test\Unit;

/**
 * The 'ItemTest' is a unit test class for testing the 'Item' class.
 * 
 * Tests the functionality of creating an instance and getting/setting the class-related properties.
 */
class ItemTest extends Unit {

    protected $tester;
    private $keyArray;
    private $item;
    private $itemWithout;

    /**
     * Sets up the necessary environment for running tests by 
     * creating an array of keys and different instances of 'Item'.
     */
    protected function _before() {

        $this->keyArray = [
            'id', 'item_id', 'title', 'current_price', 'listing_status', 'quantity', 'quantity_sold', 'condition',
            'category', 'store_category_id', 'store_category_2_id', 'view_item_url', 'pictures',
            'site', 'country', 'currency', 'ship_to_locations', 'shipping_options',
            'item_compatibility', 'item_specifics', 'html_description', 'net_price', 'filetime'
        ];

        $this->item = new Item($this->keyArray, '111111111111', 'Silk Scarf', 10.89, 'Active', 5, 3, 1000, 25894, '444', '', 'silk-scarf.com', '["galleryPic.com", "scarfPic1.com", "scarfPic2.com"]', 77, 'Germany', '€', '["Europe"]', '["DHL", "PickUp"]', '["compatibility1", "compatibility2"]', '["specifics1", "specifics2"]', '<!DOCTYPE html><html><body><h1>Silk Scarf</h1><p>Comfortable for summers.</p></body></html>', 9.0, 'filetimeString', 2);
        $this->itemWithout = new Item($this->keyArray, '222222222222', 'Woolen Hat', 14.99, 'Ended', 10, 0, 1500, 38954, '555', '777', 'woolen-hat.com', '["galleryPic.com", "hatPic1.com", "hatPic2.com"]', 88, 'France', '€', '["Asia"]', '["FedEx", "SelfPickup"]', '["hatCompatibility1", "hatCompatibility2"]', '["hatSpecifics1", "hatSpecifics2"]', '<!DOCTYPE html><html><body><h1>Woolen Hat</h1><p>Comfortable for winters.</p></body></html>', 13.5, 'differentFiletimeString', null);
    }

    /**
     * Tests whether the 'Item' instance is created correctly with and without id.
     */
    public function testItemCreation() {

        // Assert that an instance of 'Item' was created
        $this->assertInstanceOf(Item::class, $this->item);
        $this->assertInstanceOf(Item::class, $this->itemWithout);
    }

    /**
     * Tests whether the getters of the 'Item' class return the correct values with and without id.
     */
    public function testItemGetters() {

        // Assert that the getters return the expected value
        $this->assertEquals(2, $this->item->getId());
        $this->assertEquals('111111111111', $this->item->getItemId());
        $this->assertEquals('Silk Scarf', $this->item->getTitle());
        $this->assertEquals(10.89, $this->item->getCurrentPrice());
        $this->assertEquals('Active', $this->item->getListingStatus());
        $this->assertEquals(5, $this->item->getQuantity());
        $this->assertEquals(3, $this->item->getQuantitySold());
        $this->assertEquals(1000, $this->item->getCondition());
        $this->assertEquals(25894, $this->item->getCategory());
        $this->assertEquals('444', $this->item->getStoreCategoryId());
        $this->assertEquals('', $this->item->getStoreCategory2Id());
        $this->assertEquals('silk-scarf.com', $this->item->getViewItemUrl());
        $this->assertEquals('["galleryPic.com", "scarfPic1.com", "scarfPic2.com"]', $this->item->getPictures());
        $this->assertEquals(77, $this->item->getSite());
        $this->assertEquals('Germany', $this->item->getCountry());
        $this->assertEquals('€', $this->item->getCurrency());
        $this->assertEquals('["Europe"]', $this->item->getShipToLocations());
        $this->assertEquals('["DHL", "PickUp"]', $this->item->getShippingOptions());
        $this->assertEquals('["compatibility1", "compatibility2"]', $this->item->getItemCompatibility());
        $this->assertEquals('["specifics1", "specifics2"]', $this->item->getItemSpecifics());
        $this->assertEquals('<!DOCTYPE html><html><body><h1>Silk Scarf</h1><p>Comfortable for summers.</p></body></html>', $this->item->getHtmlDescription());
        $this->assertEquals(9.0, $this->item->getNetPrice());
        $this->assertEquals('filetimeString', $this->item->getFiletime());

        $this->assertEquals(null, $this->itemWithout->getId());
        $this->assertEquals('222222222222', $this->itemWithout->getItemId());
        $this->assertEquals('Woolen Hat', $this->itemWithout->getTitle());
        $this->assertEquals(14.99, $this->itemWithout->getCurrentPrice());
        $this->assertEquals('Ended', $this->itemWithout->getListingStatus());
        $this->assertEquals(10, $this->itemWithout->getQuantity());
        $this->assertEquals(0, $this->itemWithout->getQuantitySold());
        $this->assertEquals(1500, $this->itemWithout->getCondition());
        $this->assertEquals(38954, $this->itemWithout->getCategory());
        $this->assertEquals('555', $this->itemWithout->getStoreCategoryId());
        $this->assertEquals('777', $this->itemWithout->getStoreCategory2Id());
        $this->assertEquals('woolen-hat.com', $this->itemWithout->getViewItemUrl());
        $this->assertEquals('["galleryPic.com", "hatPic1.com", "hatPic2.com"]', $this->itemWithout->getPictures());
        $this->assertEquals(88, $this->itemWithout->getSite());
        $this->assertEquals('France', $this->itemWithout->getCountry());
        $this->assertEquals('€', $this->itemWithout->getCurrency());
        $this->assertEquals('["Asia"]', $this->itemWithout->getShipToLocations());
        $this->assertEquals('["FedEx", "SelfPickup"]', $this->itemWithout->getShippingOptions());
        $this->assertEquals('["hatCompatibility1", "hatCompatibility2"]', $this->itemWithout->getItemCompatibility());
        $this->assertEquals('["hatSpecifics1", "hatSpecifics2"]', $this->itemWithout->getItemSpecifics());
        $this->assertEquals('<!DOCTYPE html><html><body><h1>Woolen Hat</h1><p>Comfortable for winters.</p></body></html>', $this->itemWithout->getHtmlDescription());
        $this->assertEquals(13.5, $this->itemWithout->getNetPrice());
        $this->assertEquals('differentFiletimeString', $this->itemWithout->getFiletime());
    }

    /**
     * Tests whether the setters in the 'Item' class modify the properties correctly.
     */
    public function testItemSetters() {

        // Act 
        $this->item->setId(2);
        $this->item->setItemId('222222222222');
        $this->item->setTitle('Woolen Hat');
        $this->item->setCurrentPrice(14.99);
        $this->item->setListingStatus('Ended');
        $this->item->setQuantity(10);
        $this->item->setQuantitySold(0);
        $this->item->setCondition(1500);
        $this->item->setCategory(38954);
        $this->item->setStoreCategoryId('555');
        $this->item->setStoreCategory2Id('777');
        $this->item->setViewItemUrl('woolen-hat.com');
        $this->item->setPictures('["galleryPic.com", "hatPic1.com", "hatPic2.com"]');
        $this->item->setSite(88);
        $this->item->setCountry('France');
        $this->item->setCurrency('€');
        $this->item->setShipToLocations('["Asia"]');
        $this->item->setShippingOptions('["FedEx", "SelfPickup"]');
        $this->item->setItemCompatibility('["hatCompatibility1", "hatCompatibility2"]');
        $this->item->setItemSpecifics('["hatSpecifics1", "hatSpecifics2"]');
        $this->item->setHtmlDescription('<!DOCTYPE html><html><body><h1>Woolen Hat</h1><p>Comfortable for winters.</p></body></html>');
        $this->item->setNetPrice(13.5);
        $this->item->setFiletime('differentFiletimeString');

        // Assert
        $this->assertEquals(2, $this->item->getId());
        $this->assertEquals('222222222222', $this->item->getItemId());
        $this->assertEquals('Woolen Hat', $this->item->getTitle());
        $this->assertEquals(14.99, $this->item->getCurrentPrice());
        $this->assertEquals('Ended', $this->item->getListingStatus());
        $this->assertEquals(10, $this->item->getQuantity());
        $this->assertEquals(0, $this->item->getQuantitySold());
        $this->assertEquals(1500, $this->item->getCondition());
        $this->assertEquals(38954, $this->item->getCategory());
        $this->assertEquals('555', $this->item->getStoreCategoryId());
        $this->assertEquals('777', $this->item->getStoreCategory2Id());
        $this->assertEquals('woolen-hat.com', $this->item->getViewItemUrl());
        $this->assertEquals('["galleryPic.com", "hatPic1.com", "hatPic2.com"]', $this->item->getPictures());
        $this->assertEquals(88, $this->item->getSite());
        $this->assertEquals('France', $this->item->getCountry());
        $this->assertEquals('€', $this->item->getCurrency());
        $this->assertEquals('["Asia"]', $this->item->getShipToLocations());
        $this->assertEquals('["FedEx", "SelfPickup"]', $this->item->getShippingOptions());
        $this->assertEquals('["hatCompatibility1", "hatCompatibility2"]', $this->item->getItemCompatibility());
        $this->assertEquals('["hatSpecifics1", "hatSpecifics2"]', $this->item->getItemSpecifics());
        $this->assertEquals('<!DOCTYPE html><html><body><h1>Woolen Hat</h1><p>Comfortable for winters.</p></body></html>', $this->item->getHtmlDescription());
        $this->assertEquals(13.5, $this->item->getNetPrice());
        $this->assertEquals('differentFiletimeString', $this->item->getFiletime());
    }

    /**
     * Tests the 'toArray' method of the 'Item' class whether
     * it converts a Item object to the correct array with and without id.
     */
    public function testItemToArrayConversion() {

        $expectedArray = [
            'id' => 2,
            'item_id' => '111111111111',
            'title' => 'Silk Scarf',
            'current_price' => 10.89,
            'listing_status' => 'Active',
            'quantity' => 5,
            'quantity_sold' => 3,
            'condition' => 1000,
            'category' => 25894,
            'store_category_id' => '444',
            'store_category_2_id' => '',
            'view_item_url' => 'silk-scarf.com',
            'pictures' => '["galleryPic.com", "scarfPic1.com", "scarfPic2.com"]',
            'site' => 77,
            'country' => 'Germany',
            'currency' => '€',
            'ship_to_locations' => '["Europe"]',
            'shipping_options' => '["DHL", "PickUp"]',
            'item_compatibility' => '["compatibility1", "compatibility2"]',
            'item_specifics' => '["specifics1", "specifics2"]',
            'html_description' => '<!DOCTYPE html><html><body><h1>Silk Scarf</h1><p>Comfortable for summers.</p></body></html>',
            'net_price' => 9.0,
            'filetime' => 'filetimeString'
        ];

        $expectedArrayWithout = [
            'id' => null,
            'item_id' => '222222222222',
            'title' => 'Woolen Hat',
            'current_price' => 14.99,
            'listing_status' => 'Ended',
            'quantity' => 10,
            'quantity_sold' => 0,
            'condition' => 1500,
            'category' => 38954,
            'store_category_id' => '555',
            'store_category_2_id' => '777',
            'view_item_url' => 'woolen-hat.com',
            'pictures' => '["galleryPic.com", "hatPic1.com", "hatPic2.com"]',
            'site' => 88,
            'country' => 'France',
            'currency' => '€',
            'ship_to_locations' => '["Asia"]',
            'shipping_options' => '["FedEx", "SelfPickup"]',
            'item_compatibility' => '["hatCompatibility1", "hatCompatibility2"]',
            'item_specifics' => '["hatSpecifics1", "hatSpecifics2"]',
            'html_description' => '<!DOCTYPE html><html><body><h1>Woolen Hat</h1><p>Comfortable for winters.</p></body></html>',
            'net_price' => 13.5,
            'filetime' => 'differentFiletimeString'
        ];



        // Assert
        $this->assertEquals($expectedArray, $this->item->toArray());
        $this->assertEquals($expectedArrayWithout, $this->itemWithout->toArray());
    }

    /**
     * Tests the 'toArray' method of the 'Item' class whether
     * an exception is thrown when trying to convert an object to an array
     * with an invalid key.
     */
    public function testItemToArrayThrowsExceptionForInvalidKey() {

        // Arrange: Add an invalid key to the keyArray
        $invalidKeyArray = [
            'id', 'item_id', 'item_title', 'current_price', 'listing_status', 'quantity', 'quantity_sold', 'condition',
            'category', 'store_category_id', 'store_category_2_id', 'view_item_url', 'pictures',
            'site', 'country', 'currency', 'ship_to_locations', 'shipping_options',
            'item_compatibility', 'item_specifics', 'html_description', 'net_price', 'filetime'
        ];
        $itemWithInvalidKey = new Item($invalidKeyArray, '111111111111', 'Silk Scarf', 10.89, 'Active', 5, 3, 1000, 25894, '444', '', 'silk-scarf.com', '["galleryPic.com", "scarfPic1.com", "scarfPic2.com"]', 77, 'Germany', '€', '["Europe"]', '["DHL", "PickUp"]', '["compatibility1", "compatibility2"]', '["specifics1", "specifics2"]', '<!DOCTYPE html><html><body><h1>Silk Scarf</h1><p>Comfortable for summers.</p></body></html>', 9.0, 'filetimeString', 2);


        // Assert
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage("Invalid Request: Getter for 'item_title' does not exist in App\Entity\Item.");

        // Act
        $itemWithInvalidKey->toArray();
    }
}
