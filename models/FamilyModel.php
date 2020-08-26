<?php
class FamilyModel extends Database
{
  const table = 'Family';
  const tableColumns = array(
    'memberId' => array(
      'primaryKey' => true,
      // 'required' => true,
    ),
    'User_userId' => array(
      // 'primaryKey' => false,
      'required' => true,
    ),
    'Relationship_accountRelationshipId' => array(
      // 'primaryKey' => false,
      'required' => true,
    ),
    'basicDataBirthDate' => array(
      // 'primaryKey' => false,
      // 'required' => true,
    ),
    'basicDataDisability' => array(
      // 'primaryKey' => false,
      // 'required' => true,
    ),
    'DocType_docTypeId' => array(
      // 'primaryKey' => false,
      'required' => true,
    ),
    'basicDataDocNumber' => array(
      // 'primaryKey' => false,
      // 'required' => false,
    ),
    'basicDataFirstName' => array(
      // 'primaryKey' => false,
      // 'required' => false,
    ),
    'basicDataLastName' => array(
      // 'primaryKey' => false,
      // 'required' => true,
    ),
    'Sex_sexId' => array(
      // 'primaryKey' => false,
      'required' => true,
    ),
    'Mobility_basicDataMobiilityId' => array(
      // 'primaryKey' => false,
      'required' => true,
    ),
    'basicDataHeight' => array(
      // 'primaryKey' => false,
      // 'required' => true,
    ),
    'basicDataWeight' => array(
      // 'primaryKey' => false,
      // 'required' => false,
    ),
    'personalDataCellphone' => array(
      // 'primaryKey' => false,
      // 'required' => true,
    ),
    'emergencyContactCellphone' => array(
      // 'primaryKey' => false,
      // 'required' => true,
    ),
    'emergencyContactNamePerson' => array(
      // 'primaryKey' => false,
      // 'required' => true,
    ),
    'userEpsName' => array(
      // 'primaryKey' => false,
      // 'required' => true,
    ),
    'userObservations' => array(
      // 'primaryKey' => false,
      // 'required' => true,
    ),
    'StatusUser_statusUserId' => array(
      // 'primaryKey' => false,
      'required' => true,
    ),
    'Rol_rolId' => array(
      // 'primaryKey' => false,
      'required' => true,
    ),
    'Avatar_avatarId' => array(
      // 'primaryKey' => false,
      'required' => true,
    ),
    'Relationship_emergencyContactRelationshipId' => array(
      // 'primaryKey' => false,
      'required' => true,
    ),
    'userAllergies' => array(
      // 'primaryKey' => false,
      //'required' => true,
    ),
    'userMedicines' => array(
      // 'primaryKey' => false,
      //'required' => true,
    )
  );

  public function __construct()
  {
    parent::__construct();
  }

