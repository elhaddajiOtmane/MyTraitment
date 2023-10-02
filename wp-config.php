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
define( 'DB_NAME', 'local' );

/** Database username */
define( 'DB_USER', 'root' );

/** Database password */
define( 'DB_PASSWORD', 'root' );

/** Database hostname */
define( 'DB_HOST', 'localhost' );

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


/**#@-*/

/**
 * WordPress database table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix = 'wp_';


/* Add any custom values between this line and the "stop editing" line. */



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
	define( 'WP_DEBUG', true );
    define('WP_DEBUG_LOG', true);
}


define('AUTH_KEY',         'Nin5+g10wCuPaobCgqVSiPT8Jt5h3Ne2zfHcPyJpdBn1tdRn6yqHNGiTy73wC7Y4GsNmNbAR4GlPzXvcQFO/tw==');
define('SECURE_AUTH_KEY',  'wc6aH4cfLF4PYqrlGv0cwfVu1/Zln5UEyxEOSuFwJkW6HnGHVNEwl2Ntl0ro4S2tSBDVVZodCP+ivODkD37lPA==');
define('LOGGED_IN_KEY',    'Fau73O00FTw6UGhWUQOgBM2cXUoTlNd9OCGPnaAisJZMcnBJw1+R5sSD+orD3ivrAU3/Ps9R3Y2IH2tM1XGcPg==');
define('NONCE_KEY',        'UmpChE1rx3iJVWXharRgM5vDIAHrcGZeTUU5vFYdvCFFgQYq8dcpfOkYlkBgkVlsBtdCzoBNhsXzxwhYyKn7Ng==');
define('AUTH_SALT',        'i5m5Cqbgk+67croc2u4B8hFbImdBolWvhfWejeP8ZXqgscQleEdVa64sfgs0ALStLvvihbOpnfJNAH/9WR2cyw==');
define('SECURE_AUTH_SALT', 'kbCtzjOeCsjG+qYzD7zVsQ0rlCFXQjwsYzzxJEbzr8dgYa8Ch5NU6vPo8KrYrhqF/fZ+S5Mqs2h4dzlhnQh9zQ==');
define('LOGGED_IN_SALT',   'X/29NSZt4sk/rz1XzmGT0hfz20G+TiFbge2JimqE7chmfviWBXlEHSBtyF6H0qs3ZHAU4KOqFwNUUO2Dx5vX/g==');
define('NONCE_SALT',       'vkK19R9GXsybgQfoMLRNiolUSWswaFSYuqnDqw6RlqFR/9BX5gRtioludHDmA4QWr9PdMYnprVXsyInkCe9xYg==');
define( 'WP_ENVIRONMENT_TYPE', 'local' );
/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
