<?php
/**
 * The template for displaying search results pages.
 */

  get_header();

?>

<main class="main" id="main" role="main">

<?php

  if ( have_posts() ) :

?>
  <header class="page-header">
    <h1 class="page-title"><?php printf( __( 'Search Results for: %s', '_om' ), '<span>' . get_search_query() . '</span>' ); ?></h1>
  </header>

  <?php

    while ( have_posts() ) :

      the_post();

  ?>
  <article class="search-result">
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
  <article class="no-results">
    <h1 class="entry-title">Sorry, no results found</h1>
  </article>
<?php

  endif;

?>

</main>

<?php

  get_footer();

?>
