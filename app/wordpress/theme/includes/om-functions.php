<?php
/**
 * If there's a Featured Image on a post, get it
 *
 * @param  $size  Requested image size
 * @return $image Featured image URL
 */
function om_get_featured_image( $size = 'large' ) {

  if ( has_post_thumbnail() ) {

    // get the featured image of the post.
    $img = wp_get_attachment_image_src( get_post_thumbnail_id(), $size );
    $image = $img[0];

  } else {

    // check for images attached to the post.
    $attachments = get_children( array(
      'post_type' => 'attachment',
      'order' => 'ASC',
      'orderby' => 'menu_order',
      'post_mime_type' => 'image',
      'post_parent' => $post->ID,
    ) );

    $attachments = array_keys( $attachments );
    $attached_image = wp_get_attachment_image_src( $attachments[0], $size );
    $image = $attached_image[0];

    if ( $attached_image == '' ) {

      // check the post content for an `<img>` element and get the `src` value.
      $content = get_the_content( $post );
      preg_match( '/<img.+src=[\'"](?P<src>.+)[\'"].*>/i', $content, $img_element );

      $image = $img_element['src'];

    }

  }

  // if $image has been set to something, return it.
  if ( $image !== null ) {

    return $image;

  }

}




/**
 * Request API data
 *
 * @param  $url    The API request URL
 * @return $result The JSON response from the request
 */
function om_curl_data( $url ) {

  $ch = curl_init();
  curl_setopt( $ch, CURLOPT_URL, $url );
  curl_setopt( $ch, CURLOPT_RETURNTRANSFER, 1 );
  curl_setopt( $ch, CURLOPT_TIMEOUT, 20 );
  $result = curl_exec( $ch );
  curl_close( $ch );

  return $result;

}




/**
 * Display automatic copyright year with optional range.
 *
 * @param $year integer Start year of range
 */
function om_copyright_year( $year = 'auto' ) {

  $current_year = date( 'Y' );

  if ( 'auto' == intval( $year ) ) {

    $year = $current_year;

  }

  switch ( intval( $year ) ) {

    case ( intval( $year ) == $current_year ) :
      echo intval( $year );

      break;

    case ( intval( $year ) < $current_year ) :
      echo intval( $year ) . '&ndash;' . $current_year;

      break;

    case ( intval( $year ) > $current_year ) :
      echo $current_year;

      break;

  }

}




/**
 * Breadcrumb navigation
 *
 * Not all pages will have a `the_title` value; these can be defined here.
 * 1. Home, the first item in a breadcrumb list
 * 2. A Category archive
 * 3. A Taxonomy archive
 * 4. The Search results page
 * 5. A Tag archive
 * 6. An Author archive
 * 7. The 404 error page
 *
 * Other constants
 * 8. show current post/page title in breadcrumbs
 * 9. show breadcrumbs on the homepage
 * 10. delimiter between crumbs
 * 11. tag before the current crumb
 * 12. tag after the current crumb
 *
 * @link http://dimox.net/wordpress-breadcrumbs-without-a-plugin/
 * @return string link hierarchy
 */
