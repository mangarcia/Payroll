<?php

class MasterPaymentModel extends Database
{
  public function __construct()
  {
    parent::__construct();
  }

  private function issetData($var = null)
  {
    return (isset($var)) ? $var : NULL;
  }




  public function createMasterPayment(array $data)
  {
    error_reporting(E_ALL ^ E_WARNING);

    //echo json_encode($data);

    //var_dump($data);
    $endJSON["status"] = "";
    $endJSON["data"] = "";
    if (
      isset($data['Company_idCompany']) &&
      isset($data['CompanyPaymentAmmount']) &&
      isset($data['CompanyPaymentReference'])
    ) {

      $CompanyId = $data['Company_idCompany'];
      $PaymentAmmount = $data['CompanyPaymentAmmount'];
      $CompanyPaymentReference = $data['CompanyPaymentReference'];
      

      date_default_timezone_set('Canada/Eastern');
      $Companydate= new DateTime('NOW');

      $Companydate = $Companydate->format('Y-m-d H:i:s');

      $companyInfo= $this->GetRow("select * from company where idCompany='$CompanyId'");
      
      $priceWithoutFee= $PaymentAmmount;
      $Fee=$companyInfo['Fee'];
      $priceWithFee=$PaymentAmmount;

      

      $query="insert into companypayment values(NULL,'$Companydate','$CompanyPaymentReference','$priceWithoutFee','$Fee','$priceWithFee','$CompanyId')";
      $QueryCallback = $this->ExecuteSql($query);
      if ($QueryCallback) {

        if($companyInfo['companyDebt']>$PaymentAmmount)
        {
          $PaymentAmmount=$companyInfo['companyDebt']-$PaymentAmmount;
          $query="update company set companyDebt='$PaymentAmmount' where idCompany='$CompanyId'";
        }
        else
        {
          $PaymentAmmount=$PaymentAmmount-$companyInfo['companyDebt'];
          $query="update company set companyDebt='0',companyCurrentMoney=companyCurrentMoney+'$PaymentAmmount' where idCompany='$CompanyId'";
        }

        $QueryCallback = $this->ExecuteSql($query);
        if ($QueryCallback) 
        {
          $endJSON["status"] = "success";
          $endJSON["message"] = "Sucess";
        }
        else
        {
          $endJSON["status"] = "error";
          $endJSON["message"] = "error";
        }

      } else {

        $endJSON["message"] = "Error";
        $endJSON['status'] = 'error';
      }
    } else {
      $endJSON["message"] = "Missing info";
      $endJSON['status'] = 'error';
    }

    return $endJSON;
  }

  public function getAllCompany()
  {
    error_reporting(E_ALL ^ E_WARNING);
    $endJSON["status"] = "";
    $endJSON["data"] = "";
    try {
      $query = "SELECT * FROM Company;";
      $SelectAllResult = $this->GetAll($query);
      if ($SelectAllResult) {
         $endJSON["status"] = "success";
        foreach ($SelectAllResult as &$value) {
          // $companyId=$value["companyId"];
          // $query="SELECT CONCAT(State.StateName,' - ',City.CityName) as cityName FROM State,City,Company_City where State.idState=City.State_idState and Company_City.City_idCity=City.idCity and Company_City.Company_companyId='$companyId'";
          // $CurrCities = $this->GetAll($query);
          //$value["cities"]=$CurrCities;

        }
        $endJSON["data"] = $SelectAllResult;
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






  public function getAllMasterPayment(array $data)
  {
    $endJSON["status"] = "";
    $endJSON["data"] = "";
    try {
      $query = "SELECT distinct company.CompanyName,companypayment.* FROM companypayment,company where idCompany=Company_idCompany";
      $SelectAllResult = $this->GetAll($query);
      if ($SelectAllResult) {
         $endJSON["status"] = "success";
        $endJSON["data"] = $SelectAllResult;
      } else {
        throw new Exception("Error: SQL getCompany id", 1);
      }
    } catch (Exception $e) {
      http_response_code(400);
      $endJSON['status'] = 'error';
      die($e->getMessage());
    }
    return $endJSON;
  }

  

  
}
