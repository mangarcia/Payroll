<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");

use Rakit\Validation\Validator;

class PaymentNoteController extends Controller
{
  private $_model;

  public function __construct()
  {
     parent::__construct();
     $this->_model = $this->loadModel('PaymentNote');
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

  }

  public function createPaymentNote() {
       if ('POST' === $_SERVER['REQUEST_METHOD']) {
          $validator = new Validator();
          $validation = $validator->make($_POST, [
              'Payment_idPayment' => 'required|numeric',
              'PaymentNoteDescription' => 'required'
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
          $payloadJwt = parent::getSesionDataJwt();
          $data           = $_POST;
          $data['userId'] = $payloadJwt->aud;
          $responseDB = $this->_model->createPaymentNote($data);
          parent::json_response($responseDB, 201);
       } else {
          $endJSON['status'] = 'error';
          $endJSON['data']['title'] = '';
          $endJSON['data']['message'] = 'No exist '.$_SERVER['REQUEST_METHOD'];
          parent::json_jwt_response($endJSON, 400);
       }
  }

    public function listPaymentNote() {
         if ('POST' === $_SERVER['REQUEST_METHOD']) {
            $validator = new Validator();
            $validation = $validator->make($_POST, [
                'Payment_idPayment' => 'required|numeric'
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
            $responseDB = $this->_model->listPaimentNotes($_POST);
            parent::json_response($responseDB, 201);
         } else {
            $endJSON['status'] = 'error';
            $endJSON['data']['title'] = '';
            $endJSON['data']['message'] = 'No exist '.$_SERVER['REQUEST_METHOD'];
            parent::json_jwt_response($endJSON, 400);
         }
    }
}