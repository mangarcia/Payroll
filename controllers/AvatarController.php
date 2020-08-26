<?php

class AvatarController extends Controller
{
  private $_model;

  public function __construct()
  {
    parent::__construct();
    $this->_model = $this->loadModel('Avatar');
  }

  public function index()
  { }

  public function createAvatar()
  {
    $responseDB = $this->_model->insertAvatar($_POST);
    parent::json_response($responseDB, 201);
  }

  public function getAllAvatar()
  {
    $responseDB = $this->_model->getAllAvatar();
    parent::json_response($responseDB, 200);
  }

  public function getAvatar()
  {
    $responseDB = $this->_model->getAvatar($_POST);
    parent::json_response($responseDB, 200);
  }

  public function updateAvatar()
  {
    parse_str(file_get_contents('php://input'), $_PATCH);
    if ($_SERVER["REQUEST_METHOD"] === "PATCH") {
      $responseDB = $this->_model->updateAvatar($_PATCH);
      parent::json_response($responseDB, 200);
    }
  }

  public function deleteAvatar()
  {
    parse_str(file_get_contents('php://input'), $_DELETE);
    if ($_SERVER["REQUEST_METHOD"] == "DELETE") {
      $responseDB = $this->_model->deleteAvatar($_DELETE);
      parent::json_response($responseDB, 204);
    }
  }
}
