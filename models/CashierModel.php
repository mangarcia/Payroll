<?php

class CashierModel extends Database
{
    public function __construct()
    {
        parent::__construct();
        //error_reporting(0);
    }

    private function issetData($var = null)
    {
        return (isset($var)) ? $var : null;
    }

    

    public function GetAvailablePaymentsId(array $data)
    {
        
        //$CompanyId = Session::get('companyId');
        $endJSON["status"] = "";
        $endJSON["data"] = "";
        try {

            if (!isset($data['idCompanyPayment'])) {
                throw new Exception("Error: Params GetAvailablePaymentsId required idCompanyPayment", 400);
            }

            if (!isset($data['pageNumber'])) {
                throw new Exception("Error: Params GetAvailablePaymentsId required pageNumber", 400);
            }

            if (!isset($data['pageSize'])) {
                throw new Exception("Error: Params GetAvailablePaymentsId required pageSize", 400);
            }

            if (!isset($data['orderBy'])) {
                throw new Exception("Error: Params GetAvailablePaymentsId required orderBy", 400);
            }

            $CompanyId  = $data['idCompanyPayment'];
            $PageNumber = $data['pageNumber'];
            $PageSize   = $data['pageSize'];
            $orderBy    = $data['orderBy'];


            $calculationPage = (($PageNumber - 1) * $PageSize);

            $query = "SELECT
                            pmt.idPayment,
                            pmt.idCompanyPayment,
                            pmt.PaymentDateTime,
                            pmt.PaymentValue,
                            pmt.PaymentDocumentUrl,
                            pmt.PaymentFromDate,
                            pmt.PaymentToDate,
                            cp.CompanyName,
                            cp.companyCurrentMoney,
                            ps.*,
                            cl.idCompanyLocation, 
                            cl.CompanyLocationDescription,
                            us.idSystemUser as idUserCreator,
                            us.SystemUserName as nameUserCreator, 
                            emp.EmployeName,
                            emp.EmployeeLastName,
                            emp.EmployeePhoneNumber,
                            emp.EmployeeDocNumber,
                            (SELECT 
                            GROUP_CONCAT('[',
                                    JSON_OBJECT(
                                        'idPaymentNote', idPaymentNote,
                                        'PaymentNoteDescription', PaymentNoteDescription,
                                        'Payment_idPayment', Payment_idPayment,
                                        'SystemUser_idSystemUser', SystemUser_idSystemUser,
                                        'PaymentNoteDate', PaymentNoteDate,  
                                        'Payment_idPayment', Payment_idPayment
                                    ),']'
                                )
                            FROM paymentnote 
                            WHERE paymentnote.Payment_idPayment = pmt.idPayment ) as Note 
                        FROM payment as pmt
                            INNER JOIN companylocation cl on cl.idCompanyLocation = pmt.paymentLocationId
                            INNER JOIN company cp on cp.idCompany = pmt.idCompanyPayment
                            INNER JOIN paymentstatus ps on ps.idPaymentStatus = pmt.PaymentStatus_idPaymentStatus
                            INNER JOIN systemuser us on us.idSystemUser = pmt.SystemUser_idSystemUser
                            INNER JOIN employee emp on emp.idEmployee  = pmt.Employee_idEmployee
                            WHERE idCompanyPayment = ".$CompanyId."
                        ORDER BY pmt.PaymentDateTime ".$orderBy."
                            LIMIT ".$calculationPage.",".$PageSize.";";

            $queryTwo = "call get_page_info_by_id('$CompanyId','$PageNumber', '$PageSize')";

            $SelectAllResult = $this->GetAll($query);
            if ($SelectAllResult) {
                foreach ($SelectAllResult as &$value) {
                    $note = json_decode($value['Note']);
                    $value['Note'] = $note;
                }

                $SelectAllResultTwo = $this->GetAll($queryTwo);
                $endJSON["status"] = "success";
                $endJSON["data"] = $SelectAllResult;
                $endJSON["page"] = $this->GetAll($queryTwo);
                
            } else {
                throw new Exception("Error: SQL getAllCompany", 1);
            }
        } catch (Exception $e) {
            http_response_code(400);
            $endJSON['status'] = 'error';
            die($e->getMessage());
        }
        return $endJSON;
    }

