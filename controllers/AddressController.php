<?php

class AddressController extends Controller
{
  private $_model;

  public function __construct()
  {
    parent::__construct();
    $this->_model = $this->loadModel('Address');
  }

  public function index()
  { }

  public function createAddress()
  {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
      $authUser = parent::verfityJwt();
      if ($authUser) {
        $data = $_POST;
        $payloadJwt = parent::getDataJwt();
        $data['userId'] = $payloadJwt->aud;
        $responseDB = $this->_model->addNewAddress($data);
        parent::json_response($responseDB, 200);
      }
    } else {
      $endJSON['status'] = 'error';
      $endJSON["data"]['title'] = "";
      $endJSON["data"]['message'] = "No exist GET";
      parent::json_jwt_response($endJSON, 400);
    }
  }

  public function getAllAddress()
  {
    $responseDB = $this->_model->getAllAddress();
    parent::json_response($responseDB, 200);
  }

  public function getAddressByUser()
  {
    if ($_SERVER['REQUEST_METHOD'] === 'GET') {
      $authUser = parent::verfityJwt();
      if ($authUser) {
        $payloadJwt = parent::getDataJwt();
        $data['userId'] = $payloadJwt->aud;
        $responseDB = $this->_model->getAddressByUser($data);
        parent::json_response($responseDB, 200);
      }
    } else {
      $endJSON['status'] = 'error';
      $endJSON["data"]['title'] = "";
      $endJSON["data"]['message'] = "No exist GET";
      parent::json_jwt_response($endJSON, 400);
    }
  }

  public function updateAddress()
  {
    parse_str(file_get_contents('php://input'), $_PATCH);
    if ($_SERVER["REQUEST_METHOD"] === "PATCH") {
      $responseDB = $this->_model->updateAddress($_PATCH);
      parent::json_response($responseDB, 200);
    }
  }

  public function deleteAddress()
  {
    parse_str(file_get_contents('php://input'), $_DELETE);
    if ($_SERVER["REQUEST_METHOD"] == "DELETE") {
      $responseDB = $this->_model->deleteAddress($_DELETE);
      parent::json_response($responseDB, 204);
    }
  }
}
