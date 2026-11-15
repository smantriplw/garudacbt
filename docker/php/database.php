<?php
defined('BASEPATH') OR exit('No direct script access allowed');

// Container copy of application/config/database.php. The real file in the
// repository keeps the developer's local values; docker-compose mounts this
// one over it so the app talks to the `db` service.
$active_group = 'default';
$query_builder = TRUE;

$db['default'] = array(
    'dsn'       => '',
    'hostname' => getenv('DB_HOST') ?: 'db',
    'username' => getenv('DB_USER') ?: 'garuda',
    'password' => getenv('DB_PASSWORD') ?: 'garuda',
    'database' => getenv('DB_NAME') ?: 'garuda_cbt',
    'dbdriver' => 'mysqli',
    'dbprefix' => '',
    'pconnect' => FALSE,
    'db_debug' => (ENVIRONMENT !== 'production'),
    'cache_on' => FALSE,
    'cachedir' => '',
    'char_set' => 'utf8',
    'dbcollat' => 'utf8_general_ci',
    'swap_pre' => '',
    'encrypt' => FALSE,
    'compress' => FALSE,
    'stricton' => FALSE,
    'failover' => array(),
    'save_queries' => FALSE
);
