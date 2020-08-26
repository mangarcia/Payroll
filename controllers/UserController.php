<?php

use Rakit\Validation\Validator;


class UserController extends Controller
{
  private $_model;



  public function __construct()
  {
    parent::__construct();
    $this->_model = $this->loadModel('User');
    $this->_modelRol = $this->loadModel('Rol');
  }

  public function index()
  {
    $session = Session::get("TellaConnected");

    if($session)
    {

      $this->_view->title_ = "Usuarios";
        $this->_view->renderizar('users', 'users');
  
	 }
    else { 

      $this->redireccionar();

       }
  
  }

  
  public function getAllRoles()
  {
    $rolData = $this->_modelRol->getRolByUserRoleId(Session::get('RoleId'));
    parent::json_jwt_response($rolData, 200);
  }

  public function registerUser()
  {
    $validator = new Validator();
    if ('POST' === $_SERVER['REQUEST_METHOD']) {
      // make it
      $validation = $validator->make($_POST, [
        'accountName' => 'required|min:3',
        'email' => 'required|email',
        'password' => 'required|min:6',
        'cellphone' => 'required|min:10',
        'rolName' => 'required',
      ]);

      // then validate
      $validation->validate();

      if ($validation->fails()) {
        // handling errors
        $errors = $validation->errors();
        // echo "<pre>";
        echo json_encode($errors->firstOfAll());
        // echo "</pre>";
        exit;
      }
      // validation passes
      // echo "Success!";
      $data = $_POST;
      $rolData = $this->_modelRol->getAllRol();
      foreach ($rolData['data'] as $value) {
        if ($value['name'] == $data['rolName']) {
          $data['Rol_rolId'] = $value['rolId'];

          break;
        }
      }
      $data['Status_statusId'] = 1;
      $responseDB = $this->_model->registerNewUser($data);
      parent::json_jwt_response($responseDB, 200);
    } else {
      $endJSON['status'] = 'error';
      $endJSON['data']['title'] = '';
      $endJSON['data']['message'] = 'No exist GET';
      parent::json_jwt_response($endJSON, 400);
    }
  }


public function registerWebUser()
  { 
    $validator = new Validator();
    if ('POST' === $_SERVER['REQUEST_METHOD']) {
      $data = $_POST;
      if(isset($_POST["idSystemUser"]) && $_POST["idSystemUser"]!="")
      {

          $responseDB = $this->_model->updateWebUser($data);
      }
      else
      {

        $responseDB = $this->_model->registerNewWebUser($data);
      }
      
     
      parent::json_jwt_response($responseDB, 200);
    } else {
      $endJSON['status'] = 'error';
      $endJSON['data']['title'] = '';
      $endJSON['data']['message'] = 'No exist GET';
      parent::json_jwt_response($endJSON, 400);
    }
  }


  public function loginUser()
  {
      
    try
    {
      if ('POST' === $_SERVER['REQUEST_METHOD'])
      {
        $responseDB = $this->_model->loginUser($_POST);
      }
      else
      {
        throw new Exception('No exist GET', 400);
      }
    }
    catch (\Throwable $th)
    {
      echo "response db";
      $endJSON = parent::printError($th);
    }
    finally
    {
      $endJSON = $responseDB ?? $endJSON;
      parent::json_jwt_response($endJSON);
    }
  }

  
   public function loginUserWeb()
  {
    try
    {
      if ('POST' === $_SERVER['REQUEST_METHOD'])
      {
        $responseDB = $this->_model->loginUser($_POST);
      }
      else
      {
        throw new Exception('No exist GET', 400);
      }
    }
    catch (\Throwable $th)
    {
      echo "response db";
      $endJSON = parent::printError($th);
    }
    finally
    {
      $endJSON = $responseDB ?? $endJSON;
      parent::json_jwt_response($endJSON);
      if(isset( $endJSON['jwt']))
      {
         
         $tempJwt= "Bearer ". $endJSON['jwt'];
         Session::set('jwt', $tempJwt);

      }
     
    }
  }


  public function signOutUser()
  {
    if ('POST' === $_SERVER['REQUEST_METHOD']) {
      $authUser = parent::verfityJwt();
      if ($authUser) {
        $payloadJwt = parent::getDataJwt();
        $data['userId'] = $payloadJwt->aud;
        $responseDB = $this->_model->signOutUser($data);
        parent::json_jwt_response($responseDB, 200);
      }
    } else {
      $endJSON['status'] = 'error';
      $endJSON['data']['title'] = '';
      $endJSON['data']['message'] = 'No exist GET';
      parent::json_jwt_response($endJSON, 400);
    }
  }

