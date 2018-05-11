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

/**
 * For developers: WordPress debugging mode.
 */
define('WP_DEBUG', false);

/**#@+
 * Authentication Unique Keys and Salts.
 */
define( 'AUTH_KEY',         'XcsApVptimppMXgKIHnGtpdREVAlRYtCRxQcbbaNSONGrGrWXaplSheGuFrxVvFK' );
define( 'SECURE_AUTH_KEY',  'GagtdIcOmXFYbjkxBwiRivtveeGdmIgdlbhbxKxlHJbOhsCYEZaSvYjepSMKyrao' );
define( 'LOGGED_IN_KEY',    'CJTlBgZSUpcaJpOkbPnTFzmqfjSiSTRWersXsgYBkWZRJBuBbvKQhxPEPeKVvkXK' );
define( 'NONCE_KEY',        'oWeCRYqgXaSXefPdWEtaOntBsOlFtkpyhrosQUUpXzbOONMDzYxwCQPPgXovrQNR' );
define( 'AUTH_SALT',        'rLHQdVxBqBOJbzDpCVHTYoiydBRBJBIbpHlcslViPPZDEkZdowSRFokIywKqEkzc' );
define( 'SECURE_AUTH_SALT', 'YnycjypjYkDZEJdXgnbLTbnMCrXncxzxaQXXLeoYpruLBbcoGuzUmIUgvXlZsUPL' );
define( 'LOGGED_IN_SALT',   'jeCLUPHLdRkWDSsaAsksRjqymDDVXidHAUYKyrAYzguXHzEGbRFJEvUNFwFwYqxV' );
define( 'NONCE_SALT',       'ZcmmZkRDrIBqTLfmNIdLCVCuUVEhjDOQYTuCHEpJYEUcmIeuAtmArOXjDkFlqbTy' );

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
