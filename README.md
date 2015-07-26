# microuter

A micro php router for web apps and APIs, all written in ~30 SLOC.

microuter is largely inspired by the philosophy behind GluePHP, and the Sinatra API.

No one expects you to write the next Facebook with microuter, but it allows you to avoid
bloat when you're writing really tiny RESTful APIs or miniscule web apps.

## Installation

User composer with the familiar incantation
```
composer require jszym/microuter
```

Or, just clone the repository and autoload the src folder (or just require/include src/Router.php)

## Usage

microuter's API is extremely similar to Klein/Slim/Laravel and all of their
Sintra-inspire ilk. The main difference is that patterns and passing parameters 
uses actual regular expressions as opposed to the `[i]` sort of stuff (microuter 
uses regex and capture groups which, albeit uglier, are more standard).


Binding a route is as simple as

```
$router = \microuter\Router();
$router->bind("GET","/", function(){

    echo "Jello, Whirled";

});
$router->dispatch();
```

You can bind RESTful HTTP methods

```
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

```
$router->bind("GET","/\+\d{12}|\d{11}|\+\d{2}-\d{3}-\d{7}", function(){

    echo "You passed a phone number as a URL. That's weird... stop it.";

});

$router->dispatch();
```


Captured groups in regular expressions are passed as parameters to the callback
function.

```
$router->bind("GET","/add/(\d+)/(\d+)", function($a, $b){

    $c = $a + $b;
    
    echo "$a + $b = $c";

});

$router->dispatch();
```

Just because these examples only use anonymous functions doesn't mean you can't 
use good-old-fashioned named functions. You can use any callable function/method.

```
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