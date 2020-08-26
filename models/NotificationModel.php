<?php
class NotificationModel extends Database
{
  public function __construct()
  {
    parent::__construct();
  }

  public function sendedNewNotification(array $data)
  {
    $endJSON["status"] = "";
    $endJSON["data"] = "";
    try {
      if (!isset($data['title'])) {
        throw new Exception("Error: Params sendedNewNotification required title", 400);
      }
      if (!isset($data['subtitle'])) {
        throw new Exception("Params sendedNewNotification required subtitle", 400);
      }
      if (!isset($data['message'])) {
        throw new Exception("Params sendedNewNotification required message", 400);
      }
      if (!isset($data['typeNotification'])) {
        throw new Exception("Params sendedNewNotification required typeNotification", 400);
      }
      if (!isset($data['userId'])) {
        throw new Exception("Params sendedNewNotification required userId", 400);
      }

      $userId             =  $data['userId'];
      $title              =  $data['title'];
      $subtitle           =  $data['subtitle'];
      $message            =  $data['message'];
      $imagePath          =  $data['imagePath']        ?? NULL;
      $lanuchURL          =  $data['lanuchURL']        ?? NULL;
      $additionalData     =  $data['additionalData']   ?? NULL;
      $actionsButtons     =  $data['actionsButtons']   ?? NULL;
      $seen               =  $data['seen']             ?? 0;
      $typeNotification   =  $data['typeNotification'];
      $sendedDateTime     =  $data['sendedDateTime']   ?? $this->getTimestamp();
      $UUIDNotification   =  $data['UUIDNotification'] ?? NULL;
      $query = "INSERT INTO Notification(User_userId, sendedDateTime, title, subtitle, message, imagePath, lanuchURL, additionalData, actionsButtons, seen, UUIDNotification, TypeNotification_typeNotificationId) VALUES ('$userId', '$sendedDateTime', '$title', '$subtitle', '$message', '$imagePath', '$lanuchURL', '$additionalData', '$actionsButtons', '$seen', '$UUIDNotification', '$typeNotification');";
      $QueryCallback = $this->ExecuteSql($query);
      if ($QueryCallback) {
        $data['NotificationId'] = intval($this->GetOne("SELECT LAST_INSERT_ID()"));
         $endJSON["status"] = "success";
        $endJSON["message"] = "Notification was created.";
        unset($data['sendedDateTime']);
        unset($data['message']);
        unset($data['userId']);
        unset($data['UUIDNotification']);
        $endJSON["data"] = $data;
      } else {
        throw new Exception("Error: SQL sendNewNotification", 400);
      }
    } catch (Exception $e) {
      $endJSON['status'] = 'error';
      $endJSON["message"] = $e->getMessage();
      $endJSON["code"] = $e->getCode();
    }
    return $endJSON;
  }

  public function getAllNotification(array $data)
  {
    $endJSON["status"] = "";
    $endJSON["data"] = "";
    try {
      if (isset($data['seen'])) {
        $seen = $data['seen'];
        // $sendedDateTime     = (isset($data['sendedDateTime']))   ? $data['sendedDateTime']   : $this->getTimestamp();
        $query = "SELECT notificationId, User_userId, FROM_UNIXTIME(sendedDateTime) as sendedDateTime, message, seen, UUIDNotification FROM Notification WHERE seen = $seen AND sendedDateTime <= UNIX_TIMESTAMP() ORDER BY sendedDateTime";
      } else {
        $query = "SELECT notificationId, User_userId, FROM_UNIXTIME(sendedDateTime) as sendedDateTime, message, seen, UUIDNotification  FROM Notification ORDER BY sendedDateTime;";
      }
      $SelectAllResult = $this->GetAll($query);
      if ($SelectAllResult) {
         $endJSON["status"] = "success";
        $endJSON["data"] = $SelectAllResult;
      } else {
        throw new Exception("Error: SQL getAllNotification", 1);
      }
    } catch (Exception $e) {
      http_response_code(400);
      $endJSON['status'] = 'error';
      die($e->getMessage());
    }
    return $endJSON;
  }

  public function getAllNotificationByUser(array $data)
  {
    $endJSON["status"] = "";
    $endJSON["data"] = "";
    try {
      if (isset($data['seen'])) {
        $seen = $data['seen'];
        $dateTimeStarted = $data['dateTimeStarted'];
        // $sendedDateTime     = (isset($data['sendedDateTime']))   ? $data['sendedDateTime']   : $this->getTimestamp();
        $query = "SELECT notificationId, User_userId, FROM_UNIXTIME(sendedDateTime) as sendedDateTime, message, seen, UUIDNotification FROM Notification WHERE seen = $seen  AND sendedDateTime BETWEEN $dateTimeStarted AND UNIX_TIMESTAMP() ORDER BY sendedDateTime DESC";
      } else {
        $query = "SELECT notificationId, User_userId, FROM_UNIXTIME(sendedDateTime) as sendedDateTime, message, seen, UUIDNotification  FROM Notification ORDER BY sendedDateTime;";
      }
      $SelectAllResult = $this->GetAll($query);
      if ($SelectAllResult) {
         $endJSON["status"] = "success";
        $endJSON["data"] = $SelectAllResult;
      } else {
        throw new Exception("Error: SQL getAllNotification", 1);
      }
    } catch (Exception $e) {
      http_response_code(400);
      $endJSON['status'] = 'error';
      die($e->getMessage());
    }
    return $endJSON;
  }

  public function getNotification(array $data)
  {
    $endJSON["status"] = "";
    $endJSON["data"] = "";
    try {
      if (isset($data['id'])) {
        $id = $data['id'];
        $query = "SELECT * FROM Notification WHERE id = '$id';";
        $SelectAllResult = $this->GetAll($query);
        if ($SelectAllResult) {
           $endJSON["status"] = "success";
          $endJSON["data"] = $SelectAllResult;
        } else {
          throw new Exception("Error: SQL getNotification id", 1);
        }
      } else {
        throw new Exception("Error: invalid parameters for Notification", 1);
      }
    } catch (Exception $e) {
      http_response_code(400);
      $endJSON['status'] = 'error';
      die($e->getMessage());
    }
    return $endJSON;
  }

  public function updatedNotification(array $data)
  {
    $endJSON["status"] = "";
    $endJSON["data"] = "";
    try {
      if (
        isset($data['userId']) &&
        isset($data['seen']) &&
        isset($data['notificationId'])
      ) {
        $notificationId = $data['notificationId'];
        $seen = $data['seen'];
        $query = "UPDATE Notification SET seen = '$seen' WHERE notificationId = $notificationId;";
        $QueryCallback = $this->ExecuteSql($query);
        if ($QueryCallback) {
           $endJSON["status"] = "success";
          $endJSON["message"] = "Notification was created.";
          $endJSON["data"] = $data;
        } else {
          throw new Exception("Error: SQL updatedNotification id", 1);
        }
      } else {
        throw new Exception("Error: invalid parameters for Notification", 1);
      }
    } catch (Exception $e) {
      http_response_code(400);
      $endJSON['status'] = 'error';
      die($e->getMessage());
    }
    return $endJSON;
  }
}
