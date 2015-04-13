/* jshint node: true */
'use strict';

var util = require('util'),
    path = require('path'),
    yeoman = require('yeoman-generator'),
    chalk = require('chalk'),
    git = require('simple-git')();


var DharmaGenerator = yeoman.generators.Base.extend({
  init: function () {
    this.pkg = require('../package.json');

    this.on('end', function () {
      if (!this.options['skip-install']) {
        this.installDependencies();
      }
    });
  },

  askFor: function () {
    var done = this.async();

    // have Yeoman greet the user
    this.log(this.yeoman);

    // replace it with a short and sweet description of your generator
    this.log(chalk.magenta('Dharma: a WordPress site generator. ॐ'));

    var prompts = [
      {
        name: 'siteName',
        message: 'What is the name of the site you’re building?',
        default: 'My Site'
      },
      {
        name: 'themeSlug',
        message: 'What is the folder name?',
        default: 'my-theme'
      },
      {
        name: 'dbPrefix',
        message: 'What is your database table prefix?',
        default: 'om_'
      },
      {
        name: 'dbName',
        message: 'Database name',
        default: 'om_content'
      },
      {
        name: 'dbUser',
        message: 'Database user',
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
      // {
      //     name: 'wpLang',
      //   message: 'Want support for multiple languages?',
      //     default: ''
      //
      // },
      {
        name: 'wpDirectory',
        message: 'What folder should WordPress live in?',
        default: 'wp'
      },
    ];

    this.prompt(prompts, function (props) {
      this.siteName = props.siteName;
      this.themeSlug = props.themeSlug;
      this.wpDirectory = props.wpDirectory;
      this.dbPrefix = props.dbPrefix;
      this.dbName = props.dbName;
      this.dbUser = props.dbUser;
      this.dbPass = props.dbPass;
      this.dbHost = props.dbHost;

      done();
    }.bind(this));
  },

  app: function () {
    this.mkdir('content');
    this.mkdir('content/themes');
    // get this folder name from a user prompt
    this.mkdir('content/themes/' + this.themeSlug);
    this.mkdir('content/mu-plugins');
    this.mkdir('shared/content/uploads');
    this.template('_index.php', 'index.php');
    this.template('_local-config.php', 'local-config-sample.php');
    this.template('_wp-config.php', 'wp-config.php');
  },

  projectfiles: function () {
    this.log(chalk.blue('Creating project & build config files...'));

    this.template('_package.json', 'package.json');
    this.template('_gulpfile.js', 'gulpfile.js');
    this.template('_bower.json', 'bower.json');
  },

  dotfiles: function () {
    this.template('_bowerrc', '.bowerrc');
    this.copy('editorconfig', '.editorconfig');
    this.copy('jshintrc', '.jshintrc');
    this.copy('jscsrc', '.jscsrc');
    this.copy('_phpcs.xml', 'phpcs.xml');
    this.copy('scss-lint.yml', '.scss-lint.yml');
    this.template('_gitignore', '.gitignore');
    this.copy('gitattributes', '.gitattributes');
    this.template('_gitmodules', '.gitmodules');
    this.copy('htaccess', '.htaccess');
    this.copy('humans.txt', 'humans.txt');
    this.copy('robots.txt', 'robots.txt');

    this.log(chalk.blue('...done!'));
  },

  themefiles: function() {
    this.log(chalk.blue('Creating WordPress theme & function files...'));

    this.template('theme/_style.css', 'content/themes/' + this.themeSlug + '/style.css');
    this.copy('theme/404.php', 'content/themes/' + this.themeSlug + '/404.php');
    this.copy('theme/archive.php', 'content/themes/' + this.themeSlug + '/archive.php');
    this.copy('theme/footer.php', 'content/themes/' + this.themeSlug + '/footer.php');
    this.copy('theme/front-page.php', 'content/themes/' + this.themeSlug + '/front-page.php');
    this.copy('theme/functions.php', 'content/themes/' + this.themeSlug + '/functions.php');
    this.copy('theme/header.php', 'content/themes/' + this.themeSlug + '/header.php');
    this.copy('theme/index.php', 'content/themes/' + this.themeSlug + '/index.php');
    this.copy('theme/page.php', 'content/themes/' + this.themeSlug + '/page.php');
    this.copy('theme/search.php', 'content/themes/' + this.themeSlug + '/search.php');
    this.copy('theme/single.php', 'content/themes/' + this.themeSlug + '/single.php');
    this.copy('theme/includes/class-tgm-plugin-activation.php', 'content/themes/' + this.themeSlug + '/includes/class-tgm-plugin-activation.php');
    this.copy('theme/includes/om-configure.php', 'content/themes/' + this.themeSlug + '/includes/om-configure.php');
    this.copy('theme/includes/om-dashboard.php', 'content/themes/' + this.themeSlug + '/includes/om-dashboard.php');
    this.copy('theme/includes/om-functions.php', 'content/themes/' + this.themeSlug + '/includes/om-functions.php');
    this.copy('theme/includes/theme-functions.php', 'content/themes/' + this.themeSlug + '/includes/theme-functions.php');
    this.copy('theme/includes/theme-require-plugins.php', 'content/themes/' + this.themeSlug + '/includes/theme-require-plugins.php');

    this.log(chalk.blue('...done!'));
  },

  stylefiles: function() {
    this.log(chalk.blue('Creating initial SASS files...'));

    this.template('theme/source/styles/_style.scss', 'content/themes/' + this.themeSlug + '/source/styles/style.scss');
    this.copy('theme/source/styles/variables.scss', 'content/themes/' + this.themeSlug + '/source/styles/_variables.scss');
    this.copy('theme/source/styles/mixins.scss', 'content/themes/' + this.themeSlug + '/source/styles/_mixins.scss');
    this.copy('theme/source/styles/partials/_base.global.scss', 'content/themes/' + this.themeSlug + '/source/styles/partials/_base.global.scss');
    this.copy('theme/source/styles/partials/_base.typography.scss', 'content/themes/' + this.themeSlug + '/source/styles/partials/_base.typography.scss');

    this.log(chalk.blue('...done!'));
  },

  gitsome: function () {
    var done = this.async(),
        me = this;

    me.log(chalk.magenta('Initialising git repo...'));

    git.init(function (err) {
      if (err) {
        me.log(chalk.red(err));
      }

      done();
    });

    git.submoduleAdd('git://github.com/WordPress/WordPress.git', this._.slugify(this.wpDirectory), function (err) {
      if (err) {
        me.log(chalk.red(err));
      }

      git._baseDir = me._.slugify(me.wpDirectory);
      git.checkoutLatestTag(function (err) {
        if (err) {
          me.log(chalk.red(err));
        }

        done();
      });
    });

    git.add('./*');
    git.commit('Initialised project. ॐ');

    this.log(chalk.magenta('...done! Remember to add an origin and push.'));
  }
});

module.exports = DharmaGenerator;
