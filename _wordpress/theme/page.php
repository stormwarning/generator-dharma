<?php
/**
 * The template for displaying all pages.
 *
 * This is the template that displays all pages by default.
 * Please note that this is the WordPress construct of pages
 * and that other 'pages' on your WordPress site will use a
 * different template.
 */

  get_header();

  $featured_image = om_get_featured_image( 'full' );

?>
<main class="main" id="main" role="main">

  <?php

    while ( have_posts() ) :

      the_post();

  ?>
  <article class="main-content" role="article">
    <?php the_title( '<h1 class="entry-title">', '</h1>' ); ?>

    <?php the_content(); ?>
  </article>
  <?php

    endwhile; // close the loop.

  ?>

</main>


<?php

  get_footer();

?>
