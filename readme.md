## Lumen RESTful Boilerplate

> A simple boilerplate to start your first RESTfull API with [PHP Lumen](https://lumen.laravel.com/)

## Install

```sh
$ composer install
```

## Migration

```sh
$ php artisan migrate:refresh --env=local
```

## Usage

### Run server

```sh
$ php -S 127.0.0.1:8000 -t public
```

### Testing API

This examples was tested using `cURL` on terminal, but also it's possible to test 
on your browser directly or using [Postman](https://chrome.google.com/webstore/detail/postman/fhbjgbiflinjbdggehcddcbncdddomop).

```sh

$ curl -I http://localhost:8000/api/v1/book

$ curl -v -H "Accept:application/json" http://localhost:8000/api/v1/book/2
```

More routes at [routes.php](./app/Http/routes.php) file.


### License

MIT license

© 2016 [José Luis Quintana](http://git.io/joseluisq)
