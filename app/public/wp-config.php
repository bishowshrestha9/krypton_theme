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
define( 'AUTH_KEY',          '*0,9[M[h]P1D|GbU3KBH}aX1R-FjLvb0tEs2h`0@WjL/BWJ;DtCE0E{,I8zqG^7.' );
define( 'SECURE_AUTH_KEY',   'AHmw)|diVh}#M*()HHwR&N9}nKY1=#CT,;^9APc9!1z!|= j83}dPy+)1{Pyk{#5' );
define( 'LOGGED_IN_KEY',     'BNzB]I xZ25>glfBZ@70HNpCkkZH^Lb[s$Gcy56YsE8;.@/t)/4^m@;*|v<chgpH' );
define( 'NONCE_KEY',         '}LW1@7dO>8K=  dRz~tVoaf):XG!h?mEsmI-/M`ZTM6.H,/#3!Cgbl%^=d-b zj7' );
define( 'AUTH_SALT',         't?`}*({oSCHw&W|E3!Dj]%uGjXE!7rsRv[ai4]Es{%Wf9pjC}x[y~LGdw4209^s3' );
define( 'SECURE_AUTH_SALT',  'h$:?(E|?u[NisO:RHoP/m)pJOQ)icX(7J(gcmEm83yxys)#%8-~qV1v+gA`#K fK' );
define( 'LOGGED_IN_SALT',    '0)D`ARQ;XPm(ChWw*L9j78M 7d2mKN0P-C]Vz70=@(WuGES&m*6aS[[#l%`6+KMG' );
define( 'NONCE_SALT',        'OX|{:Erb<Yk=8{f2GXDPMmwhJ(aoJ)N|To0ycb/p3d%m!(Pbeb56ld]S@}qnH`(D' );
define( 'WP_CACHE_KEY_SALT', 'RFU?i.ShSAizk#nJ3RG3QN11sH!I1k^e9/ ?;L,XtG~W8.uP&8zqMn_;*!=-N.}D' );


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
	define( 'WP_DEBUG', false );
}

define( 'WP_ENVIRONMENT_TYPE', 'local' );
/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
