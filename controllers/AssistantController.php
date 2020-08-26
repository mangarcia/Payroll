<?php

class AssistantController extends Controller
{
  private $_model;

  public function __construct()
  {
    parent::__construct();
    $this->_model = $this->loadModel('Assistant');
  }

  public function index()
  {
    $session = Session::get("TellaConnected");
    
    if($session)
    {
        $this->_view->title_ = "Asistentes";
        $this->_view->_layoutParams["Sex"] = $this->loadModel("Sex")->getAllSex()["data"];
        $this->_view->_layoutParams["DocType"] = $this->loadModel("DocType")->getAllDocType()["data"];
        $this->_view->_layoutParams["AcademicLevel"] = $this->loadModel("AcademicLevel")->getAllAcademicLevel()["data"];
        $this->_view->renderizar('assistant', 'assistant');
	}
    else { $this->redireccionar(); }
  }

  public function createAssistant()
  {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
      $authUser = parent::verfitySessionJwt();
      if ($authUser) {
        $data = $_POST;
        $payloadJwt = parent::getSesionDataJwt();
        $data['userId'] = $payloadJwt->aud;
       
        $companyId=Session::get("companyId");
        $companyName=Session::get("companyName");
        $profileImage=NULL;
        if(isset($_FILES["basicDataPhoto"]))
        {
$profileImage=$_FILES["basicDataPhoto"];
        }


         $assistantHv=NULL;
        if(isset($_FILES["professionalHVUrl"]))
        {
          $assistantHv=$_FILES["professionalHVUrl"];
        }

       if(isset($_POST["assistantId"]))
       {
        if($_POST["assistantId"]!="")
        {
           $responseDB = $this->_model->updateNewAssistant($data,$companyId,$companyName,$profileImage,$assistantHv);
        }
        else
        {
          $responseDB = $this->_model->registerNewAssistant($data,$companyId,$companyName,$profileImage,$assistantHv);
        }
       }
       else
       {
         $responseDB = $this->_model->registerNewAssistant($data,$companyId,$companyName,$profileImage,$assistantHv);
       }
        parent::json_response($responseDB, 200);
      }
    } else {
      $endJSON['status'] = 'error';
      $endJSON["data"]['title'] = "";
      $endJSON["data"]['message'] = "No exist GET";
    }
  }

public function saveImage()
{
  $saveImage=$_POST["image"];
   $this->_model->getImageUrl($saveImage,"Aliviamos");
}

public function updateAssistantAvailability()
{
   $responseDB = $this->_model->updateAssistantAvailability($_POST);
    parent::json_jwt_response($responseDB);
   /*
   try {
      if ('POST' === $_SERVER['REQUEST_METHOD']) {
        $authUser = parent::verfitySessionJwt();
        if ($authUser) {
          $payloadJwt = parent::getSesionDataJwt();
          $data['userId'] = $payloadJwt->aud;
          $responseDB = $this->_model->updateAssistantAvailability($data);
        }
      } else {
        throw new Exception('No exist POST', 400);
      }
    } catch (\Throwable $th) {
      $endJSON = parent::printError($th);
    } finally {
      $endJSON = $responseDB ?? $endJSON;
      parent::json_jwt_response($endJSON);
    }
    */
}

  public function getAssistants()
  {
   try {
      if ('POST' === $_SERVER['REQUEST_METHOD']) {
        $authUser = parent::verfitySessionJwt();
        if ($authUser) {
          $payloadJwt = parent::getSesionDataJwt();
          $data['userId'] = $payloadJwt->aud;
          $responseDB = $this->_model->getAssistants($data);
        }
      } else {
        throw new Exception('No exist POST', 400);
      }
    } catch (\Throwable $th) {
      $endJSON = parent::printError($th);
    } finally {
      $endJSON = $responseDB ?? $endJSON;
      parent::json_jwt_response($endJSON);
    }
  }

  public function updateAssistant()
  {
    if ($_SERVER['REQUEST_METHOD'] === 'GET') {
      $authUser = parent::verfityJwt();
      if ($authUser) {
        $payloadJwt = parent::getDataJwt();
        $data['userId'] = $payloadJwt->aud;
        $responseDB = $this->_model->getAddressByUser($data);
        parent::json_response($responseDB, 200);
      }
    } else {
      $endJSON['status'] = 'error';
      $endJSON["data"]['title'] = "";
      $endJSON["data"]['message'] = "No exist GET";
      parent::json_jwt_response($endJSON, 400);
    }
  }

 
}
