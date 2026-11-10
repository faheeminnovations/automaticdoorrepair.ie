<?php
/**
 * The base configuration for WordPress
 *
 * The wp-config.php creation script uses this file during the installation.
 * You don't have to use the web site, you can copy this file to "wp-config.php"
 * and fill in the values.
 *
 * This file contains the following configurations:
 *
 * * Database settings
 * * Secret keys
 * * Database table prefix
 * * Localized language
 * * ABSPATH
 *
 * @link https://wordpress.org/support/article/editing-wp-config-php/
 *
 * @package WordPress
 */

// ** Database settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define( 'DB_NAME', 'wp_ncgtu' );

/** Database username */
define( 'DB_USER', 'wp_vdaht' );

/** Database password */
define( 'DB_PASSWORD', '09%8avzLw&WTFow6' );

/** Database hostname */
define( 'DB_HOST', 'localhost:3306' );

/** Database charset to use in creating database tables. */
define( 'DB_CHARSET', 'utf8' );

/** The database collate type. Don't change this if in doubt. */
define( 'DB_COLLATE', '' );

/**#@+
 * Authentication unique keys and salts.
 *
 * Change these to different unique phrases! You can generate these using
 * the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}.
 *
 * You can change these at any point in time to invalidate all existing cookies.
 * This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define('AUTH_KEY', 'u2Yek/*G&8Ce/:2:0j739c~hk&_*95u6A#KC+(zkh!*4*piTq-94Bm#fY9g;91OU');
define('SECURE_AUTH_KEY', '~#3LVP62HT9!C8|Q9]W7))6*[eTfI!/0-O5[dh&8Q43idJiwqT1(y/(&2/vG;e;W');
define('LOGGED_IN_KEY', 'U00Kc8hM7[f|b6_wQ7:9fy14IZ7AUI[yhB9&78_G&Efsn%9P6K+o1@mOZ(|F%3]J');
define('NONCE_KEY', 'X7Y3-912K)MOcrr6t+%~@63Cmy_H0X377-c)5)@(no8Mtffn8pi(ECB03t8WRU:#');
define('AUTH_SALT', 'z)b8;y7|~W23(ptBeR34oOmqo!2]&11tYtq|xh89PEN/C(7&3XDc:3fWKk1b]Il8');
define('SECURE_AUTH_SALT', 'T[-1-BX!d1TFujPaI34M6&m8wMM+ZwfKe7Y)hEEI21:*:(Gyl)Z9jsG251a3VE0D');
define('LOGGED_IN_SALT', ')3h()[&ds1250*%Dp8@xqwK/9VEisD3w3X7IV+[B/PIF7!d/N@GfE25FZf0|1d3P');
define('NONCE_SALT', '|biZichoau:7D5F5b|4571cnsz2m9[fM5cQI:l(P4pdV4UM8cW*246r6s|Q73)H:');


/**#@-*/

/**
 * WordPress database table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix = 'I6xCT2c5E_';


/* Add any custom values between this line and the "stop editing" line. */

define('WP_ALLOW_MULTISITE', true);
/**
 * For developers: WordPress debugging mode.
 *
 * Change this to true to enable the display of notices during development.
 * It is strongly recommended that plugin and theme developers use WP_DEBUG
 * in their development environments.
 *
 * For information on other constants that can be used for debugging,
 * visit the documentation.
 *
 * @link https://wordpress.org/support/article/debugging-in-wordpress/
 */
if ( ! defined( 'WP_DEBUG' ) ) {
	define( 'WP_DEBUG', false );
}

/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
