<?php
class TransactionPaymentGatewayModel extends Database
{

  const table = 'TransactionPaymentGateway';
  const tableColumns = array(
    'transactionPaymentGatewayId' => array(
      'primaryKey' => true,
      // 'required' => true,
    ),
    'refNumber' => array(
      // 'primaryKey' => false,
      'required' => true,
    ),
    'orderIdentifier' => array(
      // 'primaryKey' => false,
      'required' => true,
    ),
    'creationDateTime' => array(
      // 'primaryKey' => false,
      'required' => true,
    ),
    'updateDateTime' => array(
      // 'primaryKey' => false,
      // 'required' => true,
    ),
    'orderDescription' => array(
      // 'primaryKey' => false,
      // 'required' => true,
    ),
    'method' => array(
      // 'primaryKey' => false,
      // 'required' => false,
    ),
    'checkoutURL' => array(
      // 'primaryKey' => false,
      'required' => true,
    ),
    'StatusPayment_statusPaymentId' => array(
      // 'primaryKey' => false,
      'required' => true,
    )
  );

  public function __construct()
  {
    parent::__construct();
  }

  public function insertTransactionPaymentGateway(array $data):array
  {
    if (!isset($data['refNumber'])) {
      throw new Exception("Required Params on insertTransactionPaymentGateway: refNumber", 400);
    }
    if (!isset($data['orderIdentifier'])) {
      throw new Exception("Required Params on insertTransactionPaymentGateway: orderIdentifier", 400);
    }
    if (!isset($data['orderDescription'])) {
      throw new Exception("Required Params on insertTransactionPaymentGateway: orderDescription", 400);
    }
    if (!isset($data['checkoutURL'])) {
      throw new Exception("Required Params on insertTransactionPaymentGateway: checkoutURL", 400);
    }
    if (!isset($data['StatusPayment_statusPaymentId'])) {
      throw new Exception("Required Params on insertTransactionPaymentGateway: statusPaymentId", 400);
    }
    $dataSQL['transactionPaymentGatewayId'] = $data['transactionPaymentGatewayId'] ?? NULL;
    $dataSQL['refNumber'] = $data['refNumber'];
    $dataSQL['orderIdentifier'] = $data['orderIdentifier'];
    $dataSQL['creationDateTime'] = $data['creationDateTime'] ?? NULL;
    $dataSQL['updateDateTime'] = $data['updateDateTime'] ?? NULL;
    $dataSQL['orderDescription'] = $data['orderDescription'];
    $dataSQL['method'] = $data['method'] ?? NULL;
    $dataSQL['checkoutURL'] = $data['checkoutURL'];
    $dataSQL['StatusPayment_statusPaymentId'] = $data['StatusPayment_statusPaymentId'];
    $dataSQL['transactionPaymentGatewayId'] = $data['transactionPaymentGatewayId'] ?? NULL;
    $dataSQL['refNumber'] = $data['refNumber'] ?? NULL;
    $dataSQL['orderIdentifier'] = $data['orderIdentifier'] ?? NULL;
    $dataSQL['creationDateTime'] = $data['creationDateTime'] ?? NULL;
    $dataSQL['updateDateTime'] = $data['updateDateTime'] ?? NULL;
    $dataSQL['orderDescription'] = $data['orderDescription'] ?? NULL;
    $dataSQL['method'] = $data['method'] ?? NULL;
    $dataSQL['checkoutURL'] = $data['checkoutURL'] ?? NULL;
    $dataSQL['StatusPayment_statusPaymentId'] = $data['StatusPayment_statusPaymentId'] ?? NULL;


    $QueryCallback = $this->ExecuteAutoSQL(
      $mode = 'INSERT',
      $table = self::table,
      $columns = self::tableColumns,
      $data = $dataSQL
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
      $endJSON["message"] = "Request was created.";
    }
    return $endJSON;
  }

