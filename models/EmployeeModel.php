<?php

use \Firebase\JWT\JWT;

class EmployeeModel extends Database
{
  const table = 'Employee';
  const tableColumns = array(
    'userId' => array(
      'primaryKey' => true,
      // 'required' => true,
    ),
    'lastConnectionDateTime' => array(
      // 'primaryKey' => false,
      // 'required' => true,
    ),
    'accountEmail' => array(
      // 'primaryKey' => false,
      'required' => true,
    ),
    'accountPassword' => array(
      // 'primaryKey' => false,
      // 'required' => true,
    ),
    'accountCellphone' => array(
      // 'primaryKey' => false,
      // 'required' => true,
    ),
    'accountName' => array(
      // 'primaryKey' => false,
      'required' => true,
    ),
    'isEmailVerified' => array(
      // 'primaryKey' => false,
      // 'required' => false,
    ),
    'isNewUser' => array(
      // 'primaryKey' => false,
      // 'required' => false,
    ),
    'isOnline' => array(
      // 'primaryKey' => false,
      // 'required' => true,
    ),
    'photoURL' => array(
      // 'primaryKey' => false,
      // 'required' => true,
    ),
    'Rol_rolId' => array(
      // 'primaryKey' => false,
      'required' => true,
    ),
    'Status_statusId' => array(
      // 'primaryKey' => false,
      'required' => true,
    ),
    'tokenFacebook' => array(
      // 'primaryKey' => false,
      // 'required' => false,
    ),
    'tokenGoogle' => array(
      // 'primaryKey' => false,
      // 'required' => true,
    ),
    'createdAt' => array(
      // 'primaryKey' => false,
      'required' => true,
    ),
    'updatedAt' => array(
      // 'primaryKey' => false,
      // 'required' => true,
    ),
    'tempPasswordToken' => array(
      // 'primaryKey' => false,
      // 'required' => true,
    ),
    'tokenPasswordExpiredAt' => array(
      // 'primaryKey' => false,
      // 'required' => true,
    ),
    'Company_companyId' => array(
      // 'primaryKey' => false,
      // 'required' => true,
    )
  );

  public function __construct()
  {
    parent::__construct();
  }