function om_the_breadcrumbs() {
  $text['home']     = 'Home'; // [1]
  $text['blog']     = 'Blog';
  $text['category'] = '%s Archive'; // [2]
  $text['tax']      = '%s'; // [3]
  $text['search']   = 'Results for “%s”'; // [4]
  $text['tag']      = 'Posts Tagged “%s”'; // [5]
  $text['author']   = 'Articles Posted by %s'; // [6]
  $text['404']      = 'Error 404'; // [7]

  $show_current = true; // [8]
  $show_on_home = false; // [9]
  $separator    = ''; // [10]
  $pre_current  = '<li class="breadcrumb-current current" itemprop="itemListElement" itemscope
      itemtype="http://schema.org/ListItem"><strong itemprop="item"><span itemprop="name">'; // [11]
  $post_current = '</span></strong></li>'; // [12]

  global $post;

  $home_url = get_bloginfo( 'url' ) . '/';
  $pre_link = '<li itemprop="itemListElement" itemscope
      itemtype="http://schema.org/ListItem">';
  $post_link = '</li>';
  $link_attr = ' itemprop="item"';
  $link = $pre_link . '<a' . $link_attr . ' href="%1$s"><span itemprop="name">%2$s</span></a>' . $post_link;

  if ( ( is_front_page() && is_home() ) || is_front_page() ) {

    if ( $show_on_home ) {

      echo '<ol class="nav breadcrumb" itemscope itemtype="http://schema.org/BreadcrumbList"><li itemprop="itemListElement" itemscope
      itemtype="http://schema.org/ListItem"><a itemprop="item" href="' . $home_url . '" rel="home">' . $text['home'] . '</a></li></ol>';

    }

  } else {

    echo '<ol class="nav breadcrumb" itemscope itemtype="http://schema.org/BreadcrumbList">';

    if ( is_home() ) {

      if ( 1 == $show_current ) {

        echo $pre_current . $text['blog'] . $post_current;

      }

    }

    if ( is_category() ) {

      $blog = get_post( get_option( 'page_for_posts' ) );

      echo $pre_link . '<a' . $link_attr . 'href="' . get_permalink( $blog ) . '"><span itemprop="name">' . apply_filters( 'the_title', $blog->post_title ) . '</span></a>' . $post_link . $separator;

      $this_cat = get_category( get_query_var( 'cat' ), false );

      if ( 0 != $this_cat->parent ) {

        $cats = get_category_parents( $this_cat->parent, true, $separator );
        $cats = str_replace( '<a', $pre_link . '<a' . $link_attr, $cats );
        $cats = str_replace( '</a>', '</a>' . $post_link, $cats );
        echo $cats;

      }

      echo $pre_current . sprintf( $text['category'], single_cat_title( '', false ) ) . $post_current;

    } elseif ( is_tax() ) {

      $this_tax = get_queried_object();

      if ( 0 != $this_tax->parent ) {

        $taxi = get_category_parents( $this_tax->parent, true, $separator );
        $taxi = str_replace( '<a', $pre_link . '<a' . $link_attr, $taxi );
        $taxi = str_replace( '</a>', '</a>' . $post_link, $taxi );
        echo $taxi;

      }

      echo $pre_current . sprintf( $text['tax'], single_term_title( '', false ) ) . $post_current;

    } elseif ( is_search() ) {

      echo $pre_current . sprintf( $text['search'], get_search_query() ) . $post_current;

    } elseif ( is_day() ) {

      echo sprintf( $link, get_year_link( get_the_time( 'Y' ) ), get_the_time( 'Y' ) ) . $separator;
      echo sprintf( $link, get_month_link( get_the_time( 'Y' ), get_the_time( 'm' ) ), get_the_time( 'F' ) ) . $separator;
      echo $pre_current . get_the_time( 'd' ) . $post_current;

    } elseif ( is_month() ) {

      echo sprintf( $link, get_year_link( get_the_time( 'Y' ) ), get_the_time( 'Y' ) ) . $separator;
      echo $pre_current . get_the_time( 'F' ) . $post_current;

    } elseif ( is_year() ) {

      echo $pre_current . get_the_time( 'Y' ) . $post_current;

    } elseif ( is_single() && ! is_attachment() ) {

      if ( 'post' != get_post_type() ) {

        $post_type = get_post_type_object( get_post_type() );
        $slug = $post_type->rewrite;
        printf( $link, $home_url . '/' . $slug['slug'] . '/', $post_type->labels->singular_name );

        if ( 1 == $show_current ) {

          echo $separator . $pre_current . get_the_title() . $post_current;

        }

      } else {

        $cat = get_the_category(); $cat = $cat[0];
        $cats = get_category_parents( $cat, true, $separator );

        if ( 0 == $show_current ) {

          $cats = preg_replace( "#^(.+)$separator$#", '$1', $cats );

        }

        $cats = str_replace( '<a', $pre_link . '<a' . $link_attr, $cats );
        $cats = str_replace( '</a>', '</a>' . $post_link, $cats );
        echo $cats;

        if ( 1 == $show_current ) {

          echo $pre_current . get_the_title() . $post_current;

        }

      }

    } elseif ( ! is_single() && ! is_page() && 'post' != get_post_type() && ! is_404() ) {

      $post_type = get_post_type_object( get_post_type() );
      echo $pre_current . $post_type->labels->singular_name . $post_current;

    } elseif ( is_attachment() ) {

      $parent = get_post( $post->post_parent );
      $cat = get_the_category( $parent->ID ); $cat = $cat[0];
      $cats = get_category_parents( $cat, true, $separator );
      $cats = str_replace( '<a', $pre_link . '<a' . $link_attr, $cats );
      $cats = str_replace( '</a>', '</a>' . $post_link, $cats );
      echo $cats;
      printf( $link, get_permalink( $parent ), $parent->post_title );

      if ( 1 == $show_current ) {

        echo $separator . $pre_current . get_the_title() . $post_current;

      }

    } elseif ( is_page() && ! $post->post_parent ) {

      if ( 1 == $show_current ) {

        echo $pre_current . get_the_title() . $post_current;

      }

    } elseif ( is_page() && $post->post_parent ) {

      $parent_id  = $post->post_parent;
      $breadcrumbs = array();

      while ( $parent_id ) {

        $page = get_page( $parent_id );
        $breadcrumbs[] = sprintf( $link, get_permalink( $page->ID ), get_the_title( $page->ID ) );
        $parent_id  = $page->post_parent;

      }

      $breadcrumbs = array_reverse( $breadcrumbs );

      for ( $i = 0; $i < count( $breadcrumbs ); $i++ ) {

        echo $breadcrumbs[ $i ];

        if ( $i != count( $breadcrumbs ) - 1 ) {

          echo $separator;

        }
      }

      if ( $show_current ) {

        echo $separator . $pre_current . get_the_title() . $post_current;

      }

    } elseif ( is_tag() ) {

      echo $pre_current . sprintf( $text['tag'], single_tag_title( '', false ) ) . $post_current;

    } elseif ( is_author() ) {

      global $author;

      $userdata = get_userdata( $author );
      echo $pre_current . sprintf( $text['author'], $userdata->display_name ) . $post_current;

    } elseif ( is_404() ) {

      echo $pre_current . $text['404'] . $post_current;

    }

    if ( get_query_var( 'paged' ) ) {

      if ( is_category() || is_day() || is_month() || is_year() || is_search() || is_tag() || is_author() ) {

        echo ' (';

      }

      echo __( 'Page' ) . ' ' . get_query_var( 'paged' );

      if ( is_category() || is_day() || is_month() || is_year() || is_search() || is_tag() || is_author() ) {

        echo ')';

      }

    }

    echo '</ol>';

  }

}