  public function getAllTransactionPaymentGateway(array $data)
  {
    $endJSON["status"] = "";
    $endJSON["data"] = "";
    try {
      if (isset($data['seen'])) {
        $seen = $data['seen'];
        // $sendedDateTime     = (isset($data['sendedDateTime']))   ? $data['sendedDateTime']   : $this->getTimestamp();
        $query = "SELECT notificationId, User_userId, FROM_UNIXTIME(sendedDateTime) as sendedDateTime, message, seen, UUIDTransactionPaymentGateway FROM TransactionPaymentGateway WHERE seen = $seen AND sendedDateTime <= UNIX_TIMESTAMP() ORDER BY sendedDateTime";
      } else {
        $query = "SELECT notificationId, User_userId, FROM_UNIXTIME(sendedDateTime) as sendedDateTime, message, seen, UUIDTransactionPaymentGateway  FROM TransactionPaymentGateway ORDER BY sendedDateTime;";
      }
      $SelectAllResult = $this->GetAll($query);
      if ($SelectAllResult) {
         $endJSON["status"] = "success";
        $endJSON["data"] = $SelectAllResult;
      } else {
        throw new Exception("SQL getAllTransactionPaymentGateway", 1);
      }
    } catch (Exception $e) {
      http_response_code(400);
      $endJSON['status'] = 'error';
      die($e->getMessage());
    }
    return $endJSON;
  }

  public function getTransactionPaymentGateway(array $data)
  {
    $endJSON["status"] = "";
    $endJSON["data"] = "";
    try {
      if (isset($data['id'])) {
        $id = $data['id'];
        $query = "SELECT * FROM TransactionPaymentGateway WHERE id = '$id';";
        $SelectAllResult = $this->GetAll($query);
        if ($SelectAllResult) {
           $endJSON["status"] = "success";
          $endJSON["data"] = $SelectAllResult;
        } else {
          throw new Exception("Error: SQL getTransactionPaymentGateway id", 1);
        }
      } else {
        throw new Exception("Error: invalid parameters for TransactionPaymentGateway", 1);
      }
    } catch (Exception $e) {
      http_response_code(400);
      $endJSON['status'] = 'error';
      die($e->getMessage());
    }
    return $endJSON;
  }

  public function updateTransactionPaymentGateway(array $data)
  {
    $endJSON["status"] = "";
    $endJSON["data"] = "";
    try {
      $dataSQL = array();
      if (!isset($data['transactionPaymentGatewayId'])) {
        throw new Exception("Required Params on insertTransactionPaymentGateway: transactionPaymentGatewayId", 400);
      } elseif (
        isset($data['transactionPaymentGatewayId']) &&
        (isset($data['StatusPayment_statusPaymentId']) || isset($data['serviceStatusId'])) &&
        isset($data['transactionPaymentGatewayUpdateDateTime'])
      ) {
        $dataSQL['_documentID']                    = 'transactionPaymentGatewayId';
        $dataSQL['transactionPaymentGatewayId']   = $data['transactionPaymentGatewayId'];
        $dataSQL['StatusPayment_statusPaymentId'] = $data['StatusPayment_statusPaymentId'] ?? $data['serviceStatusId'];
        $dataSQL['updateDateTime']                = $data['transactionPaymentGatewayUpdateDateTime'];
      }
      $QueryCallback = $this->ExecuteAutoSQL(
        $mode = 'UPDATE',
        $table = self::table,
        $columns = self::tableColumns,
        $data = $dataSQL
      );

      if ($QueryCallback) {
         $endJSON["status"] = "success";
        $endJSON["message"] = "TransactionPaymentGateway was updated.";
        $endJSON["data"] = $data;
      } else {
        throw new Exception("Error: SQL updatedTransactionPaymentGateway id", 1);
      }
    } catch (Exception $e) {
      $endJSON['status'] = 'error';
      $endJSON["message"] = $e->getMessage();
      $endJSON["code"] = $e->getCode();
    }
    return $endJSON;
  }
}
