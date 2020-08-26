<?php
class DeviceModel extends Database
{
  const table = 'Device';
  const tableColumns = array(
    'deviceId' => array(
      'primaryKey' => true,
      // 'required' => true,
    ),
    'User_userId' => array(
      // 'primaryKey' => false,
      'required' => true,
    ),
    'UUID' => array(
      // 'primaryKey' => false,
      'required' => true,
    ),
    'StatusDevice_statusDeviceId' => array(
      // 'primaryKey' => false,
      'required' => true,
    ),
    'subscriptionDateTime' => array(
      // 'primaryKey' => false,
      // 'required' => true,
    ),
    'lastConnectionDateTime' => array(
      // 'primaryKey' => false,
      // 'required' => true,
    )
  );
  public function __construct()
  {
    parent::__construct();
  }

  public function insertDevice(array $data)
  {
    $endJSON["status"] = "";
    $endJSON["data"] = "";
    try {
      if (
        isset($data['userId']) &&
        isset($data['UUID'])
      ) {
        if ($data['UUID'] === '') {
          throw new Exception("Empty value UUID", 1);
        }

        $foundDeviceUUID = $this->getDeviceId($data['UUID']);
        if ($foundDeviceUUID['data'] == 'No registry found') {
          $userId = $data['userId'];
          $UUID = $data['UUID'];
          $statusDeviceId = 1; // Active
          $subscriptionDateTime = parent::getTimestamp();
          $lastConnectionDateTime = parent::getTimestamp();
          $query = "INSERT INTO Device(User_userId, UUID, StatusDevice_statusDeviceId, subscriptionDateTime, lastConnectionDateTime) VALUES ('$userId', '$UUID', '$statusDeviceId', '$subscriptionDateTime', '$lastConnectionDateTime');";
          $QueryCallback = $this->ExecuteSql($query);
          if ($QueryCallback) {
            $data['deviceId'] = intval($this->GetOne("SELECT LAST_INSERT_ID()"));
             $endJSON["status"] = "success";
            $endJSON["message"] = "Device was created.";
            $endJSON["data"] = $data;
          }
        } else {
          $data['statusDeviceId'] = 1;
          $data['subscriptionDateTime'] = parent::getTimestamp();
          $data['lastConnectionDateTime'] = parent::getTimestamp();
          $data['deviceId'] = $foundDeviceUUID['data'][0]['deviceId'];
          $endJSON["data"] =  $this->updateDevice($data);
          $endJSON["data"] = $endJSON["data"]["data"];
           $endJSON["status"] = "success";
          $endJSON["message"] = "Device was updated.";
        }
      } else {
        throw new Exception("Error: invalid parameters 'UUID' (required) for Device", 1);
      }
    } catch (Exception $e) {
      http_response_code(400);
      $endJSON['status'] = 'error';
      $endJSON["message"] = $e->getMessage();
    }
    return $endJSON;
  }

  public function getAllDevice()
  {
    $endJSON["status"] = "";
    $endJSON["data"] = "";
    try {
      $query = "SELECT * FROM viewDevice;";
      $SelectAllResult = $this->GetAll($query);
      if ($SelectAllResult) {
         $endJSON["status"] = "success";
        $endJSON["data"] = $SelectAllResult;
      } else {
        throw new Exception("Error: SQL getAllDevice", 1);
      }
    } catch (Exception $e) {
      http_response_code(400);
      $endJSON['status'] = 'error';
      die($e->getMessage());
    }
    return $endJSON;
  }

  public function getDeviceRol(array $data)
  {
    $QueryCallback = $this->ExecuteAutoSQL(
      $mode = 'SELECT',
      $table = 'viewDevice',
      $columns = self::tableColumns,
      $data = $data
    );

    if ($QueryCallback) {
      $endJSON["data"] = $QueryCallback;
       $endJSON["status"] = "success";
    }
    return $endJSON;
  }

