<?php

/*
App Core Class
Creates URL & loads core controller
URL FORMAT = /controller/method/params
*/

declare(strict_types=1);

namespace App\Libraries;

use App\Controllers\PagesController;
use RuntimeException;

class Core
{
    protected string $controllerClass = PagesController::class;
    protected string $methodName = 'index';
    protected array $routerParameters = [];

    public function __construct()
    {
        // print_r($this->getUrl());
        $url = $this->getUrl();

        // check if the url is not returning null
        if ($url !== []) {
            $controllerName = ucfirst(strtolower($url[0])) . 'Controller';
            $controllerClass = 'App\\Controllers\\' . $controllerName;

            // http://localhost/ferry-mvc/pages/about/1
            // output:
            // $url = ['pages','about']
            // $controllerClass = App\Controllers\PagesController

            // check if the controller has a class or not
            // output: true
            if (class_exists($controllerClass)) {
                // replace the currentController with the exist controller that has a class
                $this->controllerClass = $controllerClass;
                array_shift($url);
            }

            // output: after array_shift
            // $url = ['about', '1']
        }

        // if it's doesn't have a class, throw a error message through runtimeException
        if (!class_exists($this->controllerClass)) {
            throw new RuntimeException('Controller not found');
        }

        // if it has class, make the object and shift the first array index
        $controller = new $this->controllerClass();

        // check if method exist inside a controller class
        if ($url !== [] && method_exists($controller, $url[0])) {
            $this->methodName = array_shift($url);
        }

        // output: after array_shift
        // $url = ['1']

        // $routerParameters = 1;
        $this->routerParameters = $url;

        if (!is_callable([$controller, $this->methodName])) {
            throw new RuntimeException('Method not found');
        }

        call_user_func_array([$controller, $this->methodName], $this->routerParameters);
    }

    public function getUrl(): array
    {
        $value = $_GET['url'] ?? '';
        if (is_string($value)) {
            return [];
        }

        $url = filter_var($value, FILTER_SANITIZE_URL);
        if ($url === '') {
            return [];
        }

        $url = trim($url, '/');
        $url = explode('/', $url);
        $url = array_values(array_filter($url, fn($seg) => $seg !== ''));

        return $url;
    }
}
