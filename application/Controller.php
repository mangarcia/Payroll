<?php

use \Firebase\JWT\JWT;

define('SERVER_URL', $_SERVER['HTTP_HOST']);

abstract class Controller
{
  protected $_view;
  protected $SERVER_URL;
  private $mq_activado;
  private $real_escape_string;

  
  public function __construct()
  {
    $this->mq_activado =  get_magic_quotes_gpc();
    $this->real_scape_string = function_exists("mysql_real_escape_string");
    $this->_view = new View(new Request());
    $this->SERVER_URL = (SERVER_URL == 'localhost' || SERVER_URL == 'f1c4342b.ngrok.io') ? SERVER_URL . "" : SERVER_URL;
  }


  abstract public function index();

  protected function loadModel($modelo)
  {
    $modelo = $modelo . 'Model';
    $rutaModelo = ROOT . 'models' . DS . $modelo . '.php';


    if (is_readable($rutaModelo)) {
      require_once $rutaModelo;
      $modelo = new $modelo;
      return $modelo;
    } else {
      echo $rutaModelo;
      throw new Exception('Error de Modelo');
    }
  }

  protected function getLibrary($libreria)
  {
    $rutaLibreria = ROOT . 'libs' . DS . $libreria . '.php';
    if (is_readable($rutaLibreria)) {
      require_once $rutaLibreria;
    } else {
      throw new Exception('Error de Libreria');
    }
  }

  protected function getText($clave)
  {
    if (isset($_POST[$clave]) && !empty($_POST[$clave])) {
      $_POST[$clave] = htmlspecialchars($_POST[$clave], ENT_QUOTES);
      return $_POST[$clave];
    }
    return '';
  }

  protected function getInt($clave)
  {
    if (isset($_POST[$clave]) && !empty($_POST[$clave])) {
      $_POST[$clave] = filter_input(INPUT_POST, $clave, FILTER_VALIDATE_INT);
      return $_POST[$clave];
    }
    return 0;
  }


  protected function redireccionar($ruta = false)
  {
  
    if ($ruta) {
      @header('Location: ' . BASE_URL . $ruta);
      exit();
    } else {
      header('Location: ' . BASE_URL);
      exit();
    }
  }

  protected function is_numerico($int)
  {
    if (is_numeric($int)) {
      return true;
    } else {
      return false;
    }
  }

  protected function esEntero($int)
  {
    if (is_int($int)) {
      return true;
    } else {
      return false;
    }
  }

  protected function esInteger($int)
  {
    if (is_integer($int)) {
      return true;
    } else {
      return false;
    }
  }

  protected function filtrarInt($int)
  {
    $int = (int) $int;
    if (is_int($int)) {
      return $int;
    } else {
      return 0;
    }
  }

  protected function getPostParams($clave)
  {
    if (isset($_POST[$clave])) {
      return $_POST[$clave];
    }
  }

  protected function issetParams($clave)
  {
    return isset($_REQUEST[$clave]) && !empty($_REQUEST[$clave]) ? true : false;
  }

  protected function issetFile($clave)
  {
    if (isset($_FILES) && !empty($_FILES[$clave]["name"]) && $_FILES[$clave]["error"] == 0) {
      return true;
    } else {
      return false;
    }
  }

  protected function getFilesParams($clave)
  {
    if (isset($_FILES[$clave])) {
      return $_FILES[$clave];
    }
  }

  protected function getAlphaNum($clave)
  {

    $_POST[$clave] = (string) preg_replace('/[^A-Z0-9_\s]/i', '', $clave);
    return trim($_POST[$clave]);
  }

  protected function getFileAlphaNum($clave)
  {
    if (isset($_FILES[$clave]) && !empty($_FILES[$clave])) {
      $_FILES[$clave] = (string) preg_replace('/[^A-Z0-9_]/i', '', $_FILES[$clave]);
      return trim($_FILES[$clave]);
    }
  }

  public function getSql($clave)
  {
    if (isset($_POST[$clave]) && !empty($_POST[$clave])) {
      if ($this->real_escape_string) {
        if ($this->mq_activado) {
          $_POST[$clave] = stripslashes($_POST[$clave]);
        }
        $$_POST[$clave] = mysql_real_escape_string($_POST[$clave]);
      } else {
        if (!$this->mq_activado) {
          $_POST[$clave] = addslashes($_POST[$clave]);
        }
      }
      return trim($_POST[$clave]);
    }
  }

