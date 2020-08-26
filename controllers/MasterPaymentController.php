<?php

class MasterPaymentController extends Controller
{
  private $_model;

  public function __construct()
  {
    parent::__construct();
    $this->_model = $this->loadModel('MasterPayment');
  }

  public function index()
  { 
    $session = Session::get("TellaConnected");

   /* if($session)
    {
      if(Session::get("RoleId")==8)
      {*/

        $this->_view->title_ = "Master Payment";
        $this->_view->renderizar('masterpayment', 'masterpayment');
     /* }
      else
      {
        $this->redireccionar();
      }
       
	}
    else { $this->redireccionar(); }
  */
  }

 
  public function getAllMasterPayment()
  {

    error_reporting(0);
    
    $responseDB = $this->_model->getAllMasterPayment($_POST);
    echo json_encode($responseDB);

    /*
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
      $authUser = parent::verfitySessionJwt();
      if ($authUser) {
        $data = $_POST;
        $payloadJwt = parent::getSesionDataJwt();
        $data['userId'] = $payloadJwt->aud;
       
        if(Session::get("RolId")==8)
        {
          $responseDB = $this->_model->getCompanyTypes();
        }
      }
      else
      {
        $responseDB["status"]="Error";
        $responseDB["data"]="Invalid Auth Token";
      }
   
    parent::json_response($responseDB, 201);
  }
  else {
      $endJSON['status'] = 'error';
      $endJSON["data"] = "Invalid method";
      parent::json_response($endJSON, 201);
    }
*/

  }

  public function createMasterPayment()
  {
    $responseDB = $this->_model->createMasterPayment($_POST);
    parent::json_response($responseDB, 201);

    /*
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
      $authUser = parent::verfitySessionJwt();
      if ($authUser) {
        $data = $_POST;
        $payloadJwt = parent::getSesionDataJwt();
        $data['userId'] = $payloadJwt->aud;
       
        $companyId=Session::get("idCompany");
        $companyName=Session::get("companyName");
        $profileImage=NULL;
        if(isset($_POST['idCompany']) && $_POST['idCompany']!="")
        {
          $responseDB = $this->_model->updateCompany($_POST); 
        }
        else
        {
           $responseDB = $this->_model->insertCompany($_POST);
        }
       
      }
   
  parent::json_response($responseDB, 201);
  }
  else {
      $endJSON['status'] = 'error';
      $endJSON["data"] = "Invalid method";

    }

    */
}


  public function getAllCompany()
  {
    $responseDB = $this->_model->getAllCompany();
    parent::json_response($responseDB, 200);
  }

  public function getCompany()
  {
    $responseDB = $this->_model->getCompany($_POST);
    parent::json_response($responseDB, 200);
  }

   public function getCompanyStatus()
  {
    $responseDB = $this->_model->getCompanyStatus();
    parent::json_response($responseDB, 200);
  }

  public function updateCompany()
  {
    parse_str(file_get_contents('php://input'), $_POST);
    if ($_SERVER["REQUEST_METHOD"] === "POST") {
      $responseDB = $this->_model->updateCompany($_POST);
      parent::json_response($responseDB, 200);
    }
  }

  public function getCities()
  {
       if ($_SERVER['REQUEST_METHOD'] === 'POST') {
      $authUser = parent::verfitySessionJwt();
      if ($authUser) {
        $data = $_POST;

        $data['companyId'] =Session::get("companyId");

        $responseDB = $this->_model->getCompanyLocations($data);
        parent::json_response($responseDB, 200);
      }
    } else {
      $endJSON['status'] = 'error';
      $endJSON["data"]['title'] = "";
      $endJSON["data"]['message'] = "No exist GET";
    }

  }
  public function deleteCompany()
  {
    parse_str(file_get_contents('php://input'), $_DELETE);
    if ($_SERVER["REQUEST_METHOD"] == "DELETE") {
      $responseDB = $this->_model->deleteCompany($_DELETE);
      parent::json_response($responseDB, 204);
    }
  }
}
