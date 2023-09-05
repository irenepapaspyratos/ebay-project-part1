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
    private $item01;
    private $item01WithoutId;
    private $item02;

    /**
     * Sets up the necessary environment for running tests by 
     * creating different instances of 'Item'.
     */
    protected function _before() {

        $this->item01WithoutId = new Item(null, '111111111111', 'Silk Scarf', 10.89, 'Active', 5, 3, 1000, 25894, '444', '', 'silk-scarf.com', ['galleryPic.com', 'pic1.com', 'pic2.com'], 77, 'Germany', '€', ['Europe'], ['DHL', 'PickUp'], ['compatibility1', 'compatibility2'], ['specifics1', 'specifics2'], '<!DOCTYPE html><html><body><h1>Silk Scarf</h1><p>Comfortable for summers.</p></body></html>', 9.0, 'filetimeString');
        $this->item01 = new Item(1, '111111111111', 'Silk Scarf', 10.89, 'Active', 5, 3, 1000, 25894, '444', '', 'silk-scarf.com', ['galleryPic.com', 'pic1.com', 'pic2.com'], 77, 'Germany', '€', ['Europe'], ['DHL', 'PickUp'], ['compatibility1', 'compatibility2'], ['specifics1', 'specifics2'], '<!DOCTYPE html><html><body><h1>Silk Scarf</h1><p>Comfortable for summers.</p></body></html>', 9.0, 'filetimeString');
        $this->item02 = new Item(2, '222222222222', 'Woolen Hat', 14.99, 'Ended', 10, 0, 1500, 38954, '555', '777', 'woolen-hat.com', ['galleryPic1.com', 'hatPic2.com', 'hatPic3.com'], 88, 'France', '€', ['Asia'], ['FedEx', 'SelfPickup'], ['hatCompatibility1', 'hatCompatibility2'], ['hatSpecifics1', 'hatSpecifics2'], '<!DOCTYPE html><html><body><h1>Woolen Hat</h1><p>Comfortable for winters.</p></body></html>', 13.5, 'differentFiletimeString');
    }

    /**
     * Tests whether the 'Item' instance is created correctly without id.
     */
    public function testItemCreationWithoutId() {

        // Assert that an instance of 'Item' was created
        $this->assertInstanceOf(Item::class, $this->item01WithoutId);
    }

    /**
     * Tests whether the 'Item' instance is created correctly with id.
     */
    public function testItemCreationWithId() {

        // Assert that an instance of 'Item' was created
        $this->assertInstanceOf(Item::class, $this->item01);
    }

    /**
     * Tests whether the getters of the 'Item' class return the correct values without id.
     */
    public function testItemGettersWithoutId() {

        // Assert that the getters return the expected value
        $this->assertEquals(null, $this->item01WithoutId->getId());
        $this->assertEquals('111111111111', $this->item01WithoutId->getItemId());
        $this->assertEquals('Silk Scarf', $this->item01WithoutId->getTitle());
        $this->assertEquals(10.89, $this->item01WithoutId->getCurrentPrice());
        $this->assertEquals('Active', $this->item01WithoutId->getListingStatus());
        $this->assertEquals(5, $this->item01WithoutId->getQuantity());
        $this->assertEquals(3, $this->item01WithoutId->getQuantitySold());
        $this->assertEquals(1000, $this->item01->getConditionId());
        $this->assertEquals(25894, $this->item01WithoutId->getCategoryId());
        $this->assertEquals('444', $this->item01WithoutId->getStoreCategoryId());
        $this->assertEquals('', $this->item01WithoutId->getStoreCategory2Id());
        $this->assertEquals('silk-scarf.com', $this->item01WithoutId->getViewItemUrl());
        $this->assertEquals(['galleryPic.com', 'pic1.com', 'pic2.com'], $this->item01WithoutId->getPictures());
        $this->assertEquals(77, $this->item01WithoutId->getSite());
        $this->assertEquals('Germany', $this->item01WithoutId->getCountry());
        $this->assertEquals('€', $this->item01WithoutId->getCurrency());
        $this->assertEquals(['Europe'], $this->item01WithoutId->getShipToLocations());
        $this->assertEquals(['DHL', 'PickUp'], $this->item01WithoutId->getShippingOptions());
        $this->assertEquals(['compatibility1', 'compatibility2'], $this->item01WithoutId->getItemCompatibility());
        $this->assertEquals(['specifics1', 'specifics2'], $this->item01WithoutId->getItemSpecifics());
        $this->assertEquals('<!DOCTYPE html><html><body><h1>Silk Scarf</h1><p>Comfortable for summers.</p></body></html>', $this->item01WithoutId->getHtmlDescription());
        $this->assertEquals(9.0, $this->item01WithoutId->getNetPrice());
        $this->assertEquals('filetimeString', $this->item01WithoutId->getFiletime());
    }

    /**
     * Tests whether the getters of the 'Item' class return the correct values with id.
     */
    public function testItemGettersWithId() {

        // Assert that the getters return the expected value
        $this->assertEquals(1, $this->item01->getId());
        $this->assertEquals('111111111111', $this->item01->getItemId());
        $this->assertEquals('Silk Scarf', $this->item01->getTitle());
        $this->assertEquals(10.89, $this->item01->getCurrentPrice());
        $this->assertEquals('Active', $this->item01->getListingStatus());
        $this->assertEquals(5, $this->item01->getQuantity());
        $this->assertEquals(3, $this->item01->getQuantitySold());
        $this->assertEquals(1000, $this->item01->getConditionId());
        $this->assertEquals(25894, $this->item01->getCategoryId());
        $this->assertEquals('444', $this->item01->getStoreCategoryId());
        $this->assertEquals('', $this->item01->getStoreCategory2Id());
        $this->assertEquals('silk-scarf.com', $this->item01->getViewItemUrl());
        $this->assertEquals(['galleryPic.com', 'pic1.com', 'pic2.com'], $this->item01->getPictures());
        $this->assertEquals(77, $this->item01->getSite());
        $this->assertEquals('Germany', $this->item01->getCountry());
        $this->assertEquals('€', $this->item01->getCurrency());
        $this->assertEquals(['Europe'], $this->item01->getShipToLocations());
        $this->assertEquals(['DHL', 'PickUp'], $this->item01->getShippingOptions());
        $this->assertEquals(['compatibility1', 'compatibility2'], $this->item01->getItemCompatibility());
        $this->assertEquals(['specifics1', 'specifics2'], $this->item01->getItemSpecifics());
        $this->assertEquals('<!DOCTYPE html><html><body><h1>Silk Scarf</h1><p>Comfortable for summers.</p></body></html>', $this->item01->getHtmlDescription());
        $this->assertEquals(9.0, $this->item01->getNetPrice());
        $this->assertEquals('filetimeString', $this->item01->getFiletime());
    }

    /**
     * Tests whether the setters in the 'Item' class modify the properties correctly.
     */
    public function testItemSettersModifyProperties() {

        // Act 
        $this->item01->setId(2);
        $this->item01->setItemId('222222222222');
        $this->item01->setTitle('Woolen Hat');
        $this->item01->setCurrentPrice(14.99);
        $this->item01->setListingStatus('Ended');
        $this->item01->setQuantity(10);
        $this->item01->setQuantitySold(0);
        $this->item01->setConditionId(1500);
        $this->item01->setCategoryId(38954);
        $this->item01->setStoreCategoryId('555');
        $this->item01->setStoreCategory2Id('777');
        $this->item01->setViewItemUrl('woolen-hat.com');
        $this->item01->setPictures(['galleryPic1.com', 'hatPic2.com', 'hatPic3.com']);
        $this->item01->setSite(88);
        $this->item01->setCountry('France');
        $this->item01->setCurrency('€');
        $this->item01->setShipToLocations(['Asia']);
        $this->item01->setShippingOptions(['FedEx', 'SelfPickup']);
        $this->item01->setItemCompatibility(['hatCompatibility1', 'hatCompatibility2']);
        $this->item01->setItemSpecifics(['hatSpecifics1', 'hatSpecifics2']);
        $this->item01->setHtmlDescription('<!DOCTYPE html><html><body><h1>Woolen Hat</h1><p>Comfortable for winters.</p></body></html>');
        $this->item01->setNetPrice(13.5);
        $this->item01->setFiletime('differentFiletimeString');

        // Assert
        $this->assertEquals(2, $this->item01->getId());
        $this->assertEquals('222222222222', $this->item01->getItemId());
        $this->assertEquals('Woolen Hat', $this->item01->getTitle());
        $this->assertEquals(14.99, $this->item01->getCurrentPrice());
        $this->assertEquals('Ended', $this->item01->getListingStatus());
        $this->assertEquals(10, $this->item01->getQuantity());
        $this->assertEquals(0, $this->item01->getQuantitySold());
        $this->assertEquals(1500, $this->item01->getConditionId());
        $this->assertEquals(38954, $this->item01->getCategoryId());
        $this->assertEquals('555', $this->item01->getStoreCategoryId());
        $this->assertEquals('777', $this->item01->getStoreCategory2Id());
        $this->assertEquals('woolen-hat.com', $this->item01->getViewItemUrl());
        $this->assertEquals(['galleryPic1.com', 'hatPic2.com', 'hatPic3.com'], $this->item01->getPictures());
        $this->assertEquals(88, $this->item01->getSite());
        $this->assertEquals('France', $this->item01->getCountry());
        $this->assertEquals('€', $this->item01->getCurrency());
        $this->assertEquals(['Asia'], $this->item01->getShipToLocations());
        $this->assertEquals(['FedEx', 'SelfPickup'], $this->item01->getShippingOptions());
        $this->assertEquals(['hatCompatibility1', 'hatCompatibility2'], $this->item01->getItemCompatibility());
        $this->assertEquals(['hatSpecifics1', 'hatSpecifics2'], $this->item01->getItemSpecifics());
        $this->assertEquals('<!DOCTYPE html><html><body><h1>Woolen Hat</h1><p>Comfortable for winters.</p></body></html>', $this->item01->getHtmlDescription());
        $this->assertEquals(13.5, $this->item01->getNetPrice());
        $this->assertEquals('differentFiletimeString', $this->item01->getFiletime());
    }

    /**
     * Tests the 'toArray' method of the 'Item' class whether
     * it converts a Item object to the correct array without id.
     */
    public function testToArrayConversionWithoutId() {

        $expectedArray = [
            'id' => null,
            'item_id' => '111111111111',
            'title' => 'Silk Scarf',
            'current_price' => 10.89,
            'listing_status' => 'Active',
            'quantity' => 5,
            'quantity_sold' => 3,
            'condition_id' => 1000,
            'category_id' => 25894,
            'store_category_id' => '444',
            'store_category_2_id' => '',
            'view_item_url' => 'silk-scarf.com',
            'pictures' => ['galleryPic.com', 'pic1.com', 'pic2.com'],
            'site' => 77,
            'country' => 'Germany',
            'currency' => '€',
            'ship_to_locations' => ['Europe'],
            'shipping_options' => ['DHL', 'PickUp'],
            'item_compatibility' => ['compatibility1', 'compatibility2'],
            'item_specifics' => ['specifics1', 'specifics2'],
            'html_description' => '<!DOCTYPE html><html><body><h1>Silk Scarf</h1><p>Comfortable for summers.</p></body></html>',
            'net_price' => 9.0,
            'filetime' => 'filetimeString'
        ];


        // Assert
        $this->assertEquals($expectedArray, $this->item01WithoutId->toArray());
    }

    /**
     * Tests the 'toArray' method of the 'Item' class whether
     * it converts a Item object to the correct array with id.
     */
    public function testToArrayConversionWithId() {

        $expectedArray = [
            'id' => 2,
            'item_id' => '222222222222',
            'title' => 'Woolen Hat',
            'current_price' => 14.99,
            'listing_status' => 'Ended',
            'quantity' => 10,
            'quantity_sold' => 0,
            'condition_id' => 1500,
            'category_id' => 38954,
            'store_category_id' => '555',
            'store_category_2_id' => '777',
            'view_item_url' => 'woolen-hat.com',
            'pictures' => ['galleryPic1.com', 'hatPic2.com', 'hatPic3.com'],
            'site' => 88,
            'country' => 'France',
            'currency' => '€',
            'ship_to_locations' => ['Asia'],
            'shipping_options' => ['FedEx', 'SelfPickup'],
            'item_compatibility' => ['hatCompatibility1', 'hatCompatibility2'],
            'item_specifics' => ['hatSpecifics1', 'hatSpecifics2'],
            'html_description' => '<!DOCTYPE html><html><body><h1>Woolen Hat</h1><p>Comfortable for winters.</p></body></html>',
            'net_price' => 13.5,
            'filetime' => 'differentFiletimeString'
        ];

        // Assert
        $this->assertEquals($expectedArray, $this->item02->toArray());
    }
}
