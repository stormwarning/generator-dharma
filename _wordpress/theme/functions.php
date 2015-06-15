<?php
/**
 *
 * THEME FUNCTIONS
 *
 */

/**
 * Basic theme setup
 */
require_once( get_template_directory() . '/includes/om-configure.php' );
require_once( get_template_directory() . '/includes/om-dashboard.php' );
require_once( get_template_directory() . '/includes/om-functions.php' );


/**
 * Custom Theme functions
 */
// Require plugins
require_once( get_template_directory() . '/includes/theme-require-plugins.php' );


// Custom functions & theme options
require_once( get_template_directory() . '/includes/theme-functions.php' );
// require_once( get_template_directory() . '/includes/theme-options.php' );


// Define custom post types
// require_once( get_template_directory() . '/includes/theme-post-types.php' );
// require_once( get_template_directory() . '/includes/theme-taxonomies.php' );
