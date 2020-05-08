<?php
if (file_exists(ABSPATH . '/env.php')) {
    include_once(ABSPATH . '/env.php');
    $envPresent = true;
} else {
    $envPresent = false;
}

/** Variable settings **/

/**
 * save_queries (bool)
 * This is useful for debugging. Queries are saved in $wpdb->queries. It is not
 * a constant because you might want to use it momentarily.
 * Default: false
 */
$wpdb->save_queries = false;

/**
 * persistent (bool)
 * This determines whether to use mysql_connect or mysql_pconnect. The effects
 * of this setting may vary and should be carefully tested.
 * Default: false
 */
$wpdb->persistent = false;

/**
 * max_connections (int)
 * This is the number of mysql connections to keep open. Increase if you expect
 * to reuse a lot of connections to different servers. This is ignored if you
 * enable persistent connections.
 * Default: 10
 */
$wpdb->max_connections = 10;

/**
 * check_tcp_responsiveness
 * Enables checking TCP responsiveness by fsockopen prior to mysql_connect or
 * mysql_pconnect. This was added because PHP's mysql functions do not provide
 * a variable timeout setting. Disabling it may improve average performance by
 * a very tiny margin but lose protection against connections failing slowly.
 * Default: true
 */
$wpdb->check_tcp_responsiveness = true;


// if ($envPresent) {
//     /**
//      * Master DB Instance
//      */
//     $wpdb->add_database(array(
//         'host' => getenv("MASTER_DB_HOST"),     // If port is other than 3306, use host:port.
//         'user' => getenv("MASTER_DB_USER"),
//         'password' => getenv("MASTER_DB_PASS"),
//         'name' => getenv("MASTER_DB_NAME"),
//         'write' => 1,
//         'read' => 1,
//     ));

//     /**
//      * Slave DB Instance
//      */
//     $wpdb->add_database(array(
//         'host' => getenv("SLAVE_DB_HOST"),     // If port is other than 3306, use host:port.
//         'user' => getenv("SLAVE_DB_USER"),
//         'password' => getenv("SLAVE_DB_PASS"),
//         'name' => getenv("SLAVE_DB_NAME"),
//         'write' => 0,
//         'read' => 1,
//     ));
// } else {
    // If our ENV file is not present then we want to default back to the values in the wp-config.php
    $wpdb->add_database(array(
        'host' => DB_HOST,
        'user' => DB_USER,
        'password' => DB_PASSWORD,
        'name' => DB_NAME,
        'write' => 1,
        'read' => 1,
    ));
//}

// The ending PHP tag is omitted. This is actually safer than including it.
