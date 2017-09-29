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
define('DB_NAME', 'wordpress');

/** MySQL database username */
define('DB_USER', 'root');

/** MySQL database password */
define('DB_PASSWORD', '');

/** MySQL hostname */
define('DB_HOST', 'localhost');

/** Database Charset to use in creating database tables. */
define('DB_CHARSET', 'utf8mb4');

/** The Database Collate type. Don't change this if in doubt. */
define('DB_COLLATE', '');

/**#@+
 * Authentication Unique Keys and Salts.
 *
 * Change these to different unique phrases!
 * You can generate these using the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}
 * You can change these at any point in time to invalidate all existing cookies. This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define('AUTH_KEY',         'PggyAKimswZkQ31Fb7$et d<HaZrcw,q(([VghOO X@hYpej.^W|^`- X,lnh?vU');
define('SECURE_AUTH_KEY',  'b0LnyN|_;moZd[[JwRqNCl[f6K3FG8x=EJq9Qd)-srl2}y)PRwg-)tu]c?3!Dul4');
define('LOGGED_IN_KEY',    '2J,JtFOr(DpkloQ^4M)3<&Gf2]R/SazPtpxijJ[48)yToYI/]TN:1Qt4_ho#<a^G');
define('NONCE_KEY',        '2yxbZ+(P2mZ%KzL[KW(:0z?Go!(nlEpo0,0q:PZ!SA6[#ft ;XK+5~dEw _a6y!H');
define('AUTH_SALT',        '((Cl3qPD1-5OJ$It]lgCFk2N,hf/B%oGYK=Q(s6XvditmkNaa;rz_f]lF V%b0%j');
define('SECURE_AUTH_SALT', 'Elyna^tiq2A,`)-FhO=YAIy>oZ5Gl!!99^Hi|wN^}|xzZ(a]vXIG3@mu.TK]~&]C');
define('LOGGED_IN_SALT',   '1u8Z5f;qyz wBOTOy<gAogK%DAFi9<]Ge2&#5yB*vL!;)7]ALL`atQ I)T(;gVzy');
define('NONCE_SALT',       'e9{!{HYh2l,&G[u<bPT/3XKb-s.nB!$x#,:3T7pdMTxx{kS+<{p!rMz9zBMU9&#8');

/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix  = 'wp_';

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
define('WP_DEBUG', false);

/* That's all, stop editing! Happy blogging. */

/** Absolute path to the WordPress directory. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Sets up WordPress vars and included files. */
require_once(ABSPATH . 'wp-settings.php');

