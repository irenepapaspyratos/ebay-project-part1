<?php

namespace Tests\Unit\Entity;

use App\Entity\Item;
use Codeception\Test\Unit;

/**
 * The 'ItemTest' is a unit test class for testing the 'Item' entity.
 * 
 * Tests the functionality of creating an instance and getting/setting the entity-related properties.
 */
class ItemTest extends Unit {

    protected $tester;
    private $item;
    private $prefix = 'ebay_';

    /**
     * Sets up the necessary environment for running tests by 
     * creating an instance of 'Item'.
     */
    protected function _before() {

        $this->item = new Item($this->prefix);
    }

    /**
     * Tests whether the 'Item' instance is created correctly.
     */
    public function testItemCreation() {

        // Assert that an instance of 'Item' was created
        $this->assertInstanceOf(Item::class, $this->item);
    }

    /**
     * Tests whether the setters in the 'Item' entity modify the properties correctly.
     */
    public function testItemSettersAndGetters() {

        // Act 
        $this->item->id = 5;
        $this->item->item_id = 123456;
        $this->item->title = 'Sample Item';
        $this->item->current_price = 25.50;
        $this->item->fk_status = 1;
        $this->item->quantity = 10;
        $this->item->quantity_sold = 2;
        $this->item->fk_condition = 1;
        $this->item->fk_category = 101;
        $this->item->store_category_id = 'store_123';
        $this->item->store_category_2_id = 'store_456';
        $this->item->view_item_url = 'http://example.com/item/123456';
        $this->item->pictures = json_encode(['pic1.jpg', 'pic2.jpg']);
        $this->item->fk_site = 1;
        $this->item->fk_country = 1;
        $this->item->fk_currency = 1;
        $this->item->ship_to_locations = json_encode(['US', 'UK']);
        $this->item->shipping_options = json_encode(['Free Shipping', 'Expedited']);
        $this->item->item_compatibility = json_encode(['Model A', 'Model B']);
        $this->item->item_specifics = json_encode(['Color' => 'Red', 'Size' => 'M']);
        $this->item->html_description = '<p>Sample description</p>';
        $this->item->net_price = 20.50;
        $this->item->filetime = '2023-09-10 10:00:00';

        // Assert that the correct values of the changes are returned
        $this->assertEquals(5, $this->item->id);
        $this->assertEquals(123456, $this->item->item_id);
        $this->assertEquals('Sample Item', $this->item->title);
        $this->assertEquals(25.50, $this->item->current_price);
        $this->assertEquals(1, $this->item->fk_status);
        $this->assertEquals(10, $this->item->quantity);
        $this->assertEquals(2, $this->item->quantity_sold);
        $this->assertEquals(1, $this->item->fk_condition);
        $this->assertEquals(101, $this->item->fk_category);
        $this->assertEquals('store_123', $this->item->store_category_id);
        $this->assertEquals('store_456', $this->item->store_category_2_id);
        $this->assertEquals('http://example.com/item/123456', $this->item->view_item_url);
        $this->assertEquals(['pic1.jpg', 'pic2.jpg'], json_decode($this->item->pictures, true));
        $this->assertEquals(1, $this->item->fk_site);
        $this->assertEquals(1, $this->item->fk_country);
        $this->assertEquals(1, $this->item->fk_currency);
        $this->assertEquals(['US', 'UK'], json_decode($this->item->ship_to_locations, true));
        $this->assertEquals(['Free Shipping', 'Expedited'], json_decode($this->item->shipping_options, true));
        $this->assertEquals(['Model A', 'Model B'], json_decode($this->item->item_compatibility, true));
        $this->assertEquals(['Color' => 'Red', 'Size' => 'M'], json_decode($this->item->item_specifics, true));
        $this->assertEquals('<p>Sample description</p>', $this->item->html_description);
        $this->assertEquals(20.50, $this->item->net_price);
        $this->assertEquals('2023-09-10 10:00:00', $this->item->filetime);
    }

    /**
     * Tests the 'toArray' method of the 'Item' entity whether
     * it converts an Item object to the correct array.
     */
    public function testItemToArrayConversion() {

        // Act
        $this->item->id = 1;
        $this->item->item_id = 654321;
        $this->item->title = 'Another Item';
        $this->item->current_price = 15.50;
        // ... (similarly set other properties)

        $expectedArray = [
            'id' => 1,
            'item_id' => 654321,
            'title' => 'Another Item',
            'current_price' => 15.50,
            'fk_status' => null,
            'quantity' => null,
            'quantity_sold' => null,
            'fk_condition' => null,
            'fk_category' => null,
            'store_category_id' => null,
            'store_category_2_id' => null,
            'view_item_url' => null,
            'pictures' => null,
            'fk_site' => null,
            'fk_country' => null,
            'fk_currency' => null,
            'ship_to_locations' => null,
            'shipping_options' => null,
            'item_compatibility' => null,
            'item_specifics' => null,
            'html_description' => null,
            'net_price' => null,
            'filetime' => null
        ];

        // Assert that the correct array is returned
        $this->assertEquals($expectedArray, $this->item->toArray());
    }

    /**
     * Tests the getter of the 'Item' class whether
     * it throws an exception on a missing table key.
     */
    public function testItemMissingTableKeyException() {

        // Assert that an exception of type `\Exception` is thrown containing the correct message 
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage("Columns for table 'wrongprefix_item' not found.");

        // Act
        $item = new Item('wrongprefix_');
    }

    /**
     * Tests the setter of the 'Item' entity whether
     * it throws an exception on an invalid property.
     */
    public function testItemSetterInvalidPropertyException() {

        // Assert that an exception of type `\Exception` is thrown containing the correct message 
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage("Invalid property: invalid_property");

        // Act
        $this->item->invalid_property = 'test';
    }

    /**
     * Tests the setter of the 'Item' entity whether
     * it throws an exception on an invalid property type.
     */
    public function testItemSetterInvalidTypeException() {

        // Assert that an exception of type `\Exception` is thrown containing the correct message 
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage("Invalid type for 'item_id'. Expected integer, got string");

        // Act
        $this->item->item_id = '5';
    }

    /**
     * Tests the setter of the 'Item' class whether
     * it throws an exception on an invalid JSON string.
     */
    public function testItemSetterInvalidJsonException() {

        // Assert that an exception of type `\InvalidArgumentException` is thrown containing the correct message 
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage("Invalid JSON data for 'pictures'");

        // Act
        $this->item->pictures = 'invalid_json_string';
    }

    /**
     * Tests the getter of the 'Item' entity whether
     * it throws an exception on an invalid property.
     */
    public function testItemGetterInvalidPropertyException() {

        // Assert that an exception of type `\Exception` is thrown containing the correct message 
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage("Property 'invalid_property' not existing");

        // Act
        $value = $this->item->invalid_property;
    }
}
