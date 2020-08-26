<?php

class AvatarModel extends Database
{
  public function __construct()
  {
    parent::__construct();
  }

  public function insertAvatar(array $data)
  {
    $endJSON["status"] = "";
    $endJSON["data"] = "";
    try {
      if (isset($data['name'])) {
        $name = $data['name'];
        $query = "INSERT Avatar (name) VALUES ('$name');";
        $QueryCallback = $this->ExecuteSql($query);
        if ($QueryCallback) {
           $endJSON["status"] = "success";
          $endJSON["data"] = $data;
        } else {
          throw new Exception("Error: SQL insertAvatar", 1);
        }
      } else {
        throw new Exception("Error: invalid parameters for Avatar", 1);
      }
    } catch (Exception $e) {
      http_response_code(400);
      $endJSON['status'] = 'error';
      die($e->getMessage());
    }
    return $endJSON;
  }

  public function getAllAvatar()
  {
    $endJSON["status"] = "";
    $endJSON["data"] = "";
    try {
      $query = "SELECT * FROM Avatar ORDER BY avatarId;";
      $SelectAllResult = $this->GetAll($query);
      if ($SelectAllResult) {
         $endJSON["status"] = "success";
        $endJSON["data"] = $SelectAllResult;
      } else {
        throw new Exception("Error: SQL getAllAvatar", 1);
      }
    } catch (Exception $e) {
      http_response_code(400);
      $endJSON['status'] = 'error';
      die($e->getMessage());
    }
    return $endJSON;
  }

  public function getAvatar(array $data)
  {
    $endJSON["status"] = "";
    $endJSON["data"] = "";
    try {
      if (isset($data['id'])) {
        $avatarId = $data['id'];
        $query = "SELECT * FROM Avatar WHERE avatarId = '$avatarId';";
        $SelectAllResult = $this->GetAll($query);
        if ($SelectAllResult) {
           $endJSON["status"] = "success";
          $endJSON["data"] = $SelectAllResult;
        } else {
          throw new Exception("Error: SQL getAvatar id", 1);
        }
      } else if (isset($data['name'])) {
        $name = $data['name'];
        $query = "SELECT * FROM Avatar WHERE name = '$name';";
        $SelectAllResult = $this->GetAll($query);
        if ($SelectAllResult) {
           $endJSON["status"] = "success";
          $endJSON["data"] = $SelectAllResult;
        } else {
          throw new Exception("Error: SQL getAvatar name", 1);
        }
      } else {
        throw new Exception("Error: invalid parameters for Avatar", 1);
      }
    } catch (Exception $e) {
      http_response_code(400);
      $endJSON['status'] = 'error';
      die($e->getMessage());
    }
    return $endJSON;
  }

  public function updateAvatar(array $data)
  {
    $endJSON["status"] = "";
    $endJSON["data"] = "";
    try {
      if (
        isset($data['id']) &&
        isset($data['name'])
      ) {
        $avatarId = $data['id'];
        $name = $data['name'];
        $query = "UPDATE Avatar SET name = '$name' WHERE avatarId = '$avatarId';";
        $QueryCallback = $this->ExecuteSql($query);
        if ($QueryCallback) {
           $endJSON["status"] = "success";
          $endJSON["data"] = $data;
        } else {
          throw new Exception("Error: SQL updateAvatar", 1);
        }
      } else {
        throw new Exception("Error: invalid parameters for Avatar", 1);
      }
    } catch (Exception $e) {
      $endJSON['status'] = 'error';
      http_response_code(400);
      die($e->getMessage());
    }
    return $endJSON;
  }

  public function deleteAvatar(array $data)
  {
    $endJSON["status"] = "";
    $endJSON["data"] = "";
    try {
      if (isset($data['id'])) {
        $avatarId = $data['id'];
        $query = "DELETE FROM Avatar WHERE avatarId = '$avatarId';";
        $QueryCallback = $this->ExecuteSql($query);
        if ($QueryCallback) {
           $endJSON["status"] = "success";
          $endJSON["data"] = "{}";
        } else {
          throw new Exception("Error: SQL deleteAvatar", 1);
        }
      } else {
        throw new Exception("Error: invalid parameters for Avatar", 1);
      }
    } catch (Exception $e) {
      http_response_code(400);
      $endJSON['status'] = 'error';
      die($e->getMessage());
    }
    return $endJSON;
  }
}
