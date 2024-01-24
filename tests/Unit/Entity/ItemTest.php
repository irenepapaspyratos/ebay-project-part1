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
    private $table = 'ebay_item';
    private $tables = [
        'ebay_item' =>
        [
            'columns' => [
                'id' => 'integer',
                'item_id' => 'integer',
                'title' => 'string',
                'subtitle' => 'string',
                'current_price' => 'float',
                'fk_status' => 'integer',
                'start_time_utc' => 'DateTime',
                'end_time_utc' => 'DateTime',
                'quantity' => 'integer',
                'quantity_sold' => 'integer',
                'fk_condition' => 'integer',
                'fk_category' => 'integer',
                'fk_second_category' => 'integer',
                'store_category_id' => 'string',
                'store_category_2_id' => 'string',
                'view_item_url' => 'string',
                'pictures' => 'JSON',
                'fk_site' => 'integer',
                'fk_country' => 'integer',
                'fk_currency' => 'integer',
                'ship_to_locations' => 'JSON',
                'shipping_options' => 'JSON',
                'item_compatibility' => 'JSON',
                'item_specifics' => 'JSON',
                'html_description' => 'string',
                'net_price' => 'float',
                'last_update_utc' => 'DateTime'
            ],
        ],
    ];

    /**
     * Sets up the necessary environment for running tests by 
     * creating an instance of 'Item'.
     */
    protected function _before() {

        $this->item = new Item($this->table, $this->tables);
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
        $this->item->start_time_utc = new \DateTime('2020-01-05T06:59:20.000Z');
        $this->item->end_time_utc = new \DateTime('2020-07-10T06:59:20.000Z');
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
        $this->item->last_update_utc = new \DateTime('2023-09-10 10:00:00');

        // Assert that the correct values of the changes are returned
        $this->assertInstanceOf(\DateTime::class, $this->item->start_time_utc);
        $this->assertInstanceOf(\DateTime::class, $this->item->end_time_utc);
        $this->assertInstanceOf(\DateTime::class, $this->item->last_update_utc);

        $this->assertTrue(is_int($this->item->id));
        $this->assertEquals(5, $this->item->id);
        $this->assertEquals(123456, $this->item->item_id);
        $this->assertEquals('Sample Item', $this->item->title);
        $this->assertEquals(25.50, $this->item->current_price);
        $this->assertEquals(1, $this->item->fk_status);
        $this->assertEquals('2020-01-05 06:59:20', $this->item->start_time_utc->format('Y-m-d\ H:i:s'));
        $this->assertEquals('2020-07-10 06:59:20', $this->item->end_time_utc->format('Y-m-d\ H:i:s'));
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
        $this->assertEquals('2023-09-10 10:00:00', $this->item->last_update_utc->format('Y-m-d\ H:i:s'));
    }

    /**
     * Tests the 'toArray' method of the 'Item' entity whether
     * it converts an Item object to the correct array.
     */
    public function testItemToArrayConversion() {

        // Act 
        $this->item->id = 5;
        $this->item->item_id = 123456;
        $this->item->title = 'Sample Item';
        $this->item->subtitle = 'Sample Sub Item';
        $this->item->current_price = 25.50;
        $this->item->fk_status = 1;
        $this->item->start_time_utc = new \DateTime('2020-01-05T06:59:20.000Z');
        $this->item->end_time_utc = new \DateTime('2020-07-10T06:59:20.000Z');
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
        $this->item->html_description = '<p>Sample description</p>';
        $this->item->net_price = 20.50;
        $this->item->last_update_utc = new \DateTime('2023-09-10 10:00:00');

        // Assert that the correct types are returned
        $this->assertInstanceOf(\DateTime::class, $this->item->start_time_utc);
        $this->assertInstanceOf(\DateTime::class, $this->item->end_time_utc);
        $this->assertInstanceOf(\DateTime::class, $this->item->last_update_utc);

        $expectedArray = [
            'id' => 5,
            'item_id' => 123456,
            'title' => 'Sample Item',
            'subtitle' => 'Sample Sub Item',
            'current_price' => 25.50,
            'fk_status' => 1,
            'start_time_utc' => (new \DateTime('2020-01-05 06:59:20', new \DateTimeZone('UTC')))->format('Y-m-d H:i:s'),
            'end_time_utc' => (new \DateTime('2020-07-10 06:59:20', new \DateTimeZone('UTC')))->format('Y-m-d H:i:s'),
            'quantity' => 10,
            'quantity_sold' => 2,
            'fk_condition' => 1,
            'fk_category' => 101,
            'fk_second_category' => null,
            'store_category_id' => 'store_123',
            'store_category_2_id' => 'store_456',
            'view_item_url' => 'http://example.com/item/123456',
            'pictures' => '["pic1.jpg","pic2.jpg"]',
            'fk_site' => 1,
            'fk_country' => 1,
            'fk_currency' => 1,
            'ship_to_locations' => '["US","UK"]',
            'shipping_options' => null,
            'item_compatibility' => null,
            'item_specifics' => null,
            'html_description' => '<p>Sample description</p>',
            'net_price' => 20.50,
            'last_update_utc' => (new \DateTime('2023-09-10 10:00:00', new \DateTimeZone('UTC')))->format('Y-m-d H:i:s'),
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
        $this->expectExceptionMessage("Columns for table 'wrongtable' not found.");

        // Act
        $item = new Item('wrongtable', $this->tables);
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
