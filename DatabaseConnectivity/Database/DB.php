<?php

namespace Database;

use Database\Query;

/**
 * Provides a shortcut to get Database.
 */
class DB
{

    /**
     * Create a new database query of the given type.
     *
     *     // Create a new SELECT query
     *     $query = DB::query(Database::SELECT, 'SELECT * FROM users');
     *
     *     // Create a new DELETE query
     *     $query = DB::query(Database::DELETE, 'DELETE FROM users WHERE id = 5');
     *
     * Specifying the type changes the returned result. When using
     * `Database::SELECT`, a [Database\Result] will be returned.
     * `Database::INSERT` queries will return the insert id and number of rows.
     * For all other queries, the number of affected rows is returned.
     *
     * @param integer $type type: Database::SELECT, Database::UPDATE, etc
     * @param string $sql SQL statement
     * @return Database\Query
     */
    public static function query($type, $sql)
    {
        return new Query($type, $sql);
    }

}
