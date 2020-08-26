<?php

class StatusPaymentController extends Controller
{
  private $_model;

  public function __construct()
  {
    parent::__construct();
    $this->_model = $this->loadModel('StatusPayment');
  }

  public function index()
  { }

  public function createStatusPayment()
  {
    $responseDB = $this->_model->insertStatusPayment($_POST);
    parent::json_response($responseDB, 201);
  }

  public function getAllStatusPayment()
  {
    $responseDB = $this->_model->getAllStatusPayment();
    parent::json_response($responseDB, 200);
  }

  public function getStatusPayment()
  {
    $responseDB = $this->_model->getStatusPayment($_POST);
    parent::json_response($responseDB, 200);
  }

  public function updateStatusPayment()
  {
    parse_str(file_get_contents('php://input'), $_PATCH);
    if ($_SERVER["REQUEST_METHOD"] === "PATCH") {
      $responseDB = $this->_model->updateStatusPayment($_PATCH);
      parent::json_response($responseDB, 200);
    }
  }

  public function deleteStatusPayment()
  {
    parse_str(file_get_contents('php://input'), $_DELETE);
    if ($_SERVER["REQUEST_METHOD"] == "DELETE") {
      $responseDB = $this->_model->deleteStatusPayment($_DELETE);
      parent::json_response($responseDB, 204);
    }
  }
}
