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
    			message: 'Database host',
    			name: 'dbHost',
    		},
            {
    			message: 'Database name',
    			name: 'dbName',
    		},
            {
    			message: 'Database user',
    			name: 'dbUser',
    		},
            {
    			message: 'Database password',
    			name: 'dbPass',
    		},
            {
    			message: 'Language',
    			name: 'wpLang',
    		}
        ];

        this.prompt(prompts, function (props) {
            this.siteName = props.siteName;

            done();
        }.bind(this));
    },

    app: function () {
        this.mkdir('content');
        this.mkdir('content/themes');
        // get this folder name from a user prompt
        this.mkdir('content/themes/my-theme');
        this.mkdir('content/themes/my-theme/assets');
        this.mkdir('content/themes/my-theme/source');

        // project dependencies
        this.template('_package.json', 'package.json');
        this.template('_gruntfile.js', 'gruntfile.js');
        this.template('_bower.json', 'bower.json');
    },

    projectfiles: function () {
        // dotfiles
        this.copy('editorconfig', '.editorconfig');
        this.copy('jshintrc', '.jshintrc');
        this.copy('bowerrc', '.bowerrc');
    }
});

module.exports = DharmaGenerator;
