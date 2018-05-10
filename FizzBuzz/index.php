<?php

namespace FizzBuzz;

/**
 * FizzBuzz
 * Write a PHP script that prints all integer values from 1 to 100.
 * For multiples of three output "Fizz" instead of the value and for the multiples of five output "Buzz".
 * Values which are multiples of both three and five should output as "FizzBuzz".
 */
class FizzBuzz
{

    /**
     * Class constructor, see class description for more details.
     */
    public function __construct()
    {
        for ($i = 1; $i <= 100; $i++) {
            if ($i % (3 * 5) === 0) {
                echo 'FizzBuzz<br>';
            } else if ($i % 3 === 0) {
                echo 'Fizz<br />';
            } else if ($i % 5 === 0) {
                echo 'Buzz<br>';
            } else {
                echo $i . '<br>';
            }
        }
    }

}

new FizzBuzz;
