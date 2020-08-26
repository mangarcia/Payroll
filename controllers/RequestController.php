<?php

use \Calendarific\Calendarific;
use \Firebase\JWT\JWT;

class RequestController extends Controller
{
  private $clientHttp;
  private $_model;
  private $_modelDevice;
  private $_modelNotification;
  private $_modelTypeNotification;
  private $_modelStatusPayment;
  private $_modelTransactionPaymentGateway;
  private $_planModel;

  public function __construct()
  {
    $this->clientHttp = new GuzzleHttp\Client();
    parent::__construct();
    $this->_model = $this->loadModel('Request');
    $this->_modelDevice = $this->loadModel('Device');
    $this->_modelService = $this->loadModel('Service');
    $this->_modelNotification = $this->loadModel('Notification');
    $this->_modelStatusPayment = $this->loadModel('StatusPayment');
    $this->_modelStatusService = $this->loadModel('StatusService');
    $this->_modelTransactionPaymentGateway = $this->loadModel('TransactionPaymentGateway');
    $this->_modelTypeNotification = $this->loadModel('TypeNotification');
    $this->_planModel = $this->loadModel('Plan');
  }

  public function index()
  { }

  private function generateJwt(object $dataJwt)
  {
    $secret_key = "YOUR_SECRET_KEY";
    $issuer_claim = "api.teella.com"; // this can be the servername
    $audience_claim = $dataJwt->aud; // "THE_AUDIENCE";
    $issuedat_claim = time(); // issued at
    $notbefore_claim = $issuedat_claim + 10; //not before in seconds
    $expire_claim = $issuedat_claim + 315360000; // expire time in seconds
    $token = array(
      "ver"                     => 1.0,
      "iss"                     => $issuer_claim,
      "aud"                     => $audience_claim,
      "iat"                     => $issuedat_claim,
      "nbf"                     => $notbefore_claim,
      "exp"                     => $expire_claim,
      "sid"                     => $dataJwt->sid,
      "email"                   => $dataJwt->email,
      "cellphone"               => $dataJwt->cellphone,
      "email_verified"          => $dataJwt->email_verified,
      "picture"                 => $dataJwt->picture,
      "unique_name"             => $dataJwt->unique_name,
      "role"                    => $dataJwt->role,
      "statusUser"              => $dataJwt->statusUser,
      "agent"                   => $_SERVER['HTTP_USER_AGENT'],
      "serviceDateTimeStart"    => $dataJwt->serviceDateTimeStart,
      "serviceId"               => $dataJwt->serviceId,
      "typeServiceId"           => $dataJwt->typeServiceId,
      "familyMemberId"           => $dataJwt->familyMemberId,
      "serviceDetail"           => $dataJwt->serviceDetail,
      "serviceDateTimeEnd"      => $dataJwt->serviceDateTimeEnd,
      "serviceTotalAmount"      => $dataJwt->serviceTotalAmount,
      "serviceMeridian"         => $dataJwt->serviceMeridian,
      "userServiceId"           => $dataJwt->userServiceId,
      "userRequestObservations" => $dataJwt->userRequestObservations,
      "temp_address"            => $dataJwt->temp_address,
      "temp_addressInfo"        => $dataJwt->temp_addressInfo
    );

    http_response_code(200);

    $jwt = JWT::encode($token, $secret_key);


    $fmt = new NumberFormatter($locale = 'es_CO', NumberFormatter::CURRENCY);
    $fmt->setAttribute(NumberFormatter::FRACTION_DIGITS, 0);
    $price = $dataJwt->serviceTotalAmount;
    return array(
      "serviceTotalAmount" => $price,
      "message" => "Successful calculation price.",
      "jwt" => $jwt,
      "expireAt" => $expire_claim,
    );
  }

  private function getUUIDPlayer(string $userAccountId)
  {
    if (!isset($userAccountId)) {
      throw new Exception("Error required userAccountId", 1);
    } else {
      $data['userId'] = $userAccountId;
      $deviceUser = $this->_modelDevice->getDeviceUser($data);
    }

    if ($deviceUser["data"] == "No registry found") {
      throw new Exception("Not found devices UUID of this user, please add new device UUID", 400);
    }

    $playerUUID = array();
    foreach ($deviceUser['data'] as $value) {
      array_push($playerUUID, $value['UUID']);
    }

    return $playerUUID;
  }

  private function getHolidaysApi()
  {
    $key = '68aefdcdf26466128573442dfb6b0f91ee4dda8c';
    $country = 'CO';
    $year = 2019;
    $month = null;
    $day = null;
    $location = null;
    $types = ['national'];

    $dates = Calendarific::make(
      $key,
      $country,
      $year,
      $month,
      $day,
      $location,
      $types
    );

    return json_encode($dates['response']['holidays']);
  }

