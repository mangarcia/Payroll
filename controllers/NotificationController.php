<?php

class NotificationController extends Controller
{
  private $_model;

  public function __construct()
  {
    parent::__construct();
    $this->_model = $this->loadModel('Notification');
  }

  public function index()
  { }


  public function addNotificationFaker()
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
          $data['title'] = json_encode(
            array(
              'es' => $faker->sentence($nbWords = 3, $variableNbWords = true),
              'en' => $faker->sentence($nbWords = 3, $variableNbWords = true)
            )
          );
          $data['subtitle'] = json_encode(
            array(
              'es' => $faker->sentence($nbWords = 4, $variableNbWords = true),
              'en' => $faker->sentence($nbWords = 4, $variableNbWords = true)
            )
          );
          $data['message'] = json_encode(
            array(
              'es' => $faker->sentence($nbWords = 9, $variableNbWords = true),
              'en' => $faker->sentence($nbWords = 9, $variableNbWords = true)
            )
          );
          $data['imagePath'] = $faker->imageUrl($width = 640, $height = 480);
          $data['lanuchURL'] = $urlFaker;
          $data['additionalData'] = "{\"url\":\"$urlFaker\"}";
          $data['actionsButtons'] = "[{\"id\": \"id1\", \"text\": \"button1\", \"icon\": \"ic_menu_share\"}, {\"id\": \"id2\", \"text\": \"button2\", \"icon\": \"ic_menu_send\"}]";
          $data['seen'] = $faker->boolean();
          $data['UUIDNotification'] = $faker->uuid();
          $data['typeNotification'] = $faker->numberBetween($min = 1, $max = 4);

          $unixTimestamp = '1567375343'; // = Sunday, 1 September 2019 22:02:23
          $data['sendedDateTime'] =
            Carbon\Carbon::createFromTimestamp($unixTimestamp, 'America/Bogota')
            ->addHours($faker->numberBetween($min = 1, $max = 2400))
            ->timestamp;

          $responseDB = $this->_model->sendedNewNotification($data);
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

  public function sendedNewNotification()
  {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
      $authUser = parent::verfityJwt();
      if ($authUser) {
        $payloadJwt = parent::getDataJwt();
        $data = $_POST;
        $data['userId'] = $payloadJwt->aud;
        $responseDB = $this->_model->sendedNewNotification($data);
        parent::json_jwt_response($responseDB, 200);
      }
    } else {
      $endJSON['status'] = 'error';
      $endJSON["data"]['title'] = "";
      $endJSON["data"]['message'] = "No exist GET";
      parent::json_jwt_response($endJSON, 400);
    }
  }

  public function getAllNotification()
  {
    if ($_SERVER['REQUEST_METHOD'] === 'GET') {
      $authUser = parent::verfityJwt();
      if ($authUser) {
        $payloadJwt = parent::getDataJwt();
        $data = $_GET;
        $data['userId'] = $payloadJwt->aud;
        $responseDB = $this->_model->getAllNotification($data);
        parent::json_response($responseDB, 200);
      }
    } else {
      $endJSON['status'] = 'error';
      $endJSON["data"]['title'] = "";
      $endJSON["data"]['message'] = "No exist POST";
      parent::json_jwt_response($endJSON, 400);
    }
  }

  public function getAllNotificationByUser()
  {
    if ($_SERVER['REQUEST_METHOD'] === 'GET') {
      $authUser = parent::verfityJwt();
      if ($authUser) {
        $NO_SEEN = 0;
        $DAYS_BEFORE_LIMIT = 7;
        $payloadJwt = parent::getDataJwt();
        $data = $_GET;
        $data['userId'] = $payloadJwt->aud;
        $data['seen'] = $NO_SEEN;
        $data['dateTimeStarted'] = Carbon\Carbon::now('America/Bogota')->subDays($DAYS_BEFORE_LIMIT)->timestamp;
        $responseDB = $this->_model->getAllNotificationByUser($data);
        parent::json_response($responseDB, 200);
      }
    } else {
      $endJSON['status'] = 'error';
      $endJSON["data"]['title'] = "";
      $endJSON["data"]['message'] = "No exist POST";
      parent::json_jwt_response($endJSON, 400);
    }
  }


  public function updatedNotification()
  {
    parse_str(file_get_contents('php://input'), $_PUT);
    if ($_SERVER["REQUEST_METHOD"] === "PUT") {
      $authUser = parent::verfityJwt();
      if ($authUser) {
        $payloadJwt = parent::getDataJwt();
        $data = $_PUT;
        $data['userId'] = $payloadJwt->aud;
        $responseDB = $this->_model->updatedNotification($data);
        parent::json_response($responseDB, 200);
      }
    } else {
      $endJSON['status'] = 'error';
      $endJSON["data"]['title'] = "";
      $endJSON["data"]['message'] = "No exist GET";
      parent::json_jwt_response($endJSON, 400);
    }
  }

  public function getNotification()
  {
    $responseDB = $this->_model->getNotification($_POST);
    parent::json_response($responseDB, 200);
  }

  public function deleteNotificationLocal()
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
        echo "Error deleteNotificationLocal: $th";
      }
      parent::json_response($result, 200);
    }
  }
}