  public function addNewFamily(array $data)
  {

    $dataSQL = array();
    if (isset($data['memberId'])) {
      throw new Exception("Required param", 1);
    }
    if (
      isset($data['email']) &&
      isset($data['password']) &&
      isset($data['cellphone']) &&
      isset($data['accountName'])
    ) {
      $dataSQL['accountEmail']    = $data['email'];
      $dataSQL['accountPassword'] = $this->generateHashPass($data['password']);
      $dataSQL['accountCellphone']       = $data['cellphone'];
      $dataSQL['accountName']     = $data['accountName'];
      $dataSQL['Rol_rolId']     = $data['Rol_rolId'];
      $dataSQL['Status_statusId']     = $data['Status_statusId'];
      $dataSQL['isOnline']     = 1;
      $dataSQL['isNewUser']     = 1;
      $dataSQL['isEmailVerified']     = 0;
      $dataSQL['createdAt']     = $this->getTimestamp();
      $QueryCallback = $this->ExecuteAutoSQL(
        $mode = 'INSERT',
        $table = self::table,
        $columns = self::tableColumns,
        $data = $dataSQL
      );
    }

    $endJSON["status"] = "";
    $endJSON["data"] = "";
    try {

      if (
        isset($data['userId']) &&
        isset($data['basicDataBirthDate']) &&
        isset($data['basicDataDisability']) &&
        isset($data['basicDataDocNumber']) &&
        isset($data['basicDataFirstName']) &&
        isset($data['basicDataHeight']) &&
        isset($data['basicDataLastName']) &&
        isset($data['basicDataWeight']) &&
        isset($data['emergencyContactCellphone']) &&
        isset($data['emergencyContactNamePerson']) &&
        isset($data['userEpsName']) &&
        isset($data['userObservations']) &&
        isset($data['mobilityId']) &&
        isset($data['avatarId']) &&
        isset($data['sexId']) &&
        isset($data['docTypeId']) &&
        isset($data['accountRelationshipId']) &&
        isset($data['emergencyContactRelationshipId'])
      ) {
        $userId                         = $data['userId'];
        $basicDataDocNumber             = $data['basicDataDocNumber'];
        $basicDataBirthDate             = isset($data['basicDataBirthDate'])         ? $data['basicDataBirthDate']         : NULL;
        $basicDataDisability            = isset($data['basicDataDisability'])        ? $data['basicDataDisability']        : NULL;
        $basicDataFirstName             = isset($data['basicDataFirstName'])         ? $data['basicDataFirstName']         : NULL;
        $basicDataHeight                = isset($data['basicDataHeight'])            ? $data['basicDataHeight']            : NULL;
        $basicDataLastName              = isset($data['basicDataLastName'])          ? $data['basicDataLastName']          : NULL;
        $basicDataWeight                = isset($data['basicDataWeight'])            ? $data['basicDataWeight']            : NULL;
        $personalDataCellphone          = isset($data['personalDataCellphone'])      ? $data['personalDataCellphone']      : NULL;
        $emergencyContactCellphone      = isset($data['emergencyContactCellphone'])  ? $data['emergencyContactCellphone']  : NULL;
        $emergencyContactNamePerson     = isset($data['emergencyContactNamePerson']) ? $data['emergencyContactNamePerson'] : NULL;
        $userEpsName                    = isset($data['userEpsName'])                ? $data['userEpsName']                : NULL;
        $userObservations               = isset($data['userObservations'])           ? $data['userObservations']           : NULL;
        $mobilityId                     = $data['mobilityId'];
        $statusUserId                   = 1;
        $rolFamily                      = 7;
        $rolId                          = $rolFamily;
        $avatarId                       = $data['avatarId'];
        $sexId                          = $data['sexId'];
        $docTypeId                      = $data['docTypeId'];
        $accountRelationshipId          = $data['accountRelationshipId'];
        $emergencyContactRelationshipId = $data['emergencyContactRelationshipId'];
        $userMedicine= $data['userMedicines'];
        $userAllergies= $data['userAllergies'];

        $query = "INSERT INTO Family(User_userId, basicDataBirthDate, basicDataDisability, basicDataDocNumber, basicDataFirstName, basicDataHeight, basicDataLastName, Mobility_basicDataMobiilityId, basicDataWeight, personalDataCellphone, emergencyContactCellphone, emergencyContactNamePerson, userEpsName, userObservations, StatusUser_statusUserId, Rol_rolId, Avatar_avatarId, Sex_sexId, DocType_docTypeId, Relationship_accountRelationshipId, Relationship_emergencyContactRelationshipId,userMedicines,userAllergies) VALUES ('$userId', '$basicDataBirthDate', '$basicDataDisability', '$basicDataDocNumber', '$basicDataFirstName', '$basicDataHeight', '$basicDataLastName', '$mobilityId', '$basicDataWeight', '$personalDataCellphone', '$emergencyContactCellphone', '$emergencyContactNamePerson', '$userEpsName', '$userObservations', '$statusUserId', '$rolId', '$avatarId' ,'$sexId', '$docTypeId', '$accountRelationshipId', '$emergencyContactRelationshipId','$userMedicine','$userAllergies');";
        $QueryCallback = $this->ExecuteSql($query);
        if ($QueryCallback) {
          $data['memberId'] = intval($this->GetOne("SELECT LAST_INSERT_ID()"));
           $endJSON["status"] = "success";
          $endJSON["message"] = "Family was created.";
          $endJSON["data"] = $data;
        } else {
          throw new Exception("Error: SQL addNewFamily", 1);
        }
      } else {
        throw new Exception("Error: invalid parameters for Family", 1);
      }
    } catch (Exception $e) {
      http_response_code(400);
      $endJSON['status'] = 'error';
      die($e->getMessage());
    }
    return $endJSON;
  }

  public function getAllFamily()
  {
    $endJSON["status"] = "";
    $endJSON["data"] = "";
    try {
      $query = "SELECT * FROM Family ORDER BY familyId;";
      $SelectAllResult = $this->GetAll($query);
      if ($SelectAllResult) {
         $endJSON["status"] = "success";
        $endJSON["data"] = $SelectAllResult;
      } else {
        throw new Exception("Error: SQL getAllFamily", 1);
      }
    } catch (Exception $e) {
      http_response_code(400);
      $endJSON['status'] = 'error';
      die($e->getMessage());
    }
    return $endJSON;
  }

  public function getFamilyByUser(array $data)
  {
    $endJSON["status"] = "";
    $endJSON["data"] = "";
    try {
      if (isset($data['userId'])) {
        $userId = $data['userId'];
        $query = "SELECT * FROM viewFamily WHERE User_userId = '$userId' AND StatusUser_statusUserId = 1 ORDER BY memberId;";
        // $query = "SELECT * FROM viewFamily WHERE User_userId = '$userId' AND StatusUser_statusUserId = 1";
        $SelectAllResult = $this->GetAll($query);
        if ($SelectAllResult) {
           $endJSON["status"] = "success";
          $endJSON["data"] = $SelectAllResult;
        } else {
          throw new Exception("Error: SQL getFamily id", 1);
        }
      } else {
        throw new Exception("Error: invalid parameters for Family", 1);
      }
    } catch (Exception $e) {
      http_response_code(400);
      $endJSON['status'] = 'error';
      die($e->getMessage());
    }
    return $endJSON;
  }

