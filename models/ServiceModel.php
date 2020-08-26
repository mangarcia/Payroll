<?php

class ServiceModel extends Database
{
  public function __construct()
  {
    parent::__construct();
  }

  public function insertService(array $data)
  {
    $endJSON["status"] = "";
    $endJSON["data"] = "";
    try {
      if (
        isset($data['companyId']) &&
        isset($data['name']) &&
        isset($data['type']) &&
        isset($data['costAmount']) &&
        isset($data['serviceDetail'])
      ) {
        $companyId = $data['companyId'];
        $name = $data['name'];
        $type = $data['type'];
        $costAmount = $data['costAmount'];
        $serviceDetail = $data['serviceDetail'];
        $query = "INSERT INTO Service (Company_companyId, name, type, costAmount, serviceDetail) VALUES ('$companyId', '$name', '$type', '$costAmount', '$serviceDetail');";
        $QueryCallback = $this->ExecuteSql($query);
        if ($QueryCallback) {
           $endJSON["status"] = "success";
          $endJSON["data"] = $data;
        } else {
          throw new Exception("Error: SQL insertService", 1);
        }
      } else {
        throw new Exception("Error: invalid parameters for Service", 1);
      }
    } catch (Exception $e) {
      http_response_code(400);
      $endJSON['status'] = 'error';
      die($e->getMessage());
    }
    return $endJSON;
  }

  public function getAllService()
  {
    $endJSON["status"] = "";
    $endJSON["data"] = "";
    try {
      $query = "SELECT * FROM viewService ORDER BY serviceId;";
      $SelectAllResult = $this->GetAll($query);
      if ($SelectAllResult) {
         $endJSON["status"] = "success";
        $endJSON["data"] = $SelectAllResult;
      } else {
        throw new Exception("Error: SQL getAllService", 1);
      }
    } catch (Exception $e) {
      http_response_code(400);
      $endJSON['status'] = 'error';
      die($e->getMessage());
    }
    return $endJSON;
  }

  public function getServiceByCondition(array $data)
  {
    $endJSON["status"] = "";
    $endJSON["data"] = "";
    try {
      if (
        isset($data['typeServiceId']) &&
        isset($data['isDayHoliday']) &&
        isset($data['durationService'])
      ) {
        $typeServiceId = $data['typeServiceId'];
        $isDayHoliday = ($data['isDayHoliday']) ? 1 : 0;
        $durationService = $data['durationService'];
        $query = "SELECT * FROM viewService WHERE viewService.typeServiceId = $typeServiceId AND viewService.isDayHoliday = $isDayHoliday AND viewService.durationService = $durationService";
        $SelectAllResult = $this->GetAll($query);
        if ($SelectAllResult) {
           $endJSON["status"] = "success";
          $endJSON["data"] = $SelectAllResult;
        } else {
          throw new Exception("Error: SQL getService id", 1);
        }
      } else {
        throw new Exception("Error: invalid parameters for Service", 1);
      }
    } catch (Exception $e) {
      http_response_code(400);
      $endJSON['status'] = 'error';
      die($e->getMessage());
    }
    return $endJSON;
  }

  public function getService(array $data)
  {
    $endJSON["status"] = "";
    $endJSON["data"] = "";
    try {
      if (isset($data['id'])) {
        $id = $data['id'];
        $query = "SELECT * FROM Service WHERE id = '$id';";
        $SelectAllResult = $this->GetAll($query);
        if ($SelectAllResult) {
           $endJSON["status"] = "success";
          $endJSON["data"] = $SelectAllResult;
        } else {
          throw new Exception("Error: SQL getService id", 1);
        }
      } else if (isset($data['name'])) {
        $name = $data['name'];
        $query = "SELECT * FROM Service WHERE name = '$name';";
        $SelectAllResult = $this->GetAll($query);
        if ($SelectAllResult) {
           $endJSON["status"] = "success";
          $endJSON["data"] = $SelectAllResult;
        } else {
          throw new Exception("Error: SQL getService name", 1);
        }
      } else if (isset($data['companyId'])) {
        $companyId = $data['companyId'];
        $query = "SELECT * FROM Service WHERE companyId = '$companyId';";
        $SelectAllResult = $this->GetAll($query);
        if ($SelectAllResult) {
           $endJSON["status"] = "success";
          $endJSON["data"] = $SelectAllResult;
        } else {
          throw new Exception("Error: SQL getService name", 1);
        }
      } else {
        throw new Exception("Error: invalid parameters for Service", 1);
      }
    } catch (Exception $e) {
      http_response_code(400);
      $endJSON['status'] = 'error';
      die($e->getMessage());
    }
    return $endJSON;
  }

