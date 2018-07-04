<?php

namespace Core;


class View
{
    /**
     * Функция рендерить виды
     * @param $view
     */
    public static function render($view, $args = [])
    {
        extract($args, EXTR_SKIP);

        $file = dirname(__DIR__) . "/App/Views/$view";

        if(is_readable($file))
        {
            require $file;
        } else
        {
            throw new \Exception("$file not found");
        }
    }

    public  static function renderTemplate($template, $args = [])
    {
        static $twig = null;

        if($twig === null)
        {
            $loader = new \Twig_Loader_Filesystem('../App/Views');
            $twig = new \Twig_Environment($loader);
        }
        echo $twig->render($template, $args);
    }
}