  public function updateFamily(array $data)
  {
    $endJSON["status"] = "";
    $endJSON["data"] = "";
    try {
      if (
        isset($data['memberId']) &&
        isset($data['basicDataBirthDate']) &&
        isset($data['basicDataDisability']) &&
        isset($data['basicDataDocNumber']) &&
        isset($data['basicDataFirstName']) &&
        isset($data['basicDataHeight']) &&
        isset($data['basicDataLastName']) &&
        isset($data['basicDataWeight']) &&
        isset($data['personalDataCellphone']) &&
        isset($data['emergencyContactCellphone']) &&
        isset($data['emergencyContactNamePerson']) &&
        isset($data['userEpsName']) &&
        isset($data['userObservations']) &&
        isset($data['mobilityId']) &&
        isset($data['avatarId']) &&
        isset($data['sexId']) &&
        isset($data['docTypeId']) &&
        isset($data['accountRelationshipId']) &&
        isset($data['emergencyContactRelationshipId'])
      ) {
        $memberId                       = $data['memberId'];
        $basicDataBirthDate             = $data['basicDataBirthDate'];
        $basicDataDisability            = $data['basicDataDisability'];
        $basicDataDocNumber             = $data['basicDataDocNumber'];
        $basicDataFirstName             = $data['basicDataFirstName'];
        $basicDataHeight                = $data['basicDataHeight'];
        $basicDataLastName              = $data['basicDataLastName'];
        $mobilityId                     = $data['mobilityId'];
        $basicDataWeight                = $data['basicDataWeight'];
        $personalDataCellphone          = $data['personalDataCellphone'];
        $emergencyContactCellphone      = $data['emergencyContactCellphone'];
        $emergencyContactNamePerson     = $data['emergencyContactNamePerson'];
        $userEpsName                    = $data['userEpsName'];
        $userObservations               = $data['userObservations'];
        $avatarId                       = $data['avatarId'];
        $sexId                          = $data['sexId'];
        $docTypeId                      = $data['docTypeId'];
        $accountRelationshipId          = $data['accountRelationshipId'];
        $emergencyContactRelationshipId = $data['emergencyContactRelationshipId'];
        $userMedicine= $data['userMedicines'];
        $userAllergies= $data['userAllergies'];

        $query = "UPDATE Family SET basicDataBirthDate = '$basicDataBirthDate', basicDataDisability = '$basicDataDisability', basicDataDocNumber = '$basicDataDocNumber', basicDataFirstName = '$basicDataFirstName', basicDataHeight = '$basicDataHeight', basicDataLastName = '$basicDataLastName', Mobility_basicDataMobiilityId = '$mobilityId', basicDataWeight = '$basicDataWeight', personalDataCellphone= '$personalDataCellphone', emergencyContactCellphone = '$emergencyContactCellphone', emergencyContactNamePerson = '$emergencyContactNamePerson', userEpsName = '$userEpsName', userObservations = '$userObservations', Avatar_avatarId = '$avatarId', Sex_sexId = '$sexId', DocType_docTypeId = '$docTypeId', Relationship_accountRelationshipId = '$accountRelationshipId', Relationship_emergencyContactRelationshipId = '$emergencyContactRelationshipId' , userMedicines='$userMedicine',userAllergies='$userAllergies' WHERE memberId = '$memberId';";

        $QueryCallback = $this->ExecuteSql($query);
        if ($QueryCallback) {
           $endJSON["status"] = "success";
          $endJSON["data"] = $data;
        } else {
          throw new Exception("Error: SQL updateFamily", 1);
        }
      } else {
        throw new Exception("Error: invalid parameters for Family", 1);
      }
    } catch (Exception $e) {
      $endJSON['status'] = 'error';
      http_response_code(400);
      die($e->getMessage());
    }
    return $endJSON;
  }


  public function updateStatusFamily(array $data)
  {
    $endJSON["status"] = "";
    $endJSON["data"] = "";
    try {
      if (isset($data['memberId'])) {
        $memberId = $data['memberId'];
        $statusId = $data['statusId'];
        $query = "UPDATE `Family` SET Status_statusId = '$statusId' WHERE memberId = '$memberId';";
        $QueryCallback = $this->ExecuteSql($query);
        if ($QueryCallback) {
           $endJSON["status"] = "success";
          $endJSON["data"] = $data;
        } else {
          throw new Exception("Error: SQL updatePhotoURL", 1);
        }
      } else {
        throw new Exception("Error: invalid parameters for Family", 1);
      }
    } catch (Exception $e) {
      $endJSON['status'] = 'error';
      http_response_code(400);
      die($e->getMessage());
    }
    return $endJSON;
  }

  
  public function deleteFamily(array $data)
  {
    $data['statusId'] = 2;
    return $this->updateStatusFamily($data);
  }


}
