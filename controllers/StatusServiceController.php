<?php

class StatusServiceController extends Controller
{
  private $_model;

  public function __construct()
  {
    parent::__construct();
    $this->_model = $this->loadModel('StatusService');
  }

  public function index()
  { }

  public function createStatusService()
  {
    $responseDB = $this->_model->insertStatusService($_POST);
    parent::json_response($responseDB, 201);
  }

  public function getAllStatusService()
  {
    $responseDB = $this->_model->getAllStatusService();
    parent::json_response($responseDB, 200);
  }

  public function getStatusService()
  {
    $responseDB = $this->_model->getStatusService($_POST);
    parent::json_response($responseDB, 200);
  }

  public function updateStatusService()
  {
    parse_str(file_get_contents('php://input'), $_PATCH);
    if ($_SERVER["REQUEST_METHOD"] === "PATCH") {
      $responseDB = $this->_model->updateStatusService($_PATCH);
      parent::json_response($responseDB, 200);
    }
  }

  public function deleteStatusService()
  {
    parse_str(file_get_contents('php://input'), $_DELETE);
    if ($_SERVER["REQUEST_METHOD"] == "DELETE") {
      $responseDB = $this->_model->deleteStatusService($_DELETE);
      parent::json_response($responseDB, 204);
    }
  }
}
