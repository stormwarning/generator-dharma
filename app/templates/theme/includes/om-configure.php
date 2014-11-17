<?php
/**
 * Run filters and actions on theme activation
 */
add_action( 'after_setup_theme', 'om_startup' );

if ( ! function_exists( 'om_startup' ) ) {

  function om_startup() {

    // launching operation cleanup
    add_action( 'init', 'om_head_cleanup' );

    // remove WP version from RSS
    add_filter( 'the_generator', 'om_rss_version' );

    // remove pesky injected CSS for recent comments widget
    add_filter( 'wp_head', 'om_remove_wp_widget_recent_comments_style', 1 );

    // clean up comment styles in the head
    add_action( 'wp_head', 'om_remove_recent_comments_style', 1 );

    // clean up gallery output in WP
    add_filter( 'gallery_style', 'om_gallery_style' );

    // enqueue base scripts and styles
    add_action( 'wp_enqueue_scripts', 'om_scripts_and_styles', 999 );

    // IE conditional wrapper
    add_filter( 'style_loader_tag', 'om_ie_conditional', 10, 2 );

    // additional post-related cleaning
    add_filter( 'img_caption_shortcode', 'om_cleaner_caption', 10, 3 );
    add_filter( 'get_image_tag_class', 'om_image_tag_class', 0, 4 );
    add_filter( 'get_image_tag', 'om_image_editor', 0, 4 );
    add_filter( 'the_content', 'om_img_unautop', 30 );

    // don't compress JPGs
    add_filter( 'jpeg_quality', function($arg){ return 100; } );
    add_filter( 'wp_editor_set_quality', function($arg) { return 100; } );

    // don't update theme
    add_filter( 'http_request_args', 'om_dont_update_theme', 5, 2 );

    // enable supports
    add_theme_support( 'post-thumbnails' );
    add_theme_support( 'html5', array(
      'comment-list',
      'search-form',
      'comment-form',
      'gallery',
      'caption',
    ) );
    add_theme_support( 'automatic-feed-links' );
    add_post_type_support( 'page', 'excerpt' );

    // prevent file modifications
    define( 'DISALLOW_FILE_EDIT', true );

    // remove all comment functionality
    add_action( 'init', 'om_remove_comment_support', 100 );
    add_action( 'wp_before_admin_bar_render', 'om_remove_comments_admin_bar' );
    add_action( 'admin_menu', 'om_remove_comment_menu' );

    // keep Yoast SEO metabox in last place
    add_action( 'wpseo_metabox_prio', 'om_yoast_seo_metabox_priority' );

  }

}




/**
 * Remove unneccesary items from `<head>`
 */
if ( ! function_exists( 'om_head_cleanup' ) ) {

  function om_head_cleanup() {

    // category feeds
    // remove_action( 'wp_head', 'feed_links_extra', 3 );
    // post and comment feeds
    // remove_action( 'wp_head', 'feed_links', 2 );
    // EditURI link
    remove_action( 'wp_head', 'rsd_link' );
    // windows live writer
    remove_action( 'wp_head', 'wlwmanifest_link' );
    // index link
    remove_action( 'wp_head', 'index_rel_link' );
    // previous link
    remove_action( 'wp_head', 'parent_post_rel_link', 10, 0 );
    // start link
    remove_action( 'wp_head', 'start_post_rel_link', 10, 0 );
    // links for adjacent posts
    remove_action( 'wp_head', 'adjacent_posts_rel_link_wp_head', 10, 0 );
    // WP version
    remove_action( 'wp_head', 'wp_generator' );
    // remove WP version from css
    add_filter( 'style_loader_src', 'om_remove_wp_ver_css_js', 9999 );
    // remove WP version from scripts
    add_filter( 'script_loader_src', 'om_remove_wp_ver_css_js', 9999 );

  }

}


// remove WP version from RSS
if ( ! function_exists( 'om_rss_version' ) ) {

  function om_rss_version() { return ''; }

}


// remove WP version from scripts
if ( ! function_exists( 'om_remove_wp_ver_css_js' ) ) {

  function om_remove_wp_ver_css_js( $src ) {

    if ( strpos( $src, 'ver=' ) ) {

      $src = remove_query_arg( 'ver', $src );

    }

    return $src;

  }

}


// remove injected CSS for recent comments widget
if ( ! function_exists( 'om_remove_wp_widget_recent_comments_style' ) ) {

  function om_remove_wp_widget_recent_comments_style() {

     if ( has_filter( 'wp_head', 'wp_widget_recent_comments_style' ) ) {

        remove_filter( 'wp_head', 'wp_widget_recent_comments_style' );

     }

  }

}


// remove injected CSS from recent comments widget
if ( ! function_exists( 'om_remove_recent_comments_style' ) ) {

  function om_remove_recent_comments_style() {

    global $wp_widget_factory;

    if ( isset( $wp_widget_factory->widgets['WP_Widget_Recent_Comments'] ) ) {

      remove_action( 'wp_head', array( $wp_widget_factory->widgets['WP_Widget_Recent_Comments'], 'recent_comments_style' ) );

    }

  }

}


// remove injected CSS from gallery
if ( ! function_exists( 'om_gallery_style' ) ) {

  function om_gallery_style( $css ) {

    return preg_replace( "!<style type='text/css'>(.*?)</style>!s", '', $css );

  }

}




/**
 * Load scripts and styles
 */
