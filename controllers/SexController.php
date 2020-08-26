<?php

class SexController extends Controller
{
  private $_model;

  public function __construct()
  {
    parent::__construct();
    $this->_model = $this->loadModel('Sex');
  }

  public function index()
  { }

  public function createSex()
  {
    $responseDB = $this->_model->insertSex($_POST);
    parent::json_response($responseDB, 201);
  }

  public function getAllSex()
  {
    $responseDB = $this->_model->getAllSex();
    parent::json_response($responseDB, 200);
  }

  public function getSex()
  {
    $responseDB = $this->_model->getSex($_POST);
    parent::json_response($responseDB, 200);
  }

  public function updateSex()
  {
    parse_str(file_get_contents('php://input'), $_PATCH);
    if ($_SERVER["REQUEST_METHOD"] === "PATCH") {
      $responseDB = $this->_model->updateSex($_PATCH);
      parent::json_response($responseDB, 200);
    }
  }

  public function deleteSex()
  {
    parse_str(file_get_contents('php://input'), $_DELETE);
    if ($_SERVER["REQUEST_METHOD"] == "DELETE") {
      $responseDB = $this->_model->deleteSex($_DELETE);
      parent::json_response($responseDB, 204);
    }
  }
}
