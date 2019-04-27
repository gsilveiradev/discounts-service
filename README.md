# Discounts Service Application

This is a simple service to apply discount rules to a sample of orders.
It uses [Slim Framework](http://www.slimframework.com/), php7.2, nginx and redis.

## Install

First clone the repo: `git clone https://github.com/guissilveira/discounts-service.git`
Then, run the composer install: `php composer.phar install`

## Running

The service uses Docker to serve the environment. In this case, the [PHPDocker.io](https://github.com/guissilveira/discounts-service/tree/master/phpdocker) was used as an easy way to dockerize the environment.

Command|What it does?
-----------|---------
`php composer.phar start`|Start the containers and run the application
`php composer.phar stop`|Stop the containers
`php composer.phar test`|Run the test suite
`php composer.phar psr2`|Check the PSR2 coding styles

The aplication is served at: [localhost:8080](http://localhost:8080)

[![Run in Postman](https://run.pstmn.io/button.svg)](https://app.getpostman.com/run-collection/f5524a3e787380124fea)

That's it! I hope you enjoy it.
