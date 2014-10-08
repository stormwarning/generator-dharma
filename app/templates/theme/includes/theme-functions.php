<?php
/**
 * Run filters and actions on theme activation
 */
add_action( 'after_setup_theme', 'theme_startup' );

if ( ! function_exists( 'theme_startup' ) ) {

  function theme_startup() {

    // enqueue theme-specific scripts and styles
    add_action( 'wp_enqueue_scripts', 'theme_scripts_and_styles', 999 );

  }

}




/**
 * Load scripts and styles
 */
if ( ! function_exists( 'theme_scripts_and_styles' ) ) {

  function theme_scripts_and_styles() {

    if ( ! is_admin() ) {

      // register stylesheets
      wp_register_style( 'theme-css', get_template_directory_uri() . '/assets/styles/style.css' );

      // IE-only stylesheet
      wp_register_style( 'om-ie-only', get_template_directory_uri() . '/assets/style/ie.css', array(), '' );

      // load scripts in the footer
      // wp_register_script( 'googlemaps-js', 'https://api.tiles.mapbox.com/mapbox.js/v1.6.3/mapbox.js', array( 'jquery' ), '', true );
      wp_register_script( 'theme-plugins', get_template_directory_uri() . '/assets/scripts/plugins.js', array( 'jquery' ), '', true );
      wp_register_script( 'theme-js', get_template_directory_uri() . '/assets/scripts/main.js', array( 'jquery' ), '', true );


      // enqueue styles and scripts
      wp_enqueue_style( 'theme-css' );
      // wp_enqueue_script( 'mapbox-js' );
      wp_enqueue_script( 'theme-plugins' );
      wp_enqueue_script( 'theme-js' );

    }
  }
}
