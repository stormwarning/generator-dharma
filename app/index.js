'use strict';
var util = require('util');
var path = require('path');
var yeoman = require('yeoman-generator');
var chalk = require('chalk');


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
				message: 'What is the name of the site you\'re building?'
			},
			{
				name: 'themeSlug',
				message: 'What is the folder name?',
				default: 'some-theme'
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
			// 	message: 'Want support for multiple languages?',
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
		this.template('_package.json', 'package.json');
		this.template('_gruntfile.js', 'gruntfile.js');
		this.template('_bower.json', 'bower.json');
	},

	dotfiles: function () {
		// dotfiles
		this.template('_bowerrc', '.bowerrc');
		this.copy('editorconfig', '.editorconfig');
		this.copy('jshintrc', '.jshintrc');
		this.template('_gitignore', '.gitignore');
		this.template('_gitmodules', '.gitmodules');
		this.copy('htaccess', '.htaccess');
		this.copy('humans.txt', 'humans.txt');
		this.copy('robots.txt', 'robots.txt');
	}
});

module.exports = DharmaGenerator;
