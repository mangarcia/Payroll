<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");

use Rakit\Validation\Validator;

class PaymentController extends Controller
{
   private $_model;

   public function __construct()
   {
      parent::__construct();
      $this->_model = $this->loadModel('Payment');

   }

   public function index()
   {
      // Access-Control headers are received during OPTIONS requests
      if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {

         if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_METHOD']))
            header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");

         if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']))
            header("Access-Control-Allow-Headers: {$_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']}");
      }

      $session = Session::get("TellaConnected");
      if($session)
      {
         $this->_view->title_ = "Payment";
         $this->_view->renderizar('payment', 'payment');
      }
      else { 
         $this->redireccionar();
         }
   }


   public function GetPaymentStatus()
   {

      if ('GET' === $_SERVER['REQUEST_METHOD']) 
      {
         $responseDB = $this->_model->GetPaymentStatus();       
      }
      else
      {
         $responseDB['status'] = 'error';
         $responseDB['title'] = '';
         $responseDB['message'] = 'No exist ' . $_SERVER['REQUEST_METHOD'];
      }

      parent::json_response($responseDB, 201);

   }

public function loadPaymentsFromCsv()
{
   
}
   public function createPayment()
   {
     
      //SystemUser_idSystemUser
      //PaymentDateTime
      if ('POST' === $_SERVER['REQUEST_METHOD']) {
         $authUser = parent::verfitySessionJwt();
         if ($authUser) 
         {
           $data = $_POST;
           $payloadJwt = parent::getSesionDataJwt();
           $data['SystemUser_idSystemUser'] = $payloadJwt->aud; 
         }
         else
         {
            $endJSON['status'] = 'error';
            $endJSON['data'] = 'Invalid Authentication';
            parent::json_jwt_response($endJSON, 400);
            return;
         }

         if($_POST['idPayment']!="")
         {
            $this->updatePayment();
            return;
         }

         $validator = new Validator();
         $validation = $validator->make($_POST, [
            'idCompanyPayment' => 'required|numeric',
            'PaymentValue' => 'required|numeric',
            'Employee_idEmployee' => 'required|numeric',
            'paymentLocationId' => 'required|numeric',
            'PaymentHours' => 'required|numeric',
            'PaymentValue' => 'required|numeric',
            'PaymentFromDate' => 'required',
            'PaymentToDate' => 'required'
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

         $data["PaymentStatus_idPaymentStatus"]=1;

         date_default_timezone_set('Canada/Eastern');
         $PaymentGeneratedDateTime = new DateTime('NOW');

         $PaymentGeneratedDateTime = $PaymentGeneratedDateTime->format('Y-m-d H:i:s');

         $data["PaymentGeneratedDateTime"]=$PaymentGeneratedDateTime;

         $responseDB = $this->_model->createPayment( $data);
         parent::json_response($responseDB, 201);
      } else {
         $endJSON['status'] = 'error';
         $endJSON['data']['title'] = '';
         $endJSON['data']['message'] = 'No exist ' . $_SERVER['REQUEST_METHOD'];
         parent::json_jwt_response($endJSON, 400);
      }
   }

   public function updatePayment()
   {
      if ('POST' === $_SERVER['REQUEST_METHOD']) {

         $authUser = parent::verfitySessionJwt();
         if ($authUser) 
         {
           $data = $_POST;
           $payloadJwt = parent::getSesionDataJwt();
           $data['SystemUser_idSystemUser'] = $payloadJwt->aud; 
         }
         else
         {
            $endJSON['status'] = 'error';
            $endJSON['data'] = 'Invalid Authentication';
            parent::json_jwt_response($endJSON, 400);
            return;
         }

         $validator = new Validator();
         $validation = $validator->make($_POST, [
            'idPayment' => 'required|numeric',
            'idCompanyPayment' => 'required|numeric',
            'PaymentValue' => 'required|numeric',
            'paymentLocationId' => 'required|numeric',
            'PaymentHours' => 'required|numeric',
            'PaymentValue' => 'required|numeric',
            'PaymentFromDate' => 'required',
            'PaymentToDate' => 'required'
         ]);

         $validation->validate();
         if ($validation->fails()) {
            // handling errors
            $errors = $validation->errors();
            // echo "<pre>";
            echo json_encode($errors->firstOfAll());
            // echo "</pre>";
            exit;
         }

         date_default_timezone_set('Canada/Eastern');
         $PaymentGeneratedDateTime = new DateTime('NOW');

         $PaymentGeneratedDateTime = $PaymentGeneratedDateTime->format('Y-m-d H:i:s');

         $data["PaymentGeneratedDateTime"]=$PaymentGeneratedDateTime;

         $responseDB = $this->_model->updatePayment($data);
         parent::json_response($responseDB, 201);
      } else {
         $endJSON['status'] = 'error';
         $endJSON['data']['title'] = '';
         $endJSON['data']['message'] = 'No exist ' . $_SERVER['REQUEST_METHOD'];
         parent::json_jwt_response($endJSON, 400);
      }
   }

   public function getPayments()
   {
      if ('GET' === $_SERVER['REQUEST_METHOD']) {
         $payloadJwt = parent::getSesionDataJwt();
         
         $data['userId'] = $payloadJwt->aud;
         $responseDB = $this->_model->getPayments($data);
         parent::json_response($responseDB, 201);
      } else {
         $endJSON['status'] = 'error';
         $endJSON['data']['title'] = '';
         $endJSON['data']['message'] = 'No exist ' . $_SERVER['REQUEST_METHOD'];
         parent::json_jwt_response($endJSON, 400);
      }
   }

   public function deletedPayment()
   {
      if ('POST' === $_SERVER['REQUEST_METHOD']) {
         $validator = new Validator();
         $authUser = parent::verfityJwt();
         if ($authUser) {
            $validator = new Validator();
            $validation = $validator->make($_POST, [
               'idPayment' => 'required'
            ]);
            $validation->validate();
            if ($validation->fails()) {
               // handling errors
               $errors = $validation->errors();
               // echo "<pre>";
               echo json_encode($errors->firstOfAll());
               // echo "</pre>";
               exit;
            }
            $responseDB = $this->_model->deletePayment($_POST);
            parent::json_response($responseDB, 201);
         }
      } else {
         $endJSON['status'] = 'error';
         $endJSON['data']['title'] = '';
         $endJSON['data']['message'] = 'No exist ' . $_SERVER['REQUEST_METHOD'];
         parent::json_jwt_response($endJSON, 400);
      }
   }
}
