<?php

class AcademicLevelController extends Controller
{
  private $_model;

  public function __construct()
  {
    parent::__construct();
    $this->_model = $this->loadModel('AcademicLevel');
  }

  public function index()
  { }

  public function createAcademicLevel()
  {
    $responseDB = $this->_model->insertAcademicLevel($_POST);
    parent::json_response($responseDB, 201);
  }

  public function getAllAcademicLevel()
  {
    $responseDB = $this->_model->getAllAcademicLevel();
    parent::json_response($responseDB, 200);
  }

  public function getAcademicLevel()
  {
    $responseDB = $this->_model->getAcademicLevel($_POST);
    parent::json_response($responseDB, 200);
  }

  public function updateAcademicLevel()
  {
    parse_str(file_get_contents('php://input'), $_PATCH);
    if ($_SERVER["REQUEST_METHOD"] === "PATCH") {
      $responseDB = $this->_model->updateAcademicLevel($_PATCH);
      parent::json_response($responseDB, 200);
    }
  }

  public function deleteAcademicLevel()
  {
    parse_str(file_get_contents('php://input'), $_DELETE);
    if ($_SERVER["REQUEST_METHOD"] == "DELETE") {
      $responseDB = $this->_model->deleteAcademicLevel($_DELETE);
      parent::json_response($responseDB, 204);
    }
  }
}
