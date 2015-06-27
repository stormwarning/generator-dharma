/* jshint node: true */
/* jshint es5: true */
'use strict';

var async = require( 'async' );
var chalk = require( 'chalk' );
var exec = require( 'child_process' ).exec;
var mkdirp = require( 'mkdirp' );
var path = require( 'path' );
var util = require( 'util' );
var yeoman = require( 'yeoman-generator' );
var yosay = require( 'yosay' );
var _ = require( 'lodash' );

var project = {};

module.exports = yeoman.Base.extend({

  constructor: function() {

    project.isWordPress = false;
    project.isShopify = false;
    yeoman.Base.apply( this, arguments );

    /**
     * Command-line flags.
     * `--wp`, `--shopify`, `--static`, `--wp-shopify`
     *
     * @todo Add flags for setting initial project type.
     */

  },

  initializing: function() {

    this.pkg = require( '../package.json' );

  },

  prompting: {

    greeting: function() {

      var done = this.async();
      var prompts = [
        {
          name: 'siteName',
          message: 'What is the name of the site you’re building?',
          type: 'filter'
        },
        {
          name: 'sitePlatform',
          message: 'What platforms will this site be built for?',
          type: 'checkbox',
          choices: [
            {
              name: 'WordPress',
              value: 'WordPress',
              checked: false
            },
            {
              name: 'Shopify',
              value: 'Shopify',
              checked: false
            }
          ]
        }
      ];

      this.log( yosay( 'ॐ' ) );

      this.prompt( prompts, function( props ) {

        if ( 0 <= _.indexOf( props.sitePlatform, 'WordPress' ) ) {

          project.isWordPress = true;

        }

        if ( 0 <= _.indexOf( props.sitePlatform, 'Shopify' ) ) {

          project.isShopify = true;

        }

        project.siteName = props.siteName;
        this.siteName = props.siteName;
        this.sitePlatform = props.sitePlatform;
        done();

      }.bind( this ) );

    },

    wordPressPrompts: function() {

      var done = this.async();
      var prompts = [
        {
          name: 'themeDirectory',
          message: 'What should the theme directory be named?',
          default: _.kebabCase( project.siteName )
        },
        {
          name: 'dbName',
          message: 'What is the database name for this WordPress instance?',
          default: 'om_content'
        },
        {
          name: 'dbUser',
          message: 'Database username',
          default: 'root'
        },
        {
          name: 'dbPass',
          message: 'Database password',
          default: 'alpine'
        },
        {
          name: 'dbHost',
          message: 'Database host',
          type: 'list',
          choices: [
            {
              name: 'Environment variable',
              value: '$_ENV{DATABASE_SERVER}'
            },
            {
              name: 'Local host',
              value: '"localhost"'
            }
          ],
          default: '$_ENV{DATABASE_SERVER}'
        },
        {
          name: 'dbPrefix',
          message: 'What is your database table prefix?',
          default: 'om_'
        },
        {
          name: 'wpAdmin',
          message: 'What should the admin username be?',
          default: 'overhaul'
        },
        {
          name: 'wpEnv',
          message: 'What is your desired dev environment setup?',
          type: 'list',
          choices: [
            {
              name: 'WordPress as a git submodule',
              value: 'submodule'
            },
            {
              name: 'Symlinked to an existing WP install',
              value: 'symlink'
            }
          ],
          default: 'symlink'
        }
      ];

      if ( project.isWordPress ) {

        this.prompt( prompts, function( props ) {

          this.themeSlug = props.themeDirectory;
          this.themeDirectory = 'content/themes/' + props.themeDirectory;
          this.dbName = props.dbName;
          this.dbUser = props.dbUser;
          this.dbPass = props.dbPass;
          this.dbHost = props.dbHost;
          this.dbPrefix = props.dbPrefix;
          this.wpAdmin = props.wpAdmin;
          project.wpEnv = props.wpEnv;
          done();

        }.bind( this ) );

      }

    },

    shopifyPrompts: function() {

      var done = this.async();
      var prompts = [
        {
          name: 'shopifyApi',
          message: 'Enter your private app API key',
          default: 'API_KEY'
        },
        {
          name: 'shopifyPassword',
          message: 'Enter your private app password',
          default: 'API_PASSWORD'
        },
        {
          name: 'shopifyDomain',
          message: 'What is your Shop’s subdomain? (' + chalk.bold( 'shop' ) + '.myshopify.com)',
          default: 'SHOP_DOMAIN'
        },
        {
          name: 'shopifyTheme',
          message: 'What is the ID of your Shopify theme?',
          default: 'THEME_ID'
        }
      ];

      if ( project.isShopify ) {

        this.log( 'Shopify config options can be edited in the ' + chalk.yellow( 'config.yml' ) + ' file.' );
        this.prompt( prompts, function( props ) {

          this.shopifyApi = props.shopifyApi;
          this.shopifyPassword = props.shopifyPassword;
          this.shopifyDomain = props.shopifyDomain;
          this.shopifyTheme = props.shopifyTheme;
          done();

        }.bind( this ) );

      }

    }

  },

  configuring: {

  },

  writing: {

    folders: function() {

      mkdirp( 'source' );
      mkdirp( 'source/styles' );
      mkdirp( 'source/scripts' );
      mkdirp( 'source/images' );

      if ( project.isWordPress ) {

        mkdirp( 'content' );
        mkdirp( 'content/mu-plugins' );
        mkdirp( 'content/themes' );
        mkdirp( this.themeDirectory );

      }

      if ( project.isShopify ) {

        mkdirp( 'shopify' );
        mkdirp( 'shopify/assets' );
        mkdirp( 'shopify/config' );
        mkdirp( 'shopify/layout' );
        mkdirp( 'shopify/snippets' );
        mkdirp( 'shopify/templates' );

      }

    },

    projectfiles: function() {

      this.fs.copyTpl(
        this.templatePath( '../shared/build/_package.json' ),
        this.destinationPath( 'package.json' ),
        {
          themeSlug: this.themeSlug
        }
      );
      this.fs.copyTpl(
        this.templatePath( '../shared/build/_gulpfile.js' ),
        this.destinationPath( 'gulpfile.js' ),
        {
          themeSlug: this.themeSlug
        }
      );
      this.fs.copyTpl(
        this.templatePath( '../shared/bower/_bower.json' ),
        this.destinationPath( 'bower.json' ),
        {
          themeSlug: this.themeSlug
        }
      );

    },

    repofiles: function() {

      this.copy( '../shared/bower/_bowerrc',      '.bowerrc' );
      this.copy( '../shared/style/editorconfig',  '.editorconfig' );
      this.copy( '../shared/style/jshintrc',      '.jshintrc' );
      this.copy( '../shared/style/jscsrc',        '.jscsrc' );
      this.copy( '../shared/style/_phpcs.xml',    'phpcs.xml' );
      this.copy( '../shared/style/scss-lint.yml', '.scss-lint.yml' );
      this.copy( '../shared/git/_gitignore',      '.gitignore' );
      this.copy( '../shared/git/gitattributes',   '.gitattributes' );
      this.template( '../shared/git/_README.md',  'README.md' );

    },

    iconfiles: function() {

      // touch-icons, etc.
      this.copy( '../shared/images/android-chrome-192.png', 'android-chrome-192.png' );
      this.copy( '../shared/images/manifest.json',          'manifest.json' );
      this.copy( '../shared/images/apple-touch-icon-precomposed.png', 'apple-touch-icon-precomposed.png' );
      this.copy( '../shared/images/apple-touch-icon.png',   'apple-touch-icon.png' );
      this.copy( '../shared/images/favicon-16.png',         'favicon-16.png' );
      this.copy( '../shared/images/favicon-32.png',         'favicon-32.png' );
      this.copy( '../shared/images/favicon-48.png',         'favicon-48.png' );
      this.copy( '../shared/images/icon-1024.png',          'icon-1024.png' );
      this.copy( '../shared/images/ms-tile-wide.png',       'ms-tile-wide.png' );
      this.copy( '../shared/images/ms-tile.png',            'ms-tile.png' );
      this.copy( '../shared/images/browserconfig.xml',      'browserconfig.xml' );

      if ( project.isWordPress ) {

        this.copy( '../wordpress/theme/screenshot.png', this.themeDirectory + '/screenshot.png' );

      }

    },

    serverfiles: function() {

      this.copy( '../shared/server/htaccess',   '.htaccess' );
      this.copy( '../shared/server/humans.txt', 'humans.txt' );
      this.copy( '../shared/server/robots.txt', 'robots.txt' );

    },

    templates: function() {

      this.fs.copyTpl(
        this.templatePath( '../static/index.html' ),
        this.destinationPath( 'index.html' ),
        {

        }
      );

      // will this work? maybe not...
      this.fs.copyTpl(
        this.templatePath( '../shared/sass/' ),
        this.destinationPath( 'source/styles/' ),
        {

        }
      );

      if ( project.isWordPress ) {

        // WordPress config & bootstrap
        this.template( '../wordpress/_index.php',        'index.php' );
        this.template( '../wordpress/_local-config.php', 'local-config-sample.php' );
        this.template( '../wordpress/_wp-config.php',    'wp-config.php' );

        // WordPress theme function files
        this.directory( '../wordpress/theme/includes/',  this.themeDirectory + '/includes/' );

        // other WordPress theme files
        this.fs.copyTpl(
          this.templatePath( '../wordpress/theme/_style.css' ),
          this.destinationPath( this.themeDirectory ),
          {

          }
        );
        this.fs.copy(
          this.templatePath( '../wordpress/theme/404.php' ),
          this.destinationPath( this.themeDirectory + '/404.php' ),
          {

          }
        );
        this.copy( '../wordpress/theme/404.php',        this.themeDirectory + '/404.php' );
        this.copy( '../wordpress/theme/archive.php',    this.themeDirectory + '/archive.php' );
        this.copy( '../wordpress/theme/footer.php',     this.themeDirectory + '/footer.php' );
        this.copy( '../wordpress/theme/front-page.php', this.themeDirectory + '/front-page.php' );
        this.copy( '../wordpress/theme/functions.php',  this.themeDirectory + '/functions.php' );
        this.copy( '../wordpress/theme/header.php',     this.themeDirectory + '/header.php' );
        this.copy( '../wordpress/theme/index.php',      this.themeDirectory + '/index.php' );
        this.copy( '../wordpress/theme/page.php',       this.themeDirectory + '/page.php' );
        this.copy( '../wordpress/theme/search.php',     this.themeDirectory + '/search.php' );
        this.copy( '../wordpress/theme/single.php',     this.themeDirectory + '/single.php' );

      }

      if ( project.isShopify ) {

        // Shopify theme files

      }

    }

  },

  install: function() {

    // Install npm and bower deps.
    // this.installDependencies();

    // Run git setup.

    // Install composer deps.
    // Install wp-cli.
    // Install/configure WordPress via cli.

    // Install Shopify theme gem.

  },

  end: function() {

    // Wrap it up.

  }

});
