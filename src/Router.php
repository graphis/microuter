<?php

/*
 * 
 * Copyright 2015 Joseph Szymborski, Licensed under the MIT license.
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 */

namespace microuter;

/**
 * Router class allows you to specify routing rules and bind them to a
 * callback function.
 *
 * @author Joseph Szymborski
 *
 */
class Router {
    
    public $routes;
    
    function __construct() {
        $this->routes = array();
    }

    /**
     *  Bind a route to a callback function.
     * 
     *  @param String $method The HTTP method that the bound route listens on. One of GET, POST, PUT, DELETE or UPDATE
     *  @param String $route The rule that describes the URLs that trigger the callback function. You can use regular expressions here, and each captured group gets passed as parameters to the callback function.
     *  @param Any $callback A callable function, anonymous functions, and object methods.
     *  @return Router Returns this object. Useful for chaining method calls.
     * 
     */
    function bind($method, $route, $callback){

      $this->routes[strtolower($method)][$route] = $callback;
      return $this;


    }

    /**
     * Calls the first callback functions that matches the rules specified by the bind method.
     * 
     * @return Router Returns this object. Useful for chaining method calls. 
     * @throws \Exception When the $_SERVER["REQUEST_METHOD"] is not one of GET, POST, PUT, DELETE or UPDATE, an exception is thrown with Code Number 1
     */
    function dispatch(){

      $requestMethod = strtolower($_SERVER['REQUEST_METHOD']);
      
      if ($requestMethod != "get" && $requestMethod != "post" && $requestMethod != "put" && $requestMethod != "delete"){
          
          throw new \Exception("Bad Request. A request code was past that was not GET, POST, PUT, or DELETE", 1);

      }
      
      $request = $_SERVER['REQUEST_URI'];

      
      
      foreach($this->routes[$requestMethod] as $pattern => $callback){
        
    
        if ($request == $pattern){
            
        
            $callback();
            return $this;
            
        }else{
            
            $pattern = "@^".$pattern."$@";

            if (preg_match($pattern, $request, $parameters)==1 && count(preg_match($pattern, $request, $parameters)) > 0){

      
                // We need to get rid of the first parameter because it's the entire string,
                // not the captured group(s)
                $parameters = array_slice($parameters, 1);
                

                // This is a funky little function that takes a callback function and
                // applies an array as it's parameters
                call_user_func_array($callback, $parameters);
                return $this;

            }
            
        }

      }

    }
}
