# Simple but Modern PHP Boilerplate

*A close-to plain PHP boilerplate featuring modern routing, templating, migrations, localization and assets powered by great single-purpose packages like Slim, Pug, Phinx, Babel, SCSS and gettext.*

## Why ?

Simple Answer : :sweat_drops: :dash: :sweat_drops: :dash: :sweat_drops: 

Long Answer : PHP is still everywhere. Maintaining old PHP projects with files all other the place and routing like we're in the 90's doesn't make for a happy developer. And I strive for happiness.

Rewriting in a different language (RoR) is always more pain than expected. I wanted to gradually augment my PHP skills and the level of my PHP codebase. Without going through the hassle of a Big Framework™ .

So I found some good and simple packages + a structure that will increase my productivity and mental state without being very hard to manage.

## Education

This repo and resources can be useful for anyone looking to improving his game in PHP. I particularly loved this amazing guide that became my bible. 

http://www.phptherightway.com/

It will become yours too.


## Installation

1. you need PHP > 5.6 (Also in the command line, if you want the local dev server)
2. Configure the `config.php` file with your own db credentials and stuff
3. install composer depencies: `$ php composer.phar install`
4. install babel: `$ npm run install`
5. Run local PHP server: `$ php -S localhost:8000 -t public_html/ public_html/index.php`
6. Enjoy my magnificient home template.

## APP

### Routing and core

We use the Slim framework v3 as base for the project. https://www.slimframework.com/docs/

It's a very simple router, but very well supported in the community. Equivalent to Flask in Python.
See the project "slim skeleton" for a complete Slim setup and their docs.
See index.php for initial setup, and follow the white rabbit... Ahem, no, it's better to follow `require_once` and the function calls !

### App and Routing setup

App settings are in `config.php`.

Packages Dependencies injection and Slim App Setup are in `app.php`.

Routes are declared in `router.php`.

Controllers are in `app/classes/controllers`.

*Yep. What a boring set of names and paths.*

### Login

We're rolling our custom login hooks, with anonymous functions returned by a class inspired by:
http://stackoverflow.com/questions/26108746/authentication-in-slim-is-a-combined-middleware-singleton-and-hook-approach-sm

I'll let you extend the auth class with your own custom admin callbacks depending on your needs :)

This package implements a middleware based login, with session variables, ACl, and tutti quanti but was too complicated for me to setup given my needs. But I recommend you take a look at it:
https://github.com/jeremykendall/slim-auth/tree/slim-3.x

### DB and migrations

We use https://phinx.org/ . It's simple and powerful enough to get you out of your local vs prod db sync problems.

After `composer install` to access phinx commands you need to do `$ php vendor/bin/phinx [command]`

For example:
`$ php vendor/bin/phinx init` will give you yaml file with configuration

We've provided some sample migrations and relevant seeding so you can see:

- How you can do all those things.
- How it can be useful to handle an example problem common in old PHP apps: passing from a old pasword hash like `md5` to a secure `bcrypt` one.
- See App/Lib/Authentication

Once db config setup, you can try:

First migration, users db setup :
`$ php vendor/bin/phinx migrate -e development -t 20161128175922`

Then seed :
`$ php vendor/bin/phinx seed:run -s UserSeeder`

Then hash their md5 passwords with `password_hash` with the rest of the migrations:
`$ php vendor/bin/phinx migrate -e development `



## Build and package management

### Models

We use `j4mie/idiorm`, for a very simple ORM. You can do more if you want to. See the docs here

### Classes

We're using autocomposer to autoload our controllers and models classes with PSR4. See composer.json "autoload".

Also, all classes in app/lib are loaded with psr-0 to allow more flexible naming.

Ref: http://phpenthusiast.com/blog/how-to-autoload-with-composer

### Logging

We provide a sample setup of Monolog\Logger

### Templates

I'm a big fan of pug, coming from Node and Javascript land.

Perfect: awesome people are creating a php version of pug right now :)

+ Pug-php: https://github.com/pug-php/pug
+ Pug-Assets: https://github.com/pug-php/pug-assets for minification etc...
+ Static markdown filters: https://github.com/cebe/markdown

The available filters are
:markdown 
:github-markdown
:inline-github-markdown
:markdown-extra

### Scss

We use a php scss package creating its own server with cache
management. It's https://github.com/leafo/scssphp

See `style.php` to understand what this is about.

We route css requests to it using `/css/*` route redirection in `router.php`.

### Translations

We use oscaro/gettext, but without the hard to install gettext extension. Ref: https://github.com/oscarotero/Gettext

We just use php arrays atm, see the Translation class

There is a bit of magic happening here:

We detect in all templates trs() calls and translate them
or add the new token to the translation files
+ you can add a markdown prefix to specify what token should be parsed as markdown

So when a client wants to modify a string, you just have to send him your new.php file. He finds the string he wants to modify, send it back to you.


### JS tasks

#### Setup ES6 with Babel

You need to have you system configured for Babel.
`$ npm install`

Babel will compile all files from `views/es6` to `public_html/js` to do so:

`$ npm run build`

##### *TODO :*

We want to allow clients to customize the page texts without
asking the dev to copy paste their stuff from excel spreadsheet, so we are going to use grunt to load current traductions from
remote gettext editor (like https://poeditor.com/) and then do something like this https://github.com/Philoozushi/grunt-poeditor-pz


### Others packages and utilities

Functional programming functions for PhP to replace stuff I liked in underscore.js
https://github.com/lstrojny/functional-php

## FILE STRUCTURE

cf http://stackoverflow.com/questions/7959673/directory-structure-for-mvc

```
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
```

## TODO

- Clarify example views
- Add support for remote localization file edit and syncing in build phase or through an api
- Set up assets minification
- Setup page headers lib
