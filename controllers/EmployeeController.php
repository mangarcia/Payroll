<?php



class EmployeeController extends Controller
{
  private $_model;



  public function __construct()
  {
    parent::__construct();
    $this->_model = $this->loadModel('Employee');
  }

  public function index()
  {
    $session = Session::get("TellaConnected");

   /// if($session)
  //  {
      $this->_view->title_ = "Employee";
        $this->_view->renderizar('employee', 'employee');
  
	/* }
    else { 

      $this->redireccionar();

       }*/
  
  }

  
  



  public function loadEmployeesFromCsv()
  {
    
 
   
  }

  public function getAllEmployee()
  {
    $responseDB = $this->_model->getAllEmployee();
         parent::json_response($responseDB, 200);
 
   
  }

  
  public function CreateEmployee()
  {
    if ('POST' === $_SERVER['REQUEST_METHOD']) {
      $data = $_POST;
      //echo json_encode($data);
      if(isset($data["idEmployee"]) && $data["idEmployee"]!="")
      {
          $responseDB = $this->_model->updateEmployee($data);
      }
      else
      {       
        
        $responseDB = $this->_model->registerEmployee($data);
      }
      
     
      parent::json_jwt_response($responseDB, 200);
    } else {
      $endJSON['status'] = 'error';
      $endJSON['data']['title'] = '';
      $endJSON['data']['message'] = 'No exist GET';
      parent::json_jwt_response($endJSON, 400);
    } 
   
  }

  public function getAllEmployeeStatus()
  {
    $responseDB = $this->_model->getAllEmployeeStatus();
         parent::json_response($responseDB, 200);
    /* if(Session::get("RoleId")==8 || Session::get("RoleId")==1)
      {
         $responseDB = $this->_model->getAllUser();
         parent::json_response($responseDB, 200);
      }
      else
      {
      //    $endJSON["status"]="Error";
       //   $endJSON["message"]="invalid User Token Authentication";
        //  echo json_encode($endJSON);
      }*/
   
    }

    public function getAllEmployeeDocument()
    {
      $responseDB = $this->_model->getAllEmployeeDocument();
           parent::json_response($responseDB, 200);
      /* if(Session::get("RoleId")==8 || Session::get("RoleId")==1)
        {
           $responseDB = $this->_model->getAllUser();
           parent::json_response($responseDB, 200);
        }
        else
        {
        //    $endJSON["status"]="Error";
         //   $endJSON["message"]="invalid User Token Authentication";
          //  echo json_encode($endJSON);
        }*/
     
      }
  
}