  public function getDeviceUser(array $data)
  {
    $endJSON["status"] = "";
    $endJSON["data"] = "";
    try {
      if (isset($data['userId'])) {
        $userId = $data['userId'];
        $statusDeviceId = 1; //ACTIVE
        $query = "SELECT * FROM viewDevice WHERE viewDevice.userId = '$userId' AND statusDeviceId = '$statusDeviceId'";
        $SelectAllResult = $this->GetAll($query);
        if ($SelectAllResult) {
           $endJSON["status"] = "success";
          $endJSON["data"] = $SelectAllResult;
        } else {
          throw new Exception("SQL getDeviceUser id", 400);
        }
      } else {
        throw new Exception("invalid parameters for Device", 400);
      }
    } catch (Exception $e) {
      $endJSON['status'] = 'error';
      $endJSON["message"] = '⚠ Exception appear: ' . $e->getMessage();
      $endJSON["code"] = $e->getCode();
    }
    return $endJSON;
  }

  private function getDeviceId(string $deviceUUID)
  {
    $endJSON["status"] = "";
    $endJSON["data"] = "";
    try {
      if (isset($deviceUUID)) {
        $statusDeviceId = 1; //ACTIVE
        $query = "SELECT * FROM viewDevice WHERE viewDevice.UUID = '$deviceUUID' AND statusDeviceId = '$statusDeviceId'";
        $SelectAllResult = $this->GetAll($query);
        if ($SelectAllResult) {
           $endJSON["status"] = "success";
          $endJSON["data"] = $SelectAllResult;
        } else {
          throw new Exception("SQL getDeviceUser id", 400);
        }
      } else {
        throw new Exception("invalid parameters for Device", 400);
      }
    } catch (Exception $e) {
      $endJSON['status'] = 'error';
      $endJSON["message"] = '⚠ Exception appear: ' . $e->getMessage();
      $endJSON["code"] = $e->getCode();
    }
    return $endJSON;
  }


  public function updateDevice(array $data)
  {
    $endJSON["status"] = "";
    $endJSON["data"] = "";
    try {
      if (
        isset($data['userId']) &&
        isset($data['UUID']) &&
        isset($data['statusDeviceId']) &&
        isset($data['subscriptionDateTime']) &&
        isset($data['lastConnectionDateTime']) &&
        isset($data['deviceId'])
      ) {
        $userId = $data['userId'];
        $UUID = $data['UUID'];
        $statusDeviceId = $data['statusDeviceId'];
        $subscriptionDateTime = $data['subscriptionDateTime'];
        $lastConnectionDateTime = $data['lastConnectionDateTime'];
        $deviceId = $data['deviceId'];
        $query = "UPDATE Device SET User_userId = '$userId', UUID = '$UUID', StatusDevice_statusDeviceId = '$statusDeviceId', subscriptionDateTime = '$subscriptionDateTime', lastConnectionDateTime = '$lastConnectionDateTime' WHERE deviceId = '$deviceId';";
        $QueryCallback = $this->ExecuteSql($query);
        if ($QueryCallback) {
           $endJSON["status"] = "success";
          $endJSON["data"] = $data;
        } else {
          throw new Exception("Error: SQL updateDevice", 1);
        }
      } else {
        throw new Exception('{"Error":"No data params Device"}', 1);
      }
    } catch (Exception $e) {
      $endJSON['status'] = 'error';
      http_response_code(400);
      die($e->getMessage());
    }
    return $endJSON;
  }

  public function deleteDevice(array $data)
  {
    $endJSON["status"] = "";
    $endJSON["data"] = "";
    try {
      if (isset($data['deviceId'])) {
        $deviceId = $data['deviceId'];
        $query = "DELETE FROM Device WHERE deviceId = '$deviceId';";
        $QueryCallback = $this->ExecuteSql($query);
        if ($QueryCallback) {
          $endJSON["status"] = "Success deleted device";
          $endJSON["data"] = [];
        } else {
          throw new Exception("Error: SQL deleteDevice", 1);
        }
      } else {
        throw new Exception('{"Error":"No data params Device"}', 1);
      }
    } catch (Exception $e) {
      $endJSON['status'] = 'error';
      http_response_code(400);
      die($e->getMessage());
    }
    return $endJSON;
  }
}
