# microuter

A micro php router for web apps and APIs, all in a couple of lines.

microuter is largely inspired by the philosophy behind [GluePHP](http://gluephp.com/), and the [Sinatra](http://www.sinatrarb.com/) API.

No one expects you to write the next Facebook with microuter, but it allows you to avoid
bloat when you're writing really tiny RESTful APIs or miniscule web apps.

## Installation

User composer with the familiar incantation
```
composer require jszym/microuter
```

Or, just clone the repository and autoload the src folder (or just require/include src/Router.php)

## Usage

microuter's routing API is extremely similar to [Klein](http://chriso.github.io/klein.php/)/[Slim](http://www.slimframework.com/)/[Laravel](http://laravel.com/docs/5.1/routing) and all of their
Sintra-inspire ilk. The main difference is that patterns and passing parameters 
uses actual regular expressions as opposed to the `[i]` sort of stuff (microuter 
uses regex and capture groups which, albeit uglier, are more standard).


Binding a route is as simple as

```php
$router = \microuter\Router();
$router->bind("GET","/", function(){

    echo "Jello, Whirled";

});
$router->dispatch();
```

You can bind RESTful HTTP methods

```php
$router = \microuter\Router();
$router->bind("POST","/", function(){

    echo "Someone sent a post request to the root of this app!";

});

$router->bind("PUT","/pipe", function(){

    echo "And smoke it!";

});
$router->dispatch();
```

You can also use fancy regular expressions

```php
$router->bind("GET","//(?:(?:\+?1\s*(?:[.-]\s*)?)?(?:\(\s*([2-9]1[02-9]|[2-9][02-8]1|[2-9][02-8][02-9])\s*\)|([2-9]1[02-9]|[2-9][02-8]1|[2-9][02-8][02-9]))\s*(?:[.-]\s*)?)?([2-9]1[02-9]|[2-9][02-9]1|[2-9][02-9]{2})\s*(?:[.-]\s*)?([0-9]{4})(?:\s*(?:#|x\.?|ext\.?|extension)\s*(\d+))?", function(){

    echo "You passed a phone number as a URL. That's weird... stop it.";

});

$router->dispatch();
```


Captured groups in regular expressions are passed as parameters to the callback
function.

```php
$router->bind("GET","/add/(\d+)/(\d+)", function($a, $b){

    $c = $a + $b;
    
    echo "$a + $b = $c";

});

$router->dispatch();
```

You can use named capture groups, the names of which correspond to the parameters
of the callback function.
```php
$router->bind("GET","/greet/(?P<lastname>\w+)/(?P<firstname>\w+)", 

    function ($firstname, $lastname){
     
        echo "Hello, $firstname $lastname";
        
    });

$router->dispatch();
```

Just because these examples only use anonymous functions doesn't mean you can't 
use good-old-fashioned named functions. You can use any callable function/method.

```php
function is_bastard($person){
  switch ($person){
    case "Jon":
      echo "bastard";
      break;
    case "Rob":
      echo "not a bastard";
      break;
    case "Ramsay":
      echo "depends on who you ask";
      break;
  }
}

$router->bind("GET","/is_bastard/(\w+)", is_bastard);
$router->dispatch();
```

## License

Licensed under the MIT license, Copyright Joseph Szymborski.