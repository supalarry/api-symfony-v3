# Printify REST API

This API is written in PHP using Symfony framework.
Project runs on nginx server, php scripts are ran by php-fpm and data is stored in mysql. Phpmyadmin is accessable too.

This is an API which allows to add users and products and orders connected to them.

## Installation

Clone this repository

```
git@github.com:lauris-printify/homework-v3.git
```

In root directory run

```
composer install -d api
docker-compose up
```

## Database setup

This project uses mysql together with doctrine.

After executing **docker-compose up** mysql is setup from **docker/backup/mysql/api.sql** file.

All work occurs in **printify_api** database.

In it four tables - user, product, order and relation - are automatically created empty.

**user** - stores created users

**product** - stores products submitted by the user

**order** - stores order submitted by the user

**relation** - links an order with it's products

Connection to **mysql** docker container is defined in **api/config/services.yaml DATABASE_URL** field

## Testing

Tests are written with phpunit and are located in api/tests.

For your convenience, you can create an alias in your shell

```
alias phpunit='./api/vendor/bin/phpunit'
```
so that you can simply write phpunit from the root folder and tests will be executed.

Testing is done within in-memory storage. Production repositories are being simulated by test repositories stored in api/src/Repository/Test to improve testing time while testing. Production repositories are stored in api/src/Repository/Prod.

## Usage

Endpoints can be accessed at port 8098:

http://localhost:8098/{endpoint}

## Endpoints

all keys can be in any case. If required key is **name**, then API accepts **Name**, **nAmE** etc.

### users POST

expected request body:

```
{
	"name" : "John",
	"surname" : "Doe"
}
```
name and surname can only include letters.

## Logging into phpmyadmin

Log into phpmyadmin **http://localhost:8088** using:

```
username: root
password: rootroot
```
