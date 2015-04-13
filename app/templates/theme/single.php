<?php
/**
 * The template for displaying all single posts.
 */

  get_header();

?>

<main class="main" id="main" role="main">

  <article class="main-content" id="post-<?php the_ID(); ?>">
    <?php the_title( '<h1 class="entry-title">', '</h1>' ); ?>

    <?php the_content(); ?>
  </article>

</main>

<?php

  get_footer();

?>
