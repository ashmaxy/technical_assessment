<?php

namespace Base;

class Controller {

    public $response;

    public function __construct()
    {
        $this->response = $GLOBALS['response'];
    }

    public function model($model) {
        $file = BASE_PATH . 'App/Models/' . ucfirst($model) . 'Model.php';

        if (file_exists($file)) {
            
            require_once $file;

            $model = $model . 'Model';
            if (class_exists($model))
                return new $model;
        }
    }
}
