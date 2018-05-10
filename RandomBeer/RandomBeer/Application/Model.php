<?php

namespace RandomBeer\Application;

use RandomBeer\Config\Database;
use Exception;

/**
 * This is a very trivial Beer Model - as per task its aim is to return a random beer.
 */
class Model
{
    // This variable stores DB instance.
    private $db;
    // This variable stores DB results.
    public $result;

    /**
     * We're using Dependency Injection to have DB connection accesible from this Model.
     * @param Database $db
     */
    public function __construct(Database $db)
    {
        $this->db = $db;
    }

    /**
     * Gets random beer.
     * 
     * @return Model instance (for chaining purpose)
     * @throws Exception
     */
    public function getRandomBeer()
    {
        try {
            $query = 'SELECT * FROM beers ORDER BY RAND() LIMIT 1';
            // Prepares query statement.
            $stmt = $this->db->prepare($query);
            // Executes query.
            $stmt->execute();
            // Let's store this in an array.
            $this->result = $this->db->asArray($stmt)[0];

            return $this;
        } catch (Exception $e) {
            throw new Exception('A problem with geeting a random beer occured: ' . $e->getMessage());
        }
    }

    /**
     * Fetching the data.
     * 
     * @return array
     */
    public function fetch()
    {
        return $this->result;
    }

}
