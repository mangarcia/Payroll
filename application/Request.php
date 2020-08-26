<?php
class Request
{
  private $_controlador;
  private $_metodo;
  private $_argumentos;

  public function __construct()
  {
    if (isset($_GET['url'])) {
      $url = $_SERVER["REQUEST_URI"];
      $url = str_replace(BASE_URL, "/", $url);
      $url = explode('/', $url);
      $arg = explode('?', isset($url[2]) ? $url[2] : $url[1]);
      $url[2] = $arg[0];
      if (isset($arg[1])) {
        $arg = explode('&', $arg[1]);
      }
      $url = array_filter($url);
      $this->_controlador = array_shift($url);
      $this->_metodo = strtolower(array_shift($url));
      $this->_argumentos = $arg;
    }
    if (!$this->_controlador) {
      $this->_controlador = DEFAULT_CONTROLLER;
    }
    if (!$this->_metodo) {
      $this->_metodo = 'index';
    }

    if (!isset($this->_argumentos)) {
      $this->_argumentos = array();
    }
  }

  public function getControlador()
  {
    return $this->_controlador;
  }

  public function getMetodo()
  {
    return $this->_metodo;
  }

  public function getArgumentos()
  {
    return $this->_argumentos;
  }
}
