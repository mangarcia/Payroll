<?php

class ProductController extends Controller
{
  private $_model;

  public function __construct()
  {
    parent::__construct();
    $this->_model = $this->loadModel('Product');
  }

  public function index()
  { 
    $session = Session::get("TellaConnected");

    if($session)
    {
      if(Session::get("RoleId")==8 || Session::get("RoleId")==1)
      {
        if(Session::get("RoleId")==1 && Session::get("companyType")==2)
        {
          $this->_view->title_ = "Products";
          $this->_view->renderizar('products', 'products');
        }
        else if(Session::get("RoleId")==8 )
        {
          $this->_view->title_ = "Products";
          $this->_view->renderizar('products', 'products');
        }
        else
        {
          $this->redireccionar();
        }
       
      }
      else
      {
        $this->redireccionar();
      }
       
	}
    else { $this->redireccionar(); }
     
  }

  public function getProducts()
  {
    if(Session::get("RoleId")==8 || Session::get("RoleId")==1 )
    {
      $responseDB = $this->_model->getWebProducts($_POST);
      parent::json_response($responseDB, 201);
    }
    else
    {
      $responseDB = $this->_model->getProducts($_POST);
      parent::json_response($responseDB, 201);
    }
    
  }

  public function getProductType()
  {
    $responseDB = $this->_model->getProductType();
    parent::json_response($responseDB, 201);
  }

public function AssignDays()
{
  date_default_timezone_set('America/Bogota');
  $currDay= date("d");
  $currMonth=date("m");
  $currYear=date("Y");
  $daysInMonth= cal_days_in_month(CAL_GREGORIAN,$currMonth,$currYear);

  for ($i = $currDay; $i <= $daysInMonth; $i++)
   {
    $currDate=$i."-".$currMonth."-".$currYear;
    $currentDatePicked=date_create($currDate);
    var_dump ($currentDatePicked);

    }

}
  public function updateProduct()
  {
    if(Session::get("RoleId")==8 || Session::get("RoleId")==1 )
    {
      if(Session::get("companyType")==2 && Session::get("RoleId")==1)
      {
        $responseDB = $this->_model->updateProduct($_POST);
        parent::json_response($responseDB, 201);
      }
      else if(Session::get("RoleId")==8)
      {
        $responseDB = $this->_model->updateProduct($_POST);
        parent::json_response($responseDB, 201);
      }
      else
      {
        $endJson["status"]="error";
        $endJson["data"]="Invalid Company Permissions";
        parent::json_response($endJson, 201);
      }
      
    }
    else
    {
      $endJson["status"]="error";
      $endJson["data"]="Invalid Token";
      parent::json_response($endJson, 201);
    }
  }

  public function createProduct()
  {
    if(Session::get("RoleId")==8 || Session::get("RoleId")==1 )
    {
      $responseDB = $this->_model->createProduct($_POST);
      parent::json_response($responseDB, 201);
    }
    else
    {
      $endJson["status"]="error";
      $endJson["data"]="Invalid Token";
      parent::json_response($endJson, 201);
    }
  }


  
}