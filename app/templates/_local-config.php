<?php
/*
This is a sample local-config.php file
In it, you *must* include the four main database defines

You may include other settings here that you only want enabled on your local development checkouts
*/

define( 'DB_NAME', '<%= _.slugify(dbName) %>' );
define( 'DB_USER', '<%= _.slugify(dbUser) %>' );
define( 'DB_PASSWORD', '<%= _.slugify(dbPass) %>' );
define( 'DB_HOST', '<%= _.slugify(dbHost) %>' ); // Probably 'localhost'
