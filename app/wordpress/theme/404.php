<?php

  get_header();

?>


<main class="main  main-404" id="main" role="main">

  <h1 class="page-title">Oops! That page can&rsquo;t be found.</h1>

  <p>It looks like nothing was found at this location. <a href="<?php echo esc_url( home_url( '/' ) ); ?>">Return home</a> or try a search.</p>

  <form class="site-search" role="search" method="GET" action="/">
    <input class="search-field" type="search" name="s" value placeholder="Search for:" title="Search for:">
    <button class="search-submit" type="submit">Go</button>
  </form>

</main>


<?php

  get_footer();

?>
