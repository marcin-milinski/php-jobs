<?php

namespace DatabaseConnectivity;

use Database\DB;
use Database\Database;

ini_set('display_errors', 'on');
error_reporting(E_ALL & ~E_DEPRECATED);

require 'functions.php';

/**
 * Database Connectivity
 * Demonstrate with PHP how you would connect to a MySQL (InnoDB) database and query for all
 * records with the following fields: (name, age, job_title) from a table called 'exads_test'.
 * Also provide an example of how you would write a sanitised record to the same table.
 * 
 * The database classes and functions used in this example are loosely inspired on Database 
 * module for Kohana framework and adjusted specifically for this task.
 */
class DatabaseConnectivity
{

    /**
     * Get job.
     * 
     * @param string $user_name
     * @return Database\Result
     */
    public static function getJob($user_name)
    {
        $query = DB::query(Database::SELECT, 'SELECT * FROM `exads_test` WHERE `name` = :name')
                ->parameters(array(':name' => $user_name));

        // this is the line ("execute()" function) where we all things are happening:
        // - we connect to the database using chosen driver, ie. MySQLi
        // - we compile the above query and do escaping using "real_escape_string" (for MySQLi) - THIS IS VERY IMPORTANT
        // - finally we execute the query and return results
        $result = $query->execute();
        return $result;
    }
    
    /**
     * Update job.
     * 
     * @param array $data
     * @return int affected rows
     */
    public static function updateJob($data)
    {
        try {
            // since it's InnoDB engine (transaction support) and assuming we may have more update/insert dependent queries
            // it is good to rollback all the changes in case there's an error
            Database::instance()->begin();
            
            $query = DB::query(Database::UPDATE, 'UPDATE `exads_test` SET `job_title` = :job_title WHERE `name` = :user_name')
                    ->parameters(array(':job_title' => $data['job_title'],
                                        ':user_name' => $data['user_name']));

            $result = $query->execute();
            
            Database::instance()->commit();
            
            return $result;
        } catch (Exception $e) {
            Database::instance()->rollback();
            return $e->getMessage();
        }
    }

}

$result = DatabaseConnectivity::getJob(1);
// $result is Database object with protected result property
// and to access its records we need to iterate or use $result->as_array()
foreach ($result as $record) {
    print_r($record);
}

echo '<br><br>Update:<br>';
$data = array('job_title' => 'PHP developer', 'user_name' => 'Marcin M.');
// Now, this is important thing:
// 
// Even though we are using "real_escape_string" which protects against SQL injection when making CRUD operations, it is still vital to sanitize input
// because it may contain some dangerous data, like JS script snippets, so basically the system architect must now what data the system is expecting
// and based on that filter (sanitize) input, so for example remove all "script" words; there are many libraries like HTMLPurifier that can be used
// It is important to always save raw data and escape the output; escaping input is not recommended, because the data may be used later as JSON, HTML, XML, etc. (each with different escaping approach)
// 
// I think it's beyond the scope to create sanitize function here, so just giving here a brief idea.
$result = DatabaseConnectivity::addJob($data);
print_r($result);