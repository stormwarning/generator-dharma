<?php
// DATABASE INFO AND LOCAL DEV PARAMETERS =============================
if ( file_exists( dirname( __FILE__ ) . '/local-config.php' ) ) {

  define( 'WP_LOCAL_DEV', true );
  include( dirname( __FILE__ ) . '/local-config.php' );

} else {

  define( 'WP_LOCAL_DEV', false );
  define( 'DB_NAME', '<%= dbName %>' );
  define( 'DB_USER', '<%= dbUser %>' );
  define( 'DB_PASSWORD', '<%= dbPass %>' );
  define( 'DB_HOST', <%= dbHost %> ); // Probably 'localhost'

}


// CUSTOM CONTENT DIRECTORY ===========================================
define( 'WP_CONTENT_DIR', dirname( __FILE__ ) . '/content' );
define( 'WP_CONTENT_URL', 'http://' . $_SERVER['HTTP_HOST'] . '/content' );


// CUSTOM CONFIGURATIONS ==============================================
// Prevent file editing via the dashboard editor.
define( 'DISALLOW_FILE_EDIT', true );

// Adjust post revisioning and autosaving.
define( 'AUTOSAVE_INTERVAL', 300 );
define( 'WP_POST_REVISIONS', 3 );


// PROBABLY NEVER CHANGE THESE ========================================
define( 'DB_CHARSET', 'utf8' );
define( 'DB_COLLATE', '' );

// SALTS, FOR SECURITY ================================================
// Grab these from: https://api.wordpress.org/secret-key/1.1/salt
define('AUTH_KEY',         'put your unique phrase here');
define('SECURE_AUTH_KEY',  'put your unique phrase here');
define('LOGGED_IN_KEY',    'put your unique phrase here');
define('NONCE_KEY',        'put your unique phrase here');
define('AUTH_SALT',        'put your unique phrase here');
define('SECURE_AUTH_SALT', 'put your unique phrase here');
define('LOGGED_IN_SALT',   'put your unique phrase here');
define('NONCE_SALT',       'put your unique phrase here');


// DATABASE TABLE PREFIX ==============================================
// Change this if you have multiple installs in the same database
$table_prefix  = '<%= dbPrefix %>';


// LANGUAGE ===========================================================
// Leave blank for American English
define( 'WPLANG', '' );


// HIDE ERRORS ========================================================
ini_set( 'display_errors', 0 );
define( 'WP_DEBUG_DISPLAY', false );


// DEBUG MODE =========================================================
// Debugging? Enable these. Can also enable them in local-config.php
// define( 'SAVEQUERIES', true );
// define( 'WP_DEBUG', true );


// LOAD A MEMCACHED CONFIG IF WE HAVE ONE =============================
if ( file_exists( dirname( __FILE__ ) . '/memcached.php' ) )
  $memcached_servers = include( dirname( __FILE__ ) . '/memcached.php' );


// BOOTSTRAP WORDPRESS ================================================
if ( !defined( 'ABSPATH' ) )
  define( 'ABSPATH', dirname( __FILE__ ) . '/<%= wpDirectory %>/' );

require_once( ABSPATH . 'wp-settings.php' );