  public function testOut()
  {
    if ('POST' === $_SERVER['REQUEST_METHOD']) {
      $authUser = parent::getSesionDataJwt();
      if ($authUser) {
        $payloadJwt = parent::getSesionDataJwt();
        $data['userId'] = $payloadJwt->aud;
        $responseDB = $this->_model->signOutUser($data);
        parent::json_jwt_response($responseDB, 200);
      }
    } else {
      $endJSON['status'] = 'error';
      $endJSON['data']['title'] = '';
      $endJSON['data']['message'] = 'No exist GET';
      parent::json_jwt_response($endJSON, 400);
    }

  }

public function signOutWebUser()
  {
   
    Session::destroy("jwt");
    Session::destroy("RoleId");
    Session::destroy("TellaConnected");
   
    $endJSON['status'] = 'success';
    $endJSON['message'] = 'Signed Out Done!';

    echo json_encode($endJSON);
  }



  /**
   * Login with provider Facebook or Google.
   */
  public function loginProviderUser()
  {
    if ('POST' === $_SERVER['REQUEST_METHOD']) {
      $data = $_POST;
      $rolData = $this->_modelRol->getAllRol();
      foreach ($rolData['data'] as $value) {
        if ($value['name'] == $data['rolName']) {
          $data['Rol_rolId'] = $value['rolId'];

          break;
        }
      }

      $responseDB = $this->_model->loginProviderUser($data);
      parent::json_jwt_response($responseDB, 200);
    } else {
      $endJSON['status'] = 'error';
      $endJSON['data']['title'] = '';
      $endJSON['data']['message'] = 'No exist GET';
      parent::json_jwt_response($endJSON, 400);
    }
  }

  public function getNameUser()
  {
    $responseDB = $this->_model->loginUser($_POST);
    parent::json_jwt_response($responseDB, 200);
  }

  public function validateJwt()
  {
    // parent::json_jwt_response(parent::verfityJwt(), 200);
    $data = parent::getDataJwt();
    echo json_encode($data);
    // parent::json_response($data, 200);
  }

  public function testJwt()
  {
    $faker = Faker\Factory::create();

    for ($i = 0; $i < 10; ++$i) {
      parent::json_jwt_response($faker->name, 200);
      // echo $faker->name, "\n";
    }
    // parent::verfityJwt();
    // parent::json_jwt_response($responseDB, 200);
  }

  public function getAllUser()
  {
    $responseDB = $this->_model->getAllUser();
         parent::json_response($responseDB, 200);
    /* if(Session::get("RoleId")==8 || Session::get("RoleId")==1)
      {
         $responseDB = $this->_model->getAllUser();
         parent::json_response($responseDB, 200);
      }
      else
      {
      //    $endJSON["status"]="Error";
       //   $endJSON["message"]="invalid User Token Authentication";
        //  echo json_encode($endJSON);
      }*/
   
  }

  public function getAllUserStatus()
  {
    $responseDB = $this->_model->getAllUserStatus();
         parent::json_response($responseDB, 200);
    /* if(Session::get("RoleId")==8 || Session::get("RoleId")==1)
      {
         $responseDB = $this->_model->getAllUser();
         parent::json_response($responseDB, 200);
      }
      else
      {
      //    $endJSON["status"]="Error";
       //   $endJSON["message"]="invalid User Token Authentication";
        //  echo json_encode($endJSON);
      }*/
   
  }
  public function getAllUserRol()
  {
    $responseDB = $this->_model->getAllUserRol();
         parent::json_response($responseDB, 200);
    /* if(Session::get("RoleId")==8 || Session::get("RoleId")==1)
      {
         $responseDB = $this->_model->getAllUser();
         parent::json_response($responseDB, 200);
      }
      else
      {
      //    $endJSON["status"]="Error";
       //   $endJSON["message"]="invalid User Token Authentication";
        //  echo json_encode($endJSON);
      }*/
   
  }

  public function getUser()
  {
    if ('GET' === $_SERVER['REQUEST_METHOD']) {
      $authUser = parent::verfityJwt();
      if ($authUser) {
        // $data = json_encode(parent::getDataJwt());
        echo json_encode(parent::getDataJwt());
        // $responseDB = $this->_model->getUser($_POST);
        // parent::json_response($responseDB, 200);
      }
    } else {
      $endJSON['status'] = 'error';
      $endJSON['data']['title'] = '';
      $endJSON['data']['message'] = 'No exist POST';
      parent::json_jwt_response($endJSON, 400);
    }
  }

