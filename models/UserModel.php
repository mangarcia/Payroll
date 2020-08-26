<?php

use \Firebase\JWT\JWT;

class UserModel extends Database
{
  const table = 'User';
  const tableColumns = array(
    'userId' => array(
      'primaryKey' => true,
      // 'required' => true,
    ),
    'lastConnectionDateTime' => array(
      // 'primaryKey' => false,
      // 'required' => true,
    ),
    'accountEmail' => array(
      // 'primaryKey' => false,
      'required' => true,
    ),
    'accountPassword' => array(
      // 'primaryKey' => false,
      // 'required' => true,
    ),
    'accountCellphone' => array(
      // 'primaryKey' => false,
      // 'required' => true,
    ),
    'accountName' => array(
      // 'primaryKey' => false,
      'required' => true,
    ),
    'isEmailVerified' => array(
      // 'primaryKey' => false,
      // 'required' => false,
    ),
    'isNewUser' => array(
      // 'primaryKey' => false,
      // 'required' => false,
    ),
    'isOnline' => array(
      // 'primaryKey' => false,
      // 'required' => true,
    ),
    'photoURL' => array(
      // 'primaryKey' => false,
      // 'required' => true,
    ),
    'Rol_rolId' => array(
      // 'primaryKey' => false,
      'required' => true,
    ),
    'Status_statusId' => array(
      // 'primaryKey' => false,
      'required' => true,
    ),
    'tokenFacebook' => array(
      // 'primaryKey' => false,
      // 'required' => false,
    ),
    'tokenGoogle' => array(
      // 'primaryKey' => false,
      // 'required' => true,
    ),
    'createdAt' => array(
      // 'primaryKey' => false,
      'required' => true,
    ),
    'updatedAt' => array(
      // 'primaryKey' => false,
      // 'required' => true,
    ),
    'tempPasswordToken' => array(
      // 'primaryKey' => false,
      // 'required' => true,
    ),
    'tokenPasswordExpiredAt' => array(
      // 'primaryKey' => false,
      // 'required' => true,
    ),
    'Company_companyId' => array(
      // 'primaryKey' => false,
      // 'required' => true,
    )
  );

  public function __construct()
  {
    parent::__construct();
  }

  private function generateHashPass(string $pwd): string
  {
    $pepper = "salt";
    $pwd_peppered = hash_hmac("sha256", $pwd, $pepper);
    return password_hash($pwd_peppered, PASSWORD_ARGON2ID);
  }

  private function verfityHashPass(string $pwd, $user,$isTemp=false): Bool
  {
    $pepper = "salt";
    $pwd_peppered = hash_hmac("sha256", $pwd, $pepper);
    if($isTemp)
    {
      $pwd_hashed = $user['data']['SystemUserPassword'];
    }
    else
    {
       $pwd_hashed = $user['data']['SystemUserPassword'];
    }
    
    
    return password_verify($pwd_peppered, $pwd_hashed);
  }

  private function generateJwt(array $user)
  {
    $secret_key = "YOUR_SECRET_KEY";
    $issuer_claim = "api.teella.com"; // this can be the servername
    $audience_claim = $user['data']['idSystemUser']; // "THE_AUDIENCE";
    $issuedat_claim = time(); // issued at
    $notbefore_claim = $issuedat_claim + 10; //not before in seconds
    $expire_claim = $issuedat_claim + 315360000; // expire time in seconds
    $token = array(
      "ver" => 1.0,
      "iss" => $issuer_claim,
      "aud" => $audience_claim,
      "iat" => $issuedat_claim,
      "nbf" => $notbefore_claim,
      "exp" => $expire_claim,
      "sid" => $user['data']['idSystemUser'],
      "email" => $user['data']['SystemUserEmail'],
      "cellphone" => $user['data']['SystemUserPhone'],
           "unique_name" => $user['data']['SystemUserName'],
     // "role" => $user['data']['rol'],
      "statusUser" => $user['data']['SystemUserStatus_idSystemUserStatus'],
      "agent" => $_SERVER['HTTP_USER_AGENT']
    );

    http_response_code(200);

    $jwt = JWT::encode($token, $secret_key);
    return array(
      "message" => "Successful login.",
      "jwt" => $jwt,
      "expireAt" => $expire_claim
    );
  }


  public function checkExistEmail(array $data)
  {
    $endJSON["status"] = "";
    $endJSON["data"] = "";
    try {
      if (isset($data['email'])) {
        $email = $data['email'];
        $query = "SELECT * FROM systemuser WHERE SystemUserEmail = '$email' LIMIT 0,1;";
        $SelectOneResult = $this->GetRow($query);
        if ($SelectOneResult!="No Registry Found") {
           $endJSON["status"] = "success";
          $endJSON["data"] = $SelectOneResult;
      /*   $companyUrl="SELECT imageUrl as 'companyImageUrl' ,Company.companyId,Company.name as 'companyName',CompanyType FROM `Company`,User WHERE User.Company_companyId=companyId and User.userId='".$endJSON["data"]['userId']."'";
     //     $endJSON["data"]["companyData"]=$this->GetRow($companyUrl);
       //   if($endJSON["data"]["companyData"]=="No registry found")
        //  {
       //       $endJSON["data"]["companyData"]=array("companyImageUrl"=>"http://api.teella.com/public/img/Teella/TeellaUser.jpg","companyName"=>"Teella");          
        //  }*/
        } else {
          throw new Exception('{"Error":"User not exist in the database"}', 12);
       }
      }
    } catch (Exception $e) {
      http_response_code(400);
      $endJSON['status'] = 'error';
      die($e->getMessage());
    }
    return $endJSON;
  }

