<?php
/**
 * The base configuration for WordPress
 *
 * The wp-config.php creation script uses this file during the
 * installation. You don't have to use the web site, you can
 * copy this file to "wp-config.php" and fill in the values.
 *
 * This file contains the following configurations:
 *
 * * MySQL settings
 * * Secret keys
 * * Database table prefix
 * * ABSPATH
 *
 * @link https://codex.wordpress.org/Editing_wp-config.php
 *
 * @package WordPress
 */

// ** MySQL settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define( 'DB_NAME', 'mydb' );

/** MySQL database username */
define( 'DB_USER', 'myuser' );

/** MySQL database password */
define( 'DB_PASSWORD', 'secret' );

/** MySQL hostname */
define( 'DB_HOST', 'database' );

/** Database Charset to use in creating database tables. */
define( 'DB_CHARSET', 'utf8mb4' );

/** The Database Collate type. Don't change this if in doubt. */
define( 'DB_COLLATE', '' );

/**#@+
 * Authentication Unique Keys and Salts.
 *
 * Change these to different unique phrases!
 * You can generate these using the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}
 * You can change these at any point in time to invalidate all existing cookies. This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define( 'AUTH_KEY',         '.lUDxm>qiB6t<s0MYHoI[$?yh >Rc$nS9+ JqG*Is*|Iy:US>9+,H<d%TQ-`Cka1' );
define( 'SECURE_AUTH_KEY',  '|qXZf@Q&2Ozq91pKKE8kY>[mAPt`rfppI+Kv;Lo5<]kI>>jpIP)WPpuV3kVzp]Od' );
define( 'LOGGED_IN_KEY',    '3;*0;Z;lh2b%9|[fH+,@}f^Qkx!J2t+qH]U531,GUO&2T6.t6IrS)|RA:RT-;N7_' );
define( 'NONCE_KEY',        '}:5}0otk:U rO+!0NpJ:|z%_{m5jX> jGu4vpPe3D:7LTl{FtnYj1ks`jTZmxb [' );
define( 'AUTH_SALT',        'Lj5H=z1*QUp1~;]|XDIO!^,y`cXS%JE)m]M>ye#b,E1w8EY7(D.5#QQo&!|m1ACc' );
define( 'SECURE_AUTH_SALT', 'x%%4kV*>&Z!amE0e|OYR.lQ:h~!*+@y9mfmKXpO-7w;XRA(u8gyndyD;4n)#rsM>' );
define( 'LOGGED_IN_SALT',   '%35aQ&;^vVHJYaNAThj+eW=jBkA;/3}LMlqZ<vQ|m`0;y$Bs|7RL)%$B%-m,/w!=' );
define( 'NONCE_SALT',       'LSjScJLj y55z@.1=E6,6{fh8+;:^nO=8dBP<8z8rTW0PhM~9~,`l %@MFCoAIxY' );

/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix = 'fth_';

/**
 * For developers: WordPress debugging mode.
 *
 * Change this to true to enable the display of notices during development.
 * It is strongly recommended that plugin and theme developers use WP_DEBUG
 * in their development environments.
 *
 * For information on other constants that can be used for debugging,
 * visit the Codex.
 *
 * @link https://codex.wordpress.org/Debugging_in_WordPress
 */
define( 'WP_DEBUG', true );
define( 'WP_DEBUG_LOG', true );
define( 'WP_DEBUG_DISPLAY', false );
define( 'WP_CACHE', false );

define( 'WP_HOME', 'http://fatherly.wpengine' );
define( 'WP_SITEURL', 'http://fatherly.wpengine' );
/** Ensure the local host is used */ 
// define('WP_HOME', 'http://' . $_SERVER['HTTP_HOST']);
// define('WP_SITEURL', 'http://' . $_SERVER['HTTP_HOST']);
define('ENV','dev');
/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', dirname( __FILE__ ) . '/' );
}

/** Sets up WordPress vars and included files. */
require_once( ABSPATH . 'wp-settings.php' );
