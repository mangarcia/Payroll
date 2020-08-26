<?php

class TypeServiceController extends Controller
{
  private $_model;

  public function __construct()
  {
    parent::__construct();
    $this->_model = $this->loadModel('TypeService');
  }

  public function index()
  { }

  public function createTypeService()
  {
    $responseDB = $this->_model->insertTypeService($_POST);
    parent::json_response($responseDB, 201);
  }

  public function getAllTypeService()
  {
    $responseDB = $this->_model->getAllTypeService();
    parent::json_response($responseDB, 200);
  }

  public function getTypeService()
  {
    $responseDB = $this->_model->getTypeService($_POST);
    parent::json_response($responseDB, 200);
  }

  public function updateTypeService()
  {
    parse_str(file_get_contents('php://input'), $_PATCH);
    if ($_SERVER["REQUEST_METHOD"] === "PATCH") {
      $responseDB = $this->_model->updateTypeService($_PATCH);
      parent::json_response($responseDB, 200);
    }
  }

  public function deleteTypeService()
  {
    parse_str(file_get_contents('php://input'), $_DELETE);
    if ($_SERVER["REQUEST_METHOD"] == "DELETE") {
      $responseDB = $this->_model->deleteTypeService($_DELETE);
      parent::json_response($responseDB, 204);
    }
  }
}
