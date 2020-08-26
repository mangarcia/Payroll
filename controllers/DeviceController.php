<?php

class DeviceController extends Controller
{
  private $_model;

  public function __construct()
  {
    parent::__construct();
    $this->_model = $this->loadModel('Device');
  }

  public function index()
  { }

  public function registerDevice()
  {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
      $authUser = parent::verfityJwt();
      if ($authUser) {
        $data = $_POST;
        $payloadJwt = parent::getPayloadJwt();
        $data['userId'] = intval($payloadJwt->sid);
        $responseDB = $this->_model->insertDevice($data);
        parent::json_response($responseDB, 201);
      }
    } else {
      $endJSON['status'] = 'error';
      $endJSON["data"]['title'] = "";
      $endJSON["data"]['message'] = "No exist GET";
      parent::json_jwt_response($endJSON, 400);
    }
  }

  public function getAllDevice()
  {
    if ($_SERVER['REQUEST_METHOD'] === 'GET') {
      $authUser = parent::verfityJwt();
      if ($authUser) {
        $responseDB = $this->_model->getAllDevice();
        parent::json_response($responseDB, 200);
      }
    } else {
      $endJSON['status'] = 'error';
      $endJSON["data"]['title'] = "";
      $endJSON["data"]['message'] = "No exist GET";
      parent::json_jwt_response($endJSON, 400);
    }
  }

  public function getDeviceRol()
  {
    if ($_SERVER['REQUEST_METHOD'] === 'GET') {
      $authUser = parent::verfityJwt();
      if ($authUser) {
        $data = $_GET;
        $responseDB = $this->_model->getDeviceRol($data);
        parent::json_response($responseDB, 200);
      }
    } else {
      $endJSON['status'] = 'error';
      $endJSON["data"]['title'] = "";
      $endJSON["data"]['message'] = "No exist GET";
      parent::json_jwt_response($endJSON, 400);
    }
  }

  public function getDeviceUser()
  {
    try {
      if ($_SERVER['REQUEST_METHOD'] === 'GET') {
        $authUser = parent::verfityJwt();
        if ($authUser) {
          $payloadJwt = parent::getPayloadJwt();
          $data['userId'] = intval($payloadJwt->sid);
          $responseDB = $this->_model->getDeviceUser($data);
        }
      } else {
        throw new Exception("No exist POST", 400);
      }
    } catch (\Throwable $th) {
      $endJSON = parent::printError($th);
    } finally {
      $endJSON = $responseDB ?? $endJSON;
      parent::json_response($endJSON);
    }
  }

  public function updateDevice()
  {
    parse_str(file_get_contents('php://input'), $_PUT);
    if ($_SERVER['REQUEST_METHOD'] === 'PUT') {
      $authUser = parent::verfityJwt();
      if ($authUser) {
        $payloadJwt = parent::getPayloadJwt();
        $data = $_PUT;
        $data['userId'] = intval($payloadJwt->sid);
        $responseDB = $this->_model->updateDevice($data);
        parent::json_response($responseDB, 200);
      }
    } else {
      $endJSON['status'] = 'error';
      $endJSON["data"]['title'] = "";
      $endJSON["data"]['message'] = "No exist GET/POST";
      parent::json_jwt_response($endJSON, 400);
    }
  }

  public function deleteDevice()
  {
    parse_str(file_get_contents('php://input'), $_DELETE);
    if ($_SERVER["REQUEST_METHOD"] == "DELETE") {
      $responseDB = $this->_model->deleteDevice($_DELETE);
      parent::json_response($responseDB, 204);
    } else {
      $endJSON['status'] = 'error';
      $endJSON["data"]['title'] = "";
      $endJSON["data"]['message'] = "No exist GET/POST";
      parent::json_jwt_response($endJSON, 400);
    }
  }
}
