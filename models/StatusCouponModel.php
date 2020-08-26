<?php

class StatusCouponModel extends Database
{
  public function __construct()
  {
    parent::__construct();
  }

  public function insertStatusCoupon(array $data)
  {
    $endJSON["status"] = "";
    $endJSON["data"] = "";
    try {
      if (isset($data['name'])) {
        $name = $data['name'];
        $query = "INSERT StatusCoupon (name) VALUES ('$name');";
        $QueryCallback = $this->ExecuteSql($query);
        if ($QueryCallback) {
           $endJSON["status"] = "success";
          $endJSON["data"] = $data;
        } else {
          throw new Exception("Error: SQL insertStatusCoupon", 1);
        }
      } else {
        throw new Exception("Error: invalid parameters for StatusCoupon", 1);
      }
    } catch (Exception $e) {
      http_response_code(400);
      $endJSON['status'] = 'error';
      die($e->getMessage());
    }
    return $endJSON;
  }

  public function getAllStatusCoupon()
  {
    $endJSON["status"] = "";
    $endJSON["data"] = "";
    try {
      $query = "SELECT * FROM StatusCoupon ORDER BY statusCouponId;";
      $SelectAllResult = $this->GetAll($query);
      if ($SelectAllResult) {
         $endJSON["status"] = "success";
        $endJSON["data"] = $SelectAllResult;
      } else {
        throw new Exception("Error: SQL getAllStatusCoupon", 1);
      }
    } catch (Exception $e) {
      http_response_code(400);
      $endJSON['status'] = 'error';
      die($e->getMessage());
    }
    return $endJSON;
  }

  public function getStatusCoupon(array $data)
  {
    $endJSON["status"] = "";
    $endJSON["data"] = "";
    try {
      if (isset($data['id'])) {
        $statusCouponId = $data['id'];
        $query = "SELECT * FROM StatusCoupon WHERE statusCouponId = '$statusCouponId';";
        $SelectAllResult = $this->GetAll($query);
        if ($SelectAllResult) {
           $endJSON["status"] = "success";
          $endJSON["data"] = $SelectAllResult;
        } else {
          throw new Exception("Error: SQL getStatusCoupon id", 1);
        }
      } else if (isset($data['name'])) {
        $name = $data['name'];
        $query = "SELECT * FROM StatusCoupon WHERE name = '$name';";
        $SelectAllResult = $this->GetAll($query);
        if ($SelectAllResult) {
           $endJSON["status"] = "success";
          $endJSON["data"] = $SelectAllResult;
        } else {
          throw new Exception("Error: SQL getStatusCoupon name", 1);
        }
      } else {
        throw new Exception("Error: invalid parameters for StatusCoupon", 1);
      }
    } catch (Exception $e) {
      http_response_code(400);
      $endJSON['status'] = 'error';
      die($e->getMessage());
    }
    return $endJSON;
  }

  public function updateStatusCoupon(array $data)
  {
    $endJSON["status"] = "";
    $endJSON["data"] = "";
    try {
      if (
        isset($data['id']) &&
        isset($data['name'])
      ) {
        $statusCouponId = $data['id'];
        $name = $data['name'];
        $query = "UPDATE StatusCoupon SET name = '$name' WHERE statusCouponId = '$statusCouponId';";
        $QueryCallback = $this->ExecuteSql($query);
        if ($QueryCallback) {
           $endJSON["status"] = "success";
          $endJSON["data"] = $data;
        } else {
          throw new Exception("Error: SQL updateStatusCoupon", 1);
        }
      } else {
        throw new Exception("Error: invalid parameters for StatusCoupon", 1);
      }
    } catch (Exception $e) {
      $endJSON['status'] = 'error';
      http_response_code(400);
      die($e->getMessage());
    }
    return $endJSON;
  }

  public function deleteStatusCoupon(array $data)
  {
    $endJSON["status"] = "";
    $endJSON["data"] = "";
    try {
      if (isset($data['id'])) {
        $statusCouponId = $data['id'];
        $query = "DELETE FROM StatusCoupon WHERE statusCouponId = '$statusCouponId';";
        $QueryCallback = $this->ExecuteSql($query);
        if ($QueryCallback) {
           $endJSON["status"] = "success";
          $endJSON["data"] = "{}";
        } else {
          throw new Exception("Error: SQL deleteStatusCoupon", 1);
        }
      } else {
        throw new Exception("Error: invalid parameters for StatusCoupon", 1);
      }
    } catch (Exception $e) {
      http_response_code(400);
      $endJSON['status'] = 'error';
      die($e->getMessage());
    }
    return $endJSON;
  }
}
