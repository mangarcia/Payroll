<?php

class StatusCouponController extends Controller
{
  private $_model;

  public function __construct()
  {
    parent::__construct();
    $this->_model = $this->loadModel('StatusCoupon');
  }

  public function index()
  { }

  public function createStatusCoupon()
  {
    $responseDB = $this->_model->insertStatusCoupon($_POST);
    parent::json_response($responseDB, 201);
  }

  public function getAllStatusCoupon()
  {
    $responseDB = $this->_model->getAllStatusCoupon();
    parent::json_response($responseDB, 200);
  }

  public function getStatusCoupon()
  {
    $responseDB = $this->_model->getStatusCoupon($_POST);
    parent::json_response($responseDB, 200);
  }

  public function updateStatusCoupon()
  {
    parse_str(file_get_contents('php://input'), $_PATCH);
    if ($_SERVER["REQUEST_METHOD"] === "PATCH") {
      $responseDB = $this->_model->updateStatusCoupon($_PATCH);
      parent::json_response($responseDB, 200);
    }
  }

  public function deleteStatusCoupon()
  {
    parse_str(file_get_contents('php://input'), $_DELETE);
    if ($_SERVER["REQUEST_METHOD"] == "DELETE") {
      $responseDB = $this->_model->deleteStatusCoupon($_DELETE);
      parent::json_response($responseDB, 204);
    }
  }
}