   public function calculateServiceValue()
  {
    try {
      if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $authUser = parent::verfityJwt();
        if ($authUser) {
          $jwt = json_encode(parent::getDataJwt());
          $jwt = json_decode($jwt);

          // $serviceName          = $_POST['serviceName'];

          $serviceDateTimeEnd      = $_POST['toDate'];
          $serviceDateTimeStart    = $_POST['fromDate'];
          $serviceMeridian         = $_POST['meridianValue'];
          $cityId                  = $_POST['cityId'];
          $companyId               = $_POST['companyId'];

          $userRequestObservations = $_POST['userRequestObservations'] ?? NULL;
          $temp_address            = $_POST['tempAdress'] ?? NULL;
          $temp_addressInfo        = $_POST['temp_addressInfo'] ?? NULL;

          $_currPlan   = $this->_planModel->calculatePrice($_POST);


          $jwt->serviceId               = $_currPlan["data"]["PlanId"];
          $jwt->serviceDetail           = $_currPlan["data"]["PlanName"];
          $jwt->serviceDateTimeStart    = $serviceDateTimeStart;
          $jwt->typeServiceId           = 1;
          $jwt->serviceDateTimeEnd      = $serviceDateTimeEnd;
          $jwt->serviceMeridian         = $serviceMeridian;
          $jwt->serviceTotalAmount      = $_currPlan["data"]["ServiceValue"];
          $jwt->userServiceId           = $jwt->userServiceId;
          $jwt->userRequestObservations = $userRequestObservations;
          $jwt->temp_address            = $temp_address;
          $jwt->temp_addressInfo        = $temp_addressInfo;
          $jwt->familyMemberId          = $_POST['familyMemberId'];

          $endJSON = $this->generateJwt($jwt);


        } else {
          throw new Exception("Invalid autentication", 400);
        }
      } else {
        throw new Exception("No exist GET", 400);
      }
    } catch (\Throwable $th) {
      $endJSON = $this->printError($th);
    } finally {
      parent::json_jwt_response($endJSON);
    }
  }

  private function roundNearestHundredUp($number)
  {
    return ceil($number / 100) * 100;
  }

  private function calculatePriceServiceSameDay($price, $durationService)
  {
    return round($durationService * ($price * 4) /* fraccions1Hr */);
  }

  private function validateIsDayHoliday(string $dateName, bool $dayHoliday): bool
  {
    return $dateName == 'Sun' || $dayHoliday;
  }


  private function checkIsDayHolidayAPI($year, $month, $day): bool
  {
    // CONDITION IS DAYS HOLIDAYS
    /*
      $holidaysCo = $this->getHolidaysApi();
      echo $holidaysCo;
    */

    sleep(1);
    $urlRequestIsPublicHoliday  =  "https://kayaposoft.com/enrico/json/v2.0/?action=isPublicHoliday&date=$day-$month-$year&country=COL";
    $res = $this->clientHttp->request('GET', $urlRequestIsPublicHoliday);

    try {
      if ($res->getStatusCode() == 200) {
        $dayHoliday = json_decode($res->getBody());
      } else if ($res->getStatusCode() ==  429) {
        throw new Exception('Please retry, beacuse Too Many Requests', 429);
      } else {
        throw new Exception('Out of work REST API. Please talk to Developer', 400);
      }
    } catch (\Throwable $th) {
      $endJSON = parent::printError($th);
      parent::json_jwt_response($endJSON);
    }
    return $dayHoliday->isPublicHoliday;
  }

  private function getServiceInfo(int $typeServiceId, bool $startIsHoliday, int $serviceDuration): array
  {
    $data = array(
      'typeServiceId' => $typeServiceId,
      'isDayHoliday' => $startIsHoliday,
      'durationService' => $serviceDuration
    );
    $dataJson = $this->_modelService->getServiceByCondition($data);
    return $dataJson["data"][0];
  }

  private function getStatusPayment(): array
  {
    $dataJson = $this->_modelStatusPayment->getAllStatusPayment();
    return $dataJson['data'];
  }

