<?php
class CouponModel extends Database
{
  public function __construct()
  {
    parent::__construct();
  }

  public function addNewCoupon(array $data)
  {
    $endJSON["status"] = "";
    $endJSON["data"] = "";
    try {
      if (
        isset($data['code']) &&
        isset($data['expiredDateTime']) &&
        isset($data['statusCouponId']) &&
        isset($data['userId'])
      ) {
        $createdDateTime = $this->getTimestamp();
        $code = $data['code'];
        $expiredDateTime = $data['expiredDateTime'];
        $statusCouponId = $data['statusCouponId'];
        $userId = $data['userId'];
        $query = "INSERT INTO Coupon (code, createdDateTime, expiredDateTime, StatusCoupon_statusCouponId, User_userId) VALUES ('$code', '$createdDateTime', '$expiredDateTime', '$statusCouponId', '$userId');";
        $QueryCallback = $this->ExecuteSql($query);
        if ($QueryCallback) {
           $endJSON["status"] = "success";
          $endJSON["message"] = "Coupon was created.";
          $endJSON["data"] = $data;
        } else {
          throw new Exception("Error: SQL registerNewCoupon", 1);
        }
      } else {
        throw new Exception("Error: invalid parameters for Coupon", 1);
      }
    } catch (Exception $e) {
      http_response_code(400);
      $endJSON['status'] = 'error';
      die($e->getMessage());
    }
    return $endJSON;
  }

  public function getAllCoupon()
  {
    $endJSON["status"] = "";
    $endJSON["data"] = "";
    try {
      $query = "SELECT * FROM Coupon ORDER BY couponId;";
      $SelectAllResult = $this->GetAll($query);
      if ($SelectAllResult) {
         $endJSON["status"] = "success";
        $endJSON["data"] = $SelectAllResult;
      } else {
        throw new Exception("Error: SQL getAllCoupon", 1);
      }
    } catch (Exception $e) {
      http_response_code(400);
      $endJSON['status'] = 'error';
      die($e->getMessage());
    }
    return $endJSON;
  }

  public function getCoupon(array $data)
  {
    $endJSON["status"] = "";
    $endJSON["data"] = "";
    try {
      if (isset($data['code'])) {
        $code = $data['code'];
        $query = "SELECT * FROM Coupon WHERE code = '$code';";
        $SelectAllResult = $this->GetAll($query);
        if ($SelectAllResult) {
           $endJSON["status"] = "success";
          $endJSON["data"] = $SelectAllResult;
        } else {
          throw new Exception("Error: SQL getCoupon code", 1);
        }
      } else if (isset($data['expiredDateTime'])) {
        $expiredDateTime = $data['expiredDateTime'];
        $query = "SELECT * FROM Coupon WHERE expiredDateTime = '$expiredDateTime';";
        $SelectAllResult = $this->GetAll($query);
        if ($SelectAllResult) {
           $endJSON["status"] = "success";
          $endJSON["data"] = $SelectAllResult;
        } else {
          throw new Exception("Error: SQL getCoupon expiredDateTime", 1);
        }
      } else if (isset($data['statusCouponId'])) {
        $statusCouponId = $data['statusCouponId'];
        $query = "SELECT * FROM Coupon WHERE StatusCoupon_statusCouponId = '$statusCouponId';";
        $SelectAllResult = $this->GetAll($query);
        if ($SelectAllResult) {
           $endJSON["status"] = "success";
          $endJSON["data"] = $SelectAllResult;
        } else {
          throw new Exception("Error: SQL getCoupon statusCouponId", 1);
        }
      } else if (isset($data['userId'])) {
        $userId = $data['userId'];
        $query = "SELECT * FROM Coupon WHERE User_userId = '$userId';";
        $SelectAllResult = $this->GetAll($query);
        if ($SelectAllResult) {
           $endJSON["status"] = "success";
          $endJSON["data"] = $SelectAllResult;
        } else {
          throw new Exception("Error: SQL getCoupon userId", 1);
        }
      } else {
        throw new Exception("Error: invalid parameters for Coupon", 1);
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
        isset($data['userId']) &&
        isset($data['code']) &&
        isset($data['statusCouponId'])
      ) {
        $userId = $data['userId'];
        $code = $data['code'];
        $statusCouponId = $data['statusCouponId'];
        $query = "UPDATE `Coupon` SET StatusCoupon_statusCouponId = '$statusCouponId' WHERE userId = '$userId' AND code = '$code';";
        $QueryCallback = $this->ExecuteSql($query);
        if ($QueryCallback) {
           $endJSON["status"] = "success";
          $endJSON["data"] = $data;
        } else {
          throw new Exception("Error: SQL updateStatusCoupon", 1);
        }
      } else {
        throw new Exception("Error: invalid parameters for Coupon", 1);
      }
    } catch (Exception $e) {
      $endJSON['status'] = 'error';
      http_response_code(400);
      die($e->getMessage());
    }
    return $endJSON;
  }
}
