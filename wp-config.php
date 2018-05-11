<?php

/**
 #ddev-generated: Automatically generated WordPress wp-config.php file.
 ddev manages this file and may delete or overwrite the file unless this comment is removed.
 */

// ** MySQL settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define('DB_NAME', 'db');

/** MySQL database username */
define('DB_USER', 'db');

/** MySQL database password */
define('DB_PASSWORD', 'db');

/** MySQL hostname */
define('DB_HOST', 'db');

/** Database Charset to use in creating database tables. */
define('DB_CHARSET', 'utf8mb4');

/** The Database Collate type. Don't change this if in doubt. */
define('DB_COLLATE', '');

/**
 * WordPress Database Table prefix.
 */
$table_prefix  = 'wp_';

define( 'WP_MEMORY_LIMIT', '256M' );

/**
 * For developers: WordPress debugging mode.
 */
define('WP_DEBUG', false);

/**#@+
 * Authentication Unique Keys and Salts.
 */
define( 'AUTH_KEY',         'hypyxmbeMEmGAXyPZgJWznuLhCLWdykAEqBrvTqBzVQDmfuhDzvJxPyxFgVQRmOT' );
define( 'SECURE_AUTH_KEY',  'PebqMriURCTKQNeuggITDUlPOwDTqpsWYcdvXQsrhcAXYnZZtLMYfdfaDlBgXKSf' );
define( 'LOGGED_IN_KEY',    'XphtZaZaxJnjjOZDzUvBTXpjcpHziPzwGWKxDOaBEObKCxWiTcALUOZEVcvRmcXH' );
define( 'NONCE_KEY',        'KyPwhRgtjfMFJwmNQFZvTaDGkkFRTmgtyYTWDkaRxMTgSSxEagLxfPDgwAqQEgSS' );
define( 'AUTH_SALT',        'PuHbtKVbJkTnLXdsOdFdCPoWydlZsncwsmACbpWdBMZLPIChdsNPfleMADINIGLj' );
define( 'SECURE_AUTH_SALT', 'oRjLhRiGNnuhcFiPcnhADqwEhFXPFnbaTqRCSpbINdURVnCJAKKTDZEmpSRleTcy' );
define( 'LOGGED_IN_SALT',   'gszIhYJKkeQKzHruXaRGSPLFvKWGvNgVfXUtsXkhDrqRkuRFjVVYLvLWmUdVeqYX' );
define( 'NONCE_SALT',       'dplBznIoEjzgnfkcscTaQkAhXGPYtHtGhuZxVuHXfZTyouYhqFJrXUyGYeoeEzuN' );

/* That's all, stop editing! Happy blogging. */

/** Absolute path to the WordPress directory. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/**
Sets up WordPress vars and included files.

wp-settings.php is typically included in wp-config.php. This check ensures it is not
included again if this file is written to wp-config-local.php.
*/
if (basename(__FILE__) == "wp-config.php") {
	require_once(ABSPATH . '/wp-settings.php');
}
