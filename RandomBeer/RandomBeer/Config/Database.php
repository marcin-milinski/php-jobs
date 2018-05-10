<?php

namespace RandomBeer\Config;

use PDO;
use PDOException;
use PDOStatement;

/**
 * A simple DB class to get DB connection and instance as well as preparing DB results.
 */
class Database
{

    // Specify database credentials.
    const HOST = "localhost";
    const DB_NAME = "beer_app";
    const USERNAME = "root";
    const PASSWORD = "";
    const CHARSET = "utf8";
    
    public $conn;

    // Gets the database connection.
    public function __construct()
    {
        try {
            $this->conn = new PDO("mysql:host=" . self::HOST . ";dbname=" . self::DB_NAME, self::USERNAME, self::PASSWORD);
            $this->conn->exec("set names " . self::CHARSET);
        } catch (PDOException $e) {
            die("Connection error: " . $e->getMessage());
        }

        return $this->conn;
    }

    /**
     * Prepares the statement.
     * 
     * @param string $query
     * @return PDOStatement
     */
    public function prepare(string $query)
    {
        return $this->conn->prepare($query);
    }

    /**
     * Returns DB result as an array.
     * 
     * @param PDOStatement $stmt
     * @return array
     */
    public function asArray(PDOStatement $stmt)
    {
        $aux = array();
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $aux[] = $row;
        }

        return $aux;
    }

}
