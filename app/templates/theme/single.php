<?php
/**
 * The template for displaying all single posts.
 */

  get_header();

?>

<?php while ( have_posts() ) : the_post(); ?>

  <?php get_template_part( 'content', 'single' ); ?>

  <?php _s_post_nav(); ?>

  <?php
  
    // If comments are open or we have at least one comment, load up the comment template
    if ( comments_open() || '0' != get_comments_number() ) {

      comments_template();

    }

  ?>

<?php endwhile; // end of the loop. ?>

<?php get_footer(); ?>