  public function getUserData()
  {

    try {
      if ('GET' === $_SERVER['REQUEST_METHOD']) {
        $authUser = parent::verfityJwt();
        var_dump($$authUser);
        die();

        if ($authUser) {
          $payloadJwt = parent::getDataJwt();
          $data['userId'] = $payloadJwt->aud;
          $responseDB = $this->_model->getUserData($data);
        }
      } else {
        throw new Exception('No exist POST', 400);
      }
    } catch (\Throwable $th) {
      $endJSON = parent::printError($th);
    } finally {
      $endJSON = $responseDB ?? $endJSON;
      parent::json_jwt_response($endJSON);
    }
  }


public function updatePasswordUser()
  {
    try {
      if ('POST' === $_SERVER['REQUEST_METHOD']) {
        $authUser = parent::verfitySessionJwt();
        if ($authUser) {
          $payloadJwt = parent::getSesionDataJwt();
          $data = $_POST;
          $data['userId'] = $payloadJwt->aud;
          $responseDB = $this->_model->updatePasswordUser($data);
        }
      } else {
        throw new Exception('No exist GET', 400);
      }
    } catch (\Throwable $th) {
      $endJSON = parent::printError($th);
    } finally {
      $endJSON = $responseDB ?? $endJSON;
      parent::json_jwt_response($endJSON);
    }
  }


  public function updateUser()
  {
    try {
      parse_str(file_get_contents('php://input'), $_PUT);
      if ('PUT' === $_SERVER['REQUEST_METHOD']) {
        $authUser = parent::verfityJwt();
        if ($authUser) {
          $payloadJwt = parent::getDataJwt();
          $data = $_PUT;
          $data['userId'] = $payloadJwt->aud;
          $responseDB = $this->_model->updateUser($data);
        }
      } else {
        throw new Exception('No exist GET', 400);
      }
    } catch (\Throwable $th) {
      $endJSON = parent::printError($th);
    } finally {
      $endJSON = $responseDB ?? $endJSON;
      parent::json_jwt_response($endJSON);
    }
  }


   public function updateWebUser()
  {
    try {
       if ('POST' === $_SERVER['REQUEST_METHOD']) {
        $authUser = parent::verfitySessionJwt();
        if ($authUser) {
          $payloadJwt = parent::getSesionDataJwt();
          $data = $_POST;
          var_dump($data);
          $responseDB = $this->_model->updateWebUser($data);
        }
      } else {
        throw new Exception('No exist GET', 400);
      }
    } catch (\Throwable $th) {
      $endJSON = parent::printError($th);
    } finally {
      $endJSON = $responseDB ?? $endJSON;
      parent::json_jwt_response($endJSON);
    }
  }

  public function recoveryPassword()
  {
    try {
      if ('POST' === $_SERVER['REQUEST_METHOD']) {
        $email = $_POST['email'];
        //$phone=$_POST['phone'];
        $responseDB = $this->_model->forgetPassword($email/*,$phone*/);
      } else {
        throw new Exception('No exist GET', 400);
      }
    } catch (\Throwable $th) {
      $endJSON = parent::printError($th);
    } finally {
      $endJSON = $responseDB ?? $endJSON;
      parent::json_jwt_response($endJSON);
    }
  }

  public function updateLastConnectionDateUser()
  {
    parse_str(file_get_contents('php://input'), $_PUT);
    if ('PUT' === $_SERVER['REQUEST_METHOD']) {
      $responseDB = $this->_model->updateLastConnectionDateUser($_PUT);
      parent::json_response($responseDB, 200);
    }
  }

  public function updateVerifiedEmail()
  {
    parse_str(file_get_contents('php://input'), $_PUT);
    if ('PUT' === $_SERVER['REQUEST_METHOD']) {
      $responseDB = $this->_model->updateVerifiedEmail($_PUT);
      parent::json_response($responseDB, 200);
    }
  }

  public function deleteUser()
  {
    parse_str(file_get_contents('php://input'), $_DELETE);
    if ('DELETE' == $_SERVER['REQUEST_METHOD']) {
      $responseDB = $this->_model->deleteUser($_DELETE);
      parent::json_response($responseDB, 204);
    }
  }
}
