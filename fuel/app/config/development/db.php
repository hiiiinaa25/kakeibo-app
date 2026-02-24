<?php
return array(
    'default' => array(
        'type' => 'pdo',
        'connection' => array(
            'dsn' => 'mysql:host=db;dbname=kakeibo_app;charset=utf8mb4',
            'username' => 'root',
            'password' => 'root',
            'persistent' => false,
        ),
        'identifier' => '`',
        'table_prefix' => '',
        'charset' => 'utf8mb4',
        'enable_cache' => false,
        'profiling' => true,
    ),
);