if ( ! function_exists( 'om_scripts_and_styles' ) ) {

  function om_scripts_and_styles() {

    if ( ! is_admin() ) {

      // modernizr (without media query polyfill)
      wp_register_script( 'om-modernizr', get_template_directory_uri() . '/assets/scripts/modernizr.js', array(), '2.6.2', false );



      // comment reply script for threaded comments
      if ( get_option( 'thread_comments' ) ) {

        wp_enqueue_script( 'comment-reply' );

      }

      // load scripts in the footer
      global $is_IE;

      if ( $is_IE ) {

         wp_register_script ( 'html5shiv', "http://html5shiv.googlecode.com/svn/trunk/html5.js" , false, true );

      }

      // enqueue styles and scripts
      wp_enqueue_script( 'om-modernizr' );
      /*
      I recommend using a plugin to call jQuery
      using the google cdn. That way it stays cached
      and your site will load faster.
      */
      wp_enqueue_script( 'jquery' );
      wp_enqueue_script( 'html5shiv' );

    }
  }
}


/**
 * Adding a conditional wrapper around IE stylesheet
 *
 * @link code.garyjones.co.uk/ie-conditional-style-sheets-wordpress/
 */
if ( ! function_exists( 'om_ie_conditional' ) ) {

  function om_ie_conditional( $tag, $handle ) {

    if ( 'om-ie-only' == $handle ) {

      $tag = '<!--[if lt IE 9]>' . "\n" . $tag . '<![endif]-->' . "\n";

    }

    return $tag;

  }

}




/**
 * Clean-up the caption output.
 *
 * @link devpress.com/blog/captions-in-wordpress/
 */
if ( ! function_exists( 'om_cleaner_caption' ) ) {

  function om_cleaner_caption( $output, $attr, $content ) {

    // Return the normal output to feeds
    if ( is_feed() ) {

      return $output;

    }

    // Set up the default arguments
    $defaults = array(
      'id' => '',
      'align' => 'alignnone',
      'width' => '',
      'caption' => ''
    );

    // Merge the defaults with user input
    $attr = shortcode_atts( $defaults, $attr );

    // If the width is less than 1 or there is no caption, return the content wrapped between the [caption] tags.
    if ( 1 > $attr['width'] || empty( $attr['caption'] ) ) {

      return $content;

    }

    // Set up the attributes for the caption <div>
    $attributes = ' class="figure ' . esc_attr( $attr['align'] ) . '"';

    // Open the caption <div>
    $output = '<figure' . $attributes .'>';

    // Allow shortcodes for the content the caption was created for
    $output .= do_shortcode( $content );

    // Append the caption text
    $output .= '<figcaption>' . $attr['caption'] . '</figcaption>';

    // Close the caption </div>
    $output .= '</figure>';

    // Return the formatted, clean caption
    return $output;

  }

}


/**
 * Clean the output of attributes of images in editor
 *
 * @link www.sitepoint.com/wordpress-change-img-tag-html/
 */
if ( ! function_exists( 'om_image_tag_class' ) ) {

  function om_image_tag_class( $class, $id, $align, $size ) {

    $align = 'align' . esc_attr($align);

    return $align;

  }

}


// Remove image width and height in editor, for a better, responsive world.
if ( ! function_exists( 'om_image_editor' ) ) {

  function om_image_editor( $html, $id, $alt, $title ) {

    return preg_replace(
      array(
        '/\s+width="\d+"/i',
        '/\s+height="\d+"/i',
        '/alt=""/i'
      ),
      array(
        '',
        '',
        '',
        'alt="' . $title . '"'
      ),
      $html );

  }

}


/**
 * Wrap images with figure tag
 *
 * @link interconnectit.com/2175/how-to-remove-p-tags-from-images-in-wordpress/
 */
if ( ! function_exists( 'om_img_unautop' ) ) {

  function om_img_unautop( $pee ) {

    $pee = preg_replace( '/<p>\\s*?(<a .*?><img.*?><\\/a>|<img.*?>)?\\s*<\\/p>/s', '<figure>$1</figure>', $pee );

    return $pee;

  }

}




/**
 * Don't Update Theme
 * @since 1.0.0
 *
 * If there is a theme in the repo with the same name,
 * this prevents WP from prompting an update.
 *
 * @author Mark Jaquith
 * @link markjaquith.wordpress.com/2009/12/14/excluding-your-plugin-or-theme-from-update-checks/
 *
 * @param array $r, request arguments
 * @param string $url, request url
 * @return array request arguments
 */
function om_dont_update_theme( $r, $url ) {

  if ( 0 !== strpos( $url, 'http://api.wordpress.org/themes/update-check' ) )
    return $r; // Not a theme update request. Bail immediately.

  $themes = unserialize( $r['body']['themes'] );
  unset( $themes[ get_option( 'template' ) ] );
  unset( $themes[ get_option( 'stylesheet' ) ] );
  $r['body']['themes'] = serialize( $themes );

  return $r;

}




/**
 * Remove all comment functionality
 */
// remove comment support from post types
if ( ! function_exists( 'om_remove_comment_support' ) ) {

  function om_remove_comment_support() {

    remove_post_type_support( 'post', 'comments' );
    remove_post_type_support( 'page', 'comments' );

  }
}


// remove comments option from admin bar
if ( ! function_exists( 'om_remove_comments_admin_bar' ) ) {

  function om_remove_comments_admin_bar() {

    global $wp_admin_bar;

    $wp_admin_bar->remove_menu( 'comments' );

  }

}


// remove comments option from dashboard menu
if ( ! function_exists( 'om_remove_comment_menu' ) ) {

  function om_remove_comment_menu() {

    remove_menu_page( 'edit-comments.php' );

  }

}




/**
 * Keep Yoast SEO metabox in last place
 */
if ( ! function_exists( 'om_yoast_seo_metabox_priority' ) ) {

  function om_yoast_seo_metabox_priority() {

    return 'low';

  }

}
