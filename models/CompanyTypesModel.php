<?php

class CompanyTypesModel extends Database
{
  public function __construct()
  {
    parent::__construct();
  }

  public function createCompanyType(array $data) {
    $endJSON["status"] = "";
    $endJSON["data"] = "";
    try {
        if (!isset($data['CompanyTypeDescription'])) {
            throw new Exception("Error: Params Company type required CompanyTypeDescription", 400);
        }

        $compDescrip  = $data['CompanyTypeDescription'];

        $query = "INSERT INTO Companytype(CompanyTypeDescription) VALUES ('$compDescrip');";
        $QueryCallback = $this->ExecuteSql($query);
        if ($QueryCallback) {
            $data['CompanytypeId'] = intval($this->GetOne("SELECT LAST_INSERT_ID()"));
            $endJSON["status"] = "success";
            $endJSON["message"] = "Comapany types create was created.";
            $endJSON["data"] = $data;
        } else {
            throw new Exception("Error: SQL createCompanyType", 400);
        }
    } catch (Exception $e) {
        $endJSON['status'] = 'error';
        $endJSON["message"] = $e->getMessage();
        $endJSON["code"] = $e->getCode();
    }
    return $endJSON;  
  }

  public function getCompanyTypesById(array $data) {
    try {
        if (!isset($data['idCompanyType'])) {
            throw new Exception("Error: Params Company type required idCompanyType", 400);
        }

        $idCompanyType  = $data['idCompanyType'];

        $query = "SELECT * from Companytype WHERE idCompanyType = '$idCompanyType';";
        $SelectAllResult = $this->GetAll($query);
        if ($SelectAllResult) {
            $endJSON["status"] = "success";
            $endJSON["data"] = $SelectAllResult;
        } else {
            throw new Exception("Error: SQL getCompanyTypesById", 400);
        }
    } catch (Exception $e) {
        $endJSON['status'] = 'error';
        $endJSON["message"] = $e->getMessage();
        $endJSON["code"] = $e->getCode();
    }
    return $endJSON;  
  }

  public function getCompanyTypes() {
    try {
        $query = "SELECT * from Companytype;";
        $SelectAllResult = $this->GetAll($query);
        if ($SelectAllResult) {
            $endJSON["status"] = "success";
            $endJSON["data"] = $SelectAllResult;
        } else {
            throw new Exception("Error: SQL getCompanyTypes", 400);
        }
    } catch (Exception $e) {
        $endJSON['status'] = 'error';
        $endJSON["message"] = $e->getMessage();
        $endJSON["code"] = $e->getCode();
    }
    return $endJSON;  
  }

  public function updateCompanyType() {
    try {

        if (!isset($data['CompanyTypeDescription'])) {
            throw new Exception("Error: Params Company type required CompanyTypeDescription", 400);
        }

        if (!isset($data['idCompanyType'])) {
            throw new Exception("Error: Params Company type required idCompanyType", 400);
        }

        $idCompanyType  = $data['idCompanyType'];
        $compDescrip  = $data['CompanyTypeDescription'];

        $query = "UPDATE Companytype
                    SET CompanyTypeDescription = '$compDescrip'
                    WHERE idCompanyType = '$idCompanyType';";
        
        $QueryCallback = $this->ExecuteSql($query);

        if ($QueryCallback) {
            $endJSON["status"] = "success";
            $endJSON["message"] = "Comapany types update";
        } else {
            throw new Exception("Error: SQL updateCompanyType", 400);
        }
    } catch (Exception $e) {
        $endJSON['status'] = 'error';
        $endJSON["message"] = $e->getMessage();
        $endJSON["code"] = $e->getCode();
    }
    return $endJSON;  
  }
}