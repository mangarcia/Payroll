<?php

class FamilyController extends Controller
{
  private $_model;


  public function addFamilyFaker()
  {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
      $authUser = parent::verfityJwt();
      if ($authUser) {
        $jwt = json_encode(parent::getDataJwt());
        $jwt = json_decode($jwt);
        $data = $_POST;
        $data['userId'] = $jwt->aud;


        $faker = Faker\Factory::create();
        $unixTimestamp = '1603308856'; // = 2016-04-19T12:00:00+00:00 in ISO 8601


        for ($i = 0; $i < 20; $i++) {
          // array_push($family, $faker->name);
          $data['userId']                         = 1;
          $data['basicDataBirthDate']             = $faker->date('Y-m-d', $unixTimestamp);
          $data['basicDataDisability']            = $faker->word;
          $data['basicDataDocNumber']             = $faker->numberBetween($min = 100000000, $max = 1999999999);
          $data['basicDataFirstName']             = $faker->firstName . ' ' . $faker->firstName;
          $data['basicDataHeight']                = $faker->numberBetween($min = 140, $max = 250);
          $data['basicDataLastName']              = $faker->lastName . ' ' . $faker->lastName;
          $data['basicDataWeight']                = $faker->numberBetween($min = 40, $max = 200);
          $data['personalDataCellphone']          = $faker->numberBetween($min = 573000000000, $max = 573999999999);
          $data['emergencyContactCellphone']      = $faker->numberBetween($min = 573000000000, $max = 573999999999);
          $data['emergencyContactNamePerson']     = $faker->name;
          $data['userEpsName']                    = $faker->company;
          $data['userObservations']               = $faker->sentence($nbWords = 6, $variableNbWords = true);
          $data['statusUserId']                   = 1;
          $rolFamily                              = 7;
          $data['rolId']                          = $rolFamily;
          $data['mobilityId']                     = $faker->numberBetween($min = 1, $max = 3);
          $data['avatarId']                       = $faker->numberBetween($min = 1, $max = 4);
          $data['sexId']                          = $faker->numberBetween($min = 1, $max = 2);
          $data['docTypeId']                      = $faker->numberBetween($min = 1, $max = 3);
          $data['accountRelationshipId']          = $faker->numberBetween($min = 1, $max = 16);
          $data['emergencyContactRelationshipId'] = $faker->numberBetween($min = 1, $max = 16);
          $responseDB                             = $this->_model->addNewFamily($data);
        }
        parent::json_jwt_response($responseDB, 200);
      }
    } else {
      $endJSON['status'] = 'error';
      $endJSON["data"]['title'] = "";
      $endJSON["data"]['message'] = "No exist GET";
      parent::json_jwt_response($endJSON, 400);
    }
  }

  public function __construct()
  {
    parent::__construct();
    $this->_model = $this->loadModel('Family');
  }

  public function index()
  { }

  public function createMemberFamily()
  {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
      $authUser = parent::verfityJwt();
      if ($authUser) {
        $jwt = json_encode(parent::getDataJwt());
        $jwt = json_decode($jwt);
        $data = $_POST;
        $data['userId'] = $jwt->aud;
        $responseDB = $this->_model->addNewFamily($data);
        parent::json_jwt_response($responseDB, 200);
      }
    } else {
      $endJSON['status'] = 'error';
      $endJSON["data"]['title'] = "";
      $endJSON["data"]['message'] = "No exist GET";
      parent::json_jwt_response($endJSON, 400);
    }
  }

  public function getAllFamily()
  {
    $responseDB = $this->_model->getAllFamily();
    parent::json_response($responseDB, 200);
  }

  public function getFamilyByUser()
  {
    if ($_SERVER['REQUEST_METHOD'] === 'GET') {
      $authUser = parent::verfityJwt();
      if ($authUser) {
        $payloadJwt = parent::getDataJwt();
        $data['userId'] = $payloadJwt->aud;
        $responseDB = $this->_model->getFamilyByUser($data);
        parent::json_response($responseDB, 200);
      }
    } else {
      $endJSON['status'] = 'error';
      $endJSON["data"]['title'] = "";
      $endJSON["data"]['message'] = "No exist GET";
      parent::json_jwt_response($endJSON, 400);
    }
  }

  public function updateFamily()
  {
    parse_str(file_get_contents('php://input'), $_PUT);
    if ($_SERVER['REQUEST_METHOD'] === 'PUT') {
      $authUser = parent::verfityJwt();
      if ($authUser) {
        $responseDB = $this->_model->updateFamily($_PUT);
        parent::json_response($responseDB, 200);
      }
    } else {
      $endJSON['status'] = 'error';
      $endJSON["data"]['title'] = "";
      $endJSON["data"]['message'] = "No exist GET/POST";
      parent::json_jwt_response($endJSON, 400);
    }
  }

  public function deleteFamily()
  {
    parse_str(file_get_contents('php://input'), $_DELETE);
    if ($_SERVER["REQUEST_METHOD"] == "DELETE") {
      $responseDB = $this->_model->deleteFamily($_DELETE);
      parent::json_response($responseDB, 204);
    }
  }
}
