# Slim API Project 

Simple REST API built on Slim PHP Framework.

## Description

This project implemented account, countries and auth endpoints.

## Getting Started

### Dependencies

* PHP + MySql
* Composer

### Installing

* Configure database parameters in app/config/.env
* Import app/db/slim-api-project.sql in your database it will create two tables (users and countries) and add few sample records
* Run composer install from app/

### Executing program

* Host this application on your webserver's location or use PHP's built-in server to test
* To start PHP's built-in server run this command from project root:
```
$ php -S localhost:8000 -t public/
```

## Help

To consume API's endpoints you can use my [Postman collection](https://documenter.getpostman.com/view/896251/RzZFBbbD#3c4d1956-8b40-403c-8e5d-23058bd56543).


## Authors
  
[@farukgaric](https://twitter.com/farukgaric)

## Version History

* 0.2
    * Add README.md
* 0.1
    * Initial Release

## License

This project is licensed under the MIT License - see the LICENSE.md file for details