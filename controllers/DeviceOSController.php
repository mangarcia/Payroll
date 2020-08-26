<?php

class DeviceOSController extends Controller
{
  private $_model;

  public function __construct()
  {
    parent::__construct();
    $this->_model = $this->loadModel('DeviceOS');
  }

  public function index()
  { }

  public function createDeviceOS()
  {
    $responseDB = $this->_model->insertDeviceOS($_POST);
    parent::json_response($responseDB, 201);
  }

  public function getAllDeviceOS()
  {
    $responseDB = $this->_model->getAllDeviceOS();
    parent::json_response($responseDB, 200);
  }

  public function getDeviceOS()
  {
    $responseDB = $this->_model->getDeviceOS($_POST);
    parent::json_response($responseDB, 200);
  }

  public function updateDeviceOS()
  {
    parse_str(file_get_contents('php://input'), $_PATCH);
    if ($_SERVER["REQUEST_METHOD"] === "PATCH") {
      $responseDB = $this->_model->updateDeviceOS($_PATCH);
      parent::json_response($responseDB, 200);
    }
  }

  public function deleteDeviceOS()
  {
    parse_str(file_get_contents('php://input'), $_DELETE);
    if ($_SERVER["REQUEST_METHOD"] == "DELETE") {
      $responseDB = $this->_model->deleteDeviceOS($_DELETE);
      parent::json_response($responseDB, 204);
    }
  }
}
