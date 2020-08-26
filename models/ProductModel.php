<?php
class ProductModel extends Database
{
  public function __construct()
  {
    parent::__construct();
  }


 
  public function getProducts(array $data)
  {
   
    
    $endJSON["status"] = "";
    $endJSON["data"] = "";
    try {
      if(Session::get("RoleId")==8)
      {

      }
      if ( isset($data['companyId']) ) {
        $companyId = $data['companyId'];
        $query = "SELECT * FROM Product WHERE Company_companyId = '$companyId';";
        $QueryCallback = $this->GetAll($query);
        if ($QueryCallback) {
          $endJSON["status"] = "Ok";
          $endJSON["data"] = $QueryCallback ;
        } else {
          throw new Exception("Error: SQL deleteProduct", 1);
        }
      } else {
        throw new Exception('{"Error":"No data params Product"}', 1);
      }
    } catch (Exception $e) {
      $endJSON['status'] = 'error';
      http_response_code(400);
      die($e->getMessage());
    }
    
    return $endJSON;
  }


  public function getProductType()
  {
     $endJSON["status"] = "success";
    $endJSON["data"] = "";
    $query = "SELECT * FROM TypeProduct";
    $QueryCallback = $this->GetAll($query);
    if ($QueryCallback) {
       $endJSON["status"] = "success";
      $endJSON["data"] = $QueryCallback ;
      return $endJSON;
    } else {
      throw new Exception("Error: SQL GetProductType", 1);
    }
    
  }

  public function getWebProducts(array $data)
  {
   
    
    $endJSON["status"] = "";
    $endJSON["data"] = "";
    try {
      if(Session::get("RoleId")==8)
      {
        $query = "SELECT * FROM Product";
        $QueryCallback = $this->GetAll($query);
        if ($QueryCallback) {
           $endJSON["status"] = "success";
          $endJSON["data"] = $QueryCallback ;
        } else {
          throw new Exception("Error: SQL GetProduct", 1);
        }
      }
      else if(Session::get("RoleId")==1)
      {
        $companyId=Session::get("companyId");
        $query = "SELECT * FROM Product WHERE Company_companyId = '$companyId';";
        $QueryCallback = $this->GetAll($query);
        if ($QueryCallback) {
           $endJSON["status"] = "success";
          $endJSON["data"] = $QueryCallback ;
        } else {
          throw new Exception("Error: SQL deleteProduct", 1);
        }
      }
     
    } catch (Exception $e) {
      $endJSON['status'] = 'error';
      http_response_code(400);
      die($e->getMessage());
    }
    
    return $endJSON;
  }
}
