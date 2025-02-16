<?php
class App
{
    protected $controller = 'Home';
    protected $method = 'index';
    protected $params = [];
    protected $controllerObject;

    public function __construct()
    {
        $url = $this->parseURL();

        if (isset($url[0])) {
            $controllerName = ucfirst($url[0]);
            $controllerFile = CONTROLLERPATH . '/' . $controllerName . '.php';

            if (file_exists($controllerFile)) {
                $this->controller = $controllerName;
                unset($url[0]);
            } else {
                $this->controller = 'Error';
                error_log("Controller '$controllerName' tidak ditemukan.");
            }
        }

        $controllerFile = CONTROLLERPATH . '/' . $this->controller . '.php';
        require_once $controllerFile;

        $controllerClass = $this->controller;
        $this->controllerObject = new $controllerClass();

        if (isset($url[1])) {
            if (method_exists($this->controllerObject, $url[1])) {
                $this->method = $url[1];
                unset($url[1]);
            } else {
                error_log("Method '" . $url[1] . "' tidak ditemukan di controller '" . $controllerClass . "'.");
                $this->method = 'index';
            }
        }

        $this->params = $url ? array_values($url) : [];

        call_user_func_array([$this->controllerObject, $this->method], $this->params);
    }

    public function parseURL()
    {
        if (isset($_GET['url'])) {
            $url = rtrim($_GET['url'], '/');
            $url = filter_var($url, FILTER_SANITIZE_URL);
            $url = explode('/', $url);
            return $url;
        }
        return [];
    }
}
