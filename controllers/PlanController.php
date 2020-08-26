<?php

class PlanController extends Controller
{
   private $clientHttp;

  private $_model;

  public function __construct()
  {
    $this->clientHttp = new GuzzleHttp\Client();
    parent::__construct();
    $this->_model = $this->loadModel('Plan');
  }


  public function index()
  {
    $session = Session::get("TellaConnected");
    
    if($session)
    {
        $data['companyId'] = Session::get("companyId");

        $this->_view->title_ = "Planes";
        $this->_view->_layoutParams["Companies"] = $this->loadModel("Company")->getCompanyLocations($data)["data"];
        $this->_view->_layoutParams["Cardinality"] = $this->_model->readCardinality()["data"];
        $this->_view->renderizar('plan', 'plan');
	}
    else { $this->redireccionar(); }
  }


  public function calculatePrice()
  {
     if ($_SERVER['REQUEST_METHOD'] === 'POST') {

        $data = $_POST;
        $responseDB = $this->_model->calculatePrice($data);
        
        parent::json_jwt_response($responseDB, 200);
      
    } else {
      $endJSON['status'] = 'error';
      $endJSON["data"]['title'] = "";
      $endJSON["data"]['message'] = "No exist GET";
      parent::json_jwt_response($endJSON, 400);
    }
  }

  #region Plan

  public function createPlan()
  {
     
      if ($_SERVER['REQUEST_METHOD'] === 'POST') {
      $authUser = parent::verfitySessionJwt();
      if ($authUser) {
        $data = $_POST;
        $payloadJwt = parent::getSesionDataJwt();
        $data['userId'] = $payloadJwt->aud;
        if(isset($_POST["planId"]))
        {
           $responseDB = $this->_model->updatePlan($data);
        }
        else
        {
          $responseDB = $this->_model->createPlan($data);         
        }
        
        parent::json_response($responseDB, 200);
      }
    } else {
      $endJSON['status'] = 'error';
      $endJSON["data"]['title'] = "";
      $endJSON["data"]['message'] = "No exist GET";
      parent::json_jwt_response($endJSON, 400);
    }
  }

   public function readPlans()
  {
    
     if ($_SERVER['REQUEST_METHOD'] === 'POST') {
      $authUser = parent::verfitySessionJwt();
      if ($authUser) {
        $data = $_POST;
        $payloadJwt = parent::getSesionDataJwt();
        $data['userId'] = $payloadJwt->aud;
        $responseDB = $this->_model->readPlans($data);
        parent::json_response($responseDB, 200);
      }
    } else {
      $endJSON['status'] = 'error';
      $endJSON["data"]['title'] = "";
      $endJSON["data"]['message'] = "No exist GET";
      parent::json_jwt_response($endJSON, 400);
    }
  }

   public function updatePlan()
  {
     if ($_SERVER['REQUEST_METHOD'] === 'POST') {
      $authUser = parent::verfityJwt();
      if ($authUser) {
        $jwt = json_encode(parent::getDataJwt());
        $jwt = json_decode($jwt);
        $data = $_POST;
        $data['userId'] = $jwt->aud;
        $responseDB = $this->_model->updatePlan($data);
        parent::json_jwt_response($responseDB, 200);
      }
    } else {
      $endJSON['status'] = 'error';
      $endJSON["data"]['title'] = "";
      $endJSON["data"]['message'] = "No exist GET";
      parent::json_jwt_response($endJSON, 400);
    }
  }


   public function deletePlan()
  {
     if ($_SERVER['REQUEST_METHOD'] === 'POST') {
      $authUser = parent::verfityJwt();
      if ($authUser) {
        $jwt = json_encode(parent::getDataJwt());
        $jwt = json_decode($jwt);
        $data = $_POST;
        $data['userId'] = $jwt->aud;
        $responseDB = $this->_model->deletePlan($data);
        parent::json_jwt_response($responseDB, 200);
      }
    } else {
      $endJSON['status'] = 'error';
      $endJSON["data"]['title'] = "";
      $endJSON["data"]['message'] = "No exist GET";
      parent::json_jwt_response($endJSON, 400);
    }
  }

