<?php

namespace Core;

/**
 * Router
 */
class Router
{
    /**
     * Массив в котором храниться текущий роут
     * @var array
     */
    protected  $routes = [];

    /**
     * Хранятся параметры роута
     * @var array
     */
    protected $params = [];

    /**
     * Функция создания роута, первый параметр передает роут второй параметры
     * @param $route
     * @param $params
     */
    public function add($route, $params = [])
    {
        $route = preg_replace('/\//', '\\/', $route);

        $route = preg_replace('/\{([a-z]+)\}/', '(?P<\1>[a-z-]+)', $route);

        $route = preg_replace('/\{([a-z]+):([^\}]+)\}/', '(?P<\1>\2)', $route);

        $route = '/^' . $route . '$/i';

        $this->routes[$route] = $params;
    }

    /**
     * Возвращает все роуты
     * @return array
     */
    public function getRoutes()
    {
        return $this->routes;
    }

    /**
     * Проверяет совпадение роутов
     * @param $url
     * @return bool
     */
    public function match($url)
    {
        foreach ($this->routes as $route => $params)
        {
            if(preg_match($route, $url, $matches))
            {
                foreach ($matches as $key => $match)
                {
                    if(is_string($key))
                    {
                        $params[$key] = $match;
                    }
                }
                $this->params = $params;
                return true;
            }
        }
        return false;
    }

    /**
     * Возвращает параметры роута
     * @return array
     */
    public function getParams()
    {
        return $this->params;
    }

    /**
     * Преобразует название контроллеров в нормальный вид
     * @param string $url The route Url
     * @return void
     */
    public function dispatch($url)
    {
        $url = $this->removeQueryStringVariables($url);

        if($this->match($url))
        {
            $controller = $this->params['controller'];
            $controller = $this->convertToStudlyCaps($controller);
            $controller = $this->getNamespace() . $controller;

            if(class_exists($controller))
            {
                $controller_object = new $controller($this->params);

                $action = $this->params['action'];
                $action = $this->convertToCamelCase($action);

                if(is_callable([$controller_object, $action]))
                {
                    $controller_object->$action();
                } else
                {
                    throw new \Exception("Method $action (in controller $controller) not found");
                }
            } else
            {
                throw new \Exception("Controller class $controller not found");
            }
        } else
        {
            throw new \Exception("No route matched.", 404);
        }
    }

    /**
     * Преобразует ссылки на StudlyCaps,
     * пример: post-authors => PostAuthors
     * @param string
     * @return string
     */
    protected function convertToStudlyCaps($string)
    {
        return str_replace(' ', '', ucwords(str_replace('-', ' ', $string)));
    }

    /**
     * Преобразует ссылки на CamelCase,
     * пример: add-new => addNew
     * @param string
     * @return string
     */
    protected function convertToCamelCase($string)
    {
        return lcfirst($this->convertToStudlyCaps($string));
    }

    /**
     * Форматирует url
     * @param string
     * @return string
     */
    protected function removeQueryStringVariables($url)
    {
        if($url != '')
        {
            $parts = explode('&', $url, 2);

            if(strpos($parts[0], '=') === false)
            {
                $url = $parts[0];
            } else
            {
                $url = '';
            }
        }
        return $url;
    }

    protected function getNamespace()
    {
        $namespace = 'App\Controllers\\';

        if(array_key_exists('namespace', $this->params))
        {
            $namespace .= $this->params['namespace'] . '\\';
        }
        return $namespace;
    }
}