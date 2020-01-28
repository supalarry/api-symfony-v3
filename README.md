# Printify REST API

API for creating users, products and orders for an e-commerce store.
Products and orders are linked to the user who created them.

- Written in PHP using Symfony framework
- Runs on nginx server
- Scripts executed by php-fpm
- Data stored using mysql
- Phpmyadmin enabled
- Deployed using Docker

## Installation

Clone this repository

```
git@github.com:lauris-printify/homework-v3.git
```

Switch to project folder

```
cd homework-v3
```

Install dependencies

```
composer install -d api
```

Run the app using docker

```
docker-compose up
```

## Database setup

This project uses mysql together with doctrine within the symfony framework.

After executing **docker-compose up** mysql is setup from **docker/backup/mysql/api.sql** file.

All work occurs in **printify_api** database.

Four tables - user, product, order, relation - are automatically created empty.

**user** : stores created users

**product** : stores products submitted by the user

**order** : stores order submitted by the user

**relation** : links an order with it's products

Connection to **mysql** docker container is defined in **api/env** file's DATABASE_URL field


## Testing

Tests are written with phpunit and are located in api/tests.

For your convenience, you can create an alias in your shell

```
alias phpunit='vendor/bin/phpunit'
```
Now, you can simply write phpunit within the api folder and tests will be executed.

Testing is done within in-memory.
Production repositories are being simulated by test repositories stored in **api/src/Repository/Test** to improve testing time while testing.
Production repositories are stored in **api/src/Repository/Prod**.

This is set up in api/config/services_test.yml file where interfaces point to test repositories. api/config/services.yml interfaces point to production repositories.

To run tests, first have all the containers started with **docker-composer up** and then in a new terminal launch tests with the **phpunit** command.

## Usage

Endpoints can be accessed at port 8098:

http://localhost:8098/{endpoint}

All keys for POST body can be in any case. If required key is **name**, then API accepts **Name**, **nAmE** etc.
Request body also can include redundant keys. If name and surname keys are asked, and user also adds eye-color key, the API works just fine. It grabs what is necessary and ignores the rest.

If invalid endpoints or non existing users, products or orders are requested, API returns an empty body with 404 status code.

Accessing '/' displays HTML welcome page.

## Endpoints

### Create a new user
### endpoint -> /users
### method -> POST
### url -> http://localhost:8098/users

request body:

```
{
	"name" : "John",
	"surname" : "Doe"
}
```

name and surname values accept upper and lowercase letters, spaces, dot (.) , comma (,) , apastrophe (') and dash (-).

response body:

```
{
    "id": 1,
    "name": "John",
    "surname": "Doe",
    "balance": 10000
}
```

Each user is assigned 100$ or 10000 cents as a starting balance to make orders later on.

### View a user
### endpoint -> /users/{id}
### method -> GET
### url -> http://localhost:8098/users/{id}

response body with {id} of 1:

```
{
    "id": 1,
    "name": "John",
    "surname": "Doe",
    "balance": 10000
}
```

### View all user
### endpoint -> /users
### method -> GET
### url -> http://localhost:8098/users

response body consists of an array of user objects

response body:

```
[
    {
        "id": 1,
        "name": "John",
        "surname": "Doe",
        "balance": 10000
    },
    {
        "id": 2,
        "name": "Alice",
        "surname": "Doe",
        "balance": 10000
    }
]
```

### Create a new product
### endpoint -> /users/{id}/products
### method -> POST
### url -> http://localhost:8098/users/{id}/products

request body if user with id of 1 exists:

```
{
	"type" : "t-shirt",
	"title" : "just do it",
	"sku" : "100-abc-999",
	"cost" : 1000
}
```

supported product types as of now are "t-shirt" and "mug", but they can be written in upper or lowercase. For example, "T-Shirt" is valid.

title can consist of upper and lowercase letters, digits, dash (-) and space ( ).

sku (stock keeping unit) must be unique among products user has submitted.

cost must be an integer

response body for owner with id of 1:

```
{
    "id": 1,
    "ownerId": 1,
    "type": "t-shirt",
    "title": "just do it",
    "sku": "100-abc-999",
    "cost": 1000
}
```

### View a product
### endpoint -> /users/{id}/products/{id}
### method -> GET
### url -> http://localhost:8098/users/{id}/products/{id}

response body for owner with id of 1:

```
{
    "id": 1,
    "ownerId": 1,
    "type": "t-shirt",
    "title": "just do it",
    "sku": "100-abc-999",
    "cost": 1000
}
```

### View all products
### endpoint -> /users/{id}/products
### method -> GET
### url -> http://localhost:8098/users/{id}/products

response body consists of an array of user's product objects

response body:

```
[
    {
        "id": 1,
        "ownerId": 1,
        "type": "t-shirt",
        "title": "just do it",
        "sku": "100-abc-999",
        "cost": 1000
    },
    {
        "id": 2,
        "ownerId": 1,
        "type": "t-shirt",
        "title": "lui v",
        "sku": "100-abc-100",
        "cost": 1000
    }
]
```

### Create a new order
### endpoint -> /users/{id}/orders
### method -> POST
### url -> http://localhost:8098/users/{id}/orders

request body if user with id of 1 exists:

```
{
	"shipToAddress": {
		"name" : "John",
		"surname" : "Doe",
		"street" : "Palm street 255",
		"state" : "NY",
		"zip" : "12315",
		"country" : "US",
		"phone" : "917-568-2970"
	},
	"lineItems": [
		{"id": 1, "quantity": 1},
		{"id": 2, "quantity": 1}
	],
	"info":
	{
		"expressShipping": true
	}
}
```

Order request body consists of two mandatory parts - shipToAddress (shipping address) and lineItems (products within the order).

shipToAddress

Orders are grouped into international and domestic (US) orders.

Domestic order's shipToAddress must include all keys just like in the example, but for international order "state" and "zip" keys are optional.


lineItems

Each object within lineItems array represents a product within order.

id is the id of a product that user has created.

quantity is how many units of the product are requested.

info

info part is only applicable to US orders and is optional. If expressShipping is set to true, then express shipping is enabled for the order. In that case, shipping costs for each product is 10$ (1000 cents).

If the user has sufficient funds, order is created. Once it is created, funds from user are substracted.

response body includes request data, but also expands on each line item and adds information about order under info section.

```
{
    "shipToAddress": {
        "name": "John",
        "surname": "Doe",
        "street": "Palm Street 255",
        "country": "US",
        "phone": "917-568-2970"
    },
    "lineItems": [
        {
            "id": 1,
            "quantity": 1,
            "ownerId": 1,
            "type": "t-shirt",
            "title": "just do it",
            "sku": "100-abc-999",
            "cost": 1000,
            "totalCost": 1000
        },
        {
            "id": 2,
            "quantity": 1,
            "ownerId": 1,
            "type": "t-shirt",
            "title": "mo mamba",
            "sku": "100-abc-100",
            "cost": 1000,
            "totalCost": 1000
        }
    ],
    "info": {
        "id": 3,
        "ownerId": 1,
        "productionCost": 2000,
        "shippingCost": 2000,
        "expressShipping": true,
        "totalCost": 4000
    }
}
```


## Logging into phpmyadmin

Log into phpmyadmin **http://localhost:8088** using:

```
username: root
password: rootroot
```
