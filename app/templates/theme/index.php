<?php
/**
 * The main template file.
 *
 * This is the most generic template file in a WordPress theme
 * and one of the two required files for a theme (the other being style.css).
 * It is used to display a page when nothing more specific matches a query.
 * E.g., it puts together the home page when no home.php file exists.
 *
 * @link http://codex.wordpress.org/Template_Hierarchy
 */

  get_header();

?>

<main class="main" id="main" role="main">

  <?php

    while ( have_posts() ) :

      the_post();

  ?>
  <article class="excerpt-content">
    <?php the_title( sprintf( '<h1 class="entry-title"><a href="%s" rel="bookmark">', esc_url( get_permalink() ) ), '</a></h1>' ); ?>

    <?php the_excerpt(); ?>
  </article>
  <?php

    endwhile; // close the loop.

  ?>

</main>


<?php

  get_footer();

?>
