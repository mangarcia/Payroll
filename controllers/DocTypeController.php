<?php

class DocTypeController extends Controller
{
  private $_model;

  public function __construct()
  {
    parent::__construct();
    $this->_model = $this->loadModel('DocType');
  }

  public function index()
  { }

  public function createDocType()
  {
    $responseDB = $this->_model->insertDocType($_POST);
    parent::json_response($responseDB, 201);
  }

  public function getAllDocType()
  {
    $responseDB = $this->_model->getAllDocType();
    parent::json_response($responseDB, 200);
  }

  public function getDocType()
  {
    $responseDB = $this->_model->getDocType($_POST);
    parent::json_response($responseDB, 200);
  }

  public function updateDocType()
  {
    parse_str(file_get_contents('php://input'), $_PATCH);
    if ($_SERVER["REQUEST_METHOD"] === "PATCH") {
      $responseDB = $this->_model->updateDocType($_PATCH);
      parent::json_response($responseDB, 200);
    }
  }

  public function deleteDocType()
  {
    parse_str(file_get_contents('php://input'), $_DELETE);
    if ($_SERVER["REQUEST_METHOD"] == "DELETE") {
      $responseDB = $this->_model->deleteDocType($_DELETE);
      parent::json_response($responseDB, 204);
    }
  }
}
