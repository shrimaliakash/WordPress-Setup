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
 * @link https://wordpress.org/support/article/editing-wp-config-php/
 *
 * @package WordPress
 */

// ** MySQL settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define( 'DB_NAME', 'wordpress' );

/** MySQL database username */
define( 'DB_USER', 'root' );

/** MySQL database password */
define( 'DB_PASSWORD', '' );

/** MySQL hostname */
define( 'DB_HOST', 'localhost' );

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
define( 'AUTH_KEY',         '9x7V=vO!CjMkHWn hK+l4#gSWZDlKRHU;9sEvlF[xzp8RSb5#wb0hMM14wh2s.[r' );
define( 'SECURE_AUTH_KEY',  ',/3*xq.0>~5=<uRvGqML1Ciuj0gI#z~>9gkWR`p/q6PgqRfY0JrbmDEipykIYe?K' );
define( 'LOGGED_IN_KEY',    '#v<nYP#)<Jk}6QLQ_<fJ!)$39c=G_rP[L-RC(FRhg$(Ua;vlhCg8Aj?Nl~!_gw>2' );
define( 'NONCE_KEY',        'BvCaqBx#!Jo6Lu_2aLh9X:qt@DVdWnM.A%210*PZ}v;qqL1D!Zt%$uY^cF!Cq/s}' );
define( 'AUTH_SALT',        'ZQ/)OR-z]Ik.8e5t +:U>] Cl~gh^lFjI//?bf2{/ajAgbrIb:({<O17:Y4ocJqn' );
define( 'SECURE_AUTH_SALT', 'cSOMvjJC`V8^5z=o?//~?}|F6Y d)U1~jx|d,6^ayf4W+NNz2&ZM?_qG^;l@/1I,' );
define( 'LOGGED_IN_SALT',   'Ha1Ngw$5]+!IO8 9^[q$[?g?9F.()`dpXyXf1H=qzRzqN[9Kl>xQ:-D^g/pd-$eI' );
define( 'NONCE_SALT',       'Ug |SJvKAsfrTD!*L{KqvYviapGOi9Min}kHtdet.eKKgj .u%4HV*y(PQ2/bzv7' );

/**#@-*/

/**
 * WordPress Database Table prefix.
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

/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
