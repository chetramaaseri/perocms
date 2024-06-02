<?php
/**
 * The base configuration for Pero
 *
 * The config.php creation script uses this file during the installation.
 *
 * This file contains the following configurations:
 *
 * * Database settings
 * * Database table prefix
 * * Routing settings
 *
 * @package perocms
 */

// ** Database settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define( 'DB_NAME', 'pero' );

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


/**
 * Pero database table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix = 'pero_';


/**
 * Pero routing settings.
 *
 * This Will be served by default
 */
define( 'DefaultController', 'Home' );