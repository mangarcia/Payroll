<?php

class CompanyLocationController extends Controller
{
  private $_model;

  public function __construct()
  {
    parent::__construct();
    $this->_model = $this->loadModel('CompanyLocation');
  }

  public function index()
  { 
   
  }

 
  public function getCompanyLocationByCompanyId()
  {

    error_reporting(0);
    
    $responseDB = $this->_model->getCompanyLocationByCompanyId();
    echo json_encode($responseDB);
  }

  


  public function createCompanyLocation()
  {
   
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
}


  public function deleteCompanyLocations()
  {
    $responseDB = $this->_model->getAllCompany();
    parent::json_response($responseDB, 200);
  }

  
}
