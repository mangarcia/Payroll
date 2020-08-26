<?php

class ServiceController extends Controller
{
  private $_model;

  public function __construct()
  {
    parent::__construct();
    $this->_model = $this->loadModel('Service');
  }

  public function index()
  {
    $session = Session::get("TellaConnected");
    
    if($session)
    {
        $data['companyId'] = Session::get("companyId");

        $this->_view->title_ = "Servicios";
        $this->_view->_layoutParams["Plans"] = $this->loadModel("Plan")->readPlans($data)["data"];
        $this->_view->renderizar('service', 'service');
	}
    else { $this->redireccionar(); }
  }

  public function createService()
  {
    $responseDB = $this->_model->insertService($_POST);
    parent::json_response($responseDB, 201);
  }

  public function getAllService()
  {
    $responseDB = $this->_model->getAllService();
    parent::json_response($responseDB, 200);
  }

  public function getServicePrices()
  {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
      $data = $_POST;
      $responseDB = $this->_model->getServiceByCondition($data);
      parent::json_response($responseDB, 200);
    } else {
      $endJSON['status'] = 'error';
      $endJSON["data"]['title'] = "";
      $endJSON["data"]['message'] = "No exist POST";
      parent::json_jwt_response($endJSON, 400);
    }
  }

  public function getService()
  {
    $responseDB = $this->_model->getService($_POST);
    parent::json_response($responseDB, 200);
  }

  public function updateService()
  {
    parse_str(file_get_contents('php://input'), $_PATCH);
    if ($_SERVER["REQUEST_METHOD"] === "PATCH") {
      $responseDB = $this->_model->updateService($_PATCH);
      parent::json_response($responseDB, 200);
    }
  }

  public function deleteService()
  {
    parse_str(file_get_contents('php://input'), $_DELETE);
    if ($_SERVER["REQUEST_METHOD"] == "DELETE") {
      $responseDB = $this->_model->deleteService($_DELETE);
      parent::json_response($responseDB, 204);
    }
  }
}
