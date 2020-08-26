<?php

class TransactionPaymentGatewayController extends Controller
{
  private $_model;

  public function __construct()
  {
    parent::__construct();
    $this->_model = $this->loadModel('TransactionPaymentGateway');
  }

  public function index()
  { }


  public function addTransactionPaymentGatewayFaker()
  {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
      $authUser = parent::verfityJwt();
      if ($authUser) {
        $jwt = json_encode(parent::getDataJwt());
        $jwt = json_decode($jwt);
        $data = $_POST;
        $data['userId'] = $jwt->aud;

        $faker = Faker\Factory::create('es_ES');
        for ($i = 0; $i < 100; $i++) {
          $urlFaker = $faker->url;
          $data['title'] = $faker->sentence($nbWords = 3, $variableNbWords = true);
          $data['subtitle'] = $faker->sentence($nbWords = 4, $variableNbWords = true);
          $data['message'] = $faker->sentence($nbWords = 9, $variableNbWords = true);
          $data['imagePath'] = $faker->imageUrl($width = 640, $height = 480);
          $data['lanuchURL'] = $urlFaker;
          $data['additionalData'] = "{\"url\":\"$urlFaker\"}";
          $data['actionsButtons'] = "[{\"id\": \"id1\", \"text\": \"button1\", \"icon\": \"ic_menu_share\"}, {\"id\": \"id2\", \"text\": \"button2\", \"icon\": \"ic_menu_send\"}]";
          $data['seen'] = $faker->boolean();
          $data['UUIDTransactionPaymentGateway'] = $faker->uuid();

          $unixTimestamp = '1567375343'; // = Sunday, 1 September 2019 22:02:23
          $data['sendedDateTime'] =
            Carbon\Carbon::createFromTimestamp($unixTimestamp, 'America/Bogota')
            ->addHours($faker->numberBetween($min = 1, $max = 2400))
            ->timestamp;

          $responseDB = $this->_model->sendedNewTransactionPaymentGateway($data);
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

  public function sendedNewTransactionPaymentGateway()
  {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
      $authUser = parent::verfityJwt();
      if ($authUser) {
        $payloadJwt = parent::getDataJwt();
        $data = $_POST;
        $data['userId'] = $payloadJwt->aud;
        $responseDB = $this->_model->sendedNewTransactionPaymentGateway($data);
        parent::json_jwt_response($responseDB, 200);
      }
    } else {
      $endJSON['status'] = 'error';
      $endJSON["data"]['message'] = "No exist GET";
      // $endJSON["data"]['title'] = "";
      parent::json_jwt_response($endJSON, 400);
    }
  }

  public function getAllTransactionPaymentGateway()
  {
    if ($_SERVER['REQUEST_METHOD'] === 'GET') {
      $authUser = parent::verfityJwt();
      if ($authUser) {
        $payloadJwt = parent::getDataJwt();
        $data = $_GET;
        $data['userId'] = $payloadJwt->aud;
        $responseDB = $this->_model->getAllTransactionPaymentGateway($data);
        parent::json_response($responseDB, 200);
      }
    } else {
      $endJSON['status'] = 'error';
      $endJSON["data"]['title'] = "";
      $endJSON["data"]['message'] = "No exist POST";
      parent::json_jwt_response($endJSON, 400);
    }
  }


  public function updatedTransactionPaymentGateway()
  {
    parse_str(file_get_contents('php://input'), $_PUT);
    if ($_SERVER["REQUEST_METHOD"] === "PUT") {
      $authUser = parent::verfityJwt();
      if ($authUser) {
        $payloadJwt = parent::getDataJwt();
        $data = $_PUT;
        $data['userId'] = $payloadJwt->aud;
        $responseDB = $this->_model->updatedTransactionPaymentGateway($data);
        parent::json_response($responseDB, 200);
      }
    } else {
      $endJSON["status"] = "Error";
      $endJSON["data"]['title'] = "";
      $endJSON["data"]['message'] = "No exist GET";
      parent::json_jwt_response($endJSON, 400);
    }
  }

  public function getTransactionPaymentGateway()
  {
    $responseDB = $this->_model->getTransactionPaymentGateway($_POST);
    parent::json_response($responseDB, 200);
  }

  public function deleteTransactionPaymentGatewayLocal()
  {
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
      $notificationId = $_POST['notificationId'];
      $appId = $_POST['appId'];
      $urlRequest =  "https://onesignal.com/api/v1/notifications/$notificationId?app_id=$appId";
      $header = parent::getAutorizationHeader();
      $opts = array(
        'http' =>
        array(
          'method'  => 'DELETE',
          'header'  => "Content-Type: application/x-www-form-urlencoded, Authorization: $header",
          )
        );

        $context = stream_context_create($opts);
      try {
        $result  = file_get_contents($urlRequest, false, $context);
        $result  = json_decode($result);
      } catch (\Throwable $th) {
        echo "Error deleteTransactionPaymentGatewayLocal: $th";
      }
      parent::json_response($result, 200);
    }
  }
}
