# Discounts Service Application

This is a service to apply discounts rules to a sample of orders

## Running

I choose to use Docker to serve the environment needed by the service. In this case, I am using [PHPDocker.io](https://github.com/guissilveira/discounts-service/tree/master/phpdocker).

Command|What it does?
-----------|---------
php composer.phar start|Start the containers and run the application
php composer.phar stop|Stop the containers
php composer.phar test|Run the test suite
php composer.phar psr2|Check the PSR2 coding styles

The aplication is served at: [localhost:8080](http://localhost:8080)

That's it! I hope you enjoy it.
