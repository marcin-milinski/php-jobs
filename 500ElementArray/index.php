<?php

namespace ElementArray;

use SplFixedArray;

/**
 * 500 Element Array
 * Write a PHP script to generate a random array of 500 integers (values of 1 – 500 inclusive).
 * Randomly remove and discard an arbitary element from this newly generated array.
 * Write the code to efficiently determine the value of the missing element.
 * Explain your reasoning with comments.
 */
class ElementArray
{

    // array size
    const SIZE = 500;

    /**
     * This way seems to be the quickest
     */
    public function speed()
    {
        $start = microtime(true);
        $start_memory = memory_get_usage();
        $random_array = array();
        for ($i = 0; $i < self::SIZE; $i++) {
            $random_array[$i] = rand(1, self::SIZE);
        }
        /**
         * // array_map and array_walk are circa 25% slower than classic loop
         * $random_array = array_map(function() use (self::SIZE) {
         *   return rand(1, self::SIZE);
         * }, range(1, self::SIZE));
         */
        // print_r($random_array);
        // this is how I understand "remove and discard element"
        unset($random_array[rand(0, self::SIZE - 1)]); //rand(0, 499)
        // print_r($random_array);

        for ($i = 0; $i < self::SIZE; $i++) {
            // I could try to find a gap in this array by comparing subsequent keys (whether their difference is greater than 1)
            // but simply checking if the key exists is much more self explanatory and efficient
            // since all values are integers (not null), I can use "isset" instead of "array_key_exists" as "isset" is much faster
            if (!isset($random_array[$i])) {
                echo 'The missing element is: ' . $i;
            }
        }
        $time_elapsed_secs = microtime(true) - $start;
        $memory_used = memory_get_usage() - $start_memory;
        echo '<br>' . round($time_elapsed_secs * 100, 6) . ' milisec.';
        echo '<br>' . round($memory_used / 1024) . ' kb';
    }

    /**
     * This way with fixed length array seems to be the most memory efficient.
     */
    public function memory()
    {
        $start = microtime(true);
        $start_memory = memory_get_usage();
        $random_array = new SplFixedArray(self::SIZE);
        for ($i = 0; $i < self::SIZE; $i++) {
            $random_array->offsetSet($i, rand(1, self::SIZE));
        }
        // print_r($random_array);
        // this is how I understand "remove and discard element"
        $random_array->offsetUnset(rand(0, self::SIZE - 1)); //rand(0, 499)
        // print_r($random_array);

        for ($i = 0; $i < self::SIZE; $i++) {
            // I could try to find a gap in this array by comparing subsequent keys (whether their difference is greater than 1)
            // but simply checking if the key exists is much more self explanatory and efficient
            // since all values are integers (not null), I can use "isset" instead of "array_key_exists" as "isset" is much faster
            if (!$random_array->offsetExists($i)) {
                echo 'The missing element is: ' . $i;
            }
        }
        $time_elapsed_secs = microtime(true) - $start;
        $memory_used = memory_get_usage() - $start_memory;
        echo '<br>' . round($time_elapsed_secs * 100, 6) . ' milisec.';
        echo '<br>' . round($memory_used / 1024) . ' kb';
    }

}

// The decision which approach to use should be based on system requirements,
// I'd probably go with the faster one in most of the cases.

$obj = new ElementArray;
echo 'Fastest version<br>';
$obj->speed();

echo '<br><br>';
echo 'Best memory efficient version<br>';
$obj->memory();
