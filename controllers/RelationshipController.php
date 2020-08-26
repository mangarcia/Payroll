<?php

class RelationshipController extends Controller
{
  private $_model;

  public function __construct()
  {
    parent::__construct();
    $this->_model = $this->loadModel('Relationship');
  }

  public function index()
  { }

  public function createRelationship()
  {
    $responseDB = $this->_model->insertRelationship($_POST);
    parent::json_response($responseDB, 201);
  }

  public function getAllRelationship()
  {
    $responseDB = $this->_model->getAllRelationship();
    parent::json_response($responseDB, 200);
  }

  public function getRelationship()
  {
    $responseDB = $this->_model->getRelationship($_POST);
    parent::json_response($responseDB, 200);
  }

  public function updateRelationship()
  {
    parse_str(file_get_contents('php://input'), $_PATCH);
    if ($_SERVER["REQUEST_METHOD"] === "PATCH") {
      $responseDB = $this->_model->updateRelationship($_PATCH);
      parent::json_response($responseDB, 200);
    }
  }

  public function deleteRelationship()
  {
    parse_str(file_get_contents('php://input'), $_DELETE);
    if ($_SERVER["REQUEST_METHOD"] == "DELETE") {
      $responseDB = $this->_model->deleteRelationship($_DELETE);
      parent::json_response($responseDB, 204);
    }
  }
}
