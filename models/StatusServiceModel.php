<?php

class StatusServiceModel extends Database
{
  const PROGRESS_PAYMENT = 2;      // transactionPaymentGateway = 2 EN PROCESO
  const PENDING_PAYMENT  = 3;      // transactionPaymentGateway = 3 PENDIENTE
  const SEARCHING        = 4;      // transactionPaymentGateway = 4
  const CONFIRMED        = 5;      // transactionPaymentGateway = 4
  const ON_THE_ROAD      = 6;      // transactionPaymentGateway = 4
  const STARTED          = 7;      // transactionPaymentGateway = 4
  const FINISHED         = 8;      // transactionPaymentGateway = 4
  const CANCELED         = 9;      // transactionPaymentGateway = 6 CANCELADA
  const CANCELED_EXPIRED = 10;     // transactionPaymentGateway = 5 VENCIDA
  const CANCELED_REFUND  = 11;     // transactionPaymentGateway = 7 ANULADA
  const CANCELED_PENALTY = 12;     // transactionPaymentGateway = 4

  public function __construct()
  {
    parent::__construct();
  }

  public function insertStatusService(array $data)
  {
    $endJSON["status"] = "";
    $endJSON["data"] = "";
    try {
      if (isset($data['name'])) {
        $name = $data['name'];
        $query = "INSERT StatusService (name) VALUES ('$name');";
        $QueryCallback = $this->ExecuteSql($query);
        if ($QueryCallback) {
           $endJSON["status"] = "success";
          $endJSON["data"] = $data;
        } else {
          throw new Exception("Error: SQL insertStatusService", 1);
        }
      } else {
        throw new Exception("Error: invalid parameters for StatusService", 1);
      }
    } catch (Exception $e) {
      http_response_code(400);
      $endJSON['status'] = 'error';
      die($e->getMessage());
    }
    return $endJSON;
  }

  public function getAllStatusService()
  {
    $endJSON["status"] = "";
    $endJSON["data"] = "";
    try {
      $query = "SELECT * FROM StatusService ORDER BY statusServiceId;";
      $SelectAllResult = $this->GetAll($query);
      if ($SelectAllResult) {
         $endJSON["status"] = "success";
        $endJSON["data"] = $SelectAllResult;
      } else {
        throw new Exception("Error: SQL getAllStatusService", 1);
      }
    } catch (Exception $e) {
      http_response_code(400);
      $endJSON['status'] = 'error';
      die($e->getMessage());
    }
    return $endJSON;
  }

  public function getStatusService(array $data)
  {
    $endJSON["status"] = "";
    $endJSON["data"] = "";
    try {
      if (isset($data['id'])) {
        $statusServiceId = $data['id'];
        $query = "SELECT * FROM StatusService WHERE statusServiceId = '$statusServiceId';";
        $SelectAllResult = $this->GetAll($query);
        if ($SelectAllResult) {
           $endJSON["status"] = "success";
          $endJSON["data"] = $SelectAllResult;
        } else {
          throw new Exception("Error: SQL getStatusService id", 1);
        }
      } else if (isset($data['name'])) {
        $name = $data['name'];
        $query = "SELECT * FROM StatusService WHERE name = '$name';";
        $SelectAllResult = $this->GetAll($query);
        if ($SelectAllResult) {
           $endJSON["status"] = "success";
          $endJSON["data"] = $SelectAllResult;
        } else {
          throw new Exception("Error: SQL getStatusService name", 1);
        }
      } else {
        throw new Exception("Error: invalid parameters for StatusService", 1);
      }
    } catch (Exception $e) {
      http_response_code(400);
      $endJSON['status'] = 'error';
      die($e->getMessage());
    }
    return $endJSON;
  }

  public function updateStatusService(array $data)
  {
    $endJSON["status"] = "";
    $endJSON["data"] = "";
    try {
      if (
        isset($data['id']) &&
        isset($data['name'])
      ) {
        $statusServiceId = $data['id'];
        $name = $data['name'];
        $query = "UPDATE StatusService SET name = '$name' WHERE statusServiceId = '$statusServiceId';";
        $QueryCallback = $this->ExecuteSql($query);
        if ($QueryCallback) {
           $endJSON["status"] = "success";
          $endJSON["data"] = $data;
        } else {
          throw new Exception("Error: SQL updateStatusService", 1);
        }
      } else {
        throw new Exception("Error: invalid parameters for StatusService", 1);
      }
    } catch (Exception $e) {
      $endJSON['status'] = 'error';
      http_response_code(400);
      die($e->getMessage());
    }
    return $endJSON;
  }

  public function deleteStatusService(array $data)
  {
    $endJSON["status"] = "";
    $endJSON["data"] = "";
    try {
      if (isset($data['id'])) {
        $statusServiceId = $data['id'];
        $query = "DELETE FROM StatusService WHERE statusServiceId = '$statusServiceId';";
        $QueryCallback = $this->ExecuteSql($query);
        if ($QueryCallback) {
           $endJSON["status"] = "success";
          $endJSON["data"] = "{}";
        } else {
          throw new Exception("Error: SQL deleteStatusService", 1);
        }
      } else {
        throw new Exception("Error: invalid parameters for StatusService", 1);
      }
    } catch (Exception $e) {
      http_response_code(400);
      $endJSON['status'] = 'error';
      die($e->getMessage());
    }
    return $endJSON;
  }
}
