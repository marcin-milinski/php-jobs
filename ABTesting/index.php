<?php

namespace ABTesting;

ini_set('display_errors', 'on');
error_reporting(E_ALL & ~E_DEPRECATED);

/**
 * A company would like to A/B test a number of promotional designs to see which provides the best
 * conversion rate.
 * Write a snippet of PHP code that redirects end users to the different designs based on the database
 * table below. Extend the database model as needed.
 * i.e. - 50% of people will be shown Design A, 25% shown Design B and 25% shown Design C.
 * The code needs to be scalable as a single promotion may have upwards of 3 designs to test.
 */
class ABTesting
{

    // to store more testing data
    public static $storage = array();

    /**
     * Recursion function that calculates and returns design based on its pre-defined probability.
     * 
     * @param array $designs
     * @param int $ratio
     * @return string design name
     */
    public static function promotionalDesigns($designs, $ratio = 1)
    {
        // get the first element and reduce array as well
        $design = array_shift($designs);

        // use ratio to make sure that the sum of remaining values is always 100
        if (rand(1, 100) <= $design['split_percent'] * $ratio) {

            // benchmark start
            if (!isset(self::$storage[$design['design_name']])) {
                self::$storage[$design['design_name']] = 1;
            } else {
                self::$storage[$design['design_name']]++;
            }
            // benchmark end

            return $design['design_name'];
        } else {
            // calculate the sum of the remaining values
            // in order to find the ratio
            $sum = array_sum(array_column($designs, 'split_percent'));
            // call the function recursively passing what's left from design array and the ratio
            return self::promotionalDesigns($designs, 100 / $sum);
        }
    }

}

// to simplify things, I do not use Database class here 
// as below array structure is very similar to what would have been returned as DB result
$designs = array(
    array('design_id' => 1, 'design_name' => 'Design 1', 'split_percent' => 50),
    array('design_id' => 2, 'design_name' => 'Design 2', 'split_percent' => 25),
    array('design_id' => 3, 'design_name' => 'Design 3', 'split_percent' => 25),
);

// sort the table first to have function working correctly
usort($designs, function ($a, $b) {
    return $a['split_percent'] <=> $b['split_percent'];
});

// this is the chosen design name "selected" based on predefined "split_percent" value
$design = ABTesting::promotionalDesigns($designs);
// As a next step I would pick and render a template
// 
// For example:
//     include("view/". $design . ".php");
//     
//     or (more OO approach)
//     
//     $this->response(View::factory("views/" . $design)->render());
//     
// The task description says about redirecting to a design,
// but I assume it is not about the HTTP redirection and objective is to solve A/B Test visitor splitting problem
// 
// Additional notes:
// - I'd create a cookie, so that once a user "selects" a design, he/she will always see the same design (better UX) in subsequent HTTP requests
// - I'd consider doing this in JS on the client-side with AJAX call to the server to get the template, if there's proxy server or CloudFront/CloudFlare involved

// You can also perform some tests (eg. 100 iterations) to see if it splits as defined in DB ($designs array)
ABTesting::$storage = array();
for ($i = 0; $i < 100; $i++) {
    ABTesting::promotionalDesigns($designs);
}
print_r(ABTesting::$storage);
