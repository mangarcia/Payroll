<?php

class CompanyController extends Controller
{
    private $_model;

    public function __construct()
    {
        parent::__construct();
        $this->_model = $this->loadModel('Company');
    }

    public function index()
    {
        $session = Session::get("TellaConnected");
        if ($session) {
            if (Session::get("RoleId") == 1) {

                $this->_view->title_ = "Compañias";
                $this->_view->renderizar('company', 'company');
            } else {
                $this->redireccionar();
            }

        } else { $this->redireccionar();}

    }

    public function getcompanyammount()
    {
        $responseDB = $this->_model->getcompanyammount($_POST);
        parent::json_response($responseDB, 200);
    }
    public function getCompanyTypes()
    {
        error_reporting(0);

        $authUser = parent::verfitySessionJwt();
        if ($authUser) {
            $data = $_POST;
            $payloadJwt = parent::getSesionDataJwt();
            $data['userId'] = $payloadJwt->aud;

            if (Session::get("RoleId") == 1) {
                $responseDB = $this->_model->getCompanyTypes();
            } else {
                $responseDB["status"] = "Error";
                $responseDB["data"] = "Invalid Token Perms";
            }
        } else {
            $responseDB["status"] = "Error";
            $responseDB["data"] = "Invalid Auth Token";
        }

        parent::json_response($responseDB, 201);
    }

    public function createCompany()
    {

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $authUser = parent::verfitySessionJwt();
            if ($authUser) {
                $data = $_POST;
                $payloadJwt = parent::getSesionDataJwt();
                $data['userId'] = $payloadJwt->aud;

                $profileImage = null;
                if (isset($_POST['idCompany']) && $_POST['idCompany'] != "") {
                    $responseDB = $this->_model->updateCompany($_POST);
                } else {
                    $responseDB = $this->_model->insertCompany($_POST);
                }
            }
        } else {
            $responseDB['status'] = 'error';
            $responseDB["data"] = "Invalid method";

        }
        parent::json_response($responseDB, 201);
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

    public function getCompanyLocationsStatus()
    {
  
      error_reporting(0);
      
      $responseDB = $this->_model->getCompanyLocationsStatus();
      echo json_encode($responseDB);
    }

    
    public function getCompanyLocations()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $authUser = parent::verfitySessionJwt();
            if ($authUser) {
                $data = $_POST;
                $responseDB = $this->_model->getCompanyLocations($data);
            }
        } else {
            $responseDB['status'] = 'error';
            $responseDB["data"] = "Invalid Method";
        }
        parent::json_response($responseDB, 200);
    }

    public function UpdateCompanyLocations()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $authUser = parent::verfitySessionJwt();
            if ($authUser) {
                $data = $_POST;
                $responseDB = $this->_model->UpdateCompanyLocations($data);
            }
        } else {
            $responseDB['status'] = 'error';
            $responseDB["data"] = "Invalid Method";
        }
        parent::json_response($responseDB, 200);
    }

    public function CreateCompanyLocations()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $authUser = parent::verfitySessionJwt();
            if ($authUser) {
                $data = parent::CleanRequestData($_POST);

                if (isset($_POST['idCompanyLocation']) && $_POST['idCompanyLocation'] != "") {
                    $responseDB = $this->_model->UpdateCompanyLocations($data);
                } else {
                    $responseDB = $this->_model->CreateCompanyLocations($data);
                  
                }

               
            }
        } else {
            $responseDB['status'] = 'error';
            $responseDB["data"] = "Invalid Method";
        }
        parent::json_response($responseDB, 200);
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