  public function updateService(array $data)
  {
    $endJSON["status"] = "";
    $endJSON["data"] = "";
    try {
      if (
        isset($data['id']) &&
        isset($data['companyId']) &&
        isset($data['name']) &&
        isset($data['type']) &&
        isset($data['costAmount']) &&
        isset($data['serviceDetail'])
      ) {
        $id = $data['id'];
        $companyId = $data['companyId'];
        $name = $data['name'];
        $type = $data['type'];
        $costAmount = $data['costAmount'];
        $serviceDetail = $data['serviceDetail'];
        $query = " UPDATE Service SET name = '$name', Company_companyId = '$companyId', type = '$type', costAmount = '$costAmount', serviceDetail = '$serviceDetail' WHERE id = '$id';";
        $QueryCallback = $this->ExecuteSql($query);
        if ($QueryCallback) {
           $endJSON["status"] = "success";
          $endJSON["data"] = $data;
        } else {
          throw new Exception("Error: SQL updateService", 1);
        }
      } else if (
        isset($data['id']) &&
        isset($data['costAmount'])
      ) {
        $id = $data['id'];
        $costAmount = $data['costAmount'];
        $query = " UPDATE Service SET costAmount = '$costAmount' WHERE id = '$id';";
        $QueryCallback = $this->ExecuteSql($query);
        if ($QueryCallback) {
           $endJSON["status"] = "success";
          $endJSON["data"] = $data;
        } else {
          throw new Exception("Error: SQL updateService", 1);
        }
      } else if (
        isset($data['id']) &&
        isset($data['type'])
      ) {
        $id = $data['id'];
        $type = $data['type'];
        $query = " UPDATE Service SET type = '$type' WHERE id = '$id';";
        $QueryCallback = $this->ExecuteSql($query);
        if ($QueryCallback) {
           $endJSON["status"] = "success";
          $endJSON["data"] = $data;
        } else {
          throw new Exception("Error: SQL updateService", 1);
        }
      } else if (
        isset($data['id']) &&
        isset($data['companyId'])
      ) {
        $id = $data['id'];
        $name = $data['name'];
        $companyId = $data['companyId'];
        $query = " UPDATE Service SET Company_companyId = '$companyId' WHERE id = '$id';";
        $QueryCallback = $this->ExecuteSql($query);
        if ($QueryCallback) {
           $endJSON["status"] = "success";
          $endJSON["data"] = $data;
        } else {
          throw new Exception("Error: SQL updateService", 1);
        }
      } else if (
        isset($data['id']) &&
        isset($data['name']) &&
        isset($data['companyId'])
      ) {
        $id = $data['id'];
        $name = $data['name'];
        $companyId = $data['companyId'];
        $query = " UPDATE Service SET name = '$name', Company_companyId = '$companyId' WHERE id = '$id';";
        $QueryCallback = $this->ExecuteSql($query);
        if ($QueryCallback) {
           $endJSON["status"] = "success";
          $endJSON["data"] = $data;
        } else {
          throw new Exception("Error: SQL updateService", 1);
        }
      } else {
        throw new Exception("Error: invalid parameters for Service", 1);
      }
    } catch (Exception $e) {
      $endJSON['status'] = 'error';
      http_response_code(400);
      die($e->getMessage());
    }
    return $endJSON;
  }

  public function deleteService(array $data)
  {
    $endJSON["status"] = "";
    $endJSON["data"] = "";
    try {
      if (isset($data['id'])) {
        $id = $data['id'];
        $query = "DELETE FROM Service WHERE id = '$id';";
        $QueryCallback = $this->ExecuteSql($query);
        if ($QueryCallback) {
           $endJSON["status"] = "success";
          $endJSON["data"] = "{}";
        } else {
          throw new Exception("Error: SQL deleteService", 1);
        }
      } else {
        throw new Exception("Error: invalid parameters for Service", 1);
      }
    } catch (Exception $e) {
      http_response_code(400);
      $endJSON['status'] = 'error';
      die($e->getMessage());
    }
    return $endJSON;
  }
}
