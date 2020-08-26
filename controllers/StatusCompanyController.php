<?php

class StatusCompanyController extends Controller
{
  private $_model;

  public function __construct()
  {
    parent::__construct();
    $this->_model = $this->loadModel('StatusCompany');
  }

  public function index()
  { }

  public function createStatusCompany()
  {
    $responseDB = $this->_model->insertStatusCompany($_POST);
    parent::json_response($responseDB, 201);
  }

  public function getAllStatusCompany()
  {
    $responseDB = $this->_model->getAllStatusCompany();
    parent::json_response($responseDB, 200);
  }

  public function getStatusCompany()
  {
    $responseDB = $this->_model->getStatusCompany($_POST);
    parent::json_response($responseDB, 200);
  }

  public function updateStatusCompany()
  {
    parse_str(file_get_contents('php://input'), $_PATCH);
    if ($_SERVER["REQUEST_METHOD"] === "PATCH") {
      $responseDB = $this->_model->updateStatusCompany($_PATCH);
      parent::json_response($responseDB, 200);
    }
  }

  public function deleteStatusCompany()
  {
    parse_str(file_get_contents('php://input'), $_DELETE);
    if ($_SERVER["REQUEST_METHOD"] == "DELETE") {
      $responseDB = $this->_model->deleteStatusCompany($_DELETE);
      parent::json_response($responseDB, 204);
    }
  }
}
