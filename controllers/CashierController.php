<?php

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST");

use Rakit\Validation\Validator;

class CashierController extends Controller
{
    private $_model;
    private $_model_company;

    public function __construct()
    {
        parent::__construct();
        //error_reporting(0);
        $this->_model = $this->loadModel('Cashier');
        $this->_model_company = $this->loadModel('Company');
        $this->_model_payment = $this->loadModel('Payment');
    }

    public function index()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {

            if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_METHOD']))
                header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
  
            if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']))
                header("Access-Control-Allow-Headers: {$_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']}");
        }  

        $session = Session::get("TellaConnected");
        if ($session) {
            $this->_view->title_ = "Cashier";
                $this->_view->renderizar('cashier', 'cashier');

        } else { $this->redireccionar();}

    }


    public function UpdatePayment()
    {
        if ('POST' === $_SERVER['REQUEST_METHOD']) {
            $validator = new Validator();
            $validation = $validator->make($_POST, [
               'idPayment'         => 'required|numeric',
               'sign'              => 'required',
               'idCompany'         =>'required|numeric'
            ]);
   
            $validation->validate();
            if ($validation->fails()) {
                $errors = $validation->errors();
                echo json_encode($errors->firstOfAll());
                exit;
            }

            $_POST['id']=$_POST["idCompany"];
            $dataUpdate["sign"]=$_POST["sign"];
            try {
                $responseDB = $this->_model_company->getCompany($_POST);
               
                if ($responseDB["data"]) {
                  
                    $responseDB["data"]=$responseDB["data"][0];
                    $dataUpdate['companyInfo']=$responseDB["data"];
                    $paymentDB = $this->_model->getPaymentsById($_POST);

                    if($paymentDB["data"]) {
                        $dataUpdate['paymentInfo']=$paymentDB["data"];
                        switch($responseDB["data"]["CompanyHaveLoan"]) {
                            case 0:

                                $operation = $responseDB["data"]["companyCurrentMoney"] - $paymentDB['data']['PaymentValue'];
                                
                                if ($operation >= 0) {
                                    $result = true;
                                } else {
                                    $result = false;
                                }
                                                               
                                if ( !$result ) {
                                    $endJSON['status'] = 'Error';
                                    $endJSON['data']['title'] = '';
                                    $endJSON['data']['message'] = 'Update company insufficient balance';
                                    parent::json_jwt_response($endJSON, 400);
                                } else {
                                    $dataUpdate['idCompany'] = $responseDB["data"]['idCompany'];
                                    $dataUpdate['idPayment'] = $_POST['idPayment'];
                                    $dataUpdate['companyDebt'] = $responseDB["data"]["companyDebt"];
                                    $dataUpdate['companyCurrentMoney'] = $operation;
                                   
                                    try {
                                        $result = $this->_model->CashOut($dataUpdate);
                                        $endJSON['status'] = 'Success';
                                        $endJSON['data']['message'] = 'Company update payment';
                                        parent::json_jwt_response($endJSON, 201);
                                    } catch (Exception $e) {
                                        http_response_code(400);
                                        $endJSON['status'] = 'Error';
                                        $endJSON['data']['title'] = '';
                                        $endJSON['data']['message'] = 'Company sql error UpdatePayment';
                                        parent::json_jwt_response($endJSON, 400);
                                    }
                                }
    
                              break;
    
                            case 1:
                                $operation = $responseDB["data"]["companyCurrentMoney"] - $paymentDB['data']['PaymentValue'];
                                if ($operation >= 0) {
                                    $result = true;
                                } else {
                                    $result = false;
                                }
                                
                                if ( !$result ) {
                                    if( $responseDB["data"]["CompanyLoanMaxAmmount"] - $responseDB["data"]["companyDebt"] + $operation >= 0){
                                         
                                        $dataUpdate['idCompany'] = $responseDB['data']['idCompany'];
                                        $dataUpdate['idPayment'] = $_POST['idPayment'];
                                        $dataUpdate['companyDebt'] = $responseDB["data"]["companyDebt"] - $operation;
                                        $dataUpdate['companyCurrentMoney'] = 0;
                                        try {
                                            $result = $this->_model->CashOut($dataUpdate);
                                            
                                            parent::json_jwt_response($result, 201);
                                        } catch (Exception $e) {
                                            http_response_code(400);
                                            $endJSON['status'] = 'Error';
                                            $endJSON['data']['title'] = '';
                                            $endJSON['data']['message'] = 'Company sql error UpdatePayment';
                                            parent::json_jwt_response($endJSON, 400);
                                        }
                                    } else {
                                        $endJSON['status'] = 'Error';
                                        $endJSON['data']['title'] = '';
                                        $endJSON['data']['message'] = 'You do not have the balance to make the payment';
                                        parent::json_jwt_response($endJSON, 400);
                                    }

                                } else { 
                                    $dataUpdate['idCompany'] = $responseDB["data"]['idCompany'];
                                    $dataUpdate['idPayment'] = $_POST['idPayment'];
                                    $dataUpdate['companyDebt'] = $responseDB["data"]["companyDebt"];
                                    $dataUpdate['companyCurrentMoney'] = $operation;
                                    try {
                                        $result = $this->_model->CashOut($dataUpdate);
                                        parent::json_jwt_response($result, 201);
                                    } catch (Exception $e) {
                                        http_response_code(400);
                                        $endJSON['status'] = 'Error';
                                        $endJSON['data']['title'] = '';
                                        $endJSON['data']['message'] = 'Company sql error UpdatePayment';
                                        parent::json_jwt_response($endJSON, 400);
                                    }
                                }
                              break;
    
                            default;
                                $endJSON['status'] = 'Error';
                                $endJSON['data']['title'] = '';
                                $endJSON['data']['message'] = 'Company error extact data UpdatePayment';
                                parent::json_jwt_response($endJSON, 400);
                            break;  
                            
                        }

                    } else {
                        $endJSON['status'] = 'Error';
                        $endJSON['data']['title'] = '';
                        $endJSON['data']['message'] = 'Company payment not found UpdatePayment';
                        parent::json_jwt_response($endJSON, 400);
                    }
                
                } else {
                    $endJSON['status'] = 'Error';
                    $endJSON['data']['title'] = '';
                    $endJSON['data']['message'] = 'Company not found UpdatePayment';
                    parent::json_jwt_response($endJSON, 400);
                }
            }catch (Exception $e) {
                http_response_code(400);
                $endJSON['status'] = 'Error';
                $endJSON['data']['title'] = '';
                $endJSON['data']['message'] = 'Company sql error UpdatePayment';
                parent::json_jwt_response($endJSON, 400);
            }
            
            
        } else {
            $endJSON['status'] = 'Error';
            $endJSON['data']['title'] = '';
            $endJSON['data']['message'] = 'No exist '.$_SERVER['REQUEST_METHOD'];
            parent::json_jwt_response($endJSON, 400);
        }
    }

    public function GetAvailablePaymentsId()
    {
        if ('POST' === $_SERVER['REQUEST_METHOD']) {

            $validator = new Validator();
            $validation = $validator->make($_POST, [
               'idCompanyPayment' => 'required|numeric',
               'pageNumber' => 'required|numeric',
               'pageSize' => 'required|numeric',
               'orderBy' => 'required'
            ]);
   
            $validation->validate();
            if ($validation->fails()) {
                $errors = $validation->errors();
                echo json_encode($errors->firstOfAll());
                exit;
            }

            $responseDB = $this->_model->GetAvailablePaymentsId($_POST);
            parent::json_response($responseDB, 201);
        } else {
            $endJSON['status'] = 'Error';
            $endJSON['data']['title'] = '';
            $endJSON['data']['message'] = 'No exist '.$_SERVER['REQUEST_METHOD'];
            parent::json_jwt_response($endJSON, 400);
        }
    }

    public function GetAvailablePayments() {
        if ('POST' === $_SERVER['REQUEST_METHOD']) {
            $validator = new Validator();
            $validation = $validator->make($_POST, [
                'pageNumber' => 'required|numeric',
                'pageSize' => 'required|numeric',
                'orderBy' => 'required'
            ]);

            $validation->validate();
            if ($validation->fails()) {
                $errors = $validation->errors();
                echo json_encode($errors->firstOfAll());
                exit;
            }

            $responseDB = $this->_model->GetAvailablePayments($_POST);
            parent::json_response($responseDB, 201);
        } else {
            $endJSON['status'] = 'Error';
            $endJSON['data']['title'] = '';
            $endJSON['data']['message'] = 'No exist '.$_SERVER['REQUEST_METHOD'];
            parent::json_jwt_response($endJSON, 400);
        }
    }


    public function GetCompanyAmmount()
    {
        if ('POST' === $_SERVER['REQUEST_METHOD']) {
            $validator = new Validator();
            $validation = $validator->make($_POST, [
               'Company_idCompany' => 'required|numeric',
            ]);
   
            $validation->validate();
            if ($validation->fails()) {
                $errors = $validation->errors();
                echo json_encode($errors->firstOfAll());
                exit;
            }

            $responseDB = $this->_model->GetCompanyAmmount($_POST);
            parent::json_response($responseDB, 201);
        } else {
            $endJSON['status'] = 'Error';
            $endJSON['data']['title'] = '';
            $endJSON['data']['message'] = 'No exist '.$_SERVER['REQUEST_METHOD'];
            parent::json_jwt_response($endJSON, 400);
        }
    }
}
