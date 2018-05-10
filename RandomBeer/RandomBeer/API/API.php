<?php

namespace RandomBeer\API;

use RandomBeer\Application\Model;
use Exception;

interface HTTPMethods
{

    public function response(int $code = 200);

    public function asJSON();
}

/**
 * This is a very trivial API class that allows only GET requests 
 * to fetch a random beer from the model (beer data) provided.
 */
class API implements HTTPMethods
{

    // This variable stores Model instance.
    protected $model;
    // This variable stores DB result.
    private $response;

    /**
     * Use DI pattern to keep this class as close to SOLID principles as possible.
     * 
     * @param Model $model beer data
     * @throws Exception
     */
    public function __construct(Model $model)
    {
        $this->model = $model;
        if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
            throw new Exception('This API does not offer services other than getting a random beer.');
        }
    }

    /**
     * PHP magic method used to call same Model method name, so that we do not need
     * to update API class whenever Model gets a new method.
     * 
     * @param type $methodName
     * @param type $arguments
     * @return string
     */
    public function __call($methodName, $arguments)
    {
        $this->model->$methodName($arguments);
        return $this->response();
    }

    /**
     * A simple response method to send HTTP headers with HTTP body.
     * HTTP body is in JSON format, but this ideally should have more options, eg. to return XML whenever requested.
     * 
     * @param int $code HTTP status code
     * @return string
     */
    public function response(int $code = 200)
    {
        header("HTTP/1.1 $code");
        header("Access-Control-Allow-Origin: *");
        header("Content-Type: application/json; charset=utf-8");

        $this->response = $this->model->result;
        return $this->asJSON();
    }

    /**
     * Format array to JSON.
     * 
     * @return string
     */
    public function asJSON()
    {
        return json_encode(array($this->response));
    }

}
