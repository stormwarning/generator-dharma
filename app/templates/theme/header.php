<?php
/**
 * THEME HEADER
 */
?><!doctype html>
<html>
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?php wp_title( '|', true, 'right' ); ?></title>

    <!-- Add to homescreen for Chrome on Android -->
    <meta name="mobile-web-app-capable" content="yes">
    <link rel="icon" sizes="196x196" href="/chrome-touch-icon-196x196.png">

    <!-- Add to homescreen for Safari on iOS -->
    <meta name="apple-mobile-web-app-title" content="">

    <!-- Tile icon for Win8 (144x144 + tile color) -->
    <meta name="msapplication-TileImage" content="/ms-touch-icon-144x144-precomposed.png">
    <meta name="msapplication-TileColor" content="#ffffff">

    <link rel="stylesheet" href="<?php echo get_template_directory_uri();?>/assets/styles/style.css">

    <!-- Web Fonts -->


    <?php wp_head(); ?>
  </head>
  <body <?php body_class(); ?>>
    <!--[if lt IE 8]>
        <p class="browsehappy">You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade your browser</a> to improve your experience.</p>
    <![endif]-->

    <a class="skip-link screen-reader-text" href="#main"><?php _e( 'Skip to content', '_om' ); ?></a>

    <header class="site-header" id="top">
        <hgroup>
            <h1 class="site-title"><a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home"><?php bloginfo( 'name' ); ?></a></h1>
			<h2 class="site-description"><?php bloginfo( 'description' ); ?></h2>
        </hgroup>

        <nav class="main-navigation" role="navigation">
			<button class="menu-toggle" type="button"><?php _e( 'Menu', '_om' ); ?></button>
			<?php wp_nav_menu( array( 'theme_location' => 'primary' ) ); ?>
		</nav>
    </header>

    <main class="main" id="main" role="main">
