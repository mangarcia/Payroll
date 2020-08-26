<?php
class AddressModel extends Database
{
  public function __construct()
  {
    parent::__construct();
  }

  public function addNewAddress(array $data)
  {
    $endJSON["status"] = "";
    $endJSON["data"] = "";
    try {
      if (
        isset($data['userId']) &&
        isset($data['address'])
      ) {
        $createdAt             = parent::getTimestamp();
        $userId                = $data['userId'];
        $addressNameType       = (isset($data['addressNameType']))           ?  $data['addressNameType']           : NULL;
        $address               = $data['address'];
        $addressInfoAdditional = (isset($data['addressInfoAdditional']))     ?  $data['addressInfoAdditional']     : NULL;
        $addressCityId         = (isset($data['addressCityId']))             ?  $data['addressCityId']             : 1;
        $longitude             = (isset($data['longitude']))                 ?  $data['longitude']                 : NULL;
        $latitude              = (isset($data['latitude']))                  ?  $data['latitude']                  : NULL;
        $plusCodes             = (isset($data['plusCodes']))                 ?  $data['plusCodes']                 : NULL;
        $statusAddressId       = 1;                                          // Active
        $query = "INSERT INTO Address (User_userId, addressNameType, address, addressInfoAdditional, City_addressCityId, longitude, latitude, plusCodes, StatusAddress_statusAddressId, createdAt) VALUES ('$userId', '$addressNameType', '$address', '$addressInfoAdditional', '$addressCityId', '$longitude', '$latitude', '$plusCodes', '$statusAddressId', '$createdAt');";
        $QueryCallback = $this->ExecuteSql($query);
        if ($QueryCallback) {
           $endJSON["status"] = "success";
          $endJSON["message"] = "Address was created.";
          $endJSON["data"] = $data;
        } else {
          throw new Exception("Error: SQL addNewAddress", 1);
        }
      } else {
        throw new Exception("Error: invalid parameters for Address", 1);
      }
    } catch (Exception $e) {
      http_response_code(400);
      $endJSON['status'] = 'error';
      die($e->getMessage());
    }
    return $endJSON;
  }

  public function getAllAddress()
  {
    $endJSON["status"] = "";
    $endJSON["data"] = "";
    try {
      $query = "SELECT * FROM Address ORDER BY addressId;";
      $SelectAllResult = $this->GetAll($query);
      if ($SelectAllResult) {
         $endJSON["status"] = "success";
        $endJSON["data"] = $SelectAllResult;
      } else {
        throw new Exception("Error: SQL getAllAddress", 1);
      }
    } catch (Exception $e) {
      http_response_code(400);
      $endJSON['status'] = 'error';
      die($e->getMessage());
    }
    return $endJSON;
  }

  public function getAddressByUser(array $data)
  {
    $endJSON["status"] = "";
    $endJSON["data"] = "";
    try {
      if (isset($data['userId'])) {
        $userId = $data['userId'];
        $query = "SELECT * FROM viewAddress WHERE User_userId = '$userId' AND statusAddressId = 1;";
        $SelectAllResult = $this->GetAll($query);
        if ($SelectAllResult) {
           $endJSON["status"] = "success";
          $endJSON["data"] = $SelectAllResult;
        } else {
          throw new Exception("Error: SQL getAddressByUser: empty or sintaxis", 1);
        }
      } else {
        throw new Exception("Error: invalid parameters for Address", 1);
      }
    } catch (Exception $e) {
      http_response_code(400);
      $endJSON['status'] = 'error';
      die($e->getMessage());
    }
    return $endJSON;
  }

  public function updateAddress(array $data)
  {
    $endJSON["status"] = "";
    $endJSON["data"] = "";
    try {
      if (
        isset($data['id']) &&
        isset($data['userId']) &&
        isset($data['address']) &&
        isset($data['statusAddressId'])
      ) {
        $addressId = $data['id'];
        $userId = $data['userId'];
        $addressNameType = isset($data['addressNameType']) ? $data['addressNameType'] : NULL;
        $address = $data['address'];
        $addressCity = isset($data['addressCity']) ? $data['addressCity'] : 'Bogotá D.C.';
        $longitude = isset($data['longitude']) ? $data['longitude'] : NULL;
        $latitude = isset($data['latitude']) ? $data['latitude'] : NULL;
        $plusCodes = isset($data['plusCodes']) ? $data['plusCodes'] : NULL;
        $statusAddressId =  isset($data['plusCodes']) ? $data['StatusAddress_statusAddressId'] : 1; //active
        $createdAt = parent::getTimestamp();
        $query = "UPDATE Address SET User_userId = '$userId', addressNameType = '$addressNameType', address = '$address', addressCity = '$addressCity', longitude = '$longitude', latitude = '$latitude', plusCodes = '$plusCodes', StatusAddress_statusAddressId = '$statusAddressId', createdAt = '$createdAt' WHERE addressId = '$addressId';";
        $QueryCallback = $this->ExecuteSql($query);
        if ($QueryCallback) {
           $endJSON["status"] = "success";
          $endJSON["data"] = $data;
        } else {
          throw new Exception("Error: SQL updateEmailAddress", 1);
        }
      } else {
        throw new Exception("Error: invalid parameters for Address", 1);
      }
    } catch (Exception $e) {
      $endJSON['status'] = 'error';
      http_response_code(400);
      die($e->getMessage());
    }
    return $endJSON;
  }

  public function updateStatusAddress(array $data)
  {
    $endJSON["status"] = "";
    $endJSON["data"] = "";
    try {
      if (
        isset($data['id']) &&
        isset($data['userId']) &&
        isset($data['statusAddressId'])
      ) {
        $addressId = $data['id'];
        $userId = $data['userId'];
        $statusAddressId = $data['StatusAddress_statusAddressId'];
        $query = "UPDATE Address SET StatusAddress_statusAddressId = '$statusAddressId' WHERE addressId = '$addressId' AND User_userId = '$userId';";
        $QueryCallback = $this->ExecuteSql($query);
        if ($QueryCallback) {
           $endJSON["status"] = "success";
          $endJSON["data"] = $data;
        } else {
          throw new Exception("Error: SQL updateStatusAddress", 1);
        }
      } else {
        throw new Exception("Error: invalid parameters for Address", 1);
      }
    } catch (Exception $e) {
      $endJSON['status'] = 'error';
      http_response_code(400);
      die($e->getMessage());
    }
    return $endJSON;
  }

  public function deleteAddress(array $data)
  {
    $data['statusAddressId'] = 2;
    return $this->updateStatusAddress($data);
  }
}