  public function loginUser(array $data)
  {
     $endJSON['status'] = 'error';
    if (
      isset($data['email']) &&
      isset($data['password'])
    ) {
      $password = $data['password'];
      $userDb = $this->checkExistEmail($data);

      if ($userDb['data'] == 'No registry found') 
      {
        $endJSON['status'] = 'error';
        $endJSON["message"] = "User Not Found";
      }
      else if($this->verfityHashPass($password, $userDb))
      {
        if ($userDb['status'] == 'success' && $userDb['data']['SystemUserStatus_idSystemUserStatus'] == 1) 
        {
          $endJSON = $this->generateJwt($userDb);
           $endJSON["status"] = "success";
          Session::set('TellaConnected', true);  
          Session::set('idSystemUser', $userDb['data']['idSystemUser']);
          Session::set('RoleId', $userDb['data']['UserRol_idUserRol']);
          Session::set('accountName', $userDb['data']['SystemUserName']);
          Session::set('companyImageUrl', "/public/img/stormfellicon.png");


          //Session::set('SystemUserIdCreated', $userDb['data']['SystemUserIdCreated']);


          /*  Session::set('RoleId', $userDb['data']['rolUserAccountId']);
                   
            Session::set('accountName', $userDb['data']['accountName']);

          if(isset($userDb['data']["companyData"]['companyImageUrl']))
          {
            Session::set('companyImageUrl', $userDb['data']["companyData"]['companyImageUrl']);
            Session::set('companyId', $userDb['data']["companyData"]['companyId']);
            Session::set('companyName', $userDb['data']["companyData"]['companyName']);
            Session::set('companyType', $userDb['data']["companyData"]['CompanyType']);
          }
          
          */
        }
       
      }
      else if($this->verfityHashPass($password, $userDb,true))
      {
        if($this->validatePasswordExpireTime($userDb['data']['tempPaswwordExpTime']))
        {
           $endJSON = $this->generateJwt($userDb);
           $endJSON["status"] = "CanRecover";
        }
        else
        {
          $endJSON['status'] = 'error';
          $endJSON["message"] = "El token ya expiró";
        }
        
      }
      else
      {
         $endJSON['status'] = 'error';
        $endJSON["message"] = "Wrong authentication";

      }
      
    } else {
     
       $endJSON['status'] = 'error';
       $endJSON["message"] = "Missing Params";
    }
    return $endJSON;
  }

  public function loginProviderUser(array $data)
  {
    $endJSON["status"] = "";
    $endJSON["data"] = "";
    try {
      if (
        isset($data['typeProvider']) &&
        isset($data['token']) &&
        isset($data['email']) &&
        isset($data['accountName']) &&
        isset($data['rolName'])
      ) {
        $userDb = $this->checkExistEmail($data);
        if ($userDb['data'] == 'No registry found') {
          $this->registerNewUser($data);
          $userDb = $this->checkExistEmail($data);
        }


        $email_exists = ($userDb['status'] == 'Success');
        $user_active = ($userDb['data']['statusUserAccountId'] == "1");

        if ($email_exists && $user_active) {
          $data['typeProvider'] = strtolower($data['typeProvider']);
          if ($data['typeProvider'] == 'facebook' || $data['typeProvider'] == 'google') {
            $typeProvider = 'token' . ucfirst($data['typeProvider']);
          } else {
            throw new Exception('{"Error":"falied generate authorization for typeProvider"}', 18);
          }
          $email = $data['email'];
          $timestamp = parent::getTimestamp();
          $token = $data['token'];
          $accountName = $data['accountName'];
          $isEmailVerified = 1;
          $isNewUser = 0;
          $isOnline = 1;
          $rolClient = 6;
          $rolAssistant = 5;
          $rolId = ($data['rolName'] == 'Client') ? $rolClient : $rolAssistant;
          $statusId = 1; //ACTIVE
          $query = "UPDATE `User` SET lastConnectionDateTime = '$timestamp', accountEmail = '$email', accountName = '$accountName', isEmailVerified = '$isEmailVerified', isNewUser = '$isNewUser', isOnline = '$isOnline', photoURL = '', Rol_rolId = '$rolId', Status_statusId = '$statusId', $typeProvider = '$token' WHERE userId = " . $userDb["data"]["userId"] . ";";
          $QueryCallback = $this->ExecuteSql($query);
          if ($QueryCallback) {
            $userDb = $this->checkExistEmail($data);
            $email_exists = ($userDb['status'] == 'Success');
            $user_active = ($userDb['data']['statusUserAccountId'] == 1);
            if ($email_exists && $user_active) {
              $endJSON = $this->generateJwt($userDb);
               $endJSON["status"] = "success";
              $endJSON["message"] = "User was created.";
              unset($data['password']);
              $endJSON["data"] = $data;
            } else {
              throw new Exception('{"Error":"falied generate authorization"}', 13);
            }
          }
        } else {
          $data['typeProvider'] = strtolower($data['typeProvider']);
          if ($data['typeProvider'] == 'facebook' || $data['typeProvider'] == 'google') {
            $typeProvider = 'token' . ucfirst($data['typeProvider']);
          } else {
            throw new Exception('{"Error":"falied generate authorization for typeProvider"}', 18);
          }

          $email = $data['email'];
          $timestamp = parent::getTimestamp();
          $token = $data['token'];
          $accountName = $data['accountName'];
          $isEmailVerified = true;
          $isNewUser = true;
          $isOnline = true;
          $rolClient = 6;
          $rolAssistant = 5;
          $rolId = ($data['rolName'] == 'Client') ? $rolClient : $rolAssistant;
          $statusId = 1; //ACTIVE
          $query = "INSERT INTO `User` (createdAt, $typeProvider, accountEmail, isEmailVerified, accountName, isNewUser, isOnline, Rol_rolId, Status_statusId ) VALUES ($timestamp, '$token', '$email', '$isEmailVerified', '$accountName' , '$isNewUser', '$isOnline', '$rolId', '$statusId' );";
          $QueryCallback = $this->ExecuteSql($query);
          if ($QueryCallback) {
            $userDb = $this->checkExistEmail($data);
            $email_exists = ($userDb['status'] == 'Success');
            $user_active = ($userDb['data']['statusUserAccountId'] == 1);
            if ($email_exists && $user_active) {
              $endJSON = $this->generateJwt($userDb);
               $endJSON["status"] = "success";
              $endJSON["message"] = "User was created.";
              unset($data['password']);
              $endJSON["data"] = $data;
            } else {
              throw new Exception('{"Error":"falied generate authorization"}', 13);
            }
          }
        }
      } else {
        throw new Exception('{"Error":"Failed Login beacuse empty data for authentication"}', 14);
      }
    } catch (Exception $e) {
      http_response_code(400);
      $endJSON['status'] = 'error';
      die($e->getMessage());
    }
    return $endJSON;
  }

