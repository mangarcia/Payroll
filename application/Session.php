<?php
class Session
{
  public static function init()
  {
    session_start();
  }

  public static function destroy($clave = false)
  {
    if ($clave)
    {
      if (is_array($clave))
      {
        for ($i = 0; $i < count($clave); $i++)
        {
          if (isset($_SESSION[$clave[$i]]))
          {
            unset($_SESSION[$clave[$i]]);
          }
        }
      }
      else
      {
        if (isset($_SESSION[$clave]))
        {
          unset($_SESSION[$clave]);
        }
      }
    }
    else
    {
      session_destroy();
    }
  }

  public static function set($clave, $valor)
  {
     //echo "Attemping to set key ".$clave."  with value ".$valor;
    if (!empty($clave))
    {
      $_SESSION[$clave] = $valor;
    }
  }

  public static function get($clave)
  {
 //echo "Attemping to read key ".$clave;
    if (isset($_SESSION[$clave]))
    {
      return $_SESSION[$clave];
    }

    return false;
  }

  public static function issetSession($clave)
  {
    if (isset($_SESSION[$clave]))
    {
      return true;
    }

    return false;
  }

  public static function acceso($level)
  {
    if (!Session::get('autenticado')) {
      header('location:' . HOST_URL . 'error/access/5050');
      exit;
    }

    Session::tiempo();

    if (Session::getLevel($level) > Session::getLevel(Session::get('level'))) {
      header('location:' . HOST_URL . 'error/access/5050');
      exit;
    }
  }

  public static function accesoView($rol)
  {
    if (!Session::get('autenticado')) {
      return false;
    }
    if (in_array(Session::get('rol'), $rol)) {
      return true;
    }
    return false;
  }


  public static function getLevel($level)
  {
    $role = array();
    $role['admin'] = 3;
    $role['especial'] = 2;
    $role['usuario'] = 1;

    if (!array_key_exists($level, $role)) {
      throw  new Exception('Error de aceso');
    } else {
      return $role[$level];
    }
  }

  public static function accesoPagina($clave)
  {
    if (!Session::issetSession('autenticado')) {
      header('location:' . HOST_URL . 'error/access/5050');
      exit;
    }
    if (!Session::issetSession($clave)) {
      header('location:' . HOST_URL . 'error/access/5050');
      exit;
    }
  }

  public static function accesoEstricto(array $rol)
  {
    if (!Session::get('autenticado')) {
      header('location:' . HOST_URL . 'error/access/5050');
      exit;
    }
    if (count($rol)) {
      if (in_array(Session::get('rol'), $rol)) {
        return;
      }
    }
    header('location:' . HOST_URL . 'error/access/5050');
    exit;
  }

  public static function accesoViewEstricto(array $rol)
  {
    if (!Session::get('autenticado')) {
      return false;
    }
    if (in_array(Session::get('rol'), $rol)) {
      return true;
    }
    return false;
  }

  public static function tiempo()
  {
    if (!Session::get('tiempo') || !defined('SESSION_TIME')) {
      throw new Exception('No se ha definido el tiempo de sesion');
    }
    if (SESSION_TIME == 0) {
      return;
    }

    if (time() - Session::get('tiempo') > SESSION_TIME * 60) {
      Session::destroy();
      header('location:' . HOST_URL . 'error/access/8080');
      exit;
    } else {
      Session::set('tiempo', time());
    }
  }
}
