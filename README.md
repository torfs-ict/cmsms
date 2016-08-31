# CMS Made Simple

This packages enables you to use Composer and Bower packages for the development and deployment
of [CMS Made Simple](http://www.cmsmadesimple.org) websites and modules.

It also provides a Vagrant box for easy setup of a development environment.

## Setting up a development environment

- Create a composer project: `composer create-project torfs-ict/cmsms <path>`.
- Run `vagrant up` in the created project.
- Browse to http://192.168.33.99/install and follow the CMSMS installation procedure.

### Trivia

- The MySQL root password, username, user password and database name are all `cmsms`.
- PHPMyAdmin gets installed in the Vagrant box and is accessible at http://192.168.33.99/phpmyadmin.

### Developing modules

TODO: Finish this section

### Developing websites

TODO: Finish this section

## Deploying a completed project

TODO: Finish this section