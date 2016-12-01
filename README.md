# Simple but Modern PHP Boilerplate

*A close-to plain PHP boilerplate featuring modern Routing, Templating, Migrations, Localization and Assets by Slim, Pug, Phinx, ES6 Babel, SCSS, gettext *

## Why ?

Simple Answer : :sweat_drops: :dash: :sweat_drops: :dash: :sweat_drops: 

Long Answer : PHP is still everywhere. Maintaining old PHP projects with files all other the place and routing like we're in the 90's doesn't make for a happy developer. And I strive for happiness.

Rewriting in a different language (RoR) is always more pain. I wanted to gradually augment my PHP skills and the level of my PHP codebase. Without going to the hassle of a big framework.

So I found some good and simple packages + a structure that will increase my productivity and mental state without being very hard to manage.


## Installation

1. you need PHP > 5.6 (Also in the command line, if you want the local dev server)
2. Configure the config.php file with your own db credentials and stuff
3. install composer depencies: php composer.phar install
4. install babel: npm run install
5. Run local PHP server: php -S localhost:8000 -t public_html/ public_html/index.php

## APP

### Routing and core

We use the Slim framework v3 as base for the project. https://www.slimframework.com/docs/

It's a very simple router, but very well supported in the community. Equivalent to Flask in Python.
See the project "slim skeleton" for a complete Slim setup and their docs.
See index.php for initial setup

### App and Routing setup
App configuration is in config.php
Dependencies are injected in app.php
Routes are declared in router.php

### Login
We're rolling our custom login hooks, with anonymous functions
http://stackoverflow.com/questions/26108746/authentication-in-slim-is-a-combined-middleware-singleton-and-hook-approach-sm

I'll let you extend the auth class with your own custom admin callbacks depending on your needs :)

This package implements a middleware based login, with session variables ACl, and tutti quanti but is too complicated for us.
https://github.com/jeremykendall/slim-auth/tree/slim-3.x

### DB and migrations
We use https://phinx.org/
After install php vendor/bin/phinx
Then php vendor/bin/phinx init => yaml file with configuration

Once db setup, if you want to try
to migrate: php vendor/bin/phinx migrate -e development
to seed: php vendor/bin/phinx seed:run -s UserSeeder

Examples of migration to support old hashed password


## Build and package management

Not using composer ? You're wrong !

### Models

We use j4mie/idiorm, for a very simple ORM. You can do more if you want to.

### Classes

We're using autocomposer to autoload
to load our controllers and models classes with PSR4. See composer.json "autoload"
Also, all classes in app/lib are loaded with psr-0 to allow more flexible naming

Ref: http://phpenthusiast.com/blog/how-to-autoload-with-composer

### Logging

We provide a sample setup of Monolog\Logger

### Templates

I'm a big fan of pug, coming from Node and Javascript land.

Perfect: awesome people are creating a php version of pug right now :)

Pug-php: https://github.com/pug-php/pug
+ https://github.com/pug-php/pug-assets for minification etc...
+ Markdown filters using https://github.com/cebe/markdown
:markdown
:github-markdown
:inline-github-markdown
:markdown-extra

### Scss

We use a php scss package creating its own server with cache
management https://github.com/leafo/scssphp
see style.php
We route request to it using /css/* route redirection. See router.php

### Translations

We use oscaro/gettext, but without the hard to install gettext extension. 

We just use php arrays atm, see the Translation class

There is a bit of magic happening here:

We detect in all templates trs() calls and translate them
or add the new token to the translation files
+ you can add a markdown prefix to specify what token should be parsed as markdown

So when a client wants to modify a string, you just have to send him your new.php file. He finds the string he wants to modify, send it back to you.


### JS tasks

#### Setup ES6 with Babel

npm install

Babel will copy all files from views/es6 to public_html/js

TODO:

To allow our clients to customize the text without
asking the dev, we use grunt to load current traductions from
poeditor
and we parse our users texts with https://github.com/erusev/parsedown
https://github.com/Philoozushi/grunt-poeditor-pz

then to get the compiled js from es6 do:
npm run build

### Others packages and utilities

Functional programming functions for PhP to replace underscore.js
https://github.com/lstrojny/functional-php

## FILE STRUCTURE

cf http://stackoverflow.com/questions/7959673/directory-structure-for-mvc

public_html/              (for public files) (should be public, place only index.php in here)
public_html/images
public_html/js            (for your js files, or custom generated ones)
lib/                      (for my libs)  (should be private)
lib/vendor/               (for 3rd party libs)
locales/
app/              (for the whole app) (should be private)
app/class         (classes that make the app work such as mainApp, Controller, Model, View, etc...)
app/class/models   (all the models)
app/class/views   (all the views)
app/class/views/pug (Pug templates used by the views)
app/class/controllers (all controllers)
app/class/helpers  (helper functions, not used atm)
app/class/lib     (libs that you develop for the app)
app/config.php    (config files)
app/router.php    (router.php)   
app/logs           (log files)
app/cache          (cached library files => pug and scss)
app/cron          (scheduled jobs)
app/db/seeds+migrations  (for database migration and seed scripts)
node_modules/ (for babel)

## TODO

- Clarify example views
- Add support for remote localization file edit and syncing in build phase or through an api
- set up assets minification
- setup page headers lib
