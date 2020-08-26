<?php

class PaymentNoteModel extends Database
{
  public function __construct()
  {
    parent::__construct();
  }

   public function createPaymentNote(array $data) {
      $endJSON["status"] = "";
      $endJSON["data"] = "";
      try {
          if (!isset($data['PaymentNoteDescription'])) {
             throw new Exception("Error: Params createPayment required PaymentNoteDescription", 400);
          }
          if (!isset($data['Payment_idPayment'])) {
             throw new Exception("Error: Params createPayment required Payment_idPayment", 400);
          }
          if (!isset($data['userId'])) {
             throw new Exception("Error: Params createPayment required SystemUser_idSystemUser", 400);
          }

          $paymentDescription = $data['PaymentNoteDescription'];
          $paymentId          = $data['Payment_idPayment'];
          $systemUserId       = $data['userId'];


          date_default_timezone_set('Canada/Eastern');
          $PaymentNoteDate = new DateTime('NOW');
 
          $PaymentNoteDate = $PaymentNoteDate->format('Y-m-d H:i:s');


          $query = "INSERT INTO Paymentnote(
                                   PaymentNoteDescription,
                                   Payment_idPayment,
                                   SystemUser_idSystemUser,
                                   PaymentNoteDate)
                            VALUES (
                                   '$paymentDescription',
                                   '$paymentId',
                                   '$systemUserId',
                                   '$PaymentNoteDate');";
          $QueryCallback = $this->ExecuteSql($query);
              if ($QueryCallback) {
                  $data['PaymentNoteId'] = intval($this->GetOne("SELECT LAST_INSERT_ID()"));
                  $endJSON["status"] = "success";
                  $endJSON["message"] = "PaymentNote was created.";
                  $endJSON["data"] = $data;
              } else {
                  throw new Exception("Error: SQL createPaymentNote", 400);
              }
      } catch (Exception $e) {
         $endJSON['status'] = 'error';
         $endJSON["message"] = $e->getMessage();
         $endJSON["code"] = $e->getCode();
      }
      return $endJSON;
   }

   public function listPaimentNotes(array $data) {
        $endJSON["status"] = "";
        $endJSON["data"] = "";
        try {

             if (!isset($data['Payment_idPayment'])) {
                throw new Exception("Error: Params createPayment required Payment_idPayment", 400);
             }

             $paymentId          = $data['Payment_idPayment'];

             $query = "SELECT
                         pmt.*,
                         us.SystemUserNickName,
                         us.SystemUserEmail
                         FROM paymentnote pmt
                       inner join systemuser us on us.idSystemUser = pmt.SystemUser_idSystemUser
                       WHERE Payment_idPayment = '$paymentId' order by PaymentNoteDate desc;";
             $SelectAllResult = $this->GetAll($query);
             if ($SelectAllResult) {
                $endJSON["status"] = "success";
                $endJSON["data"] = $SelectAllResult;
             } else {
               throw new Exception("Error: SQL listPaimentNotes id", 1);
             }
        } catch (Exception $e) {
              $endJSON['status'] = 'error';
              $endJSON["message"] = $e->getMessage();
              $endJSON["code"] = $e->getCode();
        }
        return $endJSON;
   }
}
