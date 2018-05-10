<?php
declare(strict_types=1);
namespace InputSanitizeVerify;

ini_set('display_errors', 'on');
error_reporting(E_ALL & ~E_DEPRECATED);

/**
 * Input sanitizing 
 */
class InputSanitize
{
    private $input;
    
    public function __construct(string $str)
    {
        $this->input = $str;
    }
    
    public function removeMultipleSpaces()
    {
        $this->input = preg_replace("/ +/", " ", $this->input);
        return $this;
    }
    
    /**
     * Assuming special chars are all but A-Z, digits and hyphen
     */
    public function removeSpecialChars()
    {
        $this->input = preg_replace("/[^A-Za-z0-9\-]/", " ", $this->input);
        return $this;
    }
    
    public function __toString():string
    {
        return $this->input;
    }

}

$str = 'Hello    World, nice to see you!';

echo (new InputSanitize($str))
        ->removeMultipleSpaces()
        ->removeSpecialChars();

/**
 * Input verification
 */
class InputVerify
{
    private $input;
    
    public function __construct(string $str)
    {
        $this->input = $str;
    }
    
    private static function isValidEmail(string $email): bool
    { 
        return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
    }
    
    public static function isValidURL(string $url): bool
    {
        return filter_var($url, FILTER_VALIDATE_URL) !== false;
    }
    
    /**
     * Task states: "Verify that there are no email addresses".
     * There's no good solution for this as we do not know what's the type of input.
     * Assuming we deal with a regular/normal input like a sentence where a user migh have placed his e-mail address
     * then below implementation seems to be the best solution.
     * 
     * @return boolean
     */
    public function verifyContainsEmail(): bool
    {
        $words = explode(' ', $this->input);
        foreach ($words as $word) {
            if (static::isValidEmail($word)) {
                return true;
            }
        }
        
        return false;
    }
    
    /**
     * Task states: "Verify that there are no URL".
     * Task very similar to that one with email, approach taken is explained in there.
     * 
     * @return bool
     */
    public function verifyContainsURL(): bool
    {
        $words = explode(' ', $this->input);
        foreach ($words as $word) {
            if (static::isValidURL($word)) {
                return true;
            }
        }
        
        return false;
    }
    
    /**
     * The task states: "Verify if there are no only numbers"
     * so I assume we need to find out if there are other chars as well, not only numbers (digits).
     * So the easiest way is to count number of digits and check if the input string has more chars then that.
     * 
     * @return boolean
     */
    public function verifyContainsAlsoNoneDigitChars(): bool
    {
        $count_digits = preg_match_all("/[0-9]/", $this->input);
        if (mb_strlen($this->input) > $count_digits) {
            return true;
        }
        
        return false;
    }
    
    /**
     * Task states: "Verify there's no more than 9 words".
     * 
     * @param int $less_than_10
     * @return bool
     */
    public function verifyWordsCount(int $less_than_10 = 10): bool
    {
        $words = explode(' ', $this->input);
        return count($words) < $less_than_10;
    }
    
    /**
     * Task states: "Verify if no word is longer than 45 chars".
     * 
     * @param int $chars_limit
     * @return bool true if all words' length are below default 45 chars
     */
    public function verifyWordsLengthNotExceeded(int $chars_limit = 45): bool
    {
        $words = explode(' ', $this->input);
        foreach ($words as $word) {
            if (mb_strlen($word) > $chars_limit) {
                return false;
            }
        }
        
        return true;
    }
}

$str = 'My email is marcin.milinski@gmail.com if you want to drop me a message or simply visit https://www.linkedin.com and find me there. You can also call 0852158332';

$obj = new InputVerify($str);
var_dump($obj->verifyContainsEmail());

var_dump($obj->verifyContainsURL());

var_dump($obj->verifyContainsAlsoNoneDigitChars());

var_dump($obj->verifyWordsCount());

var_dump($obj->verifyWordsLengthNotExceeded());