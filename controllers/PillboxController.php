<?php

// use \faker;

class PillboxController extends Controller
{
  private $_model;

  public function __construct()
  {
    parent::__construct();
    $this->_model = $this->loadModel('Pillbox');
  }

  public function index()
  { }

  public function addPillboxFaker()
  {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
      $authUser = parent::verfityJwt();
      if ($authUser) {
        $faker = Faker\Factory::create();
        for ($i = 0; $i < 20; $i++) {
          $data['name'] = $faker->domainWord;
          $data['quantity'] = $faker->numberBetween($min = 1, $max = 100);
          $data['frequency'] = $faker->randomElement($array = array('6', '8', '12', '24'));
          $data['typePillbox'] = $faker->randomElement($array = array('a', 'b', 'c', 'd'));
          $data['familyId'] = $faker->numberBetween($min = 1, $max = 130);
          $data['startedAt'] = $faker->time($format = 'H:i:s', $max = 'now');
          $responseDB = $this->_model->addNewPillbox($data);
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

  public function addNewPillbox()
  {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
      $responseDB = $this->_model->addNewPillbox($_POST);
      parent::json_jwt_response($responseDB, 200);
    } else {
      $endJSON['status'] = 'error';
      $endJSON["data"]['title'] = "";
      $endJSON["data"]['message'] = "No exist GET";
      parent::json_jwt_response($endJSON, 400);
    }
  }

  public function getAllPillbox()
  {
    $responseDB = $this->_model->getAllPillbox();
    parent::json_response($responseDB, 200);
  }

  public function getPillboxByMember()
  {
    if ($_SERVER['REQUEST_METHOD'] === 'GET') {
      $authUser = parent::verfityJwt();
      if ($authUser) {
        $payloadJwt = parent::getDataJwt();
        $data['userId'] = $payloadJwt->aud;
        $responseDB = $this->_model->getPillboxByMember($data);
        parent::json_response($responseDB, 200);
      }
    } else {
      $endJSON['status'] = 'error';
      $endJSON["data"]['title'] = "";
      $endJSON["data"]['message'] = "No exist GET";
      parent::json_jwt_response($endJSON, 400);
    }
  }

  public function updatePillbox()
  {
    parse_str(file_get_contents('php://input'), $_PUT);
    if ($_SERVER["REQUEST_METHOD"] === "PUT") {
      $authUser = parent::verfityJwt();
      if ($authUser) {
        $responseDB = $this->_model->updatePillbox($_PUT);
        parent::json_response($responseDB, 200);
      }
    } else {
      $endJSON['status'] = 'error';
      $endJSON["data"]['title'] = "";
      $endJSON["data"]['message'] = "No exist GET";
      parent::json_jwt_response($endJSON, 400);
    }
  }

  public function deletePillbox()
  {
    parse_str(file_get_contents('php://input'), $_PUT); // TODO FIX NEXT VERSION BEACUSE NO WORKING IN UNITY
    if ($_SERVER["REQUEST_METHOD"] == "PUT") {
      $authUser = parent::verfityJwt();
      if ($authUser) {
        $responseDB = $this->_model->deletePillbox($_PUT);
        parent::json_response($responseDB, 204);
      }
    } else {
      $endJSON['status'] = 'error';
      $endJSON["data"]['title'] = "";
      $endJSON["data"]['message'] = "No exist GET";
      parent::json_jwt_response($endJSON, 400);
    }
  }
}
