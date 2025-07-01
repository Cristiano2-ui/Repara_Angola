<?php

namespace App\classes;








class Routes
{
    private string $views_path;
    private string $controllers_path;


    public function __construct(String $views_path)
    {

        $this->views_path = $views_path;
        // $this->controllers_path = $controllers_path;
    }
    public static function verifyViewExist($absolute_path){

        if(!file_exists($absolute_path)) die(' o ficheiro não existe '.$absolute_path);
    }

    public function get($view, $controller) {

        $mounted_path=$this->views_path.$view.'.php';
        self::verifyViewExist($mounted_path);
        include_once ($mounted_path);
    }
}
