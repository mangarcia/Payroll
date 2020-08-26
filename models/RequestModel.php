<?php

use \Firebase\JWT\JWT;

class RequestModel extends Database
{
  const table = 'Request';
  const tableColumns = array(
    'requestId' => array(
      'primaryKey' => true,
      // 'required' => true,
    ),
    'StatusService_serviceStatusId' => array(
      // 'primaryKey' => false,
      'required' => true,
    ),
    'Family_userServiceId' => array(
      // 'primaryKey' => false,
      'required' => true,
    ),
    'Address_addressId' => array(
      // 'primaryKey' => false,
      // 'required' => true,
    ),
    'Service_serviceId' => array(
      // 'primaryKey' => false,
      'required' => true,
    ),
    'requestDateTime' => array(
      // 'primaryKey' => false,
      // 'required' => true,
    ),
    'serviceDateTimeEnd' => array(
      // 'primaryKey' => false,
      // 'required' => false,
    ),
    'serviceDuration' => array(
      // 'primaryKey' => false,
      // 'required' => false,
    ),
    'serviceTotalAmount' => array(
      // 'primaryKey' => false,
      // 'required' => true,
    ),
    'CurrencyCode_serviceTotalAmountCurrencyCode' => array(
      // 'primaryKey' => false,
      'required' => true,
    ),
    'serviceDateTimeStart' => array(
      // 'primaryKey' => false,
      // 'required' => false,
    ),
    'userRequestObservations' => array(
      // 'primaryKey' => false,
      // 'required' => true,
    ),
    'temp_address' => array(
      // 'primaryKey' => false,
      // 'required' => false,
    ),
    'temp_addressInfo' => array(
      // 'primaryKey' => false,
      // 'required' => true,
    ),
    'TransactionPaymentGateway_transactionPaymentGatewayId' => array(
      // 'primaryKey' => false,
      // 'required' => true,
    )
  );

  public function __construct()
  {
    parent::__construct();
  }

  public function createRequest(array $data)
  {
   
    $QueryCallback="INSERT INTO `Request` (`requestId`,
     `StatusService_serviceStatusId`,
      `Family_userServiceId`, `Service_serviceId`, 
      `Address_addressId`, `requestDateTime`, `serviceDateTimeStart`,
       `serviceDateTimeEnd`, `serviceMeridian`, `serviceTotalAmount`, 
       `CurrencyCode_serviceTotalAmountCurrencyCode`, `userRequestObservations`, 
       `temp_address`, `temp_addressInfo`, 
       `TransactionPaymentGateway_transactionPaymentGatewayId`) 
       VALUES (NULL, '".$data['StatusService_serviceStatusId']."','".$data['Family_userServiceId']."','".$data['Service_serviceId']."', NULL, '".$data['requestDateTime']."', '".$data['serviceDateTimeStart'] ."', '".$data['serviceDateTimeEnd']."', '".$data['serviceMeridian']."','".$data['serviceTotalAmount']."','".$data['CurrencyCode_serviceTotalAmountCurrencyCode'] ."', '".$data['userRequestObservations']."', '".$data['temp_address']."', '".$data['temp_addressInfo']."',NULL);";

       //echo $QueryCallback;

 $QueryInsert=$this->ExecuteSql($QueryCallback);

