<?php

class TypeNotificationModel extends Database
{
  const CREATED_REQUEST     = 1;
  const PAYMENT_CALLBACK    = 2;
  const CHAT_MESSAGE        = 3;
  const SINGLE_NOTIFICATION = 4;

  const table = 'TypeNotification';
  const tableColumns = array(
    'typeNotificationId' => array(
      'primaryKey' => true,
      // 'required' => true,
    ),
    'name' => array(
      // 'primaryKey' => false,
      // 'required' => false,
    )
  );

  public function __construct()
  {
    parent::__construct();
  }

  public function insertTypeNotification(array $data, $table = 'TypeNotification')
  {
    $QueryCallback = $this->ExecuteAutoSQL(
      $mode = 'INSERT',
      $table = self::table,
      $columns = self::tableColumns,
      $data = $data
    );

    if ($QueryCallback) {
      $data['lastID'] = intval($this->GetOne("SELECT LAST_INSERT_ID()"));
      $endJSON["data"] = $this->ExecuteAutoSQL(
        $mode = 'SELECT',
        $table = self::table,
        $columns = self::tableColumns,
        $data = $data
      );
       $endJSON["status"] = "success";
      $endJSON["message"] = "TypeNotification was created.";
    }
    return $endJSON;
  }

  public function getAllTypeNotification()
  {
    try {
      $query = "SELECT * FROM viewTypeNotification ORDER BY idTypeNotification;";
      $SelectAllResult = $this->GetAll($query);
      if ($SelectAllResult) {
         $endJSON["status"] = "success";
        $endJSON["data"] = $SelectAllResult;
      } else {
        throw new Exception("SQL getAllTypeNotification", 400);
      }
    } catch (Exception $e) {
      $endJSON['status'] = 'error';
      $endJSON["code"] = $e->getCode();
      $endJSON['message'] = $e->getMessage();
    } finally {
      return $endJSON;
    }
  }

  public function getTypeNotification(array $data)
  {
    $data = $data;
    $QueryCallback = $this->ExecuteAutoSQL(
      $mode = 'SELECT',
      $table = self::table,
      $columns = self::tableColumns,
      $data = $data
    );

    if ($QueryCallback) {
      $endJSON["data"] = $QueryCallback;
       $endJSON["status"] = "success";
    }
    return $endJSON;
  }

  public function updateTypeNotification(array $data)
  {
    $data = $data;
    $data['_documentID'] = 'idTypeNotification';
    $QueryCallback = $this->ExecuteAutoSQL(
      $mode = 'UPDATE',
      $table = self::table,
      $columns = self::tableColumns,
      $data = $data
    );

    if ($QueryCallback) {
      $endJSON["data"] = $data;
       $endJSON["status"] = "success";
      $endJSON["message"] = "TypeNotification was updated.";
    }
    return $endJSON;
  }

  public function deleteTypeNotification(array $data)
  {
    $data = $data;
    $QueryCallback =  $this->ExecuteAutoSQL(
      $mode = 'DELETE',
      $table = self::table,
      $columns = self::tableColumns,
      $data = $data
    );

    if ($QueryCallback) {
      $endJSON["code"] = "204";
      $endJSON["message"] = "TypeNotification was deleted.";
       $endJSON["status"] = "success";
    }
    return $endJSON;
  }
}
