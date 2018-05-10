<?php

namespace RandomBeer\Application;

use RandomBeer\Application\Model;
use Exception;

/**
 * This is a Beer Controller.
 * It uses beer Model to render its data in an initial request/page load.
 */
class Beer
{

    // This variable stores Beer Model instance.
    public $beer;

    /**
     * Fetch a random beer.
     * 
     * @param Model $model
     * @return array
     */
    public function getRandomBeer(Model $model)
    {
        $this->beer = $model->getRandomBeer()->fetch();
        return $this->beer;
    }

    /**
     * Renders beer data on the screen.
     */
    public function render()
    {
        $beer_view = static::capture(array('beer' => $this->beer), 'view/ajax.beer.php');
        echo static::capture(array('beer' => $beer_view), 'view/main.php');
    }

    /**
     * This is a helper function that captures view output.
     * It is used to pass PHP variables (model data) to the PHP template.
     * 
     * @param array $data
     * @param string $file_name
     * @return string
     * @throws Exception
     */
    public static function capture(array $data, string $file_name)
    {
        extract($data, EXTR_SKIP | EXTR_REFS);
        // Capture the view output.
        ob_start();

        try {
            // Load the view within the current scope.
            include $file_name;
        } catch (Exception $e) {
            // Delete the output buffer.
            ob_end_clean();

            // Re-throw the exception.
            throw $e;
        }

        // Get the captured output and close the buffer.
        return ob_get_clean();
    }

}
