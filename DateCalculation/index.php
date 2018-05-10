<?php

namespace DateCalculation;
use DateTime;

ini_set('display_errors', 'on');
error_reporting(E_ALL & ~E_DEPRECATED);

/**
 * The Irish National Lottery draw takes place twice weekly on a Wednesday and a Saturday at 8pm.
 * Write a function or class that calculates and returns the next valid draw date based on the current
 * date and time AND also on an optionally supplied date and time.
 */
class DateCalculation
{
    // standard lottery days
    public static $lottery_days = ['Wednesday', 'Saturday'];
    // standard lottery time
    public static $lottery_time = '8 PM';

    /**
     * Function return date of the next lottery.
     * 
     * @param string $date
     * @return DateTime
     */
    public static function lottery($date = null)
    {
        try {
            if (is_null($date)) {
                $date = new DateTime();
            } else {
                $date = new DateTime($date);
            }

            $lottery_dates = array();
            foreach (self::$lottery_days as $day) {
                $next_date = clone $date;
                // if it's the same day and before 8pm (format to 24 hours in order to compare properly),
                // use 'this' otherwise 'next' rel text
                if ($date->format('l') == $day && $date->format('G') < date('G', strtotime(self::$lottery_time))) {
                    $reltext = 'this';
                } else {
                    $reltext = 'next ';
                }
                // eg. "next Wednesday 8 PM"
                $next_date->modify($reltext . ' ' . $day . ' ' . self::$lottery_time);
                $lottery_dates[] = $next_date;
            }

            // sort the dates, so the closest is always first
            usort($lottery_dates, function ($a, $b) {
                return $a <=> $b;
                // could compare their timestamps to be on the safe side
                //return call_user_func(array($a, 'getTimestamp')) <=> call_user_func(array($b, 'getTimestamp'));
            });

            return $lottery_dates[0];

        } catch(Exception $e) {
            // might be invalid format date, also can be checked with strtotime($date) !== false
            return $e->getMessage();
        }
    }

}


$lottery = DateCalculation::lottery();

if ($lottery instanceof DateTime) {
    echo 'Next lottery will take place on ' . $lottery->format('Y-m-d H:i');
} else {
    echo 'An error occured: ' . $lottery;
}