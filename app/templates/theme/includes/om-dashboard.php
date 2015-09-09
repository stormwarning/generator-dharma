<?php
/**
 * WordPress Dashboard modifications
 * Customising login screens, and other wp-admin hooks
 *
 * @package dharma
 */


/**
 * Customise the login screen.
 */
add_action( 'login_head', 'om_login_styles' );
add_filter( 'login_headerurl', 'om_login_logourl' );
add_filter( 'login_headertitle', 'om_login_logotitle' );

if ( ! function_exists( 'om_login_styles' ) ) {

  function om_login_logo() {

    wp_register_style( 'login-css', get_template_directory_uri() . '/assets/styles/login-style.css' );
    wp_enqueue_style( 'login-css' );

  }

}


if ( ! function_exists( 'om_login_logourl' ) ) {

  function om_login_logourl() {

    return 'http://overhaulmedia.com/';

  }

}


if ( ! function_exists( 'om_login_logotitle' ) ) {

  function om_login_logotitle() {

    return 'From your friends at Overhaul Media';

  }

}




/**
 * Add Overhaul contact notice to Core Update notice
 * First, remove the default notice.
 * Next, replicate the original function, with our updated notice text.
 *
 * @since 3.9.1
 * @todo Find a way to append to the original notice instead of duplicating
 */
add_action( 'admin_init', 'remove_default_update_nag' );

function remove_default_update_nag() {

  remove_action( 'admin_notices', 'update_nag', 3 );
  remove_action( 'network_admin_notices', 'update_nag', 3 );

}


add_action( 'admin_notices', 'om_update_nag', 3 );
add_action( 'network_admin_notices', 'om_update_nag', 3 );

function om_update_nag() {

  if ( is_multisite() && ! current_user_can( 'update_core' ) ) {

    return false;

  }

  global $pagenow;

  if ( 'update-core.php' == $pagenow ) {

    return;

  }

  $cur = get_preferred_from_update_core();

  if ( ! isset( $cur->response ) || 'upgrade' != $cur->response ) {

    return false;

  }

  if ( current_user_can( 'update_core' ) ) {

    $msg = sprintf( __( '<a href="http://codex.wordpress.org/Version_%1$s">WordPress %1$s</a> is available! <a href="%2$s">Please update now</a>.<br><a href="mailto:info@overhaulmedia.com?subject=WordPress updating">Reach out to your friends</a> at <a href="http://overhaulmedia.com/" target="_blank">Overhaul Media</a> for assistance!' ), $cur->current, network_admin_url( 'update-core.php' ) );

  } else {

    $msg = sprintf( __( '<a href="http://codex.wordpress.org/Version_%1$s">WordPress %1$s</a> is available! Please notify the site administrator.<br><a href="mailto:info@overhaulmedia.com?subject=WordPress updating">Reach out to your friends</a> at <a href="http://overhaulmedia.com/" target="_blank">Overhaul Media</a> for assistance!' ), $cur->current );

  }

  echo '<div class="update-nag">' . $msg . '</div>';

}
