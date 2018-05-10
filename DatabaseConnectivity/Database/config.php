<?php

return array
(
    'default' => array
    (
        'type'       => 'MySQL',//MySQLi in fact
        'connection' => array(
            'hostname'   => 'localhost',
            'database'   => 'db_name',
            'username'   => 'user_name',
            'password'   => 'password',
            'persistent' => false,
        ),
        'table_prefix' => '',
        'charset'      => 'utf8'
    )
);
