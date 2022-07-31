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
define( 'DB_NAME', 'imenehscdb' );

/** Database username */
define( 'DB_USER', 'imeneadmin' );

/** Database password */
define( 'DB_PASSWORD', 'imenepassword' );

/** Database hostname */
define( 'DB_HOST', 'db4free.net' );

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
define( 'AUTH_KEY',         'P8lgj8!b|q+0 coST_(2Ux+A%Iov)o!{XH3>d|Z~BJ7/#m(3Q7=sTQ_$}~gt Wb:' );
define( 'SECURE_AUTH_KEY',  's(#/}mtLfGE)v1:/KFq22HZw49V|GQ:Nct`]!b-x;+H9S]A>ZlH;i<*Wz}b!-cDe' );
define( 'LOGGED_IN_KEY',    '[lM*>U]2v7Dmv_^+JNq#@EXJVD=fw!Lq%wOq6b1uV@_a9tXx&NeWNWX=iBS@F%xq' );
define( 'NONCE_KEY',        ')NJZ;2$Bhys|E>P]NeLc?W&gaGCs$68Phz*kjzwC#G?6?e$c26.Y)sR*tejaYIoj' );
define( 'AUTH_SALT',        'K3E@[AL@?N,wk;O~MngXS@lEv8A,3HCH5T(gDf@4~sacA,Ub,?E8|E`vH#E,V]0&' );
define( 'SECURE_AUTH_SALT', '0w2:lkM*h2b;f`[V7mTFk4M*U9^dj!F[n`h4k~PW@0VO{JE&=MPsu 0`6,0_Ji7u' );
define( 'LOGGED_IN_SALT',   'Mfqr}t$@=r&/dc{$(f$P7E?DWO(y%_(X92F7F2l=Q69(*J#IW3lBK^]w*}NJ7Gbc' );
define( 'NONCE_SALT',       '@,r!|h0$<8fclwd-VS[`r,&cqGy{U|Kf;.cd H@g{OpM_6uliso$<4!/GZ* S{`:' );

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