  public function getAllEmployee()
  {

    $endJSON["status"] = "";
    $endJSON["data"] = "";
    try {

      // $roleId=Session::get("RoleId");
      $roleId = 8;
      if ($roleId == 8) {
        $query = "SELECT * FROM employee;";

        $SelectAllResult = $this->GetAll($query);
        if ($SelectAllResult) {
           $endJSON["status"] = "success";
        } else {
          throw new Exception("Error: SQL getAllemployee", 1);
        }

        $query = "SELECT DISTINCT User.*,'http://api.teella.com/public/img/Teella/TeellaUser.jpg' as 'companyPhotoUrl', NULL as CompanyName FROM User where Company_companyId is null ORDER BY User.accountName asc;";
        $SelectTeellaUsers = $this->GetAll($query);
        if ($SelectTeellaUsers) {
           $endJSON["status"] = "success";
        } else {
          throw new Exception("Error: SQL getAllemployee", 1);
        }
        $endJSON["data"] = $SelectAllResult;
       
      } else if ($roleId == 1) {
        $companyId = Session::get("companyId");
        $query = "SELECT DISTINCT User.*,Company.imageUrl as 'companyPhotoUrl',Company.name as CompanyName FROM User,Company where Company.companyId=User.Company_companyId and Company.companyId='$companyId' ORDER BY CompanyName asc;";
        $SelectAllResult = $this->GetAll($query);
        if ($SelectAllResult) {
           $endJSON["status"] = "success";
        } else {
          throw new Exception("Error: SQL Get Users", 1);
        }

        $endJSON["data"] = $SelectAllResult;
       
      } else {
        $endJSON['status'] = 'error';
        $endJSON["message"] = "error";
      }
    } catch (Exception $e) {
      http_response_code(400);
      $endJSON['status'] = 'error';
      
      die($e->getMessage());
    }
    return $endJSON;
  }


  
  public function updateEmployee(array $data)
  {
    $endJSON["status"] = "";
    $endJSON["data"] = "";

    try {

      $QueryCallback = "update Employee set ";
      if (isset($data['EmployeName'])) {
        $QueryCallback .= "EmployeName='" . $data['EmployeName'] . "',";
      }

      if (isset($data['EmployeeLastName'])) {
        $QueryCallback .= "EmployeeLastName='" . $data['EmployeeLastName'] . "',";
      }

      if (isset($data['EmployeePhoneNumber'])) {
        $QueryCallback .= "EmployeePhoneNumber='" . $data['EmployeePhoneNumber'] . "',";
      }

      if (isset($data['DocumentType_idDocumentType'])) {
        $QueryCallback .= "DocumentType_idDocumentType='" . $data['DocumentType_idDocumentType'] . "',";
      }

      if (isset($data['EmployeeStatus_idEmployeeStatus'])) {
        $QueryCallback .= "EmployeeStatus_idEmployeeStatus='" . $data['EmployeeStatus_idEmployeeStatus'] . "',";
      }

      if (isset($data['EmployeeDocNumber'])) {
        $QueryCallback .= "EmployeeDocNumber='" . $data['EmployeeDocNumber'] . "',";
      }
      



      $QueryCallback = rtrim($QueryCallback, ",");

      $QueryCallback .= " where idEmployee ='" . $data['idEmployee'] . "'";

      //echo $QueryCallback;

      $QueryCallback = $this->ExecuteSql($QueryCallback);

      if ($QueryCallback) {
         $endJSON["status"] = "success";
        $endJSON["message"] = "Employee update ok";
      } else {
        throw new Exception('{"Error":"SQL udpateemployee"}', 1);
      }
    } catch (\Throwable $th) {
      $message = $th->getMessage();
      if (strpos($message, 'Duplicate entry') !== false) {
        $endJSON['status'] = 'error';
        $endJSON['message'] = 'That username or number cellphone is already in use. Try another one.';
      } elseif (strpos($message, 'Data too long for column') !== false) {
        $endJSON['status'] = 'error';
        $endJSON['message'] = 'That number cellphone is error.';
      } else {
        $endJSON['status'] = 'error';
        $endJSON['message'] = $message;
      }
    }

    return $endJSON;
  }
  public function registerEmployee(array $data)
  {
    //echo json_encode($data);
    try {
      $dataSQL = array();
      if (
        isset($data['EmployeName']) &&
        isset($data['EmployeeLastName'])
      ) {
        $EmployeName   = $data['EmployeName'];
        $EmployeeLastName       = $data['EmployeeLastName'];
        $EmployeePhoneNumber   = $data['EmployeePhoneNumber'];
        $EmployeeDocNumber   = $data['EmployeeDocNumber'];
        $DocumentType_idDocumentType   = $data['DocumentType_idDocumentType'];
        $EmployeeStatus_idEmployeeStatus    = $data['EmployeeStatus_idEmployeeStatus'];
        $SystemUserIdCreated    = Session::get('idSystemUser');



        $EmployeeCreatedDate     = date("y-m-d");


        $query = "insert into employee (EmployeName,EmployeeLastName,EmployeePhoneNumber,DocumentType_idDocumentType,EmployeeStatus_idEmployeeStatus,EmployeeCreatedDate,SystemUserIdCreated,EmployeeDocNumber  ) values ('$EmployeName','$EmployeeLastName','$EmployeePhoneNumber',$DocumentType_idDocumentType ,'$EmployeeStatus_idEmployeeStatus ','$EmployeeCreatedDate','$SystemUserIdCreated','$EmployeeDocNumber')";

        $QueryCallback = $this->ExecuteSql($query);
        if (!$QueryCallback) {
          $endJSON['status'] = 'error';
          $endJSON['message'] = 'The user cant be created';
        } else {
          $endJSON['status'] = 'success';
          $endJSON['message'] = 'Employee Created';
        }
      }
    } catch (\Throwable $th) {
      $message = $th->getMessage();
      if (strpos($message, 'Duplicate entry') !== false) {
        $endJSON['status'] = 'error';
        $endJSON['message'] = 'That username or number cellphone is already in use. Try another one.';
      } elseif (strpos($message, 'Data too long for column') !== false) {
        $endJSON['status'] = 'error';
        $endJSON['message'] = 'That number cellphone is error.';
      } else {
        $endJSON['status'] = 'error';
        $endJSON['message'] = $message;
      }
    }

    return $endJSON;
  }

  public function getAllEmployeeStatus()
  {

    $endJSON["status"] = "";
    $endJSON["data"] = "";
    try {

      $roleId = 8;
      if ($roleId == 8) {
        $query = "SELECT * FROM employeestatus;";

        $SelectAllResult = $this->GetAll($query);
        if ($SelectAllResult) {
           $endJSON["status"] = "success";
        } else {
          throw new Exception("Error: SQL getAllStatusEmployee", 1);
        }


        $endJSON["data"] = $SelectAllResult;
       
      }
    } catch (Exception $e) {
      http_response_code(400);
      $endJSON['status'] = 'error';
      die($e->getMessage());
    }
    return $endJSON;
  }



  public function getAllEmployeeDocument()
  {

    $endJSON["status"] = "";
    $endJSON["data"] = "";
    try {

      $roleId = 8;
      if ($roleId == 8) {
        $query = "SELECT * FROM documenttype;";

        $SelectAllResult = $this->GetAll($query);
        if ($SelectAllResult) {
           $endJSON["status"] = "success";
        } else {
          throw new Exception("Error: SQL documenttype", 1);
        }


        $endJSON["data"] = $SelectAllResult;
      }
    } catch (Exception $e) {
      http_response_code(400);
      $endJSON['status'] = 'error';
      die($e->getMessage());
    }
    return $endJSON;
  }
}
