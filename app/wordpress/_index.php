<?php
// WordPress view bootstrapper
define( 'WP_USE_THEMES', true );
require( './<%= _.slugify(wpDirectory) %>/wp-blog-header.php' );
