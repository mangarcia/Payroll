<?php

class CompanyModel extends Database
{
    public function __construct()
    {
        parent::__construct();
    }

    private function issetData($var = null)
    {
        return (isset($var)) ? $var : null;
    }

//GetOne
    //GetAll
    //GetRow
    //ExecuteSql

    public function insertCompany(array $data)
    {
        error_reporting(E_ALL ^ E_WARNING);

        //echo json_encode($data);

        //var_dump($data);
        $endJSON["status"] = "";
        $endJSON["data"] = "";
        if (
            isset($data['CompanyName']) &&
            isset($data['CompanyHaveLoan']) &&
            isset($data['PaymentPeriod']) &&
            isset($data['companyStatus_id'])

        ) {
            $CompanyName = $data['CompanyName'];
            $CompanyHaveLoan = $data['CompanyHaveLoan'];
            $CompanyLoanMaxAmmount = $data['CompanyLoanMaxAmmount'];
            $PaymentPeriod = $data['PaymentPeriod'];
            $ContactName = $data['ContactName'];
            $ContactPhone = $data['ContactPhone'];
            $PaymentPeriod = $data['PaymentPeriod'];
            $companyStatus_id = $data['companyStatus_id'];
            if(isset($data['PayablePaysFee']))
            {
                $PayablePaysFee = $data['PayablePaysFee'];
            }
            else
            {
                $PayablePaysFee=0;
            }
            
            $Agency_idAgency = 1;
            $Fee = $data['Fee'];
            $CompanyImageUrl = "";


            $FilesPath="./payRollFiles/".$data['CompanyName'];

            if (!mkdir($FilesPath, 0, true)) 
            {  
                 $endJSON["status"] = "error";
                 $endJSON["data"] = "No se pudo crear el directorio de archivos para ".$data['CompanyName'];
                 $endJSON["message"] = "Couldn't Create Company Path";
                return $endJSON;
            }
     
             chmod($FilesPath, 755);


            if (isset($data["CompanyImageUrl"])) {
                $CompanyImageUrl = $data["CompanyImageUrl"];

                if ($CompanyImageUrl) {
                    $CompanyImageUrl = $this->getImageUrl($CompanyImageUrl, $CompanyName, "Profile");
                }
            }

            $query = " INSERT INTO `Company` (`CompanyName`, `CompanyHaveLoan`, `CompanyLoanMaxAmmount`, `PaymentPeriod`, `companyStatus_id`, `Agency_idAgency`,`CompanyImageUrl`,Fee,ContactPhone,ContactName,PayablePaysFee)
        VALUES ('$CompanyName', '$CompanyHaveLoan', '$CompanyLoanMaxAmmount', '$PaymentPeriod', '$companyStatus_id','$Agency_idAgency', '$CompanyImageUrl','$Fee','$ContactPhone','$ContactName','$PayablePaysFee');";

            $QueryCallback = $this->ExecuteSql($query);
            if ($QueryCallback) {
                $endJSON["status"] = "success";
                $endJSON["message"] = "Succesfull";
            } else {

                $endJSON["message"] = "Bad Request";
                $endJSON['status'] = 'error';
            }
        } else {
            $endJSON["message"] = "Company Information Missing";
            $endJSON['status'] = 'error';
        }

        return $endJSON;
    }
    
    public function getcompanyammount(array $data)
    {
        error_reporting(E_ALL ^ E_WARNING);

        //echo json_encode($data);

        $endJSON["status"] = "";
        $endJSON["data"] = "";
        try {
            $companyLocation=$data["idCompanyLocation"];
            $query = "SELECT distinct Company.*,companylocation.CompanyLocationDescription 
            FROM Company,companylocation 
            where Company.idCompany=companylocation.Company_CompanyId and companylocation.idCompanyLocation='$companyLocation'";
            $SelectAllResult = $this->GetRow($query);
            $companyResult=array();
            if ($SelectAllResult) {
                $endJSON["status"] = "success";
                $companyResult['CompanyName']= $SelectAllResult['CompanyName'];
                $companyResult['locationName']= $SelectAllResult['CompanyLocationDescription'];
                $companyResult['CompanyImageUrl']= $SelectAllResult['CompanyImageUrl'];

                if($SelectAllResult['CompanyHaveLoan']==1)
                {
                    if($SelectAllResult["companyDebt"]==0)
                    {
                        $companyResult["MoneyAvailable"]=$SelectAllResult["companyCurrentMoney"]+$SelectAllResult["CompanyLoanMaxAmmount"];
                    }
                    else
                    {
                        $companyResult["MoneyAvailable"]=$SelectAllResult["CompanyLoanMaxAmmount"]-$SelectAllResult["companyDebt"]+$SelectAllResult["companyCurrentMoney"];
                    }
                }
                else
                {
                    $companyResult["MoneyAvailable"]=$SelectAllResult["companyCurrentMoney"];
                }
                $companyResult["MoneyAvailable"]=round( $companyResult["MoneyAvailable"],2);
                $endJSON["data"] = $companyResult;   
            }
            else {
                throw new Exception("Error: SQL getAllCompany", 1);
            }
        } catch (Exception $e) {
            http_response_code(400);
            $endJSON['status'] = 'error';
            die($e->getMessage());
        }
        return $endJSON;
    }