function generateRandomString($length = 10) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
}

  public function createRequest()
  {
    try {
      if ($_SERVER['REQUEST_METHOD'] === 'POST') {
       
        $authUser = parent::verfityJwt();
        if ($authUser) {
          $payloadJwt                      = parent::getPayloadJwt();

          // $data['userId']                  = intval($payloadJwt->aud);
          $data['StatusService_serviceStatusId']               = $this->_modelStatusService::PROGRESS_PAYMENT;
          $data['Family_userServiceId']                        = intval($payloadJwt->familyMemberId);
          $data['Service_serviceId']                           = intval($payloadJwt->serviceId);
          $data['requestDateTime']                             = $this->getTimestamp();
          $data['serviceDateTimeStart']                        = $payloadJwt->serviceDateTimeStart;
          $data['serviceMeridian']                             = $payloadJwt->serviceMeridian;
          $data['serviceDuration']                             = intval($payloadJwt->serviceDuration);
          $data['serviceDateTimeEnd']                          = $payloadJwt->serviceDateTimeEnd;
          $data['serviceTotalAmount']                          = intval($payloadJwt->serviceTotalAmount);
          $data['CurrencyCode_serviceTotalAmountCurrencyCode'] = 1;
          $data['userRequestObservations']                     = $payloadJwt->userRequestObservations ?? NULL;
          $data['temp_address']                                = $payloadJwt->temp_address;
          $data['temp_addressInfo']                            = $payloadJwt->temp_addressInfo;
          $responseDBcreateRequest                             = $this->_model->createRequest($data);

          // send data to payvlaida for created order buy
          // 2019_11_18_0903_001_00001
          $date = Carbon\Carbon::now()->settings(['locale' => 'es_CO', 'timezone' => 'America/Bogota']);
          $year    = $date->format('Y');
          $month   = $date->format('m');
          $day     = $date->format('d');
          $hour    = $date->format('H');
          $minutes = $date->format('i');

          $requestId = $responseDBcreateRequest['data']['requestId'];


          $email            = $payloadJwt->email;
          $order            = $data['Service_serviceId'] . '_' . $year . $month . $day . $hour . $minutes . '_' . $requestId; //This orderIdentifier
          $amount           = "$payloadJwt->serviceTotalAmount";
          $serviceDateStart = $payloadJwt->serviceDateStart;
          $serviceDetail    = $payloadJwt->serviceDetail;
          $description      = "$serviceDetail $serviceDateStart";                                                                  // teella Esencial 12 Horas 20191019 RequestUID 47HG1P5Dx3tNJiSt5Qag

          $responsePayValida = $this->registerPurchaseOrderPayValida($email, $order, $amount, $description);
          

          $orderStatus       = $responsePayValida->Operacion;
          $refNumber         = $responsePayValida->Referencia;
          $checkoutURL       = $responsePayValida->checkout;

          $statusPaymentData = $this->getStatusPayment();
          foreach ($statusPaymentData as $value) {
            if ($value['name'] == $orderStatus) {
              $statusPaymentId = $value['statusPaymentId'];
              break;
            }
          }
          

          /**
           *
           * TABLA DE DASHBOARD PAYVALIDA
           *
           * NÚMERO DE REFERENCIA         --- transactionPaymentGatewayRefNumber
           * IDENTIFICADOR DE LA ORDEN    --- transactionPaymentGatewayOrderIdentifier
           * FECHA DE CREACIÓN            --- transactionPaymentGatewayCreationDate
           * FECHA DE ACTUALIZACIÓN       --- transactionPaymentGatewayUpdateDate
           * DESCRIPCIÓN DE LA ORDEN      --- transactionPaymentGatewayOrderDescription
           * ESTADO DE LA ORDEN           --- transactionPaymentGatewayOrderStatus
           * MÉTODO                       --- transactionPaymentGatewayMethod
           * MONTO                        --- serviceTotalAmount
           * VOUCHER                      --- transactionPaymentGatewayVoucher
           *
           */

          $timestampCreated = $date->timestamp;
          $data['checkoutURL']                   = $checkoutURL;
          $data['creationDateTime']              = $timestampCreated;
          $data['orderDescription']              = $description;
          $data['orderIdentifier']               = $order;
          $data['refNumber']                     = $refNumber;
          $data['StatusPayment_statusPaymentId'] = $statusPaymentId;

          $responseDBTransactionPaymentGateway                           = $this->_modelTransactionPaymentGateway->insertTransactionPaymentGateway($data);
          $data['TransactionPaymentGateway_transactionPaymentGatewayId'] = $responseDBTransactionPaymentGateway['data']['transactionPaymentGatewayId'];
          $data['requestId']                                             = $requestId;

          $responseDBUpdatedRequest                                      = $this->_model->addedTransactionPaymentGatewayIDRequest($data);
          if (!$responseDBUpdatedRequest['status'] === 'Success') {
            throw new Exception('Error: Request was created, but error addedTransactionPaymentGatewayIDRequest 112');
          }

          $playerUUID = $this->getUUIDPlayer($userAccountId = intval($payloadJwt->sid));

          $title = array(
            "es" => 'Tienes un nuevo mensaje de TEELLA',
            "en" => 'You have a new message from teella',
          );
          $subtitle = array(
            "es" => 'Tiene nuevo orden de compra para su servicio.',
            "en" => 'You have a new purchase order for your service.',
          );
          $message = array(
            "es" => 'Selecciona opciones para realizar pago.',
            "en" => 'Select payment options.',
          );

          $button1 = array(
            "id" => 'id1',
            "text" => 'buttonText1',
            "icon" => 'ic_menu_share',
          );
          $button2 = array(
            "id" => 'id2',
            "text" => 'buttonText2',
            "icon" => 'ic_menu_send',
          );

          $buttons = array(
            $button1,
            $button2,
          );

          $additionalData = array(
            "requestId" => $requestId,
            "createdDateTimeNotification" => $timestampCreated,
            "typeNotification" => 'createdRequest',
            "checkoutURL" => $checkoutURL,
            "redirectPage" => 'historyRequest',
          );

          $lanuchURL = $data['checkoutURL'];

          $CREATED_REQUEST = 1;
          $dataPush['title']                      = $title;
          $dataPush['subtitle']                   = $subtitle;
          $dataPush['message']                    = $message;
          $dataPush['buttons']                    = $buttons ?? null;
          $dataPush['lanuchURL']                  = $lanuchURL;
          $dataPush['additionalData']             = $additionalData;
          $responseSendMessagePush                = $this->sendMessagePush($playerUUID, $dataPush);
          if (!isset($responseSendMessagePush->id)) {
            throw new Exception('Error: Request was created, but error sendMessagePush 112');
          }
        }
      } else {
        throw new Exception('No exist GET');
      }
    } catch (\Throwable $th) {
      $endJSON = $this->printError($th);
    } finally {
      $endJSON = $data ?? $endJSON;
      parent::json_response($endJSON);
    }
  }

  private function generatePayValidaRegistrarOrdenCompraSHA512(string $email, string $order, int $amount, $country = 343, $money = 'COP' /* 343	COP	Colombia */): ?string
  {
    $email       = $email;
    $country     = $country;
    $order       = $order;
    $money       = $money;
    $amount      = $amount;
    $FIXED_HASH  = '59bff66d23f8421a14b04c9cc8e6c1539930e8d75308abe2b979afef39bb51776831bb821d70152f4af7d3a8de89ea6224a684dbcb43a847a5f3907444a7b7d8';
    return hash("sha512",  $email . $country . $order . $money . $amount . $FIXED_HASH);
  }

  /**
   * registrarOrdenCompra
   * see detali https://docs.payvalida.com/
   */
  public function registerPurchaseOrderPayValida($email = null, $order = null, $amount = null, $description = null)
  {
    $inputParam = isset($email);
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
      $authUser = parent::verfityJwt();
      if ($authUser) {
        $email       = $_POST['email']  ?? $email;
        $order       = $_POST['order']  ?? $order;
        $amount      = $_POST['amount'] ?? $amount;
        $country     = 343;             // 343	COP	Colombia
        $money       = 'COP';           // 343	COP	Colombia
        $checksum    = $this->generatePayValidaRegistrarOrdenCompraSHA512($email, $order, $amount, $country, $money);

        $merchant    = 'innlab';
        $description = $_POST['description'] ?? $description;
        $reference   = $_POST['reference'] ?? NULL;
        $recurrent   = false;
        $method      = $_POST['method'] ?? NULL;
        $language    = 'es';
        $iva         = '0.02';
        $limitHour   = 24;

        $expiration = Carbon\Carbon::now()
          ->settings([
            'locale' => 'es_CO',
            'timezone' => 'America/Bogota',
          ])
          ->addHours($limitHour)
          ->format('d/m/Y');

        $res = $this->clientHttp->request('POST', 'https://api-test.payvalida.com/api/v3/porders', [
          'Accept'     => 'application/json',
          'json' => [
            'country'     => $country,
            'email'       => $email,
            'merchant'    => $merchant,
            'order'       => $order,
            'reference'   => $reference,
            'money'       => $money,
            'amount'      => $amount,
            'description' => $description,
            'language'    => $language,
            'recurrent'   => $recurrent,
            'expiration'  => $expiration,
            'method'      => $method,
            'iva'         => $iva,
            'checksum'    => $checksum
          ],
        ]);

        try {
          if ($res->getStatusCode() == 200) {
            $dataJson = json_decode($res->getBody());
            if (
              $dataJson->DESC == 'Ya existe una orden activa con la misma referencia de pago' || $dataJson->CODE == '0043' ||
              $dataJson->DESC == 'Ya existe una orden con el mismo PO_ID'                     || $dataJson->CODE == '0042'
            ) {
              throw new Exception($dataJson->DESC);
            } else {
              if ($inputParam) {
                return $dataJson->DATA;
              } else {
                $responseDB = $res->getBody();
                parent::json_response($responseDB, 200);
              }
            }
          } else {
            throw new Exception('Error request API Payvalida 0043');
          }
        } catch (\Throwable $th) {
          $endJSON['status'] = 'error';
          $endJSON["data"]['title'] = "";
          $endJSON["data"]['message'] = '⚠ Exception appear: ' . $th->getMessage();
          parent::json_jwt_response($endJSON, 400);
        }
        // parent::json_response($responseDB, 200);
      }
    } else {
      $endJSON['status'] = 'error';
      $endJSON["data"]['title'] = "";
      $endJSON["data"]['message'] = "No exist GET";
      parent::json_jwt_response($endJSON, 400);
    }
  }


  private function generatePayValidaConsultarOrdenCompraSHA512(string $order): ?string
  {
    $order       = $order;
    $merchantId  = 'innlab';
    $FIXED_HASH  = '59bff66d23f8421a14b04c9cc8e6c1539930e8d75308abe2b979afef39bb51776831bb821d70152f4af7d3a8de89ea6224a684dbcb43a847a5f3907444a7b7d8';
    return hash("sha512",  $order . $merchantId . $FIXED_HASH);
  }

  private function checksumPVWebhook(string $po_id, string $status, string $pv_checksum): ?bool
  {
    $FIXED_HASH_NOTIFICACION  = 'a285c7b97dcafe3e981203cbf3c0357c748a2fc7f839322a964ef1b28da11e44480aaf03c04f37f26007c648bb308b117e6e117c3209d258c07b549ac5b6440b';
    $checksumSHA256 = hash("sha256",  $po_id . $status . $FIXED_HASH_NOTIFICACION);
    return ($pv_checksum == $checksumSHA256);
  }

  /**
   * consultarOrdenCompra
   * see detali https://docs.payvalida.com/
   */
  public function consultarOrdenCompra()
  {
    if ($_SERVER['REQUEST_METHOD'] === 'GET') {
      $authUser = parent::verfityJwt();
      if ($authUser) {

        // Sandbox
        $urlAPI = "https://api-test.payvalida.com/api/v3/porders/";

        // Production
        //$service_url = "https://api.payvalida.com/api/v3/porders/";

        //Change orderID by you order
        $orderID = $_GET['orderID'];

        //You must change merchantID by you merchant
        $merchantId  = 'innlab';

        //Calculate checksum = SHA512(order + merchant + FIXED_HASH)
        $checksum  = $this->generatePayValidaConsultarOrdenCompraSHA512($orderID);

        $service_url = $urlAPI . $orderID . "?merchant=" . $merchantId . "&checksum=" . $checksum;

        $res = $this->clientHttp->request(
          'GET',
          $service_url,
          ['Accept'     => 'application/json']
        );

        try {
          if ($res->getStatusCode() == 200) {
            $dataJson = json_decode($res->getBody());
            if ($dataJson->DESC == 'No se encontraron resultados' || $dataJson->CODE == '0001') {
              throw new Exception($dataJson->DESC);
            } else {
              echo $res->getBody();
            }
          } else {
            throw new Exception('Error request API Payvalida 001');
          }
        } catch (\Throwable $th) {
          echo '⚠ Exception appear: ' . $th->getMessage();
          // throw $th;
        }
      }
    } else {
      $endJSON['status'] = 'error';
      $endJSON["data"]['title'] = "";
      $endJSON["data"]['message'] = "No exist POST";
      parent::json_jwt_response($endJSON, 400);
    }
  }

  /**
   * actualizarOrdenCompra
   * see detali https://docs.payvalida.com/
   */
  public function actualizarOrdenCompra()
  {
    parse_str(file_get_contents('php://input'), $_PATCH);
    if ($_SERVER["REQUEST_METHOD"] === "PATCH") {
      $authUser = parent::verfityJwt();
      if ($authUser) {
        $email       = $_PATCH['email'];
        $country     = 343;     // 343	COP	Colombia
        $order       = $_PATCH['order'];
        $money       = 'COP';   // 343	COP	Colombia
        $amount      = $_PATCH['amount'];
        $checksum      = $this->generatePayValidaRegistrarOrdenCompraSHA512($email, $order, $amount, $country, $money);

        $merchant    = 'innlab';
        $description = $_PATCH['description'];
        $reference   = $_PATCH['reference'];
        $recurrent   = false;
        $method      = $_PATCH['method'] ?? NULL;
        $language    = 'es';
        $iva         = '0.02';
        $limitHour   = $_PATCH['$addHour'] ?? NULL;
        $expiration   = $_PATCH['$expiration'] ?? NULL;

        $expiration = Carbon\Carbon::now()
          ->settings([
            'locale' => 'es_CO',
            'timezone' => 'America/Bogota',
          ])
          ->addHours($limitHour)
          ->format('d/m/Y');

        $res = $this->clientHttp->request('PATCH', 'https://api-test.payvalida.com/api/v3/porders', [
          'Accept'     => 'application/json',
          'json' => [
            'country'     => $country,
            'email'       => $email,
            'merchant'    => $merchant,
            'order'       => $order,
            'reference'   => $reference,
            'money'       => $money,
            'amount'      => $amount,
            'description' => $description,
            'language'    => $language,
            'recurrent'   => $recurrent,
            'expiration'  => $expiration,
            'method'      => $method,
            'iva'         => $iva,
            'checksum'    => $checksum
          ],
        ]);

        try {
          if ($res->getStatusCode() == 200) {
            $dataJson = json_decode($res->getBody());
            if ($dataJson->DESC == 'Ya existe una orden activa con la misma referencia de pago' || $dataJson->CODE == '0043') {
              throw new Exception($dataJson->DESC);
            } else {
              echo $res->getBody();
            }
          } else {
            throw new Exception('Error request API Payvalida 0043');
          }
        } catch (\Throwable $th) {
          echo '⚠ Exception appear: ' . $th->getMessage();
          // throw $th;
        }
        // parent::json_response($responseDB, 200);
      }
    } else {
      $endJSON['status'] = 'error';
      $endJSON["data"]['title'] = "";
      $endJSON["data"]['message'] = "No exist GET";
      parent::json_jwt_response($endJSON, 400);
    }
  }

  /**
   * eliminarOrdenCompra
   * see detali https://docs.payvalida.com/
   */
  public function removePurchaseOrderPayValida($orderId = null)
  {
    $inputParam = isset($orderId);
    try {
      parse_str(file_get_contents('php://input'), $_DELETE);
      if ($_SERVER["REQUEST_METHOD"] === "DELETE" || $_SERVER["REQUEST_METHOD"] === "PUT") {
        $authUser = parent::verfityJwt();
        if ($authUser) {

          // Sandbox
          $urlAPI = "https://api-test.payvalida.com/api/v3/porders/";

          // Production
          //$service_url = "https://api.payvalida.com/api/v3/porders/";

          //Change orderID by you order
          $orderID = $_GET['orderID'] ?? $orderId;

          //You must change merchantID by you merchant
          $merchantId  = 'innlab';

          //Calculate checksum = SHA512(order + merchant + FIXED_HASH)
          $checksum  = $this->generatePayValidaConsultarOrdenCompraSHA512($orderID);

          $service_url = $urlAPI . $orderID . "?merchant=" . $merchantId . "&checksum=" . $checksum;

          $res = $this->clientHttp->request(
            'DELETE',
            $service_url,
            ['Accept'     => 'application/json']
          );

          if ($res->getStatusCode() == 200) {
            $dataJson = json_decode($res->getBody());
            if (
              $dataJson->DESC == 'No se encontraron resultados'                                    || $dataJson->CODE == '0001' ||
              $dataJson->DESC == 'La orden no se encuentra registrada'                             || $dataJson->CODE == '0018' ||
              $dataJson->DESC == 'Error buscando Order'                                            || $dataJson->CODE == '0025' ||
              $dataJson->DESC == 'Ha ocurrido un error de ejecución, por favor intente nuevamente' || $dataJson->CODE == '0032'
            ) {
              if ($inputParam) {
                return $dataJson->DESC;
              } else {
                throw new Exception($dataJson->DESC);
              }
            } else {
              if ($inputParam) {
                return $dataJson->DATA;
              } else {
                $responseDB = $res->getBody();
                parent::json_response($responseDB, 200);
              }
            }
          } else {
            throw new Exception('Error request API Payvalida 0043');
          }
        } else {
          throw new Exception('No exist GET for webhook Teella', 400);
        }
      }
    } catch (\Throwable $th) {
      $endJSON = $this->printError($th);
    }
    // finally {
    //   parent::json_response($endJSON);
    // }
  }

  /**
   * webhookPayvalida
   * For Responsefrom Payvalida
   */
  public function webhookPayvalida()
  {
    try {
      if ($_SERVER["REQUEST_METHOD"] === "POST") {
        $req = json_decode(file_get_contents('php://input'), true) ?? $_POST;
        if (!isset($req) || empty($req)) {
          throw new Exception('No recibido datos desde servidor test INNLAB', 400);
        } else {
          // $data = array(
          //   'status' => 'OK',
          //   'message' => 'Recibida datos desde servidor test INNLAB',
          //   'req' => $req
          // );
          // echo json_encode($data);
          $amount          = $req['amount'];
          $pv_checksum     = $req['pv_checksum'];
          $po_id           = $req['po_id'];
          $iso_currency    = $req['iso_currency'];
          $pv_po_id        = $req['pv_po_id'];
          $status          = $req['status'];
          $isValidChecksum = $this->checksumPVWebhook($po_id, $status, $pv_checksum);
          if (!$isValidChecksum) {
            throw new Exception('Invalid checksum', 400);
          } else {
            $data['transactionPaymentGatewayOrderIdentifer'] = $po_id;
            $responseDBRequest = $this->_model->getRequest($data);
            if ($responseDBRequest['data'] == 'No registry found') {
              throw new Exception('Server not found po_id, please check po_id', 400);
            } else {
              $requestData = $responseDBRequest['data'][0];
            }

            $data['transactionPaymentGatewayId'] = $requestData['transactionPaymentGatewayId'];
            $data['transactionPaymentGatewayUpdateDateTime'] = $this->getTimestamp();
            $data['requestId'] = $requestData['requestId'];

            switch ($status) {
              case 'approved':
                // Se ha recibido el pago correspondiente
                $messagePush = array(
                  "es" => 'Hemos recibido tu pago! Pronto recibirás la confirmación del servicio!',
                  "en" => 'The corresponding payment has been received',
                );

                $titlePush = array(
                  "es" => 'Pago exitoso',
                  "en" => 'You have a new message from teella',
                );

                $data['StatusPayment_statusPaymentId'] = $this->_modelStatusPayment::STATUS_PAYMENT_APROBADA;
                $data['StatusService_serviceStatusId'] = $this->_modelStatusService::SEARCHING;
                break;
              case 'cancelled':
                if ($requestData) {
                  // No se ha recibido el pago correspondiente antes de la fecha de vencimiento
                  $messagePush = array(
                    "es" => 'No se ha recibido el pago correspondiente antes de la fecha de vencimiento',
                    "en" => 'The corresponding payment has not been received before the due date',
                  );
                  $data['StatusPayment_statusPaymentId'] = $this->_modelStatusPayment::STATUS_PAYMENT_VENCIDA;
                  $data['StatusService_serviceStatusId'] = $this->_modelStatusService::CANCELED;
                } else {
                  // El cliente realiza un reclamo de devolución del pago realizado
                  $messagePush = array(
                    "es" => 'El cliente realiza un reclamo de devolución del pago realizado',
                    "en" => 'The customer makes a claim for a refund of the payment made',
                  );
                  $data['StatusPayment_statusPaymentId'] = $this->_modelStatusPayment::STATUS_PAYMENT_ANULADA;
                  $data['StatusService_serviceStatusId'] = $this->_modelStatusService::CANCELED;
                }
                break;

              default:
                throw new Exception('Unknown new state of payvalida', 400);
                break;
            }
            $responseDBTransactionPaymentGateway = $this->_modelTransactionPaymentGateway->updateTransactionPaymentGateway($data);

            $responseDBUpdatedRequest = $this->_model->updateRequestTransactionPaymentGateway($data);

            if (
              $responseDBTransactionPaymentGateway['status'] == 'Success' &&
              $responseDBUpdatedRequest['status'] == 'Success'
            ) {
              $statusPaymentData = $this->getStatusPayment();
              foreach ($statusPaymentData as $value) {
                if ($value['statusPaymentId'] == $data['StatusPayment_statusPaymentId']) {
                  $data['statusPaymentName'] = $value['name'];
                  $data['statusPayment']     = $value['status'];
                  break;
                }
              }

              $playerUUID = $this->getUUIDPlayer($userAccountId = $requestData["userAccountId"]);

              $title = $titlePush ?? array(
                "es" => 'Tienes un nuevo mensaje de TEELLA',
                "en" => 'You have a new message from teella',
              );
              $subtitle = $messagePush;

              $message = $messagePush;

              // $button1 = array(
              //   "id" => 'id1',
              //   "text" => 'buttonText1',
              //   "icon" => 'ic_menu_share',
              // );
              // $button2 = array(
              //   "id" => 'id2',
              //   "text" => 'buttonText2',
              //   "icon" => 'ic_menu_send',
              // );

              // $buttons = array(
              //   $button1,
              //   $button2,
              // );

              // $checkoutURL = NULL;

              $additionalData = array(
                "typeNotification"  => 'paymentCallback',
                "requestId"         => $data["requestId"],
                "requestStatus"     => $responseDBUpdatedRequest['data']["serviceStatus"],
                "paymentStatusName" => $data["statusPaymentName"],
                "paymentStatus"     => $data["statusPayment"]
              );

              $lanuchURL = $data['checkoutURL'] ?? NULL;
              $PAYMENT_CALLBACK = 2;

              $dataPush['title']                      = $title;
              $dataPush['subtitle']                   = $subtitle;
              $dataPush['message']                    = $message;
              $dataPush['buttons']                    = $buttons ?? NULL;
              $dataPush['lanuchURL']                  = $lanuchURL;
              $dataPush['additionalData']             = $additionalData;
              $responseSendMessagePush                = $this->sendMessagePush($playerUUID, $dataPush);
              $sendedDateTime                         = $this->getTimestamp();
              $dataNotification['userId']             = $requestData["userAccountId"];
              $dataNotification['title']              = json_encode($title,           JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES );
              $dataNotification['subtitle']           = json_encode($subtitle,        JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES );
              $dataNotification['message']            = json_encode($message,         JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES );
              $dataNotification['lanuchURL']          = json_encode($lanuchURL,       JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES );
              $dataNotification['$buttons']           = json_encode($buttons ?? null, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES );
              $dataNotification['additionalData']     = json_encode($additionalData,  JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES );
              $dataNotification['sendedDateTime']     = $sendedDateTime;
              $dataNotification['typeNotification']   = $PAYMENT_CALLBACK;
              $dataNotification['UUIDNotification']   = $responseSendMessagePush->id;

              $responseSaveNewNotificationDb = $this->_modelNotification->sendedNewNotification($dataNotification);
              if ($responseSaveNewNotificationDb['status'] === 'Success') {
                unset($responseDBUpdatedRequest['data']);
                $responseDBUpdatedRequest['message'] = 'orderPurchase was updated';
                $endJSON = $responseDBUpdatedRequest;
              } else {
                throw new Exception('orderPurchase was updated, but server have error sendedNewNotification', 202);
              }
            }
          }
        }
      } else {
        throw new Exception('No exist GET for webhook Teella', 400);
      }
    } catch (\Throwable $th) {
      $endJSON = $this->printError($th);
    } finally {
      parent::json_response($endJSON);
    }
  }

  public function getRequestByUser()
  {
    try {
      if ($_SERVER['REQUEST_METHOD'] === 'GET') {
        $authUser = parent::verfityJwt();
        if ($authUser) {
          $payloadJwt       = parent::getDataJwt();
          $data['userId']   = $payloadJwt->aud;
          $responseDB       = $this->_model->getRequestByUser($data);
        }
      } else {
        throw new Exception("No exist GET", 400);
      }
    } catch (\Throwable $th) {
      $endJSON = parent::printError($th);
    } finally {
      $endJSON = $responseDB ?? $endJSON;
      parent::json_response($endJSON);
    }
  }

  public function getAllRequest()
  {
    try {
      if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // $authUser = parent::verfityJwt();
        // if ($authUser) {
        $responseDB = $this->_model->getAllRequest();
        // }
      } else {
        throw new Exception("Wrong Request", 400);
      }
    } catch (\Throwable $th) {
      $endJSON = parent::printError($th);
    } finally {
      $endJSON = $responseDB ?? $endJSON;
      parent::json_response($endJSON);
    }
  }

  public function getRequest()
  {
    try {
      if ($_SERVER['REQUEST_METHOD'] === 'GET') {
        $authUser = parent::verfityJwt();
        if ($authUser) {
          $payloadJwt       = parent::getDataJwt();
          $data             = $_POST;
          $data['userId']   = $payloadJwt->aud;
          $responseDB       = $this->_model->getRequest($data);
          parent::json_response($responseDB, 200);
        }
      } else {
        throw new Exception("No exist GET", 400);
      }
    } catch (\Throwable $th) {
      $endJSON = parent::printError($th);
    } finally {
      $endJSON = $responseDB ?? $endJSON;
      parent::json_response($endJSON);
    }
  }

  public function getAllRequestByUser()
  {
    try {
      if ($_SERVER['REQUEST_METHOD'] === 'GET') {
        $authUser = parent::verfityJwt();
        if ($authUser) {
          $payloadJwt = parent::getDataJwt();
          $data['userId'] = $payloadJwt->aud;
          $responseDB = $this->_model->getAllRequestByUser($data);
        }
      } else {
        throw new Exception("No exist GET", 400);
      }
    } catch (\Throwable $th) {
      $endJSON = parent::printError($th);
    } finally {
      $endJSON = $responseDB ?? $endJSON;
      parent::json_response($endJSON);
    }
  }

  private function getRequestId($requestId)
  {
    return $responseDB = $this->_model->getAllRequestId($requestId);
    // parent::json_response($responseDB, 200);
  }

  public function isPenaltyBeforeHourMinimum(int $dateTimeUnixStart, int $minHours): bool
  {
    $minHours = $_GET['minHours'] ?? $minHours;
    $dateTimeUnixStart = $_GET['dateTimeUnixStart'] ?? $dateTimeUnixStart;
    $diffHours = $this->checkDiffHours($dateTimeUnixStart);
    if ($diffHours <= 0) {
      throw new Exception("expired date to request cancellation of service", 400);
    }
    return ($minHours >= $diffHours);
    // if ($minHours >= $diffHours)
    // {
    //   echo 'Penalty';
    // } else {
    //   echo 'Okay';
    // }
  }

  public function checkDiffHours($dateTimeUnixStart)
  {
    $tz            = 'America/Bogota';
    $dateTimeStart = Carbon\Carbon::createFromTimestamp($dateTimeUnixStart, $tz);
    $dateTimeToday = Carbon\Carbon::now($tz);
    $diffHours     = $dateTimeToday->diffInHours($dateTimeStart, false);
    $test          = $diffHours;
    return $test;
  }

  public function checkCancelledRequestConfirmed(array $data): int
  {
    $isPenaltyAfterMiniumHour = $this->isPenaltyBeforeHourMinimum($data['serviceDateTimeStart'], 2);
    switch ($data['userAccountRol']) {
      case 'Client':
        if ($isPenaltyAfterMiniumHour) {
          return $this->_modelStatusService::CANCELED_PENALTY;
        } else {
          $this->removePurchaseOrderPayValida($data['transactionPaymentGatewayOrderIdentifer']);
          return $this->_modelStatusService::CANCELED_REFUND;
        }
        break;
      case 'Assistant':
        $this->setPenaltyAssistant();

        if ($isPenaltyAfterMiniumHour) {
          return $this->_modelStatusService::SEARCHING;
        } else {
          $this->enableAlertCallUser($userAccountId = 1, $requestId = 1);
          return $this->_modelStatusService::SEARCHING;
        }
        break;
    }
  }

  public function setPenaltyAssistant()
  {
    // TODO
  }

  /**
   * Enable alert call to user for informed
   */
  private function enableAlertCallUser($userAccountId, $requestId)
  {
    // TODO
    $userAccountId;
    $requestId;
  }


  public function checkedStatusPayment(array $data): int
  {
    switch ($data['statusPaymentName']) {

      case 'CREADA':
        // // El comercio elimina la orden (API)
        // return $this->_modelStatusService::CANCELED;
      case 'EN PROCESO':
        // // El comercio elimina la orden (API)
        // return $this->_modelStatusService::CANCELED;
      case 'PENDIENTE':
        // El comercio elimina la orden (API)
        $responsePayvalida = $this->removePurchaseOrderPayValida($data['transactionPaymentGatewayOrderIdentifer']);
        // if ($responsePayvalida == 'La orden no se encuentra registrada') {
        //   echo 'La orden no se encuentra registrada';
        // }
        return $this->_modelStatusService::CANCELED;
      case 'APROBADA':
        return $this->checkCancelledRequestConfirmed($data);
      default:
        throw new Exception("Already done changed state to cancel", 400);
        break;
    }
  }

  public function checkStatusTransactionCancelled(array $data): int
  {
    if ($data['StatusService_serviceStatusId'] == $this->_modelStatusService::CANCELED_REFUND) {
      return  $this->_modelStatusPayment::STATUS_PAYMENT_ANULADA;
    } else if ($data['StatusService_serviceStatusId'] == $this->_modelStatusService::CANCELED_EXPIRED) {
      return  $this->_modelStatusPayment::STATUS_PAYMENT_VENCIDA;
    } else if ($data['StatusService_serviceStatusId'] == $this->_modelStatusService::CANCELED) {
      return  $this->_modelStatusPayment::STATUS_PAYMENT_CANCELADA;
    }
  }

  public function cancelledRequest()
  {
    try {
      parse_str(file_get_contents('php://input'), $_PUT);
      if ($_SERVER["REQUEST_METHOD"] === "PUT") {
        $authUser = parent::verfityJwt();
        if ($authUser) {
          $data       = $_PUT;
          $responseDBRequest = $this->_model->getRequest($data);
          if ($responseDBRequest['data'] == 'No registry found') {
            throw new Exception("No found requestId. Please check correct requestId", 400);
          }
          $data       = $responseDBRequest['data'][0];
          unset($responseDBRequest);

          $payloadJwt = parent::getPayloadJwt();
          if ($data["userAccountRol"] == $payloadJwt->role && $data["userAccountId"] == $payloadJwt->aud) {
            $data['StatusService_serviceStatusId']           = $this->checkedStatusPayment($data);
            $data['StatusPayment_statusPaymentId']           = $this->checkStatusTransactionCancelled($data);
            $responseDBRequestUpdated                        = $this->_model->updateRequestTransactionPaymentGateway($data);
            $data['transactionPaymentGatewayUpdateDateTime'] = $this->getTimestamp();
            $responseDBTransaction                           = $this->_modelTransactionPaymentGateway->updateTransactionPaymentGateway($data);
            $responseDBRequestUpdated                        = $this->_model->getRequest($data);
            if (
              $responseDBRequestUpdated["status"] == "success" &&
              $responseDBTransaction["status"] == "success"
            ) {
              $endJSON['status']                           = 'Success';
              $endJSON['message']                          = 'Cancelled request was confirmed';
              $responseDB                                  = $responseDBRequestUpdated['data'][0];
              $endJSON['data']['requestId']                = $responseDB["requestId"];
              $endJSON['data']['statusServiceDisplayName'] = $responseDB["statusServiceDisplayName"];
              $endJSON['data']['statusPaymentName']        = $responseDB["statusPaymentName"];


              $playerUUID = $this->getUUIDPlayer($userAccountId = $responseDB["userAccountId"]);

              $title = array(
                "es" => 'Cancelación del servicio',
                "en" => 'You have a new message from teella',
              );

              $messagePush = array(
                "es" => 'Tu servicio ha sido cancelado con éxito! No olvides programar tu nuevo servicio! ',
                "en" => 'The corresponding payment has been received',
              );

              $subtitle = $messagePush;

              $message = $messagePush;

              // $button1 = array(
              //   "id" => 'id1',
              //   "text" => 'buttonText1',
              //   "icon" => 'ic_menu_share',
              // );
              // $button2 = array(
              //   "id" => 'id2',
              //   "text" => 'buttonText2',
              //   "icon" => 'ic_menu_send',
              // );

              // $buttons = array(
              //   $button1,
              //   $button2,
              // );

              // $checkoutURL = NULL;

              $additionalData = array(
                "typeNotification"  => 'paymentCallback',
                "requestId"         => $responseDB["requestId"],
                "requestStatus"     => $responseDB["serviceStatus"], //
                "paymentStatusName" => $responseDB["statusPaymentName"],
                "paymentStatus"     => $responseDB["statusPaymentStatus"]
              );

              $lanuchURL = $data['checkoutURL'] ?? NULL;

              $dataPush['title']                      = $title;
              $dataPush['subtitle']                   = $subtitle;
              $dataPush['message']                    = $message;
              $dataPush['buttons']                    = $buttons ?? NULL;
              $dataPush['lanuchURL']                  = $lanuchURL;
              $dataPush['additionalData']             = $additionalData;
              $responseSendMessagePush                = $this->sendMessagePush($playerUUID, $dataPush);
              $sendedDateTime                         = $this->getTimestamp();
              $dataNotification['userId']             = $responseDB["userAccountId"];
              $dataNotification['title']              = json_encode($title);
              $dataNotification['subtitle']           = json_encode($subtitle);
              $dataNotification['message']            = json_encode($message);
              $dataNotification['lanuchURL']          = json_encode($lanuchURL);
              $dataNotification['$buttons']           = json_encode($buttons ?? null);
              $dataNotification['additionalData']     = json_encode($additionalData);
              $dataNotification['sendedDateTime']     = $sendedDateTime;
              $dataNotification['typeNotification']   = $this->_modelTypeNotification::PAYMENT_CALLBACK;
              $dataNotification['UUIDNotification']   = $responseSendMessagePush->id;

              $responseSaveNewNotificationDb = $this->_modelNotification->sendedNewNotification($dataNotification);
              if ($responseSaveNewNotificationDb['status'] === 'Success') {
                unset($responseDB);
                $responseDBUpdatedRequest['status'] = 'Success';
                $responseDBUpdatedRequest['message'] = 'orderPurchase and requestService was cancelled';
                $endJSON = $responseDBUpdatedRequest;
              } else {
                throw new Exception('orderPurchase was updated, but server have error sendedNewNotification', 202);
              }
            }
          } else {
            throw new Exception("Unauthenticated for different user", 401);
          }
        } else {
          throw new Exception("Unauthenticated", 401);
        }
      } else {
        throw new Exception("No exist GET-POST", 400);
      }
    } catch (\Throwable $th) {
      $endJSON = parent::printError($th);
    } finally {
      $endJSON = $endJSON;
      parent::json_response($endJSON);
    }
  }

  public function updateRequest()
  {
    try {
      parse_str(file_get_contents('php://input'), $_PUT);
      if ($_SERVER["REQUEST_METHOD"] === "PUT") {
        $responseDB = $this->_model->updateRequest($_PUT);
      } else {
        throw new Exception("No exist GET-POST", 400);
      }
    } catch (\Throwable $th) {
      $endJSON = parent::printError($th);
    } finally {
      $endJSON = $responseDB ?? $endJSON;
      parent::json_response($endJSON);
    }
  }

  public function updateRequestUserObservations()
  {
    try {
      parse_str(file_get_contents('php://input'), $_PUT);
      if ($_SERVER["REQUEST_METHOD"] === "PUT") {
        $responseDB = $this->_model->updateRequestUserObservations($_PUT);
      } else {
        throw new Exception("No exist GET-POST", 400);
      }
    } catch (\Throwable $th) {
      $endJSON = parent::printError($th);
    } finally {
      $endJSON = $responseDB ?? $endJSON;
      parent::json_response($endJSON);
    }
  }

  public function deleteRequest()
  {
    try {
      parse_str(file_get_contents('php://input'), $_DELETE);
      if ($_SERVER["REQUEST_METHOD"] === "DELETE") {
        $responseDB = $this->_model->deleteRequest($_DELETE);
      } else {
        throw new Exception("No exist GET-POST", 400);
      }
    } catch (\Throwable $th) {
      $endJSON = parent::printError($th);
    } finally {
      $endJSON = $responseDB ?? $endJSON;
      parent::json_response($endJSON, 204);
    }
  }
}
