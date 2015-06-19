/* jshint node: true */
/* jshint es5: true */
'use strict';

var async = require( 'async' );
var chalk = require( 'chalk' );
var exec = require( 'child_process' ).exec;
var path = require( 'path' );
var util = require( 'util' );
var yeoman = require( 'yeoman-generator' );
var yosay = require( 'yosay' );
var _ = require( 'lodash' );
var _s = require( 'underscore.string' );

module.exports = yeoman.Base.extend({

  constructor: function() {

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
          message: 'What is the name of the site you’re building?'
        },
        {
          name: 'sitePlatform',
          message: 'What platforms will this site be built for?',
          choices: [
            {
              name: 'WordPress',
              value: 'isWordPress',
              checked: true
            },
            {
              name: 'Shopify',
              value: 'isShopify',
              checked: false
            }
          ]
        }
      ];

      this.log( yosay( 'ॐ' ) );

      this.prompt( prompts, function( props ) {

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
          default: this._.slugify( this.siteName )
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

      if ( this.isWordPress ) {

        this.prompt( prompts, function( props ) {

          this.themeDirectory = 'content/themes/' + props.themeDirectory;
          this.dbName = props.dbName;
          this.dbUser = props.dbUser;
          this.dbPass = props.dbPass;
          this.dbHost = props.dbHost;
          this.dbPrefix = props.dbPrefix;
          this.wpAdmin = props.wpAdmin;
          this.wpEnv = props.wpEnv;
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

      if ( this.isShopify ) {

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

    // ?

  },

  writing: {

    folders: function() {

      this.mkdir( 'source' );
      this.mkdir( 'source/styles' );
      this.mkdir( 'source/scripts' );
      this.mkdir( 'source/images' );

      if ( this.isWordPress ) {

        this.mkdir( 'content' );
        this.mkdir( 'content/mu-plugins' );
        this.mkdir( 'content/themes' );
        this.mkdir( this.themeDirectory );

      }

      if ( this.isShopify ) {

        this.mkdir( 'shopify' );
        this.mkdir( 'shopify/assets' );
        this.mkdir( 'shopify/config' );
        this.mkdir( 'shopify/layout' );
        this.mkdir( 'shopify/snippets' );
        this.mkdir( 'shopify/templates' );

      }

    },

    projectfiles: function() {

      this.template( '../_shared/build/_package.json', 'package.json' );
      this.template( '../_shared/build/_gulpfile.js',  'gulpfile.js' );
      this.template( '../_shared/bower/_bower.json',   'bower.json' );

    },

    repofiles: function() {

      this.copy( '../_shared/bower/_bowerrc',      '.bowerrc' );
      this.copy( '../_shared/style/editorconfig',  '.editorconfig' );
      this.copy( '../_shared/style/jshintrc',      '.jshintrc' );
      this.copy( '../_shared/style/jscsrc',        '.jscsrc' );
      this.copy( '../_shared/style/_phpcs.xml',    'phpcs.xml' );
      this.copy( '../_shared/style/scss-lint.yml', '.scss-lint.yml' );
      this.copy( '../_shared/git/_gitignore',      '.gitignore' );
      this.copy( '../_shared/git/gitattributes',   '.gitattributes' );
      this.template( '../_shared/git/_README.md',   'README.md' );

    },

    iconfiles: function() {

      // touch-icons, etc.
      //     this.copy('android-chrome-192.png', 'android-chrome-192.png');
      //     this.copy('manifest.json', 'manifest.json');
      //     this.copy('apple-touch-icon-precomposed.png', 'apple-touch-icon-precomposed.png');
      //     this.copy('apple-touch-icon.png', 'apple-touch-icon.png');
      //     this.copy('favicon-16.png', 'favicon-16.png');
      //     this.copy('favicon-32.png', 'favicon-32.png');
      //     this.copy('favicon-48.png', 'favicon-48.png');
      //     this.copy('icon-1024.png', 'icon-1024.png');
      //     this.copy('ms-tile-wide.png', 'ms-tile-wide.png');
      //     this.copy('ms-tile.png', 'ms-tile.png');
      //     this.copy('browserconfig.xml', 'browserconfig.xml');
      //     this.copy('theme/screenshot.png', this.themeDir + '/screenshot.png');

    },

    serverfiles: function() {

      this.copy( '../_shared/server/htaccess',     '.htaccess' );
      this.copy( '../_shared/server/humans.txt',   'humans.txt' );
      this.copy( '../_shared/server/robots.txt',   'robots.txt' );

    },

    templates: function() {

      this.fs.copyTpl(
        this.templatePath( 'static/index.html' ),
        this.destinationPath( 'index.html' ),
        {

        }
      );

      // will this work? maybe not...
      this.fs.copyTpl(
        this.templatePath( 'shared/sass/' ),
        this.destinationPath( 'source/styles/' ),
        {

        }
      );

      if ( this.isWordPress ) {

        this.template( 'wordpress/_index.php',        'index.php' );
        this.template( 'wordpress/_local-config.php', 'local-config-sample.php' );
        this.template( 'wordpress/_wp-config.php',    'wp-config.php' );

        this.directory( 'wordpress/theme/includes/',  this.themeDirectory + '/includes/' );

        // other WordPress theme files
        //     this.template(
        //       'theme/_style.css',
        //       this.themeDir + '/style.css');
        //     this.copy(
        //       'theme/404.php',
        //       this.themeDir + '/404.php');
        //     this.copy(
        //       'theme/archive.php',
        //       this.themeDir + '/archive.php');
        //     this.copy(
        //       'theme/footer.php',
        //       this.themeDir + '/footer.php');
        //     this.copy(
        //       'theme/front-page.php',
        //       this.themeDir + '/front-page.php');
        //     this.copy(
        //       'theme/functions.php',
        //       this.themeDir + '/functions.php');
        //     this.copy(
        //       'theme/header.php',
        //       this.themeDir + '/header.php');
        //     this.copy(
        //       'theme/index.php',
        //       this.themeDir + '/index.php');
        //     this.copy(
        //       'theme/page.php',
        //       this.themeDir + '/page.php');
        //     this.copy(
        //       'theme/search.php',
        //       this.themeDir + '/search.php');
        //     this.copy(
        //       'theme/single.php',
        //       this.themeDir + '/single.php');

      }

      if ( this.isShopify ) {

        // Shopify theme files

      }

    }

  },

  install: function() {

    // Install npm and bower deps.
    this.installDependencies();

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