  #End Region

public function readCardinality()
  {
    echo json_encode($this->_model->readCardinality());
  }


  #region PlanDays

  public function createPlanDays()
  {
     if ($_SERVER['REQUEST_METHOD'] === 'POST') {
      $authUser = parent::verfityJwt();
      if ($authUser) {
        $jwt = json_encode(parent::getDataJwt());
        $jwt = json_decode($jwt);
        $data = $_POST;
        $data['userId'] = $jwt->aud;
        $responseDB = $this->_model->createPlanDays($data);
        parent::json_jwt_response($responseDB, 200);
      }
    } else {
      $endJSON['status'] = 'error';
      $endJSON["data"]['title'] = "";
      $endJSON["data"]['message'] = "No exist GET";
      parent::json_jwt_response($endJSON, 400);
    }
  }

   public function readPlanDays()
  {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
      $authUser = parent::verfityJwt();
      if ($authUser) {
        $jwt = json_encode(parent::getDataJwt());
        $jwt = json_decode($jwt);
        $data = $_POST;
        $data['userId'] = $jwt->aud;
        $responseDB = $this->_model->readPlanDays($data);
        parent::json_jwt_response($responseDB, 200);
      }
    } else {
      $endJSON['status'] = 'error';
      $endJSON["data"]['title'] = "";
      $endJSON["data"]['message'] = "No exist GET";
      parent::json_jwt_response($endJSON, 400);
    }
  }

   public function updatePlanDays()
  {
     if ($_SERVER['REQUEST_METHOD'] === 'POST') {
      $authUser = parent::verfityJwt();
      if ($authUser) {
        $jwt = json_encode(parent::getDataJwt());
        $jwt = json_decode($jwt);
        $data = $_POST;
        $data['userId'] = $jwt->aud;
        $responseDB = $this->_model->updatePlanDays($data);
        parent::json_jwt_response($responseDB, 200);
      }
    } else {
      $endJSON['status'] = 'error';
      $endJSON["data"]['title'] = "";
      $endJSON["data"]['message'] = "No exist GET";
      parent::json_jwt_response($endJSON, 400);
    }
  }


   public function deletePlanDays()
  {
     if ($_SERVER['REQUEST_METHOD'] === 'POST') {
      $authUser = parent::verfityJwt();
      if ($authUser) {
        $jwt = json_encode(parent::getDataJwt());
        $jwt = json_decode($jwt);
        $data = $_POST;
        $data['userId'] = $jwt->aud;
        $responseDB = $this->_model->deletePlanDays($data);
        parent::json_jwt_response($responseDB, 200);
      }
    } else {
      $endJSON['status'] = 'error';
      $endJSON["data"]['title'] = "";
      $endJSON["data"]['message'] = "No exist GET";
      parent::json_jwt_response($endJSON, 400);
    }
  }

  #End Region
function generateRandomString($length = 10) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
}

private function generatePayValidaRegistrarOrdenCompraSHA512(string $email, string $order, int $amount, $country = 343, $money = 'COP' /* 343	COP	Colombia */): ?string
  {
    echo "sada".$email;
    echo "Order ".$order;
    echo "amount".$amount;

    $email       = $email;
    $country     = $country;
    $order       = $order;
    $money       = $money;
    $amount      = $amount;
    $FIXED_HASH  = '59bff66d23f8421a14b04c9cc8e6c1539930e8d75308abe2b979afef39bb51776831bb821d70152f4af7d3a8de89ea6224a684dbcb43a847a5f3907444a7b7d8';
    return hash("sha512",  $email . $country . $order . $money . $amount . $FIXED_HASH);
  }


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


  
}