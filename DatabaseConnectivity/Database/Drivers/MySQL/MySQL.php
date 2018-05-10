<?php

namespace Database\Drivers\MySQL;

use Database\Database;
use Database\Drivers\MySQL\Result;
use mysqli,
    Exception;

/**
 * MySQL database connection.
 */
class MySQL extends Database
{

    // Database in use by each connection
    protected static $_current_databases = array();
    // Use SET NAMES to set the character set
    protected static $_set_names;
    // Identifier for this connection within the PHP driver
    protected $_connection_id;
    // MySQL uses a backtick for identifiers
    protected $_identifier = '`';

    public function connect()
    {
        if ($this->_connection) {
            return;
        }

        if (MySQL::$_set_names === null) {
            // Determine if we can use mysqli_set_charset(), which is only
            // available on PHP 5.2.3+ when compiled against MySQL 5.0+
            MySQL::$_set_names = !function_exists('mysqli_set_charset');
        }

        // Extract the connection parameters, adding required variabels
        extract($this->_config['connection'] + array(
            'database' => '',
            'hostname' => '',
            'username' => '',
            'password' => '',
            'port' => 3306,
            'socket' => '',
        ));

        // Prevent this information from showing up in traces
        unset($this->_config['connection']['username'], $this->_config['connection']['password']);

        try {
            $this->_connection = new mysqli($hostname, $username, $password, $database, $port, $socket);
        } catch (Exception $e) {
            // No connection exists
            $this->_connection = null;

            throw new Exception('Error: ' . $e->getMessage());
        }

        $this->_connection_id = sha1($hostname . '_' . $username . '_' . $password);

        if (!empty($this->_config['charset'])) {
            // Set the character set
            $this->set_charset($this->_config['charset']);
        }

        if (!empty($this->_config['connection']['variables'])) {
            // Set session variables
            $variables = array();

            foreach ($this->_config['connection']['variables'] as $var => $val) {
                $variables[] = 'SESSION ' . $var . ' = ' . $this->quote($val);
            }

            $this->_connection->query('SET ' . implode(', ', $variables));
        }
    }

    public function disconnect()
    {
        try {
            // Database is assumed disconnected
            $status = true;

            if (is_resource($this->_connection)) {
                if ($status = $this->_connection->close()) {
                    // Clear the connection
                    $this->_connection = null;

                    // Clear the instance
                    parent::disconnect();
                }
            }
        } catch (Exception $e) {
            // Database is probably not disconnected
            $status = !is_resource($this->_connection);
        }

        return $status;
    }

    public function set_charset($charset)
    {
        // Make sure the database is connected
        $this->_connection or $this->connect();

        if (MySQL::$_set_names === true) {
            // PHP is compiled against MySQL 4.x
            $status = (bool) $this->_connection->query('SET NAMES ' . $this->quote($charset));
        } else {
            // PHP is compiled against MySQL 5.x
            $status = $this->_connection->set_charset($charset);
        }

        if ($status === false) {
            throw new Exception(__(':error', array(':error' => $this->_connection->error)));
        }
    }

    public function query($type, $sql, $as_object = false, array $params = null)
    {
        // Make sure the database is connected
        $this->_connection or $this->connect();

        // Execute the query
        if (($result = $this->_connection->query($sql)) === false) {
            throw new Exception(__(':error [ :query ]', array(
                ':error' => $this->_connection->error,
                ':query' => $sql
            )));
        }

        // Set the last query
        $this->last_query = $sql;

        if ($type === Database::SELECT) {
            // Return an iterator of results
            return new Result($result, $sql, $as_object, $params);
        } else if ($type === Database::INSERT) {
            // Return a list of insert id and rows created
            return array(
                $this->_connection->insert_id,
                $this->_connection->affected_rows,
            );
        } else {
            // Return the number of rows affected
            return $this->_connection->affected_rows;
        }
    }

    public function escape($value)
    {
        // Make sure the database is connected
        $this->_connection or $this->connect();

        if (($value = $this->_connection->real_escape_string((string) $value)) === false) {
            throw new Exception(__(':error', array(
                ':error' => $this->_connection->error
                    )
            ));
        }

        // SQL standard is to use single-quotes for all values
        return "'$value'";
    }
    
    
	/**
	 * Start a SQL transaction
	 *
	 * @link http://dev.mysql.com/doc/refman/5.0/en/set-transaction.html
	 *
	 * @param string $mode  Isolation level
	 * @return boolean
	 */
	public function begin($mode = null)
	{
		// Make sure the database is connected
		$this->_connection or $this->connect();

		if ($mode && ! $this->_connection->query("SET TRANSACTION ISOLATION LEVEL $mode"))
		{
			throw new Exception(__(':error', array(
				':error' => $this->_connection->error
			)));
		}

		return (bool) $this->_connection->query('START TRANSACTION');
	}

	/**
	 * Commit a SQL transaction
	 *
	 * @return boolean
	 */
	public function commit()
	{
		// Make sure the database is connected
		$this->_connection or $this->connect();

		return (bool) $this->_connection->query('COMMIT');
	}

	/**
	 * Rollback a SQL transaction
	 *
	 * @return boolean
	 */
	public function rollback()
	{
		// Make sure the database is connected
		$this->_connection or $this->connect();

		return (bool) $this->_connection->query('ROLLBACK');
	}

}
