<?php

class StatusDeviceModel extends Database
{
  public function __construct()
  {
    parent::__construct();
  }

  public function insertStatusDevice(array $data)
  {
    $endJSON["status"] = "";
    $endJSON["data"] = "";
    try {
      if (isset($data['name'])) {
        $name = $data['name'];
        $query = "INSERT StatusDevice (name) VALUES ('$name');";
        $QueryCallback = $this->ExecuteSql($query);
        if ($QueryCallback) {
           $endJSON["status"] = "success";
          $endJSON["data"] = $data;
        } else {
          throw new Exception("Error: SQL insertStatusDevice", 1);
        }
      } else {
        throw new Exception("Error: invalid parameters for StatusDevice", 1);
      }
    } catch (Exception $e) {
      http_response_code(400);
      $endJSON['status'] = 'error';
      die($e->getMessage());
    }
    return $endJSON;
  }

  public function getAllStatusDevice()
  {
    $endJSON["status"] = "";
    $endJSON["data"] = "";
    try {
      $query = "SELECT * FROM StatusDevice ORDER BY statusDeviceId;";
      $SelectAllResult = $this->GetAll($query);
      if ($SelectAllResult) {
         $endJSON["status"] = "success";
        $endJSON["data"] = $SelectAllResult;
      } else {
        throw new Exception("Error: SQL getAllStatusDevice", 1);
      }
    } catch (Exception $e) {
      http_response_code(400);
      $endJSON['status'] = 'error';
      die($e->getMessage());
    }
    return $endJSON;
  }

  public function getStatusDevice(array $data)
  {
    $endJSON["status"] = "";
    $endJSON["data"] = "";
    try {
      if (isset($data['id'])) {
        $statusDeviceId = $data['id'];
        $query = "SELECT * FROM StatusDevice WHERE statusDeviceId = '$statusDeviceId';";
        $SelectAllResult = $this->GetAll($query);
        if ($SelectAllResult) {
           $endJSON["status"] = "success";
          $endJSON["data"] = $SelectAllResult;
        } else {
          throw new Exception("Error: SQL getStatusDevice id", 1);
        }
      } else if (isset($data['name'])) {
        $name = $data['name'];
        $query = "SELECT * FROM StatusDevice WHERE name = '$name';";
        $SelectAllResult = $this->GetAll($query);
        if ($SelectAllResult) {
           $endJSON["status"] = "success";
          $endJSON["data"] = $SelectAllResult;
        } else {
          throw new Exception("Error: SQL getStatusDevice name", 1);
        }
      } else {
        throw new Exception("Error: invalid parameters for StatusDevice", 1);
      }
    } catch (Exception $e) {
      http_response_code(400);
      $endJSON['status'] = 'error';
      die($e->getMessage());
    }
    return $endJSON;
  }

  public function updateStatusDevice(array $data)
  {
    $endJSON["status"] = "";
    $endJSON["data"] = "";
    try {
      if (
        isset($data['id']) &&
        isset($data['name'])
      ) {
        $statusDeviceId = $data['id'];
        $name = $data['name'];
        $query = "UPDATE StatusDevice SET name = '$name' WHERE statusDeviceId = '$statusDeviceId';";
        $QueryCallback = $this->ExecuteSql($query);
        if ($QueryCallback) {
           $endJSON["status"] = "success";
          $endJSON["data"] = $data;
        } else {
          throw new Exception("Error: SQL updateStatusDevice", 1);
        }
      } else {
        throw new Exception("Error: invalid parameters for StatusDevice", 1);
      }
    } catch (Exception $e) {
      $endJSON['status'] = 'error';
      http_response_code(400);
      die($e->getMessage());
    }
    return $endJSON;
  }

  public function deleteStatusDevice(array $data)
  {
    $endJSON["status"] = "";
    $endJSON["data"] = "";
    try {
      if (isset($data['id'])) {
        $statusDeviceId = $data['id'];
        $query = "DELETE FROM StatusDevice WHERE statusDeviceId = '$statusDeviceId';";
        $QueryCallback = $this->ExecuteSql($query);
        if ($QueryCallback) {
           $endJSON["status"] = "success";
          $endJSON["data"] = "{}";
        } else {
          throw new Exception("Error: SQL deleteStatusDevice", 1);
        }
      } else {
        throw new Exception("Error: invalid parameters for StatusDevice", 1);
      }
    } catch (Exception $e) {
      http_response_code(400);
      $endJSON['status'] = 'error';
      die($e->getMessage());
    }
    return $endJSON;
  }
}
