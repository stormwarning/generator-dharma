<?php
/**
 * The template for displaying archive pages.
 *
 * @link http://codex.wordpress.org/Template_Hierarchy
 */

  get_header();

?>

<main class="main" id="main" role="main">

<?php

  if ( have_posts() ) :

?>
  <header class="page-header">
    <h1 class="page-title">
      <?php
        if ( is_category() ) :
          single_cat_title();

        elseif ( is_tag() ) :
          single_tag_title();

        elseif ( is_author() ) :
          printf( __( 'Author: %s', '_om' ), '<span class="vcard">' . get_the_author() . '</span>' );

        elseif ( is_day() ) :
          printf( __( 'Day: %s', '_om' ), '<span>' . get_the_date() . '</span>' );

        elseif ( is_month() ) :
          printf( __( 'Month: %s', '_om' ), '<span>' . get_the_date( _x( 'F Y', 'monthly archives date format', '_om' ) ) . '</span>' );

        elseif ( is_year() ) :
          printf( __( 'Year: %s', '_om' ), '<span>' . get_the_date( _x( 'Y', 'yearly archives date format', '_om' ) ) . '</span>' );

        elseif ( is_tax( 'post_format', 'post-format-aside' ) ) :
          _e( 'Asides', '_om' );

        elseif ( is_tax( 'post_format', 'post-format-gallery' ) ) :
          _e( 'Galleries', '_om' );

        elseif ( is_tax( 'post_format', 'post-format-image' ) ) :
          _e( 'Images', '_om' );

        elseif ( is_tax( 'post_format', 'post-format-video' ) ) :
          _e( 'Videos', '_om' );

        elseif ( is_tax( 'post_format', 'post-format-quote' ) ) :
          _e( 'Quotes', '_om' );

        elseif ( is_tax( 'post_format', 'post-format-link' ) ) :
          _e( 'Links', '_om' );

        elseif ( is_tax( 'post_format', 'post-format-status' ) ) :
          _e( 'Statuses', '_om' );

        elseif ( is_tax( 'post_format', 'post-format-audio' ) ) :
          _e( 'Audios', '_om' );

        elseif ( is_tax( 'post_format', 'post-format-chat' ) ) :
          _e( 'Chats', '_om' );

        else :
          _e( 'Archives', '_om' );

        endif;
      ?>
    </h1>

    <?php

      // Show an optional term description.
      $term_description = term_description();

      if ( ! empty( $term_description ) ) {

        printf( '<div class="taxonomy-description">%s</div>', $term_description );

      }

    ?>
  </header>

  <?php

    while ( have_posts() ) :

      the_post();

  ?>
  <article class="excerpt-content" role="article">
    <?php the_title( sprintf( '<h1 class="entry-title"><a href="%s" rel="bookmark">', esc_url( get_permalink() ) ), '</a></h1>' ); ?>

    <?php the_excerpt(); ?>
  </article>
  <?php

    endwhile; // close the loop.

  ?>

  <?php

    // put pagination function here

  ?>

<?php

  else :

?>

  <article class="no-results" role="article">
    <h1 class="entry-title">Sorry, no results found</h1>
  </article>

<?php

  endif;

?>

</main>

<?php

  get_footer();

?>
