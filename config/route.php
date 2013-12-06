<?php

class Route
{
    private $_uri = array();

    private $_controller;
    private $_method;
    private $_arguments; 


    public function __construct(){     
            if (isset($_GET["uri"])) {
                $url = filter_input(INPUT_GET, 'uri', FILTER_SANITIZE_URL);
                $url = explode('/', $url);
                $url = array_filter($url);    
           
                $this->_controller = array_shift($url);
                $this->_method = array_shift($url);
                $this->_arguments = $url;
            }

            if(!$this->_controller){
                $this->_controller = DEFAULT_CONTROLLER;
            }
            
            if (!$this->_method) {
                $this->_method = 'index';
            }
             
            if (!isset($this->_arguments)) {
                $this->_arguments = array();
            }

    }

    
    public function init()
    {
        $pathController = PATH_ROOT . 'modulos/' . $this->_controller . '.php';
        if (is_readable($pathController)) {
            require_once $pathController;

            $c = new $this->_controller; 

            if(is_callable(array($this->_controller, $this->_method))){
                $method = $this->_method;
            }else{
                $method = 'index';
            }

            if (!empty($this->_arguments)) {
                call_user_func_array( array($this->_controller, $method), $this->_arguments);
            }else{
                call_user_func( array($this->_controller, $method) );
            }
        }else{
            throw new Exception('El controlador no Existe!');
        }

    }
}
