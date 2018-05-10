<?php

namespace Database;

/**
 * Database connection wrapper/helper.
 *
 * You may get a database instance using Database::instance('name') where
 * name is config.php group.
 */
abstract class Database
{

    // Query types
    const SELECT = 1;
    const INSERT = 2;
    const UPDATE = 3;
    const DELETE = 4;

    /**
     * @var string default instance name
     */
    public static $default = 'default';

    /**
     * @var array Database instances
     */
    public static $instances = array();

    /**
     * Get Database instance.
     *
     * @param string $name instance name
     * @param array $config configuration parameters
     * @return Database
     */
    public static function instance($name = null, array $config = null)
    {
        if ($name === null) {
            // Use the default instance name
            $name = Database::$default;
        }

        if (!isset(Database::$instances[$name])) {
            if ($config === null) {
                // Load the configuration for this database
                // this could have been done better...
                $db_config = include 'config.php';
                $config = $db_config[$name];
            }

            // Set the driver class name
            $driver = 'Database\\Drivers\\' . $config['type'] . '\\' . $config['type'];

            // Create the database connection instance
            $driver = new $driver($name, $config);

            // Store the database instance
            Database::$instances[$name] = $driver;
        }

        return Database::$instances[$name];
    }

    /**
     * @var  string  the last query executed
     */
    public $last_query;
    // Character that is used to quote identifiers
    protected $_identifier = '"';
    // Instance name
    protected $_instance;
    // Raw server connection
    protected $_connection;
    // Configuration array
    protected $_config;

    /**
     * Stores the database configuration locally and name the instance.
     *
     * This method cannot be accessed directly, you must use Database::instance.
     *
     * @return  void
     */
    public function __construct($name, array $config)
    {
        // Set the instance name
        $this->_instance = $name;

        // Store the config locally
        $this->_config = $config;

        if (empty($this->_config['table_prefix'])) {
            $this->_config['table_prefix'] = '';
        }
    }

    /**
     * Disconnect from the database when the object is destroyed.
     *
     *     // Destroy the database instance
     *     unset(Database::instances[(string) $db], $db);
     *
     * Calling unset($db) is not enough to destroy the database, as it
     * will still be stored in Database::$instances.
     *
     * @return  void
     */
    public function __destruct()
    {
        $this->disconnect();
    }

    /**
     * Returns the database instance name.
     *
     *     echo (string) $db;
     *
     * @return  string
     */
    public function __toString()
    {
        return $this->_instance;
    }

    /**
     * Connect to the database. This is called automatically when the first
     * query is executed.
     *
     *     $db->connect();
     *
     * @throws  Exception
     * @return  void
     */
    abstract public function connect();

    /**
     * Disconnect from the database. This is called automatically by [Database::__destruct].
     * Clears the database instance from [Database::$instances].
     *
     *     $db->disconnect();
     *
     * @return  boolean
     */
    public function disconnect()
    {
        unset(Database::$instances[$this->_instance]);

        return true;
    }

    /**
     * Set the connection character set. This is called automatically by [Database::connect].
     *
     *     $db->set_charset('utf8');
     *
     * @throws  Exception
     * @param   string   $charset  character set name
     * @return  void
     */
    abstract public function set_charset($charset);

    /**
     * Perform an SQL query of the given type.
     *
     *     // Make a SELECT query and use objects for results
     *     $db->query(Database::SELECT, 'SELECT * FROM groups', true);
     *
     *     // Make a SELECT query and use "Model_User" for the results
     *     $db->query(Database::SELECT, 'SELECT * FROM users LIMIT 1', 'Model_User');
     *
     * @param   integer  $type       Database::SELECT, Database::INSERT, etc
     * @param   string   $sql        SQL query
     * @param   mixed    $as_object  result object class string, true for stdClass, false for assoc array
     * @param   array    $params     object construct parameters for result class
     * @return  object   Database\Result for SELECT queries
     * @return  array    list (insert id, row count) for INSERT queries
     * @return  integer  number of affected rows for all other queries
     */
    abstract public function query($type, $sql, $as_object = false, array $params = null);

    /**
     * Return the table prefix defined in the current configuration.
     *
     *     $prefix = $db->table_prefix();
     *
     * @return  string
     */
    public function table_prefix()
    {
        return $this->_config['table_prefix'];
    }

    /**
     * Quote a value for an SQL query.
     *
     *     $db->quote(null);   // 'null'
     *     $db->quote(10);     // 10
     *     $db->quote('fred'); // 'fred'
     *
     * Objects passed to this function will be converted to strings.
     *
     * @param mixed $value any value to quote
     * @return string
     * @uses Database::escape
     */
    public function quote($value)
    {
        if ($value === null) {
            return 'NULL';
        } else if ($value === true) {
            return "'1'";
        } else if ($value === false) {
            return "'0'";
        } else if (is_object($value)) {
            // Convert the object to a string
            return $this->quote((string) $value);
        } else if (is_array($value)) {
            return '(' . implode(', ', array_map(array($this, __FUNCTION__), $value)) . ')';
        } else if (is_int($value)) {
            return (int) $value;
        } else if (is_float($value)) {
            // Convert to non-locale aware float to prevent possible commas
            return sprintf('%F', $value);
        }

        return $this->escape($value);
    }

    /**
     * Sanitize a string by escaping characters that could cause an SQL
     * injection attack.
     *
     *     $value = $db->escape('any string');
     *
     * @param   string   $value  value to quote
     * @return  string
     */
    abstract public function escape($value);
}
