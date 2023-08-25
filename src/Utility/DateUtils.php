<?php

namespace App\Utility;

/**
 * The 'DateUtils' class provides methods to deal with dates or time.
 * 
 * The contained method calculates a new timestamp by adding or subtracting a 
 * specified quantity of time units from a given timestamp.
 */
class DateUtils {

    /**
     * The 'calculateNewUtcTimestamp' method calculates a new timestamp by adding or subtracting a specified quantity 
     * of time units from a given timestamp using the UTC+0 timezone without any DST transitions.
     * 
     * To be able to calculate with months and years, variable in lengths, 
     * the 'DateTime' class and 'DateInterval' is used instead of simply calculating via strtotime(). 
     * 
     * @param string $timestamp Starting timestamp.
     * @param string $operator Specifies whether to add or subtract ('+' or '-').
     * @param string $quantity Positive integer as string specifying the amount of time to be added or subtracted.
     * @param string $unit Specifies wether to add/substract seconds, minutes, hours or days ('S', 'M', 'H' or 'D').
     * @param string $format Format in which the new is returned (by default: ISO8601 -> 'Y-m-d\TH:i:s.u\Z'). 
     * 
     * @return string New calculated timestamp in ISO8601 format.
     * @throws \Exception If the operator, quantity or unit parameter is not valid.
     */
    public function calculateNewUtcTimestamp(
        string $timestamp,
        string $operator,
        int $quantity,
        string $unit,
        string $format = 'Y-m-d\TH:i:s.v\Z'
    ): string {

        // Validate parameters
        if (($operator !== '+' && $operator !== '-')
            || (!is_int($quantity))
            || (!in_array($unit, ['S', 'M', 'H', 'D']))
        ) throw new \Exception('Timestamp Calculation: Invalid parameter.');

        // Create formatted UTC timestamp, build interval and execute operation
        $datetime = new \DateTime($timestamp, new \DateTimeZone('UTC'));
        $interval = $unit === 'D' ? new \DateInterval("P{$quantity}{$unit}") : new \DateInterval("PT{$quantity}{$unit}");
        $operator === '+' ? $datetime->add($interval) : $datetime->sub($interval);

        return $datetime->format($format);
    }
}
