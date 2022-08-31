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
 * * ABSPATH
 *
 * @link https://wordpress.org/support/article/editing-wp-config-php/
 *
 * @package WordPress
 */

// ** Database settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define( 'DB_NAME', 'kiosalonv3' );

/** Database username */
define( 'DB_USER', 'root' );

/** Database password */
define( 'DB_PASSWORD', '' );

/** Database hostname */
define( 'DB_HOST', 'localhost' );

/** Database charset to use in creating database tables. */
define( 'DB_CHARSET', 'utf8mb4' );

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
define( 'AUTH_KEY',         'Pp#N}TnG*$$hbX^hTN,ccp}SPW4xJX+={~><dU8a82FXHuRf0P<RO/.N ~EVU,,e' );
define( 'SECURE_AUTH_KEY',  'TE4k}>l`{b;sLfQ46[JUq;FbQ2VEzi;u}|SJ>q$-YmNHHOc,UZ$?27@~ITIkbIrG' );
define( 'LOGGED_IN_KEY',    'Kjy0-B,&1_JhlLO]XjPPVVX eV| 8#{CPORz3QSL/]~Tw;*.T=5K6[9L<f*N#PQu' );
define( 'NONCE_KEY',        'Y(?CiGPZG+;_9$_yyd{]E:8)bLOa!5?ASeE![D=C3pZ4yl*1lJkm7|=:,N$uJO7z' );
define( 'AUTH_SALT',        '0U!cTE;z#RqUs1XK6vx=IM9f3ikPAZ?XyW8EahQbiK&-&+I%9B,7ySVO58bXNIoX' );
define( 'SECURE_AUTH_SALT', '57C84d<PDK,}LTiul_e;,&1:= Vk4GZD(jOI5OCer&N^NC -g[}dl.DYi/~y4gx3' );
define( 'LOGGED_IN_SALT',   'yX2^9qlm|[6.J ^w:r|Q`5tUz@s$:8-Mk{il8+}2=tTK^Om[+8ogqvw2),*hb|p{' );
define( 'NONCE_SALT',       't7HA+j/R!HF2yNY:6&DA|PP10L_rfZ_l.WJ~H$cb3^<%6t:4mtn1So:U8?(1BJ:m' );

/**#@-*/

/**
 * WordPress database table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix = 'wp_';

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
define( 'WP_DEBUG', false );

/* Add any custom values between this line and the "stop editing" line. */



/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
