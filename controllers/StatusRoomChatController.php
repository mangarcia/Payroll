<?php

class StatusRoomChatController extends Controller
{
  private $_model;

  public function __construct()
  {
    parent::__construct();
    $this->_model = $this->loadModel('StatusRoomChat');
  }

  public function index()
  { }

  public function createStatusRoomChat()
  {
    $responseDB = $this->_model->insertStatusRoomChat($_POST);
    parent::json_response($responseDB, 201);
  }

  public function getAllStatusRoomChat()
  {
    $responseDB = $this->_model->getAllStatusRoomChat();
    parent::json_response($responseDB, 200);
  }

  public function getStatusRoomChat()
  {
    $responseDB = $this->_model->getStatusRoomChat($_POST);
    parent::json_response($responseDB, 200);
  }

  public function updateStatusRoomChat()
  {
    parse_str(file_get_contents('php://input'), $_PATCH);
    if ($_SERVER["REQUEST_METHOD"] === "PATCH") {
      $responseDB = $this->_model->updateStatusRoomChat($_PATCH);
      parent::json_response($responseDB, 200);
    }
  }

  public function deleteStatusRoomChat()
  {
    parse_str(file_get_contents('php://input'), $_DELETE);
    if ($_SERVER["REQUEST_METHOD"] == "DELETE") {
      $responseDB = $this->_model->deleteStatusRoomChat($_DELETE);
      parent::json_response($responseDB, 204);
    }
  }
}