  public function signOutUser(array $data)
  {
    $endJSON["status"] = "";
    $endJSON["data"] = "";
    try {
      if (
        isset($data['userId'])
        // isset($data['isOnline'])
      ) {
        $data['isOnline'] = 0;
        $endJSON = $this->updateIsOnline($data);
      } else {
        throw new Exception('{"Error":"Failed Login beacuse empty data for authentication"}', 14);
      }
    } catch (Exception $e) {
      http_response_code(400);
      $endJSON['status'] = 'error';
      die($e->getMessage());
    }
    return $endJSON;
  }

  public function registerNewUser(array $data)
  {
    try {
      $dataSQL = array();
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
      } elseif (
        isset($data['accountName']) &&
        isset($data['email']) &&
        isset($data['rolName']) &&
        isset($data['typeProvider']) &&
        isset($data['token'])
      ) {
        $data['typeProvider'] = strtolower($data['typeProvider']);
        if ($data['typeProvider'] == 'facebook' || $data['typeProvider'] == 'google') {
          $typeProvider = 'token' . ucfirst($data['typeProvider']);
        } else {
          throw new Exception('{"Error":"falied generate authorization for typeProvider"}', 18);
        }

        $isEmailVerified = 1;
        $isNewUser       = 0;
        $isOnline        = 1;
        $statusId        = 1;  //ACTIVE

        $dataSQL['accountEmail']    = $data['email'];
        $dataSQL['accountName']     = $data['accountName'];
        $dataSQL['createdAt']       = parent::getTimestamp();
        $dataSQL['isEmailVerified'] = $isEmailVerified;
        $dataSQL['isNewUser']       = $isNewUser;
        $dataSQL['isOnline']        = $isOnline;
        $dataSQL['Rol_rolId']       = intval($data['Rol_rolId']);
        $dataSQL['Status_statusId'] = $statusId;
        $dataSQL[$typeProvider]    = $data['token'];

        $QueryCallback = $this->ExecuteAutoSQL(
          $mode = 'INSERT',
          $table = self::table,
          $columns = self::tableColumns,
          $data = $dataSQL
        );
      }
      if ($QueryCallback) {
        $data['lastID'] = intval($this->GetOne("SELECT LAST_INSERT_ID()"));
        $data = $this->ExecuteAutoSQL(
          $mode = 'SELECT',
          $table = self::table,
          $columns = self::tableColumns,
          $data = $data
        );
        $user['data'] = $data;
        $user_active = ($data['Status_statusId'] == 1);
        if ($user_active) {
          $endJSON = $this->generateJwt($user);
          $data = $this->cleanDataSensive($data);
          $endJSON["data"] = $data;
           $endJSON["status"] = "success";
          $endJSON["message"] = "User was created.";
        } else {
          throw new Exception('falied generate authorization', 400);
        }
      }
    } catch (\Throwable $th) {
      $message = $th->getMessage();
      if (strpos($message, 'Duplicate entry') !== false) {
        $endJSON['status'] = 'error';
        $endJSON['message'] = 'That username or number cellphone is already in use. Try another one.';
      } elseif (strpos($message, 'Data too long for column') !== false) {
        $endJSON['status'] = 'error';
        $endJSON['message'] = 'That number cellphone is error.';
      } else {
        $endJSON['status'] = 'error';
        $endJSON['message'] = $message;
      }
    }

