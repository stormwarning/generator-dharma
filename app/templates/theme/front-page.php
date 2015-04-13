<?php
/**
 * The template for displaying the static front page.
 */

  get_header();

?>


<main class="main" id="main" role="main">

  <?php

    while ( have_posts() ) : the_post();

  ?>
  <article class="main-content">
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
