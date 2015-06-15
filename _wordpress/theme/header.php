<?php
/**
 *
 * THEME HEADER
 *
 */
?><!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?php wp_title( '&ndash;', true, 'right' ); ?></title>

    <link rel="icon" type="image/x-icon" href="/favicon.ico">
    <link rel="apple-touch-icon" href="/apple-touch-icon.png">
    <meta name="apple-mobile-web-app-title" content="">
    <meta name="application-name" content="">


    <?php wp_head(); ?>
  </head>
  <body <?php body_class(); ?>>
    <!--[if lt IE 8]>
      <p class="browsehappy">You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade your browser</a> to improve your experience.</p>
    <![endif]-->

    <a class="skip-navigation" href="#main">Skip to content</a>


    <header class="site-header" id="top" role="banner">
      <a class="logo" href="<?php echo esc_url( home_url( '/' ) ); ?>">
        <?php bloginfo( 'name' ); ?>
      </a>

      <nav class="main-navigation" role="navigation">
        <?php

          wp_nav_menu( array( 'theme_location' => 'primary' ) );

        ?>
      </nav>
    </header>
