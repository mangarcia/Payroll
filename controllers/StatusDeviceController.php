<?php

class StatusDeviceController extends Controller
{
  private $_model;

  public function __construct()
  {
    parent::__construct();
    $this->_model = $this->loadModel('StatusDevice');
  }

  public function index()
  { }

  public function createStatusDevice()
  {
    $responseDB = $this->_model->insertStatusDevice($_POST);
    parent::json_response($responseDB, 201);
  }

  public function getAllStatusDevice()
  {
    $responseDB = $this->_model->getAllStatusDevice();
    parent::json_response($responseDB, 200);
  }

  public function getStatusDevice()
  {
    $responseDB = $this->_model->getStatusDevice($_POST);
    parent::json_response($responseDB, 200);
  }

  public function updateStatusDevice()
  {
    parse_str(file_get_contents('php://input'), $_PATCH);
    if ($_SERVER["REQUEST_METHOD"] === "PATCH") {
      $responseDB = $this->_model->updateStatusDevice($_PATCH);
      parent::json_response($responseDB, 200);
    }
  }

  public function deleteStatusDevice()
  {
    parse_str(file_get_contents('php://input'), $_DELETE);
    if ($_SERVER["REQUEST_METHOD"] == "DELETE") {
      $responseDB = $this->_model->deleteStatusDevice($_DELETE);
      parent::json_response($responseDB, 204);
    }
  }
}