    public function getAllCompany()
    {
        error_reporting(E_ALL ^ E_WARNING);
        $endJSON["status"] = "";
        $endJSON["data"] = "";
        try {
            $query = "SELECT distinct CompanyStatusDescription as Status, Company.* FROM Company,CompanyStatus where companyStatus_id=companystatus.idCompanyStatus;";
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

    public function getCompany(array $data)
    {
        $endJSON["status"] = "";
        $endJSON["data"] = "";
        try {
            if (isset($data['id'])) {
                $companyId = $data['id'];
                $query = "SELECT * FROM Company WHERE idCompany = '$companyId';";
                $SelectAllResult = $this->GetAll($query);
                if ($SelectAllResult) {
                    $endJSON["status"] = "success";
                    $endJSON["data"] = $SelectAllResult;
                } else {
                    throw new Exception("Error: SQL getCompany id", 1);
                }
            } else if (isset($data['name'])) {
                $name = $data['name'];
                $query = "SELECT * FROM Company WHERE name = '$name';";
                $SelectAllResult = $this->GetAll($query);
                if ($SelectAllResult) {
                    $endJSON["status"] = "success";
                    $endJSON["data"] = $SelectAllResult;
                } else {
                    throw new Exception("Error: SQL getCompany name", 1);
                }
            } else if (isset($data['statusCompanyId'])) {
                $statusCompanyId = $data['statusCompanyId'];
                $query = "SELECT * FROM Company WHERE statusCompanyId = '$statusCompanyId';";
                $SelectAllResult = $this->GetAll($query);
                if ($SelectAllResult) {
                    $endJSON["status"] = "success";
                    $endJSON["data"] = $SelectAllResult;
                } else {
                    throw new Exception("Error: SQL getCompany name", 1);
                }
            } else {
                throw new Exception("Error: invalid parameters for Company", 1);
            }
        } catch (Exception $e) {
            http_response_code(400);
            $endJSON['status'] = 'error';
            die($e->getMessage());
        }
        return $endJSON;
    }

    public function updateCompany(array $data)
    {
        $endJSON["status"] = "";
        $endJSON["data"] = "";


        //echo json_encode($data);

        try {
            
            $CompanyName=$data['CompanyName'];
                $idCompany = $data['idCompany'];
                $Agency_idAgency = 1;

                $query = "update Company set ";


                $CompanyHaveLoan = $this->issetData($data['CompanyHaveLoan']);
                if ($CompanyHaveLoan != null) {
                    $query .= "CompanyHaveLoan='$CompanyHaveLoan',";
                }

                if(isset($data['CompanyLoanMaxAmmount']))
                {
                    $CompanyLoanMaxAmmount =$data['CompanyLoanMaxAmmount'] ;
                    $query .= "CompanyLoanMaxAmmount='$CompanyLoanMaxAmmount',";
                }

                $PaymentPeriod = $this->issetData($data['PaymentPeriod']);
                if ($PaymentPeriod != null) {
                    $query .= "PaymentPeriod='$PaymentPeriod',";
                }


                $query .= "Agency_idAgency='1',";


                $companyStatus_id = $this->issetData($data['companyStatus_id']);
                if ($companyStatus_id != null) {
                    $query .= "companyStatus_id='$companyStatus_id',";
                }


                if(isset($data['PayablePaysFee']))
                {
                    $PayablePaysFee=$data['PayablePaysFee'];
                    $query .= "PayablePaysFee='$PayablePaysFee',";
                }

                 

                $ContactName = $this->issetData($data['ContactName']);
                if ($ContactName != null) {
                    $query .= "ContactName='$ContactName',";
                }

                $ContactPhone = $this->issetData($data['ContactPhone']);
                if ($ContactPhone != null) {
                    $query .= "ContactPhone='$ContactPhone',";
                }

                $Fee = $this->issetData($data['Fee']);
                if ($Fee != null) {
                    $query .= "Fee='$Fee',";
                }


                if (isset($data["CompanyImageUrl"])) {
                    $CompanyImageUrl = $data["CompanyImageUrl"];
    
                    if ($CompanyImageUrl) {
                        $CompanyImageUrl = $this->getImageUrl($CompanyImageUrl, $CompanyName, "Profile");
                        $query .= "CompanyImageUrl='$CompanyImageUrl',";
                    }
                }


                $query = rtrim($query, ",");

                $query .= " where idCompany='" . $data['idCompany'] . "'";

                //echo $query;

                //$query = " UPDATE Company SET name = '$name' WHERE companyId = '$companyId';";
                $QueryCallback = $this->ExecuteSql($query);

                if ($QueryCallback) {
                    $endJSON["status"] = "success";
                    $endJSON["data"] = $data;
                } else {
                    throw new Exception("Error: SQL updateCompany", 1);
                }
             
        } catch (Exception $e) {
            $endJSON['status'] = 'error';
            http_response_code(400);
            die($e->getMessage());
        }
        return $endJSON;
    }

    public function deleteCompany(array $data)
    {
        $endJSON["status"] = "";
        $endJSON["data"] = "";
        try {
            if (isset($data['id'])) {
                $companyId = $data['id'];
                $query = "DELETE FROM Company WHERE companyId = '$companyId';";
                $QueryCallback = $this->ExecuteSql($query);
                if ($QueryCallback) {
                    $endJSON["status"] = "success";
                    $endJSON["data"] = "{}";
                } else {
                    throw new Exception("Error: SQL deleteCompany", 1);
                }
            } else {
                throw new Exception("Error: invalid parameters for Company", 1);
            }
        } catch (Exception $e) {
            http_response_code(400);
            $endJSON['status'] = 'error';
            die($e->getMessage());
        }
        return $endJSON;
    }

    public function getCompanyLocations(array $data)
    {
        
        //echo json_encode($data);

        $endJSON["status"] = "";
        $endJSON["data"] = "";
        try {

            if (!isset($data['companyId'])) {
                $endJSON["status"] = "errror";
                $endJSON["data"] = "missing params";
                return $endJSON;
            }
            $companyId = $data['companyId'];
            $query = "SELECT distinct companylocationstatus.CompanyLocationStatusDescription,companyLocation.* 
            FROM companyLocation,companylocationstatus 
            where Company_CompanyId='$companyId' 
            and companylocationstatus.IdCompanyLocationStatus=companylocation.CompanyLocationStatusId;";



            $SelectAllResult = $this->GetAll($query);
            if ($SelectAllResult) {
                $endJSON["status"] = "success";
            } else {
                throw new Exception("Error: SQL getAllUser", 1);
            }

            $endJSON["data"] = $SelectAllResult;

        } catch (Exception $e) {
            http_response_code(400);
            $endJSON['status'] = 'error';
            die($e->getMessage());
        }
        return $endJSON;
    }

    public function getCompanyTypes()
    {
        error_reporting(0);
        $endJSON["status"] = "";
        $endJSON["data"] = "";
        $query = "select * from companytype";
        $SelectAllResult = $this->GetAll($query);
        if ($SelectAllResult) {
            $endJSON["status"] = "success";
            $endJSON["data"] = $SelectAllResult;
        } else {
            throw new Exception("Error: SQL getAllUser", 1);
        }

        return $endJSON;
    }

    public function getCompanyLocationsStatus()
    {
        error_reporting(0);
        $endJSON["status"] = "";
        $endJSON["data"] = "";
        $query = "select * from CompanyLocationStatus";
        $SelectAllResult = $this->GetAll($query);
        if ($SelectAllResult) {
            $endJSON["status"] = "success";
            $endJSON["data"] = $SelectAllResult;
        } else {
            throw new Exception("Error: SQL getAllUser", 1);
        }

        return $endJSON;
    }


    public function UpdateCompanyLocations(array $data)
    {
        $endJSON["status"] = "";
        $endJSON["data"] = "";
        try {

            if (!isset($data['idCompanyLocation']) && 
            !isset($data['CompanyLocationDescription']) && 
            !isset($data['CompanyLocationAddress'])&&
            !isset($data['CompanyLocationStatusId'])){
                $endJSON["status"] = "errror";
                $endJSON["data"] = "missing params";
                return $endJSON;
            }

            $query = "update CompanyLocation set ";


            $CompanyLocationDescription = $this->issetData($data['CompanyLocationDescription']);
            if ($CompanyLocationDescription != null) {
                $query .= "CompanyLocationDescription='$CompanyLocationDescription',";
            }

            $CompanyLocationAddress = $this->issetData($data['CompanyLocationAddress']);
            if ($CompanyLocationAddress != null) {
                $query .= "CompanyLocationAddress='$CompanyLocationAddress',";
            }

            $CompanyLocationStatusId = $this->issetData($data['CompanyLocationStatusId']);
            if ($CompanyLocationStatusId != null) {
                $query .= "CompanyLocationStatusId='$CompanyLocationStatusId',";
            }

            $query = rtrim($query, ",");

            $query .= " where idCompanyLocation='" . $data['idCompanyLocation'] . "'";

 
            $SelectAllResult = $this->ExecuteSql($query);
            if ($SelectAllResult) {
                $endJSON["status"] = "success";
            } else {
                throw new Exception("Error: SQL Updating Company Locations", 1);
            }

            $endJSON["data"] = $SelectAllResult;

        } catch (Exception $e) {
            http_response_code(400);
            $endJSON['status'] = 'error';
            $endJSON["message"] = "The location couldnt be  updated";
            die($e->getMessage());
        }
        return $endJSON;
    }

    public function CreateCompanyLocations(array $data)
    {
        $endJSON["status"] = "";
        $endJSON["data"] = "";

       
        try {

            if (!isset($data['companyId']) && 
            !isset($data['CompanyLocationDescription']) && 
            !isset($data['CompanyLocationAddress'])&&
            !isset($data['CompanyLocationStatusId'])) {
                $endJSON["status"] = "errror";
                $endJSON["data"] = "missing params";
                return $endJSON;
            }

            $companyId = $data['companyId'];
            $CompanyLocationDescription = $data['CompanyLocationDescription'];
            $CompanyLocationAddress = $data['CompanyLocationAddress'];
            $CompanyLocationStatusId=$data['CompanyLocationStatusId'];

            $dataSql["CompanyLocationDescription"]=$CompanyLocationDescription;
            $dataSql["CompanyLocationAddress"]=$CompanyLocationAddress;
            $dataSql["companyId"]=$companyId;
            $dataSql["CompanyLocationStatusId"]=$CompanyLocationStatusId;


            $query = "insert into companyLocation
            values(NULL,:CompanyLocationDescription,:CompanyLocationAddress,:companyId,:CompanyLocationStatusId);";

            $SelectAllResult = $this->ExecuteSql($query,$dataSql);
            if ($SelectAllResult) {
                $endJSON["status"] = "success";
            } else {
                throw new Exception("Error: SQL create Location", 1);
            }

            $endJSON["data"] = $SelectAllResult;

        } catch (Exception $e) {
            http_response_code(400);
            $endJSON['status'] = 'error';
            $endJSON["message"] = "Cant create the location";
            die($e->getMessage());
        }
        return $endJSON;
    }

    public function DeleteCompanyLocations(array $data)
    {
        $endJSON["status"] = "";
        $endJSON["data"] = "";
        try {

            $idCompanyLocation = $data['idCompanyLocation'];
            $query = "delete FROM companyLocation where idCompanyLocation='$idCompanyLocation';";

            $SelectAllResult = $this->ExecuteSql($query);
            if ($SelectAllResult) {
                $endJSON["status"] = "success";
            } else {
                throw new Exception("Error: SQL Deleting Location", 1);
            }

            $endJSON["data"] = $SelectAllResult;

        } catch (Exception $e) {
            http_response_code(400);
            $endJSON['status'] = 'error';
            $endJSON["message"] = "This location cant be deleted";
            die($e->getMessage());
        }
        return $endJSON;
    }

    public function getcompanystatus()
    {

        $endJSON["status"] = "";
        $endJSON["data"] = "";
        try {

            $roleId = 8;
            if ($roleId == 8) {
                $query = "SELECT * FROM companystatus;";

                $SelectAllResult = $this->GetAll($query);
                if ($SelectAllResult) {
                    $endJSON["status"] = "success";
                } else {
                    throw new Exception("Error: SQL getAllUser", 1);
                }

                $endJSON["data"] = $SelectAllResult;

            }
        } catch (Exception $e) {
            http_response_code(400);
            $endJSON['status'] = 'error';

            die($e->getMessage());
        }
        return $endJSON;
    }
}