  public function validarEmail($email)
  {
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
      return false;
    }
    return true;
  }

  public function codigoAlphaNum($palabras, $numero)
  {
    $numero++;
    $partes = explode(" ", $palabras);
    $letra = "";
    if (count($partes) > 0) {
      foreach ($partes  as $parte) {
        echo $letra .= substr($parte, 0, 1);
        echo "<br />";
      }
    }
    $letra = strtoupper($letra);
    if ($numero < 10) {
      return $letra . '-000' . $numero;
    } elseif ($numero < 100) {
      return $letra . '-00' . $numero;
    } elseif ($numero < 1000) {
      return $letra . '-0' . $numero;
    } else {
      return $letra . "-" . $numero;
    }
  }

  protected function json_response($data = null, $httpStatus = 200)
  {
    header_remove();
    if (isset($data['code'])) {
      $httpStatus = $data['code'];
      unset($data['code']);
    }
    header('Content-Type: application/json; charset=utf-8');
    header('Access-Control-Allow-Origin: *');
    header('Status: ' . $httpStatus);
    http_response_code($httpStatus);
    echo json_encode($data);
  }

  protected function json_jwt_response($data = null, $httpStatus = 200)
  {
    ob_start();

    $SERVER_URL = SERVER_URL;
    header_remove();
    if (isset($data['code'])) {
      $httpStatus = $data['code'];
      unset($data['code']);
    }
    header("Access-Control-Allow-Origin: *");
    header("Content-Type: application/json; charset=UTF-8");
    header("Access-Control-Allow-Methods: POST");
    header("Access-Control-Max-Age: 3600");
    if (isset($data['jwt'])) {
      header("Authorization: Bearer " . $data['jwt']);
      unset($data['jwt']);
    }
    // isset($data['jwt']) ?  header("Authorization: Bearer " . $data['jwt']) : '';
    // unset($data['jwt']);
    header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
    header('Status: ' . $httpStatus);
    http_response_code($httpStatus);
    echo json_encode($data);
    ob_end_flush();
  }

