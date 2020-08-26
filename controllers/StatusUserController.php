<?php

class StatusUserController extends Controller
{
  private $_model;

  public function __construct()
  {
    parent::__construct();
    $this->_model = $this->loadModel('StatusUser');
  }

  public function index()
  { }

  public function createStatusUser()
  {
    $responseDB = $this->_model->insertStatusUser($_POST);
    parent::json_response($responseDB, 201);
  }

  public function getAllStatusUser()
  {
    $responseDB = $this->_model->getAllStatusUser();
    parent::json_response($responseDB, 200);
  }

  public function getStatusUser()
  {
    $responseDB = $this->_model->getStatusUser($_POST);
    parent::json_response($responseDB, 200);
  }

  public function updateStatusUser()
  {
    parse_str(file_get_contents('php://input'), $_PATCH);
    if ($_SERVER["REQUEST_METHOD"] === "PATCH") {
      $responseDB = $this->_model->updateStatusUser($_PATCH);
      parent::json_response($responseDB, 200);
    }
  }

  public function deleteStatusUser()
  {
    parse_str(file_get_contents('php://input'), $_DELETE);
    if ($_SERVER["REQUEST_METHOD"] == "DELETE") {
      $responseDB = $this->_model->deleteStatusUser($_DELETE);
      parent::json_response($responseDB, 204);
    }
  }
}