    public function CashOut(array $data) {
        $endJSON["status"] = "";
        try {
          if (!isset($data['sign'])) {
              throw new Exception("Error: Params createPayment required idCompanyPayment", 400);
          }
          if (!isset($data['idPayment'])) {
              throw new Exception("Error: Params createPayment required PaymentValue", 400);
          }
          
  
          $idPayment  = $data['idPayment'];

          $sign      = $this->issetData($data['sign']);

          if($sign!=null)
           {
             $sign   = $this->getImageUrl($sign,"Signs_Payment",$idPayment);
           }

           date_default_timezone_set('Canada/Eastern');
           $PaymentDateTime = new DateTime('NOW');
  
           $PaymentDateTime = $PaymentDateTime->format('Y-m-d H:i:s');
           
           $currentAmmount=$data["paymentInfo"]["PaymentValue"];

           $companyDebt=$data["companyInfo"]["companyDebt"];
           $companyCurrentMoney=$data["companyInfo"]["companyCurrentMoney"];

           $canPay=true;

           $CompanyId=$data["companyInfo"]["idCompany"];
           $PaymentFee=$data["companyInfo"]["Fee"];
           $PaymentFeeValue=$currentAmmount*$PaymentFee/100;
           $PayablePaysFee=$data["companyInfo"]["PayablePaysFee"];
           
           if($PayablePaysFee==0)
           {
             $currentAmmount=$currentAmmount+$PaymentFeeValue;
           }


           if($companyCurrentMoney==0)
           {
                if($data["companyInfo"]["CompanyHaveLoan"]==1)
                {
                    
                    $debtAvailable=$data["companyInfo"]["CompanyLoanMaxAmmount"]-$companyDebt;
                   if($debtAvailable>=$currentAmmount)
                   {
                      $companyDebt=$currentAmmount+ $companyDebt;
                        $canPay=true;
                   }
                   else
                   {
                        $canPay=false;
                        $endJSON["status"] = "error";
                        $endJSON["message"] = "The company doesnt have Money";
                   }
                }
                else
                {
                    $canPay=false;
                    $endJSON["status"] = "error";
                    $endJSON["message"] = "The company doesnt have Money";
                }
           }
           else
           {
               if($companyCurrentMoney<$currentAmmount)
               {
                    if($data["companyInfo"]["CompanyHaveLoan"]==1)
                    {
                        if($companyCurrentMoney>0)
                        {
                            $currentAmmount=$currentAmmount-$companyCurrentMoney;
                            $companyCurrentMoney=0;
                        }

                        $debtAvailable=$data["companyInfo"]["CompanyLoanMaxAmmount"]-$companyDebt;
                        
                        if($debtAvailable>=$currentAmmount)
                        {
                            $companyDebt=$currentAmmount+ $companyDebt;
                             $canPay=true;
                        }
                        else
                        {
                             $canPay=false;
                             $endJSON["status"] = "error";
                             $endJSON["message"] = "The company doesnt have Money";
                        }
                    }
                    else
                    {
                        $canPay=false;
                        $endJSON["status"] = "error";
                        $endJSON["message"] = "The company doesnt have Money";
                    }
               }
               else
               {
                    $companyCurrentMoney=$companyCurrentMoney-$currentAmmount;
                    $canPay=true;
               }
               
           }
/*
           echo "Payment value ".$data["paymentInfo"]["PaymentValue"]."\n";
           echo "Max Ammount value ".$data["companyInfo"]["CompanyLoanMaxAmmount"]."\n";
           echo "Company Debt value ".$data["companyInfo"]["companyDebt"]."\n";
           echo "Company current money value ".$data["companyInfo"]["companyCurrentMoney"]."\n"."\n"."\n";
           


           echo "Company Debt value ".$companyDebt."\n";
           echo "Company current money value ".$companyCurrentMoney."\n"."\n"."\n";
*/
           
           $queryUpdateCompany="update company set companyDebt='$companyDebt', companyCurrentMoney='$companyCurrentMoney' where idCompany='$CompanyId'";

           $QueryCallback = $this->ExecuteSql($queryUpdateCompany);

            if ($canPay) {
                $query = "UPDATE Payment
                            SET PaymentStatus_idPaymentStatus = '2',
                            PayablePaysFee ='$PayablePaysFee',
                            PaymentFeeValue ='$PaymentFeeValue',
                            PaymentFeePercent ='$PaymentFee',                            
                            PaymentDocumentSign ='$sign',
                            PaymentDateTime='$PaymentDateTime'
                            WHERE idPayment = '$idPayment';";
                $QueryCallback = $this->ExecuteSql($query);
                if ($QueryCallback) {
                    $data['PaymentId'] = intval($this->GetOne("SELECT LAST_INSERT_ID()"));
                    $endJSON["status"] = "success";
                    $endJSON["message"] = "You can pay now!";
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

    public function GetAvailablePayments(array $data)
    {
        $endJSON["status"] = "success";
        $endJSON["data"] = "";
        try {
            if (!isset($data['pageNumber'])) {
                throw new Exception("Error: Params GetAvailablePayments required pageNumber", 400);
            }

            if (!isset($data['pageSize'])) {
                throw new Exception("Error: Params GetAvailablePayments required pageSize", 400);
            }

            $PageNumber = $data['pageNumber'];
            $PageSize   = $data['pageSize'];

            $queryOne = "call get_available_payments()";
            $queryTwo = "call get_page_info('$PageNumber', '$PageSize')";

            $SelectAllResult = $this->GetAll($queryOne);
            if ($SelectAllResult) {

                
                if($SelectAllResult=="No registry found")
                {
                    $endJSON["status"] = "success";
                    $queryPayedPayments = "call get_payed_payments()";
                    $SelectPayedPayments = $this->GetAll($queryPayedPayments);

                    foreach ($SelectPayedPayments as &$value2) {


                        $value2["PaymentHideValue"]=$value2["PaymentTotalValue"];
                        $value2["PaymentValue"]=$value2["PaymentTotalValue"];
                        $value2["Fee"]=$value2["Fee"];
                        if($value2["PayablePaysFee"]==1)
                        {
                            $value2["PaymentTotalValue"]= $value2["PaymentTotalValue"]-$value2["FeeValue"];
                            $value2["CompanyPays"]="Payable";
                            
                        }
                        else
                        {
                            $value2["CompanyPays"]="Company";
                        }

                            $note = json_decode($value2['Note']);
                            $value2['Note'] = $note;
                    }


                    $endJSON["data"]=$SelectPayedPayments;
                    return $endJSON;
                }

                foreach ($SelectAllResult as &$value) {
                $value["FeeValue"]=$value["PaymentValue"]*$value["Fee"]/100;

                $value["PaymentHideValue"]=$value["PaymentValue"];
                $value["PaymentTotalValue"]=$value["PaymentValue"];

                if($value["PayablePaysFee"]==1)
                {
                    $value["PaymentTotalValue"]= $value["PaymentTotalValue"]-$value["FeeValue"];
                    $value["CompanyPays"]="Payable";
                    
                }
                else
                {
                    $value["CompanyPays"]="Company";
                    $value["PaymentHideValue"]=$value["PaymentHideValue"]+  $value["FeeValue"];
                }

                    $note = json_decode($value['Note']);
                    $value['Note'] = $note;
                }
                $endJSON["status"] = "success";
                $queryPayedPayments = "call get_payed_payments()";
                $SelectPayedPayments = $this->GetAll($queryPayedPayments);

                if($SelectPayedPayments=="No registry found")
                {
                    $endJSON["data"]=$SelectAllResult;
                    return $endJSON;
                }

               
                
                foreach ($SelectPayedPayments as &$value2) {


                    $value2["PaymentHideValue"]=$value2["PaymentTotalValue"];
                    $value2["PaymentValue"]=$value2["PaymentTotalValue"];
                    $value2["Fee"]=$value2["PaymentFeePercent"];
                    if($value2["PayablePaysFee"]==1)
                    {
                        $value2["PaymentTotalValue"]= $value2["PaymentTotalValue"]-$value2["FeeValue"];
                        $value2["CompanyPays"]="Payable";
                        
                    }
                    else
                    {
                        $value2["CompanyPays"]="Company";
                    }
    
                        $note = json_decode($value2['Note']);
                        $value2['Note'] = $note;
                    }

                    
                $GeneralPayments=array_merge($SelectAllResult,$SelectPayedPayments);

                $endJSON["data"]= $GeneralPayments;

                $endJSON["page"]   = $this->GetAll($queryTwo);
            } else {
                throw new Exception("Error: SQL getAllCompany", 1);
            }
        } catch (Exception $e) {
            http_response_code(400);
            $endJSON['status'] = 'error';
            die($e->getMessage());
        }
        return $endJSON;
    }
    
    public function getPaymentsById(array $data)
    {
        $endJSON["status"] = "";
        $endJSON["data"] = "";

        $idPayment=$data['idPayment'];
        $queryOne = "Select * from Payment where idPayment='$idPayment'";

        $SelectAllResult = $this->GetRow($queryOne);

        if ($SelectAllResult) {
            $endJSON["status"] = "success";
            $endJSON["data"]   = $SelectAllResult;
        } else {
            throw new Exception("Error: SQL getAllCompany", 1);
        }

        return $endJSON;
    }
}
