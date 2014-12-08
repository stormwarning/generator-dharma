# ॐ

`generator-dharma` : A [WordPress](http://wordpress.org/) site [Yeoman](http://yeoman.io/) generator for [Overhaul Media](http://overhaulmedia.com/).

> _Dharma_ signifies behaviours that are considered to be in accord with order that makes life and universe possible, and includes duties, rights, laws, conduct, virtues and "right way of living".

The Dharma generator builds a base WordPress project following best practices and methods for WordPress and front-end development.


[![GitHub Release](https://img.shields.io/github/release/stormwarning/generator-dharma.svg?style=flat)](/stormwarning/generator-dharma/releases)
[![Build Status](https://img.shields.io/travis/stormwarning/generator-dharma.svg?style=flat)](https://travis-ci.org/stormwarning/generator-dharma)
[![GitHub Issues](https://img.shields.io/github/issues/stormwarning/generator-dharma.svg?style=flat)](/stormwarning/generator-dharma/issues)

## How do I get started?

Install `generator-dharma` globally from npm and initiate in a fresh project directory:

```shell
$ npm install -g generator-dharma
$ yo dharma
```

## No, really, _how do I get started?_

Okay, first you need to be in the right environment. This should only need to be done one time as once the tools are installed they can be used on multiple projects.

On a Mac? Got Terminal open? Here we go:

1. First, install [Homebrew](http://brew.sh/).

    ```shell
    $ ruby -e "$(curl -fsSL https://raw.githubusercontent.com/Homebrew/install/master/install)"
    ```

    Homebrew is great for managing command-line executables, like `git` and `npm`, which we'll install next.

2. Next, install [node package manager](https://npmjs.org/).

    ```shell
    $ brew install npm
    ```

    Node is like Homebrew except (generally) front-end–focused and everything is built with Javascript.

3. Install [Yeoman](http://yeoman.io/) and his friend, [Bower](http://bower.io/).

    ```shell
    $ npm install -g yo bower
    ```

    Yeoman uses generators—like this one!—to rapidly generate a project structure. Bower is a package manager like Node and Homebrew, but specifically for front-end components (_i.e._ jQuery plugins, &c.).

4. Install [Gulp](http://gulpjs.com/). or Grunt. Or both, why not?

    ```shell
    $ npm install -g gulp grunt
    ```

    Gulp (and Grunt) is a task runner. Used during development, these tools can automate a lot of the tedious stuff so we don't need to remember if the CSS was minimised or images were optimised.

5. Now install `generator-dharma` and run it in a new project folder

    ```shell
    $ npm install -g generator-dharma
    $ yo dharma
    ```

    Just follow the on-screen prompts from there!



## License

MIT