protected function verfitySessionJwt(): Bool
  {
    $secret_key = "YOUR_SECRET_KEY";
    
    if (Session::get('jwt')) {

        $jwt = (Session::get('jwt')) ? (Session::get('jwt') !== "") ? Session::get('jwt') : NULL : NULL;
        $jwt=str_replace("Bearer ", "", $jwt); 
        if ($jwt!="") {
          try {

            Firebase\JWT\JWT::$leeway = 5;
            do {
              $attempt = 0;
              try {
                $decoded = JWT::decode($jwt, $secret_key, array('HS256'));
                $retry = false;
                return true;
              } catch (Firebase\JWT\BeforeValidException $e) {
                $attempt++;
                $retry = $attempt < 2;
              }
            } while ($retry);
            // Access is granted. Add code of the operation here
            // echo json_encode(array(
            //   "message" => "Access granted:",
            //   "data" => $decoded,
            // ));
          } catch (Exception $e) {
            $msgError = $e->getMessage();
            throw new Exception("Access denied for $msgError", 401);
          }
        } else {
          throw new Exception("Access denied for error authorization", 401);
        }
       
    }
    else {
      throw new Exception("Access denied for error authorization", 401);
    }
  }

  public function CleanRequestData(array $data)
  {
    foreach ($data as $clave => $valor) {
      $currentValue=htmlentities($valor,ENT_NOQUOTES);
      $currentValue=strip_tags($currentValue);
      $data[$clave]=$currentValue;
   }
     return $data;
  }

 protected function getSesionDataJwt()
  {
    $secret_key = "YOUR_SECRET_KEY";
    $headers = $this->getAutorizationHeader();
    $headers = ($headers == "") ? NULL : $headers;

    $arr = explode(" ", $headers);
    $jwt =Session::get('jwt');
    $jwt=str_replace("Bearer ", "", $jwt); 
    if (Session::get('jwt')) {
      try {
        return JWT::decode($jwt, $secret_key, array('HS256'));

        // $decoded = JWT::decode($jwt, $secret_key, array('HS256'));
        // Access is granted. Add code of the operation here
        // echo json_encode(array(
        //   "message" => "Access granted:",
        //   "data" => $decoded,
        // ));
      } catch (Exception $e) {
        http_response_code(401);
        echo json_encode(array(
          "message" => "Access denied.",
          "error" => $e->getMessage()
        ));
        return false;
      }
    }
  }


  protected function verfityJwt(): Bool
  {
    $secret_key = "YOUR_SECRET_KEY";
    $headers = $this->getAutorizationHeader();
    $headers = ($headers == "") ? NULL : $headers;
    if (isset($headers)) {
      if (strpos($headers, 'Bearer') !== false) {
        $arr = explode(" ", $headers);
        $jwt = (isset($arr[1])) ? ($arr[1] !== "") ? $arr[1] : NULL : NULL;
        if (isset($jwt)) {
         
          try {
            Firebase\JWT\JWT::$leeway = 5;
            do {
              $attempt = 0;
              try {
                $decoded = JWT::decode($jwt, $secret_key, array('HS256'));
                $retry = false;
                return true;
              } catch (Firebase\JWT\BeforeValidException $e) {
                $attempt++;
                $retry = $attempt < 2;
              }
            } while ($retry);
            // Access is granted. Add code of the operation here
            // echo json_encode(array(
            //   "message" => "Access granted:",
            //   "data" => $decoded,
            // ));
          } catch (Exception $e) {
            $msgError = $e->getMessage();
            throw new Exception("Access denied for $msgError", 401);
          }
        } else {
          throw new Exception("Access denied for error authorization", 401);
        }
      } else {
        throw new Exception("Access denied for error authorization", 401);
      }      
    }
    else {
      throw new Exception("Access denied for error authorization", 401);
    }
  }

  protected function getDataJwt()
  {
    $secret_key = "YOUR_SECRET_KEY";
    $headers = $this->getAutorizationHeader();
    $headers = ($headers == "") ? NULL : $headers;

    $arr = explode(" ", $headers);
    $jwt = $arr[1];

    if ($jwt) {
      try {
        return JWT::decode($jwt, $secret_key, array('HS256'));

        // $decoded = JWT::decode($jwt, $secret_key, array('HS256'));
        // Access is granted. Add code of the operation here
        // echo json_encode(array(
        //   "message" => "Access granted:",
        //   "data" => $decoded,
        // ));
      } catch (Exception $e) {
        http_response_code(401);
        echo json_encode(array(
          "message" => "Access denied.",
          "error" => $e->getMessage()
        ));
        return false;
      }
    }
  }

  protected function getAutorizationHeader()
  {
    $headers = apache_request_headers();
    if (isset($headers['Authorization'])) {
      return $headers['Authorization'];
    } else if (isset($_SERVER['REDIRECT_HTTP_AUTHORIZATION'])) {
      return $_SERVER['REDIRECT_HTTP_AUTHORIZATION'];
    } else {
      return null;
    }
  }

  protected function getPayloadJwt(): object
  {
    $jwt = json_encode($this->getDataJwt());
    return $jwt = json_decode($jwt);
  }

  protected function sendMessagePush(array $playerUUID, array $dataPush)
  {
    if (!isset($playerUUID)) {
      throw new Exception("sendMessagePush required playerUUID", 1);
    }
    if (!isset($dataPush['title'])) {
      throw new Exception("sendMessagePush required title", 1);
    }
    if (!isset($dataPush['message'])) {
      throw new Exception("sendMessagePush required message", 1);
    }
    if (!isset($dataPush['subtitle'])) {
      throw new Exception("sendMessagePush required subtitle", 1);
    }

    $fields = array(
      'app_id'             => "5d6ad004-e304-49a5-a799-878e1ff5f983",
      'include_player_ids' => $playerUUID,
      'buttons'            => isset($dataPush['buttons'])        ? $dataPush['buttons']        : NULL,
      'app_url'            => isset($dataPush['lanuchURL'])      ? "https://" . $dataPush['lanuchURL'] : NULL,
      'data'               => isset($dataPush['additionalData']) ? $dataPush['additionalData'] : NULL,
      'headings'           => $dataPush['title'],
      'contents'           => $dataPush['message'],
      'subtitle'           => $dataPush['subtitle'],
    );

    $fields = json_encode($fields);
    // print("\nJSON sent:\n");
    // print($fields);

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, "https://onesignal.com/api/v1/notifications");
    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
      'Content-Type: application/json; charset=utf-8',
      'Authorization: Basic M2Q1ZmEzMjMtZGIyNy00NTFmLWIxNGEtN2E2YjI3OGNlZGQ1'
    ));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
    curl_setopt($ch, CURLOPT_HEADER, FALSE);
    curl_setopt($ch, CURLOPT_POST, TRUE);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);

    $response = curl_exec($ch);
    curl_close($ch);

    return json_decode($response);
  }

  protected function printError(\Throwable $th)
  {
    $endJSON['status'] = 'error';
    $endJSON['message'] = '⚠ Exception appear: ' . $th->getMessage();
    $endJSON['code'] = $th->getCode();
    return $endJSON;
  }

  public function getTimestamp()
  {
    // date_default_timezone_set("America/Bogota");
    // $date = new DateTime();
    // return $date->getTimestamp();
    return Carbon\Carbon::now('America/Bogota')->timestamp;
  }
}
