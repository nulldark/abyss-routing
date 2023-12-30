# This repo has moved here [abyssphp/routing](https://github.com/abyssphp/routing).

# abyss/routing

Routing library inspired by express.js

## Installation

It's recommended that you use [Composer](https://getcomposer.org/)

```bash
$ composer require abyss/routing
```

## Choose a PSR-7 Implementation

Before use the these lib you will need to choose a PSR-7 implementation.
- [Nyholm/psr7](https://github.com/Nyholm/psr7) & [Nyholm/psr7-server](https://github.com/Nyholm/psr7-server)
- [Guzzle/psr7](https://github.com/guzzle/psr7)
- [laminas-diactoros](https://github.com/laminas/laminas-diactoros)

## Examples

Below is the pseudocode for using the library.

```php
/** @var \Psr\Http\Message\ServerRequestInterface $request */
$request = new ServerRequest();

$router = new Router();
$router->get('/', fn () => Response(200));

/** @var \Abyss\Routing\Route $results */
$results = $router->match($request);
```

