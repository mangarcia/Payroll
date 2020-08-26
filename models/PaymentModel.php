<?php

class PaymentModel extends Database
{
  public function __construct()
  {
    parent::__construct();
  }

  public function createPayment(array $data) {

    $endJSON["status"] = "";
    $endJSON["data"] = "";
    try {
        if (!isset($data['idCompanyPayment'])) {
            throw new Exception("Error: Params createPayment required idCompanyPayment", 400);
        }
        if (!isset($data['PaymentValue'])) {
            throw new Exception("Error: Params createPayment required PaymentValue", 400);
        }
        if (!isset($data['PaymentStatus_idPaymentStatus'])) {
            throw new Exception("Error: Params createPayment required PaymentStatus_idPaymentStatus", 400);
        }
        if (!isset($data['PaymentGeneratedDateTime'])) {
            throw new Exception("Error: Params createPayment required PaymentGeneratedDateTime", 400);
        }
        if (!isset($data['SystemUser_idSystemUser'])) {
            throw new Exception("Error: Params createPayment required SystemUser_idSystemUser", 400);
        }
        if (!isset($data['Employee_idEmployee'])) {
            throw new Exception("Error: Params createPayment required Employee_idEmployee", 400);
        }
        if (!isset($data['paymentLocationId'])) {
            throw new Exception("Error: Params createPayment required paymentLocationId", 400);
        }
        if (!isset($data['PaymentFromDate'])) {
            throw new Exception("Error: Params createPayment required PaymentFromDate", 400);
        }
        if (!isset($data['PaymentToDate'])) {
            throw new Exception("Error: Params createPayment required PaymentToDate", 400);
        }
        if (!isset($data['PaymentHours'])) {
            throw new Exception("Error: Params createPayment required PaymentHours", 400);
        }

        $idCompanyPayment  = $data['idCompanyPayment'];
        $paymentValue      = $data['PaymentValue'];
        $paymentStatusId   = $data['PaymentStatus_idPaymentStatus'];
        $paymentGenerated  = $data['PaymentGeneratedDateTime'];
        $systemUserId      = $data['SystemUser_idSystemUser'];
        $employeeId        = $data['Employee_idEmployee'];
        $companyIdLocation = $data['paymentLocationId'];
        $paymentFromDate   = $data['PaymentFromDate'];
        $paymentToDate     = $data['PaymentToDate'];
        $PaymentHours     = $data['PaymentHours'];

        $query = "INSERT INTO Payment(
                    idCompanyPayment,
                    PaymentValue,
                    PaymentStatus_idPaymentStatus,
                    PaymentGeneratedDateTime,   
                    SystemUser_idSystemUser,
                    Employee_idEmployee,
                    paymentLocationId,
                    PaymentFromDate,
                    PaymentToDate,
                    PaymentHours) VALUES (
                        '$idCompanyPayment',
                        '$paymentValue',
                        '$paymentStatusId',
                        '$paymentGenerated',
                        '$systemUserId',
                        '$employeeId',
                        '$companyIdLocation',
                        '$paymentFromDate',
                        '$paymentToDate',
                        '$PaymentHours'
                    );";
        $QueryCallback = $this->ExecuteSql($query);
        if ($QueryCallback) {
            $data['PaymentId'] = intval($this->GetOne("SELECT LAST_INSERT_ID()"));
            $endJSON["status"] = "success";
            $endJSON["message"] = "Payment was created.";
            $endJSON["data"] = $data;
        } else {
            throw new Exception("Error: SQL createPayment", 400);
        }
    } catch (Exception $e) {
        $endJSON['status'] = 'error';
        $endJSON["message"] = $e->getMessage();
        $endJSON["code"] = $e->getCode();
    }
    return $endJSON;
  }

  public function updatePayment(array $data) {
      $endJSON["status"] = "";
      $endJSON["data"] = "";
      try {
        if (!isset($data['idPayment'])) {
            throw new Exception("Error: Params createPayment required idCompanyPayment", 400);
        }
        if (!isset($data['PaymentValue'])) {
            throw new Exception("Error: Params createPayment required PaymentValue", 400);
        }
        if (!isset($data['PaymentStatus_idPaymentStatus'])) {
            throw new Exception("Error: Params createPayment required PaymentStatus_idPaymentStatus", 400);
        }
        if (!isset($data['PaymentGeneratedDateTime'])) {
            throw new Exception("Error: Params createPayment required PaymentGeneratedDateTime", 400);
        }
        if (!isset($data['SystemUser_idSystemUser'])) {
            throw new Exception("Error: Params createPayment required SystemUser_idSystemUser", 400);
        }
        if (!isset($data['paymentLocationId'])) {
            throw new Exception("Error: Params createPayment required paymentLocationId", 400);
        }
        if (!isset($data['PaymentFromDate'])) {
            throw new Exception("Error: Params createPayment required PaymentFromDate", 400);
        }
        if (!isset($data['PaymentToDate'])) {
            throw new Exception("Error: Params createPayment required PaymentToDate", 400);
        }
        if (!isset($data['PaymentHours'])) {
            throw new Exception("Error: Params createPayment required PaymentHours", 400);
        }

        $idCompanyPayment  = $data['idCompanyPayment'];
        $paymentValue      = $data['PaymentValue'];
        $paymentStatusId   = $data['PaymentStatus_idPaymentStatus'];
        $paymentGenerated  = $data['PaymentGeneratedDateTime'];
        $systemUserId      = $data['SystemUser_idSystemUser'];
        $companyIdLocation = $data['paymentLocationId'];
        $paymentFromDate   = $data['PaymentFromDate'];
        $paymentToDate     = $data['PaymentToDate'];
        $PaymentHours      = $data['PaymentHours'];
        $idPayment         = $data['idPayment'];


          if (true) {
              $query = "UPDATE Payment
                          SET idCompanyPayment = '$idCompanyPayment',
                              PaymentValue = '$paymentValue',
                              PaymentStatus_idPaymentStatus = '$paymentStatusId',
                              PaymentGeneratedDateTime = '$paymentGenerated',
                              SystemUser_idSystemUser = '$systemUserId',
                              paymentLocationId = '$companyIdLocation',
                              PaymentFromDate = '$paymentFromDate',
                              PaymentToDate = '$paymentToDate',
                              PaymentHours     = '$PaymentHours'
                          WHERE idPayment = '$idPayment';";
              $QueryCallback = $this->ExecuteSql($query);
              if ($QueryCallback) {
                  $data['PaymentId'] = intval($this->GetOne("SELECT LAST_INSERT_ID()"));
                  $endJSON["status"] = "success";
                  $endJSON["message"] = "Payment was Updated.";
                  $endJSON["data"] = $data;
              } else {
                  throw new Exception("Error: SQL updatePayment", 400);
              }
          } else {
            throw new Exception("Error: SQL Update Payment", 1);
          }
      } catch (Exception $e) {
          $endJSON['status'] = 'error';
          $endJSON["message"] = $e->getMessage();
          $endJSON["code"] = $e->getCode();
      }
      return $endJSON;
    }

    


  public function getPaymentsByEmployeeId($data) {
      $endJSON["status"] = "";
      $endJSON["data"] = "";
      try {
         if (isset($data['userId'])) {
             $userId = $data['userId'];
             $query = "SELECT
                            pmt.*,
                            us.SystemUserNickName,
                            emp.EmployeeDocNumber,
                            emp.EmployeeLastName,
                            emp.EmployeName,
                            ps.PaymentStatusDescription,
                            cp.CompanyName,
                            cl.CompanyLocationDescription
                        FROM Payment as pmt
                        INNER JOIN companylocation cl on cl.idCompanyLocation = pmt.paymentLocationId
                        INNER JOIN company cp on cp.idCompany = pmt.idCompanyPayment
                        INNER JOIN Paymentstatus ps on ps.idPaymentStatus = pmt.PaymentStatus_idPaymentStatus
                        INNER JOIN Systemuser us on us.idSystemUser = pmt.SystemUser_idSystemUser
                        INNER JOIN Employee emp on emp.idEmployee = pmt.Employee_idEmployee
                        WHERE SystemUser_idSystemUser = '$userId' order by idPayment desc;";

             
             $SelectAllResult = $this->GetAll($query);
             if ($SelectAllResult) {
                $endJSON["status"] = "success";
                $endJSON["data"] = $SelectAllResult;
             } else {
               throw new Exception("Error: SQL getPaymentsByEmployeeId", 1);
             }
         } else {
             throw new Exception("Error: invalid parameters for Family", 1);
         }

      }  catch (Exception $e) {
         $endJSON['status'] = 'error';
         $endJSON["message"] = $e->getMessage();
         $endJSON["code"] = $e->getCode();
      }
      return $endJSON;
  }

  public function getPayments() {
    $endJSON["status"] = "";
    $endJSON["data"] = "";
    try {
      
        $query = "SELECT
                        pmt.*,
                        us.SystemUserNickName,
                        emp.EmployeeLastName,
                        emp.EmployeeDocNumber,
                        emp.EmployeName,
                        ps.PaymentStatusDescription,
                        cp.CompanyName,
                        cl.CompanyLocationDescription
                    FROM Payment as pmt
                    INNER JOIN companylocation cl on cl.idCompanyLocation = pmt.paymentLocationId
                    INNER JOIN company cp on cp.idCompany = pmt.idCompanyPayment
                    INNER JOIN Paymentstatus ps on ps.idPaymentStatus = pmt.PaymentStatus_idPaymentStatus
                    INNER JOIN Systemuser us on us.idSystemUser = pmt.SystemUser_idSystemUser
                    INNER JOIN Employee emp on emp.idEmployee = pmt.Employee_idEmployee order by idPayment desc;";
        
        $SelectAllResult = $this->GetAll($query);
        if ($SelectAllResult) {
            $endJSON["status"] = "success";
            $endJSON["data"] = $SelectAllResult;
        } else {
            throw new Exception("Error: SQL getPayments id", 1);
        }
  
    }  catch (Exception $e) {
       $endJSON['status'] = 'error';
       $endJSON["message"] = $e->getMessage();
       $endJSON["code"] = $e->getCode();
    }
    return $endJSON;
}