    return $endJSON;
  }

  public function registerNewWebUser(array $data)
  {
    $endJSON["status"] = "";
    $endJSON["data"] = "";


    if(strlen($data['SystemUserPassword'])<6)
    {
      $endJSON["status"] = "error";
    $endJSON["data"] = "";
    $endJSON["message"] = "The password must be at least 6 Digits";
      return $endJSON;
    }
    
    try {
      $dataSQL = array();
      if (
        isset($data['SystemUserNickName']) &&
        isset($data['SystemUserName']) &&
        isset($data['UserRol_idUserRol'])&&
        isset($data['SystemUserPassword'])&&
        isset($data['SystemUserEmail'])
      ) {
        $SystemUserNickName   = $data['SystemUserNickName'];
        $SystemUserName   = $data['SystemUserName'];
        $SystemUserPhone       = $data['SystemUserPhone'];
        $SystemUserEmail       = $data['SystemUserEmail'];
        $SystemUserPhone       = $data['SystemUserPhone'];

        $UserRol_idUserRol   = $data['UserRol_idUserRol'];
        $SystemUserStatus_idSystemUserStatus    = $data['SystemUserStatus_idSystemUserStatus'];
        
        $password     = $data['SystemUserPassword'];

        $SystemUserPassword = $this->generateHashPass($password);

        date_default_timezone_set('Canada/Eastern');
        $CreatedDate= new DateTime('NOW');
  
        $SystemUserCreateDate = $CreatedDate->format('Y-m-d');



        $query="insert into SystemUser (
          SystemUserNickName,
          SystemUserPassword,
          SystemUserName,
          SystemUserEmail,
          UserRol_idUserRol,
          SystemUserCreateDate,
          Agency_idAgency,
          SystemUserStatus_idSystemUserStatus,
          SystemUserPhone
          ) 
          values 
          ('$SystemUserNickName',
          '$SystemUserPassword',
          '$SystemUserName',
          '$SystemUserEmail' ,
          '$UserRol_idUserRol',
          '$SystemUserCreateDate', 
          '1',
          '$SystemUserStatus_idSystemUserStatus',
          '$SystemUserPhone')";
        

        $QueryCallback = $this->ExecuteSql($query);
         if (!$QueryCallback) 
      {
         $endJSON['status'] = 'error';
         $endJSON['message'] = 'The user cant be created';
         

      }
      else
      {
        $endJSON['status'] = 'success';
        $endJSON['message'] = 'Se creo el usuario correctamente';

      }
       
      }
      else
      {
        $endJSON['status'] = 'error';
        $endJSON['message'] = 'Missing fields';
      }
    } catch (\Throwable $th) {
      $message = $th->getMessage();
      if (strpos($message, 'Duplicate entry') !== false) {
        $endJSON['status'] = 'error';
        $endJSON['message'] = 'That username or number cellphone is already in use. Try another one.';
      } elseif (strpos($message, 'Data too long for column') !== false) {
        $endJSON['status'] = 'error';
        $endJSON['message'] = 'That number cellphone is error.';
      } else {
        $endJSON['status'] = 'error';
        $endJSON['message'] ="The user cant be created";
      }
    }

    return $endJSON;
  }


  public function getAllUser()
  {

    $endJSON["status"] = "";
    $endJSON["data"] = "";
    try {

     // $roleId=Session::get("RoleId");
     $roleId=8;
      if($roleId==8)
      {
         $query = "SELECT idSystemUser, SystemUserNickName,
          SystemUserPhone,
          SystemUserName,
          SystemUserEmail,
          UserRol_idUserRol,
          SystemUserCreateDate,
          Agency_idAgency,
          SystemUserStatus_idSystemUserStatus
          FROM systemuser;";

              $SelectAllResult = $this->GetAll($query);
          if ($SelectAllResult) {
             $endJSON["status"] = "success";
          } else {
            throw new Exception("Error: SQL getAllUser", 1);
          }

           $query = "SELECT DISTINCT User.*,'http://api.teella.com/public/img/Teella/TeellaUser.jpg' as 'companyPhotoUrl', NULL as CompanyName FROM User where Company_companyId is null ORDER BY User.accountName asc;";
          $SelectTeellaUsers = $this->GetAll($query);
          if ($SelectTeellaUsers) {
             $endJSON["status"] = "success";
          } else {
            throw new Exception("Error: SQL getAllUser", 1);
          }
            $endJSON["data"] =  array('CompanyUsers' =>  $SelectAllResult,'TeellaUsers' =>  $SelectTeellaUsers); 
      }
      else if($roleId==1)
      { 
         $companyId=Session::get("companyId");
         $query = "SELECT DISTINCT User.*,Company.imageUrl as 'companyPhotoUrl',Company.name as CompanyName FROM User,Company where Company.companyId=User.Company_companyId and Company.companyId='$companyId' ORDER BY CompanyName asc;";
          $SelectAllResult = $this->GetAll($query);
          if ($SelectAllResult) {
             $endJSON["status"] = "success";
          } else {
            throw new Exception("Error: SQL Get Users", 1);
          }

          $endJSON["data"] =  array('CompanyUsers' =>  $SelectAllResult,'TeellaUsers' => ""); 
      }
      else
      {
         $endJSON['status'] = 'error';
         $endJSON["message"]="error";
      }
     


    } catch (Exception $e) {
      http_response_code(400);
      $endJSON['status'] = 'error';
      die($e->getMessage());
    }
    return $endJSON;
  }


  public function getAllUserStatus()
  {
  
  $endJSON["status"] = "";
  $endJSON["data"] = "";
  try {
  
  $roleId=8;
  if($roleId==8)
  {
  $query = "SELECT * FROM systemuserstatus;";
  
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


  public function getAllUserRol()
  {
  
  $endJSON["status"] = "";
  $endJSON["data"] = "";
  try {
  
  $roleId=Session::get('RoleId');
  if($roleId==1)
  {
  $query = "SELECT * FROM userrol;";
  
  $SelectAllResult = $this->GetAll($query);
  if ($SelectAllResult) {
   $endJSON["status"] = "success";
  } else {
  throw new Exception("Error: SQL getallrol", 1);
  }
  
  
  $endJSON["data"] = $SelectAllResult;
  }
  else if($roleId==2)
  {
  $query = "SELECT * FROM userrol where idUserRol>$roleId;";
  
  $SelectAllResult = $this->GetAll($query);
  if ($SelectAllResult) {
   $endJSON["status"] = "success";
  } else {
  throw new Exception("Error: SQL getallrol", 1);
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


  public function getUser(array $data)
  {
    $endJSON["status"] = "";
    $endJSON["data"] = "";
    try {
      if (isset($data['aud'])) {
        $userId = $data['aud'];
        $query = "SELECT * FROM User WHERE userId = '$userId';";
        $SelectAllResult = $this->GetAll($query);
        if ($SelectAllResult) {
           $endJSON["status"] = "success";
          $endJSON["data"] = $SelectAllResult;
        } else {
          throw new Exception("Error: SQL getUser id", 1);
        }
      } elseif (isset($data['name'])) {
        $name = $data['name'];
        $query = "SELECT * FROM User WHERE name = '$name';";
        $SelectAllResult = $this->GetAll($query);
        if ($SelectAllResult) {
           $endJSON["status"] = "success";
          $endJSON["data"] = $SelectAllResult;
        } else {
          throw new Exception("Error: SQL getUser name", 1);
        }
      } else {
        throw new Exception("Error: invalid parameters for User", 1);
      }
    } catch (Exception $e) {
      http_response_code(400);
      $endJSON['status'] = 'error';
      die($e->getMessage());
    }
    return $endJSON;
  }

  public function getUserData(array $data)
  {
      if (!isset($data['userId'])) {
        throw new Exception("required userId", 400);
      }
        $dataSQL = $data;
        $QueryCallback = $this->ExecuteAutoSQL(
          $mode = 'SELECT',
          $table = 'viewUserAccount',
          $columns = self::tableColumns,
          $data = $dataSQL
      );

      if ($QueryCallback) {
        $endJSON["data"] = $this->cleanDataSensive($QueryCallback);
         $endJSON["status"] = "success";
      }
      return $endJSON;
  }

  public function updateUser(array $data)
  {
    try {
      $dataSQL = array();
      $sucessParam = array();
      if (isset($data['userId'])) {
        $sucessMsg = 'User';
        $dataSQL['_documentID'] = 'userId';
        $dataSQL['userId']     = $data['userId'];
        if (isset($data['email'])) {
          $dataSQL['accountEmail'] = $data['email'];
          array_push($sucessParam, 'email');
        }
        if (isset($data['password'])) {
          $dataSQL['accountPassword'] = $this->generateHashPass($data['password']);
          array_push($sucessParam, 'password');
        }
        if (isset($data['cellphone'])) {
          $dataSQL['accountCellphone'] = $data['cellphone'];
          array_push($sucessParam, 'cellphone');
        }
        if (isset($data['accountName'])) {
          $dataSQL['accountName'] = $data['accountName'];
          array_push($sucessParam, 'accountName');
        }
        // $dataSQL['isNewUser']        = 0;
        // $dataSQL['isEmailVerified']  = 0;
        // $dataSQL['updatedAt']        = $this->getTimestamp();

        $QueryCallback = $this->ExecuteAutoSQL(
          $mode    = 'UPDATE',
          $table   = self::table,
          $columns = self::tableColumns,
          $data    = $dataSQL
        );
        if ($QueryCallback) {
          $dataSQL['lastID'] = $data['userId'];
          $data = $this->ExecuteAutoSQL(
            $mode = 'SELECT',
            $table = 'viewUserAccount',
            $columns = self::tableColumns,
            $data = $dataSQL
          );
          $user_active = ($data['statusUserAccountId'] == 1);
          if ($user_active) {
            $data = $this->cleanDataSensive($data);
            $endJSON["data"] = $data;
             $endJSON["status"] = "success";
            $paramsUpdated = $this->printArgsName($sucessParam);
            $sucessMsg .= "$paramsUpdated was updated.";
            $endJSON["message"] = $sucessMsg;
          } else {
            throw new Exception('falied generate authorization', 400);
          }
        }
      }
    } catch (\Throwable $th) {
      $message = $th->getMessage();
      if (strpos($message, 'Duplicate entry') !== false) {
        $endJSON['status'] = 'error';
        $endJSON['message'] = 'That username or number cellphone is already in use. Try another one.';
      } elseif (strpos($message, 'Data too long for column') !== false) {
        $endJSON['status'] = 'error';
        $endJSON['message'] = 'That number cellphone is error.';
      } else {
        $endJSON['status'] = 'error';
        $endJSON['message'] = $message;
      }
    }

    return $endJSON;
  }


   public function updateWebUser(array $data)
  {
    $endJSON["status"] = "";
    $endJSON["data"] = "";
    if($data['SystemUserPassword']!="" && strlen($data['SystemUserPassword'])<6)
    {
      $endJSON["status"] = "error";
    $endJSON["data"] = "";
    $endJSON["message"] = "The password must be at least 6 Digits";
      return $endJSON;
    }
    try {

      $QueryCallback="update systemuser set ";
      if(isset($data['SystemUserNickName']))
      {
        $QueryCallback.="SystemUserNickName='".$data['SystemUserNickName']."',";
      }

      if(isset($data['SystemUserName']))
      {
        $QueryCallback.="SystemUserName='".$data['SystemUserName']."',";
      }

      if(isset($data['SystemUserPhone']))
      {
        $QueryCallback.="SystemUserPhone='".$data['SystemUserPhone']."',";
      }

       if(isset($data['SystemUserEmail']))
      {
        $QueryCallback.="SystemUserEmail='".$data['SystemUserEmail']."',";
      }

        if($data['SystemUserPassword']!="" && isset($data['SystemUserPassword']))
      {
        $password     = $data['SystemUserPassword'];

        $SystemUserPassword = $this->generateHashPass($password);
        $QueryCallback.="SystemUserPassword='".$SystemUserPassword."',";
      }

      if(isset($data['UserRol_idUserRol']))
      {
        $QueryCallback.="UserRol_idUserRol='".$data['UserRol_idUserRol']."',";
      }

      if(isset($data['SystemUserStatus_idSystemUserStatus']))
      {
        $QueryCallback.="SystemUserStatus_idSystemUserStatus='".$data['SystemUserStatus_idSystemUserStatus']."',";
      }


      $QueryCallback=rtrim($QueryCallback,",");

      $QueryCallback .= " where idSystemUser='".$data['idSystemUser']."'";

      //echo $QueryCallback;

       $QueryCallback = $this->ExecuteSql($QueryCallback);
       
        if ($QueryCallback) {
           $endJSON["status"] = "success";
        } else {
          throw new Exception('{"Error":"SQL registerNewAssistant"}', 1);
        }



    } catch (\Throwable $th) {
      $message = $th->getMessage();
      if (strpos($message, 'Duplicate entry') !== false) {
        $endJSON['status'] = 'error';
        $endJSON['message'] = 'That username or number cellphone is already in use. Try another one.';
      } elseif (strpos($message, 'Data too long for column') !== false) {
        $endJSON['status'] = 'error';
        $endJSON['message'] = 'That number cellphone is error.';
      } else {
        $endJSON['status'] = 'error';
        $endJSON['message'] = $message;
      }
    }

    return $endJSON;
  }

  private function cleanDataSensive(array $data): array
  {
    unset($data['accountCellphone']);
    unset($data['accountPassword']);
    unset($data['Company_companyId']);
    unset($data['createdAt']);
    unset($data['isEmailVerified']);
    unset($data['isNewUser']);
    unset($data['isOnline']);
    unset($data['lastConnectionDateTime']);
    unset($data['photoURL']);
    unset($data['rolUserAccountId']);
    unset($data['Status_statusId']);
    unset($data['statusUserAccountId']);
    unset($data['tempPasswordToken']);
    unset($data['tokenFacebook']);
    unset($data['tokenGoogle']);
    unset($data['tokenPasswordExpiredAt']);
    return $data;
  }

  private function printArgsName(array $argName): string
  {
    $paramsUpdated = '';
    $i = 0;
    foreach ($argName as $key => $value) {
      $count  = count($argName);
      $paramsUpdated .= " $value";
      $i++;

      if ($i < $count - 1) {
        $paramsUpdated .= ",";
        # code...
      } elseif ($i < $count) {
        $paramsUpdated .= " and";
      } else {
        continue;
      }
    }
    return $paramsUpdated;
  }

  public function updateEmailUser(array $data)
  {
    $endJSON["status"] = "";
    $endJSON["data"] = "";
    try {
      if (
        isset($data['userId']) &&
        isset($data['email'])
      ) {
        $userId = $data['userId'];
        $accountEmail = $data['email'];
        $query = "UPDATE `User` SET accountEmail = '$accountEmail' WHERE userId = '$userId';";
        $QueryCallback = $this->ExecuteSql($query);
        if ($QueryCallback) {
          $endJSON["status"] = "Success updated email";
          $endJSON["data"] = $data;
        } else {
          throw new Exception("Error: SQL updateEmailUser", 1);
        }
      } else {
        throw new Exception("Error: invalid parameters for User", 1);
      }
    } catch (Exception $e) {
      $endJSON['status'] = 'error';
      http_response_code(400);
      die($e->getMessage());
    }
    return $endJSON;
  }


  public function updatePasswordUser(array $data)
  {

    $endJSON["status"] = "";
    $endJSON["data"] = "";
    try {
      if (
        isset($data['userId']) &&
        isset($data['password'])
      ) {
        $userId = $data['userId'];
        $accountPassword = $this->generateHashPass($data['password']);
        $query = "UPDATE `User` SET accountPassword = '$accountPassword',tempPasswordToken=NULL WHERE userId = '$userId';";
        $QueryCallback = $this->ExecuteSql($query);
        if ($QueryCallback) {
           $endJSON["status"] = "success";
          unset($data['password']);
          $endJSON["data"] = $data;
        } else {
          throw new Exception("Error: SQL updatePasswordUser", 1);
        }
      } else {
        throw new Exception("Error: invalid parameters for User", 1);
      }
    } catch (Exception $e) {
      $endJSON['status'] = 'error';
      http_response_code(400);
      die($e->getMessage());
    }
    return $endJSON;
  }

  public function updateCellphoneUser(array $data)
  {
    $endJSON["status"] = "";
    $endJSON["data"] = "";
    try {
      if (
        isset($data['userId']) &&
        isset($data['email'])
      ) {
        $userId = $data['userId'];
        $accountCellphone = $data['cellphone'];
        $query = "UPDATE `User` SET accountCellphone = '$accountCellphone' WHERE userId = '$userId';";
        $QueryCallback = $this->ExecuteSql($query);
        if ($QueryCallback) {
          $endJSON["status"] = "Success updated cellphone";
          $endJSON["data"] = $data;
        } else {
          throw new Exception("Error: SQL updateCellPhoneUser", 1);
        }
      } else {
        throw new Exception("Error: invalid parameters for User", 1);
      }
    } catch (Exception $e) {
      $endJSON['status'] = 'error';
      http_response_code(400);
      die($e->getMessage());
    }
    return $endJSON;
  }

  public function validateUser($email, $password)
  {
    $query = "select * from User where Document='$email'";
    $value = $this->GetRow($query);

    
    $query = "select * from PasswordRecovery where Document='$email'";
    $value2 = $this->GetRow($query);

    $result = array();

    if ($value["Password"] == "") {
      $result["State"] = "NoExist";
      return $result;
    }

    if (password_verify($password, $value["Password"])) {
      if ($value["UserStatus_id_UserStatus"] == 0) {
        $result["State"] = "Banned";
      } else {
        $result["State"] = "OK";
        $result["User"] = $value;
      }
      if ($value2) {
        $query = "DELETE from PasswordRecovery where Document='$email'";
        $this->ExecuteSql($query);
      }
    } else {
      if (password_verify($password, $value2["PasswordRecover"])) {
        $result["State"] = "CanRecover";
        return $result;
      }

      $result["State"] = "error";
      $result["data"]["message"] = "invalid user or password";
    }
    return $result;
  }

  public function checkEmailUser(string $email = null)
  {
    $query = "SELECT userId, accountEmail,accountName FROM User WHERE accountEmail = '$email'";
    $result = $this->GetRow($query);
    if (isset($result)) {
      return $result;
    } else {
      throw new Exception("No found email user", 400);
    }
  }

  public function generatePasswordTemp(): string
  {
    //$randomNum=rand(10000, 1000000);
    //dechex(rand(3e8, f4240));
    return dechex(rand(0x000000, 0xFFFFFF));
  }

  public function sendMailResetPassword(string $accountName,string $email, string $passTemp): bool
  {

    $body='<table align="center" cellspacing="0" cellpadding="0" style="width:538px; background-color:#393836">
<tbody>
<tr>
<td style="height:65px; background-color:#ffffff; border-bottom:0px solid #ffffff">
<img data-imagetype="External" src="https://teella.com/TeellaEmailHeader.png" width="538" height="65" alt="Teella">
</td>
</tr>
<tr >
<td bgcolor="#FFFFFF" bordercolor="Red" >
<table width="470" border="0" align="center" cellpadding="0" cellspacing="0" style="padding-left:5px; padding-right:5px; padding-bottom:10px">
<tbody>
<tr bgcolor="#FFFFFF">
<td style="padding-top:32px"><span style="padding-top: 16px; padding-bottom: 16px; font-size: 24px; color: rgb(255, 0, 231); font-family:  Helvetica, sans-serif; font-weight: bold;">Hola, '.$accountName.':
</span><br>
</td>
</tr>
<tr>
<td style="padding-top:12px"><span style="font-size: 17px; color: black; font-family: Arial, Helvetica, sans-serif, serif, EmojiFont; font-weight: bold;">
<p>Este es el código de recuperación de contraseña de Teella:</p>
</span></td>
</tr>
<tr>
<td>
<div align="center"><span  style="font-size: 50px; color: rgb(255, 0, 231); font-family: Helvetica; font-weight: bold;">'.$passTemp.'
</span></div>
</td>
</tr>
<td style="padding-top:50px"><span style="font-size: 15px; color: grey; font-family: Arial font-weight: bold;">
<p>Si tu no fuiste el que hizo esta solicitud ignora este mensaje.</p>

</table>
</td>
</tr>
</tbody>
</table>
 ';



    //dirección del remitente
 
    $headers = "From: Recovery Teella < no-response@teella.com >\r\n";
    $headers .= "MIME-Version: 1.0\r\n";
    $headers .= "Content-Type: text/html\r\n";
    
    // $headers = 'From: noreply @ teella . com';

    // Enviamos el mensaje al destinatario email
    $to_email = $email;
    //Titulo
    $subject = 'Recuperación contraseña de Teella';
    //$message = "Hemos recibido una solicitud de cambio de contraseña \n si NO realizaste esta solicitud ignora este mensaje \n de lo contrario ingresa a nuestra plataforma con el siguiente codigo: $passTemp";

    $bool = mail(utf8_decode($to_email),utf8_decode($subject),$body,$headers);


     return $bool;
  }


   public function sendMailNewUserPassword(string $accountName,string $email, string $passTemp): bool
  {

    $body='<table align="center" cellspacing="0" cellpadding="0" style="width:538px; background-color:#393836">
<tbody>
<tr>
<td style="height:65px; background-color:#ffffff; border-bottom:0px solid #ffffff">
<img data-imagetype="External" src="https://teella.com/TeellaEmailHeader.png" width="538" height="65" alt="Teella">
</td>
</tr>
<tr >
<td bgcolor="#FFFFFF" bordercolor="Red" >
<table width="470" border="0" align="center" cellpadding="0" cellspacing="0" style="padding-left:5px; padding-right:5px; padding-bottom:10px">
<tbody>
<tr bgcolor="#FFFFFF">
<td style="padding-top:32px"><span style="padding-top: 16px; padding-bottom: 16px; font-size: 24px; color: rgb(255, 0, 231); font-family:  Helvetica, sans-serif; font-weight: bold;">Hola, '.$accountName.':
</span><br>
</td>
</tr>
<tr>
<td style="padding-top:12px"><span style="font-size: 17px; color: black; font-family: Arial, Helvetica, sans-serif, serif, EmojiFont; font-weight: bold;">
<p>Este es el código de temporal de contraseña de Teella:</p>
</span></td>
</tr>
<tr>
<td>
<div align="center"><span  style="font-size: 50px; color: rgb(255, 0, 231); font-family: Helvetica; font-weight: bold;">'.$passTemp.'
</span></div>
</td>
</tr>
<td style="padding-top:50px"><span style="font-size: 15px; color: grey; font-family: Arial font-weight: bold;">
<p>El código expira en 2 dias.</p>

</table>
</td>
</tr>
</tbody>
</table>
 ';



    //dirección del remitente
 
    $headers = "From: New User Teella < no-response@teella.com >\r\n";
    $headers .= "MIME-Version: 1.0\r\n";
    $headers .= "Content-Type: text/html\r\n";
    
    // $headers = 'From: noreply @ teella . com';

    // Enviamos el mensaje al destinatario email
    $to_email = $email;
    //Titulo
    $subject = 'Usuario Nuevo Teella';
    //$message = "Hemos recibido una solicitud de cambio de contraseña \n si NO realizaste esta solicitud ignora este mensaje \n de lo contrario ingresa a nuestra plataforma con el siguiente codigo: $passTemp";

    $bool = mail(utf8_decode($to_email),utf8_decode($subject),$body,$headers);


     return $bool;
  }

  public function setExpiredDatePasswordTemp()
  {
    date_default_timezone_set('America/Bogota');
    $currDate = date('Y-m-d H:i:s');
    // $exptime = date('Y-m-d H:i:s', $exptime);
    return strtotime($currDate . "+ 15minutes");
  }

  public function setNewUserExpiredDatePasswordTemp()
  {
    date_default_timezone_set('America/Bogota');
    $currDate = date('Y-m-d H:i:s');
    // $exptime = date('Y-m-d H:i:s', $exptime);
    return strtotime($currDate . "+ 2Days");
  }



  public function validatePasswordExpireTime($date)
  {
    date_default_timezone_set('America/Bogota');
    $currDate = date('Y-m-d H:i:s');
    

    if($date<strtotime($currDate))
    {
      return false;
    }
    else
    {
      return true;
    }
  }

  public function forgetPassword($email/*$phone,*/)
  {
    $isHavedEmail = $this->checkEmailUser($email);
    if($isHavedEmail=='No registry found')
    {
      $endJSON['status'] = 'error';
        return $endJSON;
    }

    $passTemp     = $this->generatePasswordTemp();
    $accountName = $isHavedEmail['accountName'];
    if ($this->sendMailResetPassword($accountName = $accountName, $email = $email, $passTemp = $passTemp)) {
      $exptime    = $this->setExpiredDatePasswordTemp();
      $pwd_hashed = $this->generateHashPass($passTemp);
      $dataSQL['tempPasswordToken']      = $pwd_hashed;
      $dataSQL['tokenPasswordExpiredAt'] = $exptime;
      $dataSQL['userId']                 = $isHavedEmail['userId'];
      $dataSQL['_documentID']            = 'userId';

      $QueryCallback = $this->ExecuteAutoSQL(
        $mode    = 'UPDATE',
        $table   = self::table,
        $columns = self::tableColumns,
        $data    = $dataSQL
      );

      if ($QueryCallback) {
        $dataSQL['lastID'] = $isHavedEmail['userId'];
        $data = $this->ExecuteAutoSQL(
          $mode    = 'SELECT',
          $table   = 'viewUserAccount',
          $columns = self::tableColumns,
          $data    = $dataSQL
        );
        // $user_active = ($data['statusUserAccountId'] == 1);
        // if ($user_active) {
        $data = $this->cleanDataSensive($data);
        $endJSON["data"] = $data;
         $endJSON["status"] = "success";
        //   $paramsUpdated = $this->printArgsName($sucessParam);
        //   $sucessMsg .= "$paramsUpdated was updated.";


        // } else {
        //   throw new Exception('falied generate authorization', 400);
        // }
      }
      return $endJSON;
    }
  }

  public function updateLastConnectionDateUser(array $data)
  {
    $endJSON["status"] = "";
    $endJSON["data"] = "";
    try {
      if (isset($data['userId'])) {
        $userId = $data['userId'];
        $lastConnectionDateTime = parent::getTimestamp();
        $query = "UPDATE `User` SET lastConnectionDateTime = '$lastConnectionDateTime' WHERE userId = '$userId';";
        $QueryCallback = $this->ExecuteSql($query);
        if ($QueryCallback) {
           $endJSON["status"] = "success";
          $endJSON["data"] = $data;
        } else {
          throw new Exception("Error: SQL updateLastConnectionDateUser", 1);
        }
      } else {
        throw new Exception("Error: invalid parameters for User", 1);
      }
    } catch (Exception $e) {
      $endJSON['status'] = 'error';
      http_response_code(400);
      die($e->getMessage());
    }
    return $endJSON;
  }

  public function updateVerifiedEmail(array $data)
  {
    $endJSON["status"] = "";
    $endJSON["data"] = "";
    try {
      if (isset($data['userId'])) {
        $userId = $data['userId'];
        $isEmailVerified = 1;
        $query = "UPDATE `User` SET isEmailVerified = '$isEmailVerified' WHERE userId = '$userId';";
        $QueryCallback = $this->ExecuteSql($query);
        if ($QueryCallback) {
           $endJSON["status"] = "success";
          $endJSON["data"] = $data;
        } else {
          throw new Exception("Error: SQL updateVerifiedEmail", 1);
        }
      } else {
        throw new Exception("Error: invalid parameters for User", 1);
      }
    } catch (Exception $e) {
      $endJSON['status'] = 'error';
      http_response_code(400);
      die($e->getMessage());
    }
    return $endJSON;
  }

  public function updateIsOnline(array $data)
  {
    $endJSON["status"] = "";
    $endJSON["data"] = "";
    try {
      if (
        isset($data['userId']) &&
        isset($data['isOnline'])
      ) {
        $userId = $data['userId'];
        $isOnline = $data['isOnline'];
        $query = "UPDATE `User` SET isOnline = '$isOnline' WHERE userId = '$userId';";
        $QueryCallback = $this->ExecuteSql($query);
        if ($QueryCallback) {
          $endJSON["status"] = "Success updated isOnline";
          $endJSON["data"] = $data;
        } else {
          throw new Exception("Error: SQL updateIsOnline", 1);
        }
      } else {
        throw new Exception("Error: invalid parameters for User", 1);
      }
    } catch (Exception $e) {
      $endJSON['status'] = 'error';
      http_response_code(400);
      die($e->getMessage());
    }
    return $endJSON;
  }

  public function updateRol(array $data)
  {
    $endJSON["status"] = "";
    $endJSON["data"] = "";
    try {
      if (
        isset($data['userId']) &&
        isset($data['rolId'])
      ) {
        $userId = $data['userId'];
        $rolId = $data['rolId'];
        $query = "UPDATE `User` SET Rol_rolId = '$rolId' WHERE userId = '$userId';";
        $QueryCallback = $this->ExecuteSql($query);
        if ($QueryCallback) {
           $endJSON["status"] = "success";
          $endJSON["data"] = $data;
        } else {
          throw new Exception("Error: SQL updateRol", 1);
        }
      } else {
        throw new Exception("Error: invalid parameters for User", 1);
      }
    } catch (Exception $e) {
      $endJSON['status'] = 'error';
      http_response_code(400);
      die($e->getMessage());
    }
    return $endJSON;
  }

  public function updatePhotoURL(array $data)
  {
    $endJSON["status"] = "";
    $endJSON["data"] = "";
    try {
      if (
        isset($data['userId']) &&
        isset($data['photoURL'])
      ) {
        $userId = $data['userId'];
        $photoURL = $data['photoURL'];
        $query = "UPDATE `User` SET photoURL = '$photoURL' WHERE userId = '$userId';";
        $QueryCallback = $this->ExecuteSql($query);
        if ($QueryCallback) {
           $endJSON["status"] = "success";
          $endJSON["data"] = $data;
        } else {
          throw new Exception("Error: SQL updatePhotoURL", 1);
        }
      } else {
        throw new Exception("Error: invalid parameters for User", 1);
      }
    } catch (Exception $e) {
      $endJSON['status'] = 'error';
      http_response_code(400);
      die($e->getMessage());
    }
    return $endJSON;
  }

  public function updateStatusUser(array $data)
  {
    $endJSON["status"] = "";
    $endJSON["data"] = "";
    try {
      if (
        isset($data['userId']) &&
        isset($data['statusId'])
      ) {
        $userId = $data['userId'];
        $statusId = $data['statusId'];
        $query = "UPDATE `User` SET Status_statusId = '$statusId' WHERE userId = '$userId';";
        $QueryCallback = $this->ExecuteSql($query);
        if ($QueryCallback) {
           $endJSON["status"] = "success";
          $endJSON["data"] = $data;
        } else {
          throw new Exception("Error: SQL updatePhotoURL", 1);
        }
      } else {
        throw new Exception("Error: invalid parameters for User", 1);
      }
    } catch (Exception $e) {
      $endJSON['status'] = 'error';
      http_response_code(400);
      die($e->getMessage());
    }
    return $endJSON;
  }

  public function updateTokenFacebook(array $data)
  {
    $endJSON["status"] = "";
    $endJSON["data"] = "";
    try {
      if (
        isset($data['userId']) &&
        isset($data['tokenFacebook'])
      ) {
        $userId = $data['userId'];
        $tokenFacebook = $data['tokenFacebook'];
        $query = "UPDATE `User` SET tokenFacebook = '$tokenFacebook' WHERE userId = '$userId';";
        $QueryCallback = $this->ExecuteSql($query);
        if ($QueryCallback) {
           $endJSON["status"] = "success";
          $endJSON["data"] = $data;
        } else {
          throw new Exception("Error: SQL updatePhotoURL", 1);
        }
      } else {
        throw new Exception("Error: invalid parameters for User", 1);
      }
    } catch (Exception $e) {
      $endJSON['status'] = 'error';
      http_response_code(400);
      die($e->getMessage());
    }
    return $endJSON;
  }

  public function updateTokenGoogle(array $data)
  {
    $endJSON["status"] = "";
    $endJSON["data"] = "";
    try {
      if (
        isset($data['userId']) &&
        isset($data['tokenGoogle'])
      ) {
        $userId = $data['userId'];
        $token = $data['tokenGoogle'];
        $query = "UPDATE `User` SET token = '$token' WHERE userId = '$userId';";
        $QueryCallback = $this->ExecuteSql($query);
        if ($QueryCallback) {
           $endJSON["status"] = "success";
          $endJSON["data"] = $data;
        } else {
          throw new Exception("Error: SQL updatePhotoURL", 1);
        }
      } else {
        throw new Exception("Error: invalid parameters for User", 1);
      }
    } catch (Exception $e) {
      $endJSON['status'] = 'error';
      http_response_code(400);
      die($e->getMessage());
    }
    return $endJSON;
  }

  public function deleteUser(array $data)
  {
    $data['statusId'] = 2;
    return $this->updateStatusUser($data);
  }
}
