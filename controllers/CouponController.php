<?php

class CouponController extends Controller
{
  private $_model;

  public function __construct()
  {
    parent::__construct();
    $this->_model = $this->loadModel('Coupon');
  }

  public function index()
  { }

  public function createCoupon()
  {
    $responseDB = $this->_model->addNewCoupon($_POST);
    parent::json_jwt_response($responseDB, 200);
  }

  public function loginCoupon()
  {
    $responseDB = $this->_model->loginCoupon($_POST);
    parent::json_jwt_response($responseDB, 200);
  }

  public function getAllCoupon()
  {
    $responseDB = $this->_model->getAllCoupon();
    parent::json_response($responseDB, 200);
  }

  public function getCoupon()
  {
    $responseDB = $this->_model->getCoupon($_POST);
    parent::json_response($responseDB, 200);
  }

  public function updateCoupon()
  {
    parse_str(file_get_contents('php://input'), $_PATCH);
    if ($_SERVER["REQUEST_METHOD"] === "PATCH") {
      $responseDB = $this->_model->updateCoupon($_PATCH);
      parent::json_response($responseDB, 200);
    }
  }

  public function deleteCoupon()
  {
    parse_str(file_get_contents('php://input'), $_DELETE);
    if ($_SERVER["REQUEST_METHOD"] == "DELETE") {
      $responseDB = $this->_model->deleteCoupon($_DELETE);
      parent::json_response($responseDB, 204);
    }
  }
}
