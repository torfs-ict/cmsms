# CMS Made Simple

This packages enables you to use Composer and Bower packages for the development and deployment
of [CMS Made Simple](http://www.cmsmadesimple.org) websites and modules.

It also provides a Vagrant box for easy setup of a development environment.

## Setting up a development environment

- Create a composer project: `composer create-project torfs-ict/cmsms <path>`.
- Run `vagrant up` in the created project.
- Browse to http://192.168.33.99/install and follow the CMSMS installation procedure.
- Include the Composer autoloader in the CMSMS `config.php` file:

```php
require_once(__DIR__ . '/vendor/autoload.php');
```

### Trivia

- The MySQL root password, username, user password and database name are all `cmsms`.
- PHPMyAdmin gets installed in the Vagrant box and is accessible at http://192.168.33.99/phpmyadmin.

### Developing modules

All modules (in development) should be put in the `modules` directory of the development environment and have a valid
`composer.json` file as you can see below in the example taken from the [Google Maps module](https://github.com/torfs-ict/cmsms-google-maps).

```json
{
    "name": "torfs-ict/cmsms-google-maps",
    "description": "Google Maps module for CMS Made Simple",
    "version": "1.0.0",
    "license": "MIT",
    "authors": [
        {
            "name": "Kristof Torfs",
            "email": "kristof@torfs.org"
        }
    ],
    "require": {
        "torfs-ict/cmsms": "^2.1"
    },
    "extra": {
        "cmsms": {
            "module": true,
            "name": "GoogleMaps",
            "bower": {
                "gmaps": "~0.4.22",
                "hint.css": "^2.3.2"
            }
        }
    }
}
```

#### Composer.json requirements

1. The version must be set.
2. At least one author must be set.
3. The `extra/cmsms` section must be defined.
    - The `module` field must be set to TRUE, so our dev environment knows it should treat it as a module when installing.
    - The `name` field must be set to the actual module name.
    - The `bower` field contains the Bower package requirements (optional). 
      These will automatically be installed when installing/updating the composer package of your module.

#### Generating a new module

1. Create the module directory and change to that directory.
2. Run `php ../../src/Module.php`

#### Install module in the development environment

1. Make sure your module directory is a Git repository.
2. Add the VCS to the repositories in the development environment composer.json e.g.
    ```json
    "repositories": [{
        "type": "path",
        "url": "modules/GoogleMaps"
    }]
    ```
3. Add the module as a requirement in composer.json e.g.
    ```json
    "requires": {
        "torfs-ict/cmsms-google-maps": "*"
    }
    ```
4. Run `composer update` in the root of the development environment.

_Note: if you generated the module with our script, you can skip steps 1 & 2._

## Deploying a completed project

1. Use the Composer `create-project` command as when setting up the development environment.
2. Make sure the webroot of your virtual host points to the `cms` directory.
3. Run `composer require` for each module you need.
4. Include the Composer autoloader in the CMSMS `config.php` file: `require_once(__DIR__ . '/vendor/autoload.php');`
5. Navigate your browser to the install directory and complete the CMS Made Simple installation.
6. Remove the install directory.