    if ($QueryInsert) {
      $data['lastID'] = intval($this->GetOne("SELECT LAST_INSERT_ID()"));
      $query="Select * from Request where requestId=". $data['lastID'];
      $endJSON["data"] = $this->GetRow($query);
       $endJSON["status"] = "success";
      $endJSON["message"] = "Request was created.";
    }
    return $endJSON;
  }

  public function addedTransactionPaymentGatewayIDRequest(array $data)
  {
    $endJSON["status"] = "";
    $endJSON["data"] = "";
    try {
      if (
        isset($data['requestId']) &&
        isset($data['TransactionPaymentGateway_transactionPaymentGatewayId'])
      ) {
        $requestId                      = $data['requestId'];
        $transactionPaymentGatewayId    = $data['TransactionPaymentGateway_transactionPaymentGatewayId'];

        $query = "UPDATE Request SET TransactionPaymentGateway_transactionPaymentGatewayId = '$transactionPaymentGatewayId' WHERE requestId = '$requestId';";
        $QueryCallback = $this->ExecuteSql($query);
        if ($QueryCallback) {
           $endJSON["status"] = "success";
          $endJSON["message"] = "Request was added transactionPaymentGatewayId.";
          $endJSON["data"] = $data;
        } else {
          throw new Exception("Error: SQL createRequest", 1);
        }
      } else {
        throw new Exception("Error: invalid parameters for Request", 1);
      }
    } catch (Exception $e) {
      http_response_code(400);
      $endJSON['status'] = 'error';
      die($e->getMessage());
    }
    return $endJSON;
  }

  public function updateRequestTransactionPaymentGateway(array $data)
  {
    $dataSQL = array();
    if (
      isset($data['requestId']) &&
      isset($data['StatusService_serviceStatusId'])
    ) {
      $dataSQL['_documentID']                    = 'requestId';
      $dataSQL['requestId']                     = $data['requestId'];
      $dataSQL['StatusService_serviceStatusId'] = $data['StatusService_serviceStatusId'];
    }
    $QueryCallback = $this->ExecuteAutoSQL(
      $mode = 'UPDATE',
      $table = self::table,
      $columns = self::tableColumns,
      $data = $dataSQL
    );

    if ($QueryCallback) {
      $dataSQL['lastID']                        = $data['requestId'];
      $endJSON["data"] = $this->ExecuteAutoSQL(
        $mode = 'SELECT',
        $table = 'viewRequest',
        $columns = self::tableColumns,
        $data = $dataSQL
      );
       $endJSON["status"] = "success";
      $endJSON["message"] = "Request was updated.";
    }
    return $endJSON;
  }

  public function getAllRequest()
  {
    $endJSON["status"] = "";
    $endJSON["data"] = "";
    try {
      $query = "SELECT * FROM Request ORDER BY requestId;";
      $SelectAllResult = $this->GetAll($query);
      if ($SelectAllResult) {
         $endJSON["status"] = "success";
        foreach ($SelectAllResult as &$valor) 
        {
            $query = "SELECT * FROM Family where memberId=".$valor['Family_userServiceId'];
            $currentFamiliars = $this->GetRow($query);
            $valor["Familiars"]=$currentFamiliars;
        }
        $endJSON["data"] = $SelectAllResult;
      } else {
        throw new Exception("Error: SQL getAllRequest", 1);
      }
    } catch (Exception $e) {
      http_response_code(400);
      $endJSON['status'] = 'error';
      die($e->getMessage());
    }
    return $endJSON;
  }


  public function getRequestServerStatus()
  {
     $endJSON["status"] = "";
    $endJSON["data"] = "";
    try {
      $query = "SELECT * FROM `StatusService`";
      $SelectAllResult = $this->GetAll($query);
      if ($SelectAllResult) {
         $endJSON["status"] = "success";
        $endJSON["data"] = $SelectAllResult;
      } else {
        throw new Exception("Error: SQL get request server status", 1);
      }
    } catch (Exception $e) {
      http_response_code(400);
      $endJSON['status'] = 'error';
      die($e->getMessage());
    }
    return $endJSON;
  }

  public function getAllRequestByUser(array $data)
  {
    $endJSON["status"] = "";
    $endJSON["data"] = "";
    try {
      if (isset($data['userId'])) {
        $userId = $data['userId'];
        $query = "SELECT * FROM viewRequest WHERE userAccountId = '$userId';";
        //echo $query;

        $SelectAllResult = $this->GetAll($query);
        if ($SelectAllResult) {
           $endJSON["status"] = "success";
          $endJSON["data"] = $SelectAllResult;
        } else {
          throw new Exception("Error: SQL getAllRequestByUser id", 1);
        }
      } else {
        throw new Exception("Error: invalid parameters for Request", 1);
      }
    } catch (Exception $e) {
      http_response_code(400);
      $endJSON['status'] = 'error';
      die($e->getMessage());
    }
    return $endJSON;
  }


  public function getRequest(array $data)
  {
    $endJSON["status"] = "";
    $endJSON["data"] = "";
    try {
      if (isset($data["transactionPaymentGatewayOrderIdentifer"])) {
        $transactionPaymentGatewayOrderIdentifer = $data['transactionPaymentGatewayOrderIdentifer'];
        $query = "SELECT * FROM viewRequest WHERE transactionPaymentGatewayOrderIdentifer = '$transactionPaymentGatewayOrderIdentifer'";
      } else if (isset($data['requestId'])) {
        $requestId = $data['requestId'];
        $query = "SELECT * FROM viewRequest WHERE requestId = '$requestId'";
      } else {
        throw new Exception("getRequest required params", 400);
      }

      $SelectAllResult = $this->GetAll($query);
      if ($SelectAllResult) {
         $endJSON["status"] = "success";
        $endJSON["data"] = $SelectAllResult;
      } else {
        throw new Exception("Error: SQL getRequest id", 1);
      }

      // if (isset($data['id'])) {
      //   $id = $data['id'];
      //   $query = "SELECT * FROM Request WHERE id = '$id';";
      // } else if (isset($data['name'])) {
      //   $name = $data['name'];
      //   $query = "SELECT * FROM Request WHERE name = '$name';";
      //   $SelectAllResult = $this->GetAll($query);
      //   if ($SelectAllResult) {
      //      $endJSON["status"] = "success";
      //     $endJSON["data"] = $SelectAllResult;
      //   } else {
      //     throw new Exception("Error: SQL getRequest name", 1);
      //   }
      // } else if (isset($data['companyId'])) {
      //   $companyId = $data['companyId'];
      //   $query = "SELECT * FROM Request WHERE companyId = '$companyId';";
      //   $SelectAllResult = $this->GetAll($query);
      //   if ($SelectAllResult) {
      //      $endJSON["status"] = "success";
      //     $endJSON["data"] = $SelectAllResult;
      //   } else {
      //     throw new Exception("Error: SQL getRequest name", 1);
      //   }
      // } else {
      //   throw new Exception("Error: invalid parameters for Request", 1);
      // }
    } catch (Exception $e) {
      http_response_code(400);
      $endJSON['status'] = 'error';
      die($e->getMessage());
    }
    return $endJSON;
  }

  public function updateRequest(array $data)
  {
    $data = $data;
    $data['_documentID'] = 'requestId';
    $QueryCallback = $this->ExecuteAutoSQL(
      $mode = 'UPDATE',
      $table = self::table,
      $columns = self::tableColumns,
      $data = $data
    );

    if ($QueryCallback) {
      $endJSON["data"] = $data;
       $endJSON["status"] = "success";
      $endJSON["message"] = "Request was updated.";
    }
    return $endJSON;
  }


  public function updateRequestUserObservations(array $data)
  {
    $endJSON["status"] = "";
    $endJSON["data"] = "";
    try {
      if (
        isset($data['userRequestObservations']) &&
        isset($data['requestId'])
      ) {
        $userRequestObservations = $data['userRequestObservations'];
        $requestId = $data['requestId'];
        $query = "UPDATE Request SET  userRequestObservations = '$userRequestObservations' WHERE requestId = '$requestId';";
        $QueryCallback = $this->ExecuteSql($query);
        if ($QueryCallback) {
           $endJSON["status"] = "success";
          $endJSON["data"] = $data;
        } else {
          throw new Exception("Error: SQL updateRequestUserRequestObservations", 1);
        }
      } else {
        throw new Exception("Error: invalid parameters for Request", 1);
      }
    } catch (Exception $e) {
      $endJSON['status'] = 'error';
      http_response_code(400);
      die($e->getMessage());
    }
    return $endJSON;
  }

  // public function updateRequestDataPaymentGateway(array $data)
  // {
  //   $endJSON["status"] = "";
  //   $endJSON["data"] = "";
  //   try {
  //     if (
  //       isset($data['serviceStatusId']) &&
  //       isset($data['paymentStatusId']) &&
  //       isset($data['refNumber']) &&
  //       isset($data['order']) &&
  //       isset($data['transactionPaymentGatewayCreationDate']) &&
  //       isset($data['description']) &&
  //       isset($data['orderStatus']) &&
  //       isset($data['checkoutURL']) &&
  //       isset($data['requestId'])
  //     ) {
  //       $serviceStatusId                       = $data['serviceStatusId'];
  //       $paymentStatusId                       = $data['paymentStatusId'];
  //       $refNumber                             = $data['refNumber'];
  //       $order                                 = $data['order'];
  //       $transactionPaymentGatewayCreationDate = $data['transactionPaymentGatewayCreationDate'];
  //       $description                           = $data['description'];
  //       $orderStatus                           = $data['orderStatus'];
  //       $checkoutURL                           = $data['checkoutURL'];
  //       $requestId                             = $data['requestId'];
  //       $query = "UPDATE Request SET StatusService_serviceStatusId = $serviceStatusId, StatusPayment_paymentStatusId = $paymentStatusId, transactionPaymentGatewayRefNumber = '$refNumber', transactionPaymentGatewayOrderIdentifier = '$order', transactionPaymentGatewayCreationDate = $transactionPaymentGatewayCreationDate, transactionPaymentGatewayOrderDescription = '$description', transactionPaymentGatewayOrderStatus = '$orderStatus', transactionPaymentGatewayCheckoutURL='$checkoutURL' WHERE requestId = $requestId;";
  //       $QueryCallback = $this->ExecuteSql($query);
  //       if ($QueryCallback) {
  //          $endJSON["status"] = "success";
  //         $endJSON["data"] = $data;
  //       } else {
  //         throw new Exception("Error: SQL updateRequestDataPaymentGateway", 1);
  //       }
  //     } else {
  //       throw new Exception("Error: invalid parameters for Request", 1);
  //     }
  //   } catch (Exception $e) {
  //     $endJSON['status'] = 'error';
  //     http_response_code(400);
  //     die($e->getMessage());
  //   }
  //   return $endJSON;
  // }

  public function deleteRequest(array $data)
  {
    $data['statusId'] = 2;
    return $this->updateStatusRequest($data);
  }
}