public function getPaymentsById(array $data) {
    $endJSON["status"] = "";
    $endJSON["data"] = "";
    try {
        
        $idPayment=$data['idPayment'];
        $query = "SELECT
                        pmt.*,
                        us.SystemUserNickName,
                        emp.EmployeeLastName,
                        emp.EmployeeDocNumber,
                        emp.EmployeName,
                        ps.PaymentStatusDescription,
                        cp.CompanyName,
                        cl.CompanyLocationDescription
                    FROM Payment as pmt
                    INNER JOIN companylocation cl on cl.idCompanyLocation = pmt.paymentLocationId
                    INNER JOIN company cp on cp.idCompany = pmt.idCompanyPayment
                    INNER JOIN Paymentstatus ps on ps.idPaymentStatus = pmt.PaymentStatus_idPaymentStatus
                    INNER JOIN Systemuser us on us.idSystemUser = pmt.SystemUser_idSystemUser
                    INNER JOIN Employee emp on emp.idEmployee = pmt.Employee_idEmployee where pmt.idPayment='$idPayment' order by idPayment desc;";
        
        $SelectAllResult = $this->GetAll($query);
        if ($SelectAllResult) {
            $endJSON["status"] = "success";
            $endJSON["data"] = $SelectAllResult;
        } else {
            throw new Exception("Error: SQL getPayments id", 1);
        }
  
    }  catch (Exception $e) {
       $endJSON['status'] = 'error';
       $endJSON["message"] = $e->getMessage();
       $endJSON["code"] = $e->getCode();
    }
    return $endJSON;
}

  public function deletePayment(array $data){
       $endJSON["status"] = "";
       $endJSON["data"] = "";
       try {
           if (!isset($data['idPayment'])) {
              throw new Exception("Error: Params createPayment required idPayment", 400);
           }
           $paymentStatusId = 2;
           $query = "UPDATE Payment
                     SET PaymentStatus_idPaymentStatus = '$paymentStatusId'
                     WHERE idPayment = '$idPayment';";

           $QueryCallback = $this->ExecuteSql($query);
           if ($QueryCallback) {
                 $endJSON["status"] = "success";
                 $endJSON["message"] = "Payment was de;ete.";
           } else {
              throw new Exception("Error: SQL deletePayment", 400);
           }

       } catch (Exception $e) {
          $endJSON['status'] = 'error';
          $endJSON["message"] = $e->getMessage();
          $endJSON["code"] = $e->getCode();
       }
       return $endJSON;
  }

  public function GetPaymentStatus()
  {

    $query = "SELECT * from paymentstatus";
    $QueryCallback = $this->GetAll($query);

    if ($QueryCallback) {
          $endJSON["status"] = "success";
          $endJSON["data"]   = $QueryCallback;
    } else {
       throw new Exception("Error: SQL deletePayment", 400);
    }

    return $endJSON;
  }

}

