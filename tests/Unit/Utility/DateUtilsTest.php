<?php

namespace Tests\Unit\Utility;

use App\Utility\DateUtils;
use Codeception\Test\Unit;

/**
 * The 'DateUtilsTest' is a unit test class for the 'DateUtils' class. 
 * 
 * Tests the functionality of calculating dates regarding addition and substraction. 
 */
class DateUtilsTest extends Unit {

    protected $tester;
    private $dateUtils;
    private $timestampStart;
    private $timestampStartLeap;

    /**
     * Sets up the necessary environment for running tests 
     * by initializing the DateUtils object and setting timestamp variables 
     * representing a regular and a leap year.
     */
    protected function _before() {

        $this->dateUtils = new DateUtils();
        $this->timestampStart = '2023-02-28T05:42:13.030Z';
        $this->timestampStartLeap = '2024-02-28T05:42:13.030Z';
    }

    /**
     * Tests wether the 'calculateNewTimestamp' method of the 'DateUtils' class
     * adds the correct amount of time.
     */
    public function testCalculateUtcTimestampAddition() {

        // Arrange 'add 1 day'
        $expect = '2023-03-01T05:42:13.030Z';

        // Act
        $result = $this->dateUtils->calculateNewUtcTimestamp($this->timestampStart, '+', 1, 'D');

        // Assert
        $this->assertEquals($expect, $result);
    }

    /**
     * Tests wether the 'calculateNewTimestamp' method of the 'DateUtils' class
     * substracts the correct amount of time.
     */
    public function testCalculateUtcTimestampSubtraction() {

        // Arrange 'substract 2 hours'
        $expect = '2023-02-27T23:42:13.030Z';

        // Act
        $result = $this->dateUtils->calculateNewUtcTimestamp($this->timestampStart, '-', 6, 'H');

        // Assert
        $this->assertEquals($expect, $result);
    }

    /**
     * Tests wether the 'calculateNewTimestamp' method of the 'DateUtils' class
     * throws an exception with invalid operator provided.
     */
    public function testCalculateUtcTimestampInvalidOperator() {

        // Assert 'wrong operator'
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('Timestamp Calculation: Invalid parameter.');

        // Act
        $this->dateUtils->calculateNewUtcTimestamp($this->timestampStart, '*', 5, 'M');
    }

    /**
     * Tests wether the 'calculateNewTimestamp' method of the 'DateUtils' class
     * throws an exception with invalid unit provided.
     */
    public function testCalculateUtcTimestampInvalidUnit() {

        // Assert 'wrong unit'
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('Timestamp Calculation: Invalid parameter.');

        // Act
        $this->dateUtils->calculateNewUtcTimestamp($this->timestampStart, '+', 5, 'Y');
    }

    /**
     * Tests wether the 'calculateNewTimestamp' method of the 'DateUtils' class
     * calculates a new timestamp in a leap year correctly.
     */
    public function testCalculateUtcTimestampLeapYear() {

        // Arrange 'not switching to next month'
        $expectLeap = '2024-02-29T05:42:13.030Z';

        // Act
        $result = $this->dateUtils->calculateNewUtcTimestamp($this->timestampStartLeap, '+', 1, 'D');

        // Assert
        $this->assertEquals($expectLeap, $result);
    }
}
