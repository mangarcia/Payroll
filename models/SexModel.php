<?php

class SexModel extends Database
{
  public function __construct()
  {
    parent::__construct();
  }

  public function insertSex(array $data)
  {
    $endJSON["status"] = "";
    $endJSON["data"] = "";
    try {
      if (isset($data['name'])) {
        $name = $data['name'];
        $query = "INSERT Sex (name) VALUES ('$name');";
        $QueryCallback = $this->ExecuteSql($query);
        if ($QueryCallback) {
           $endJSON["status"] = "success";
          $endJSON["data"] = $data;
        } else {
          throw new Exception("Error: SQL insertSex", 1);
        }
      } else {
        throw new Exception("Error: invalid parameters for Sex", 1);
      }
    } catch (Exception $e) {
      http_response_code(400);
      $endJSON['status'] = 'error';
      die($e->getMessage());
    }
    return $endJSON;
  }

  public function getAllSex()
  {
    $endJSON["status"] = "";
    $endJSON["data"] = "";
    try {
      $query = "SELECT * FROM Sex ORDER BY sexId;";
      $SelectAllResult = $this->GetAll($query);
      if ($SelectAllResult) {
         $endJSON["status"] = "success";
        $endJSON["data"] = $SelectAllResult;
      } else {
        throw new Exception("Error: SQL getAllSex", 1);
      }
    } catch (Exception $e) {
      http_response_code(400);
      $endJSON['status'] = 'error';
      die($e->getMessage());
    }
    return $endJSON;
  }

  public function getSex(array $data)
  {
    $endJSON["status"] = "";
    $endJSON["data"] = "";
    try {
      if (isset($data['id'])) {
        $sexId = $data['id'];
        $query = "SELECT * FROM Sex WHERE sexId = '$sexId';";
        $SelectAllResult = $this->GetAll($query);
        if ($SelectAllResult) {
           $endJSON["status"] = "success";
          $endJSON["data"] = $SelectAllResult;
        } else {
          throw new Exception("Error: SQL getSex id", 1);
        }
      } else if (isset($data['name'])) {
        $name = $data['name'];
        $query = "SELECT * FROM Sex WHERE name = '$name';";
        $SelectAllResult = $this->GetAll($query);
        if ($SelectAllResult) {
           $endJSON["status"] = "success";
          $endJSON["data"] = $SelectAllResult;
        } else {
          throw new Exception("Error: SQL getSex name", 1);
        }
      } else {
        throw new Exception("Error: invalid parameters for Sex", 1);
      }
    } catch (Exception $e) {
      http_response_code(400);
      $endJSON['status'] = 'error';
      die($e->getMessage());
    }
    return $endJSON;
  }

  public function updateSex(array $data)
  {
    $endJSON["status"] = "";
    $endJSON["data"] = "";
    try {
      if (
        isset($data['id']) &&
        isset($data['name'])
      ) {
        $sexId = $data['id'];
        $name = $data['name'];
        $query = "UPDATE Sex SET name = '$name' WHERE sexId = '$sexId';";
        $QueryCallback = $this->ExecuteSql($query);
        if ($QueryCallback) {
           $endJSON["status"] = "success";
          $endJSON["data"] = $data;
        } else {
          throw new Exception("Error: SQL updateSex", 1);
        }
      } else {
        throw new Exception("Error: invalid parameters for Sex", 1);
      }
    } catch (Exception $e) {
      $endJSON['status'] = 'error';
      http_response_code(400);
      die($e->getMessage());
    }
    return $endJSON;
  }

  public function deleteSex(array $data)
  {
    $endJSON["status"] = "";
    $endJSON["data"] = "";
    try {
      if (isset($data['id'])) {
        $sexId = $data['id'];
        $query = "DELETE FROM Sex WHERE sexId = '$sexId';";
        $QueryCallback = $this->ExecuteSql($query);
        if ($QueryCallback) {
           $endJSON["status"] = "success";
          $endJSON["data"] = "{}";
        } else {
          throw new Exception("Error: SQL deleteSex", 1);
        }
      } else {
        throw new Exception("Error: invalid parameters for Sex", 1);
      }
    } catch (Exception $e) {
      http_response_code(400);
      $endJSON['status'] = 'error';
      die($e->getMessage());
    }
    return $endJSON;
  }
}
