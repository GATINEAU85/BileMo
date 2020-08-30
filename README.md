# Adrien GATINEAU

Hello, I'm Adrien GATINEAU. I create for my studies the BileMo API for the smartphone sells. Enjoy your discovery of  
[BileMo](http://51.15.234.228:8001/doc)

The documentation of this API is [here](http://51.15.234.228:8001/doc)

## Method
This project was maintened and versionned by GitHub
[GitHub : GATINEAU85 / BileMo](https://github.com/GATINEAU85/BileMo)

Differentes issues are made. They was been gradually resolved during the developpement and the creation of pull request. 

## Project

This project took place in continuity with my work-study training in web development at openclassrooms.
UML diagram are present on the path : App\UML\. It could be help you to understand this projet. 

## Prerequisite 

* Install PHP 7.2.5 or higher and these PHP extensions
* Install Composer, which is used to install PHP packages.
* Install Mysql to manage database
* Install Git

## Install 

1. Run command : `git clone https://github.com/GATINEAU85/BileMo.git`
2. Run command : `cd BileMo`
3. Run command in bash `composer install`
4. Set environnement variables of the project thanks to the file that I joined you.

## More 

1. Config dev environment "App/.env". Thanks to this file, you can configure your database connection.
```php
    DATABASE_URL=mysql://user:password@host:port/database
```
2. Data import

```php
    php bin/console doctrine:database:create : To create database which is configured on .env file
    php bin/console doctrine:schema:update --dump-sql : To show SQL statement will be executed
    php bin/console doctrine:schema:update --force : To execture SQL statement and create table on database
    php bin/console doctrine:fixtures:load : To load data fixture on tables
```

## Code Analyse

The last codacy analyse is [here](https://app.codacy.com/manual/GATINEAU85/BileMo) . I receved a B appreciation.