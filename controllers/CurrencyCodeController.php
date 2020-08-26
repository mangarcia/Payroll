<?php

class CurrencyCodeController extends Controller
{
  private $_model;

  public function __construct()
  {
    parent::__construct();
    $this->_model = $this->loadModel('CurrencyCode');
  }

  public function index()
  { }

  public function createCurrencyCode()
  {
    $responseDB = $this->_model->insertCurrencyCode($_POST);
    parent::json_response($responseDB, 201);
  }

  public function getAllCurrencyCode()
  {
    $responseDB = $this->_model->getAllCurrencyCode();
    parent::json_response($responseDB, 200);
  }

  public function getCurrencyCode()
  {
    $responseDB = $this->_model->getCurrencyCode($_POST);
    parent::json_response($responseDB, 200);
  }

  public function updateCurrencyCode()
  {
    parse_str(file_get_contents('php://input'), $_PATCH);
    if ($_SERVER["REQUEST_METHOD"] === "PATCH") {
      $responseDB = $this->_model->updateCurrencyCode($_PATCH);
      parent::json_response($responseDB, 200);
    }
  }

  public function deleteCurrencyCode()
  {
    parse_str(file_get_contents('php://input'), $_DELETE);
    if ($_SERVER["REQUEST_METHOD"] == "DELETE") {
      $responseDB = $this->_model->deleteCurrencyCode($_DELETE);
      parent::json_response($responseDB, 204);
    }
  }
}
