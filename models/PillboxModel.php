<?php
class PillboxModel extends Database
{
  public function __construct()
  {
    parent::__construct();
  }

  public function addNewPillbox(array $data)
  {
    $endJSON["status"] = "";
    $endJSON["data"] = "";
    try {
      if (
        isset($data['name']) &&
        isset($data['quantity']) &&
        isset($data['frequency']) &&
        isset($data['typePillbox']) &&
        isset($data['familyId']) &&
        isset($data['startedAt'])
      ) {
        $name = $data['name'];
        $quantity = $data['quantity'];
        $frequency = $data['frequency'];
        $typePillbox = $data['typePillbox'];
        $familyId = $data['familyId'];
        $startedAt = $data['startedAt'];
        $query = "INSERT INTO Pillbox(name, quantity, frequency, typePillbox, Family_familyId, startedAt) VALUES ('$name', '$quantity', '$frequency', '$typePillbox', '$familyId', '$startedAt');";
        $QueryCallback = $this->ExecuteSql($query);
        if ($QueryCallback) {
          $data['pillboxId'] = intval($this->GetOne("SELECT LAST_INSERT_ID()"));
           $endJSON["status"] = "success";
          $endJSON["message"] = "Pillbox was created.";
          $endJSON["data"] = $data;
        } else {
          throw new Exception('{"Error":"SQL registerNewPillbox"}', 1);
        }
      } else {
        throw new Exception("Error: invalid parameters for Pillbox", 1);
      }
    } catch (Exception $e) {
      http_response_code(400);
      $endJSON['status'] = 'error';
      die($e->getMessage());
    }
    return $endJSON;
  }

  public function getAllPillbox()
  {
    $endJSON["status"] = "";
    $endJSON["data"] = "";
    try {
      $query = "SELECT * FROM viewPillbox WHERE viewPillbox.statusMemberId = 1 ORDER BY pillboxId;";
      $SelectAllResult = $this->GetAll($query);
      if ($SelectAllResult) {
         $endJSON["status"] = "success";
        $endJSON["data"] = $SelectAllResult;
      } else {
        throw new Exception("Error: SQL getAllPillbox", 1);
      }
    } catch (Exception $e) {
      http_response_code(400);
      $endJSON['status'] = 'error';
      die($e->getMessage());
    }
    return $endJSON;
  }

  public function getPillboxByMember(array $data)
  {
    $endJSON["status"] = "";
    $endJSON["data"] = "";
    try {
      if (isset($data['userId'])) {
        $userId = $data['userId'];
        $query = "SELECT * FROM viewPillbox WHERE viewPillbox.statusMemberId = 1 AND userAccountId ='$userId';";
        $SelectAllResult = $this->GetAll($query);
        if ($SelectAllResult) {
           $endJSON["status"] = "success";
          $endJSON["data"] = $SelectAllResult;
        } else {
          throw new Exception("Error: SQL getPillboxByMember id", 1);
        }
      } else {
        throw new Exception("Error: invalid parameters for Family", 1);
      }
    } catch (Exception $e) {
      http_response_code(400);
      $endJSON['status'] = 'error';
      die($e->getMessage());
    }
    return $endJSON;
  }

  public function updatePillbox(array $data)
  {
    $endJSON["status"] = "";
    $endJSON["data"] = "";
    try {
      if (
        isset($data['pillboxId']) &&
        isset($data['name']) &&
        isset($data['quantity']) &&
        isset($data['frequency']) &&
        isset($data['typePillbox']) &&
        isset($data['familyId']) &&
        isset($data['startedAt'])
      ) {
        $pillboxId = $data['pillboxId'];
        $name = $data['name'];
        $quantity = $data['quantity'];
        $frequency = $data['frequency'];
        $typePillbox = $data['typePillbox'];
        $familyId = $data['familyId'];
        $startedAt = $data['startedAt'];
        $query = "UPDATE Pillbox SET name = '$name', quantity = '$quantity', frequency = '$frequency', typePillbox = '$typePillbox', Family_familyId = '$familyId', startedAt = '$startedAt' WHERE pillboxId = '$pillboxId';";
        $QueryCallback = $this->ExecuteSql($query);
        if ($QueryCallback) {
           $endJSON["status"] = "success";
          $endJSON["data"] = $data;
        } else {
          throw new Exception("Error: SQL updatePillbox", 1);
        }
      } else {
        throw new Exception('{"Error":"No data params Pillbox"}', 1);
      }
    } catch (Exception $e) {
      $endJSON['status'] = 'error';
      http_response_code(400);
      die($e->getMessage());
    }
    return $endJSON;
  }

  public function deletePillbox(array $data)
  {
    $endJSON["status"] = "";
    $endJSON["data"] = "";
    try {
      if ( isset($data['pillboxId']) ) {
        $pillboxId = $data['pillboxId'];
        $query = "DELETE FROM Pillbox WHERE pillboxId = '$pillboxId';";
        $QueryCallback = $this->ExecuteSql($query);
        if ($QueryCallback) {
          $endJSON["status"] = "Success deleted pillbox";
          $endJSON["data"] = [];
        } else {
          throw new Exception("Error: SQL deletePillbox", 1);
        }
      } else {
        throw new Exception('{"Error":"No data params Pillbox"}', 1);
      }
    } catch (Exception $e) {
      $endJSON['status'] = 'error';
      http_response_code(400);
      die($e->getMessage());
    }
    return $endJSON;
  }
}
