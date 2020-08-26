<?php

class RelationshipModel extends Database
{
  public function __construct()
  {
    parent::__construct();
  }

  public function insertRelationship(array $data)
  {
    $endJSON["status"] = "";
    $endJSON["data"] = "";
    try {
      if (isset($data['name'])) {
        $name = $data['name'];
        $query = "INSERT Relationship (name) VALUES ('$name');";
        $QueryCallback = $this->ExecuteSql($query);
        if ($QueryCallback) {
           $endJSON["status"] = "success";
          $endJSON["data"] = $data;
        } else {
          throw new Exception("Error: SQL insertRelationship", 1);
        }
      } else {
        throw new Exception("Error: invalid parameters for Relationship", 1);
      }
    } catch (Exception $e) {
      http_response_code(400);
      $endJSON['status'] = 'error';
      die($e->getMessage());
    }
    return $endJSON;
  }

  public function getAllRelationship()
  {
    $endJSON["status"] = "";
    $endJSON["data"] = "";
    try {
      $query = "SELECT * FROM Relationship ORDER BY relationshipId;";
      $SelectAllResult = $this->GetAll($query);
      if ($SelectAllResult) {
         $endJSON["status"] = "success";
        $endJSON["data"] = $SelectAllResult;
      } else {
        throw new Exception("Error: SQL getAllRelationship", 1);
      }
    } catch (Exception $e) {
      http_response_code(400);
      $endJSON['status'] = 'error';
      die($e->getMessage());
    }
    return $endJSON;
  }

  public function getRelationship(array $data)
  {
    $endJSON["status"] = "";
    $endJSON["data"] = "";
    try {
      if (isset($data['id'])) {
        $relationshipId = $data['id'];
        $query = "SELECT * FROM Relationship WHERE relationshipId = '$relationshipId';";
        $SelectAllResult = $this->GetAll($query);
        if ($SelectAllResult) {
           $endJSON["status"] = "success";
          $endJSON["data"] = $SelectAllResult;
        } else {
          throw new Exception("Error: SQL getRelationship id", 1);
        }
      } else if (isset($data['name'])) {
        $name = $data['name'];
        $query = "SELECT * FROM Relationship WHERE name = '$name';";
        $SelectAllResult = $this->GetAll($query);
        if ($SelectAllResult) {
           $endJSON["status"] = "success";
          $endJSON["data"] = $SelectAllResult;
        } else {
          throw new Exception("Error: SQL getRelationship name", 1);
        }
      } else {
        throw new Exception("Error: invalid parameters for Relationship", 1);
      }
    } catch (Exception $e) {
      http_response_code(400);
      $endJSON['status'] = 'error';
      die($e->getMessage());
    }
    return $endJSON;
  }

  public function updateRelationship(array $data)
  {
    $endJSON["status"] = "";
    $endJSON["data"] = "";
    try {
      if (
        isset($data['id']) &&
        isset($data['name'])
      ) {
        $relationshipId = $data['id'];
        $name = $data['name'];
        $query = "UPDATE Relationship SET name = '$name' WHERE relationshipId = '$relationshipId';";
        $QueryCallback = $this->ExecuteSql($query);
        if ($QueryCallback) {
           $endJSON["status"] = "success";
          $endJSON["data"] = $data;
        } else {
          throw new Exception("Error: SQL updateRelationship", 1);
        }
      } else {
        throw new Exception("Error: invalid parameters for Relationship", 1);
      }
    } catch (Exception $e) {
      $endJSON['status'] = 'error';
      http_response_code(400);
      die($e->getMessage());
    }
    return $endJSON;
  }

  public function deleteRelationship(array $data)
  {
    $endJSON["status"] = "";
    $endJSON["data"] = "";
    try {
      if (isset($data['id'])) {
        $relationshipId = $data['id'];
        $query = "DELETE FROM Relationship WHERE relationshipId = '$relationshipId';";
        $QueryCallback = $this->ExecuteSql($query);
        if ($QueryCallback) {
           $endJSON["status"] = "success";
          $endJSON["data"] = "{}";
        } else {
          throw new Exception("Error: SQL deleteRelationship", 1);
        }
      } else {
        throw new Exception("Error: invalid parameters for Relationship", 1);
      }
    } catch (Exception $e) {
      http_response_code(400);
      $endJSON['status'] = 'error';
      die($e->getMessage());
    }
    return $endJSON;
  }
}
