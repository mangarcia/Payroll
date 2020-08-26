<?php

class RolModel extends Database
{
  public function __construct()
  {
    parent::__construct();
  }

  public function insertRol(array $data)
  {
    $endJSON["status"] = "";
    $endJSON["data"] = "";
    try {
      if (isset($data['name'])) {
        $name = $data['name'];
        $query = "INSERT Rol (name) VALUES ('$name');";
        $QueryCallback = $this->ExecuteSql($query);
        if ($QueryCallback) {
           $endJSON["status"] = "success";
          $endJSON["data"] = $data;
        } else {
          throw new Exception("Error: SQL insertRol", 1);
        }
      } else {
        throw new Exception("Error: invalid parameters for Rol", 1);
      }
    } catch (Exception $e) {
      http_response_code(400);
      $endJSON['status'] = 'error';
      die($e->getMessage());
    }
    return $endJSON;
  }

 public function getRolByUserRoleId($roleId)
  {

      $endJSON["status"] = "success";
     $endJSON["data"] = "";
     $query="";
     if($roleId==8)
     {
       $query = "SELECT * FROM Rol ORDER BY rolId;";
        $SelectAllResult = $this->GetAll($query);
        $endJSON["data"] = $SelectAllResult;
        return $endJSON;
     }
     else if($roleId==1)
     {
        $query = "SELECT * FROM Rol where rolId!=8 ORDER BY rolId;";
        $SelectAllResult = $this->GetAll($query);
        $endJSON["data"] = $SelectAllResult;
        return $endJSON;
     }
  }

  public function getAllRol()
  {
    $endJSON["status"] = "";
    $endJSON["data"] = "";
    try {
      $query = "SELECT * FROM Rol ORDER BY rolId;";
      $SelectAllResult = $this->GetAll($query);
      if ($SelectAllResult) {
         $endJSON["status"] = "success";
        $endJSON["data"] = $SelectAllResult;
      } else {
        throw new Exception("Error: SQL getAllRol", 1);
      }
    } catch (Exception $e) {
      http_response_code(400);
      $endJSON['status'] = 'error';
      die($e->getMessage());
    }
    return $endJSON;
  }


  public function getRol(array $data)
  {
    $endJSON["status"] = "";
    $endJSON["data"] = "";
    try {
      if (isset($data['id'])) {
        $rolId = $data['id'];
        $query = "SELECT * FROM Rol WHERE rolId = '$rolId';";
        $SelectAllResult = $this->GetAll($query);
        if ($SelectAllResult) {
           $endJSON["status"] = "success";
          $endJSON["data"] = $SelectAllResult;
        } else {
          throw new Exception("Error: SQL getRol id", 1);
        }
      } else if (isset($data['name'])) {
        $name = $data['name'];
        $query = "SELECT * FROM Rol WHERE name = '$name';";
        $SelectAllResult = $this->GetAll($query);
        if ($SelectAllResult) {
           $endJSON["status"] = "success";
          $endJSON["data"] = $SelectAllResult;
        } else {
          throw new Exception("Error: SQL getRol name", 1);
        }
      } else {
        throw new Exception("Error: invalid parameters for Rol", 1);
      }
    } catch (Exception $e) {
      http_response_code(400);
      $endJSON['status'] = 'error';
      die($e->getMessage());
    }
    return $endJSON;
  }

  public function updateRol(array $data)
  {
    $endJSON["status"] = "";
    $endJSON["data"] = "";
    try {
      if (
        isset($data['id']) &&
        isset($data['name'])
      ) {
        $rolId = $data['id'];
        $name = $data['name'];
        $query = "UPDATE Rol SET name = '$name' WHERE rolId = '$rolId';";
        $QueryCallback = $this->ExecuteSql($query);
        if ($QueryCallback) {
           $endJSON["status"] = "success";
          $endJSON["data"] = $data;
        } else {
          throw new Exception("Error: SQL updateRol", 1);
        }
      } else {
        throw new Exception("Error: invalid parameters for Rol", 1);
      }
    } catch (Exception $e) {
      $endJSON['status'] = 'error';
      http_response_code(400);
      die($e->getMessage());
    }
    return $endJSON;
  }

  public function deleteRol(array $data)
  {
    $endJSON["status"] = "";
    $endJSON["data"] = "";
    try {
      if (isset($data['id'])) {
        $rolId = $data['id'];
        $query = "DELETE FROM Rol WHERE rolId = '$rolId';";
        $QueryCallback = $this->ExecuteSql($query);
        if ($QueryCallback) {
           $endJSON["status"] = "success";
          $endJSON["data"] = "{}";
        } else {
          throw new Exception("Error: SQL deleteRol", 1);
        }
      } else {
        throw new Exception("Error: invalid parameters for Rol", 1);
      }
    } catch (Exception $e) {
      http_response_code(400);
      $endJSON['status'] = 'error';
      die($e->getMessage());
    }
    return $endJSON;
  }
}
