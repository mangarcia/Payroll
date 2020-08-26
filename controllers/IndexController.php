<?php



class IndexController extends Controller

{

  private $_model;





  public function __construct()

  {

    parent::__construct();



    $this->_model = $this->loadModel('Index');

  }





  public function index()

  {

    $session = Session::get("TellaConnected");

    

    if ($session) { $this->HomePage(); }

    else { $this->LoginPage(); }

  }





  private function LoginPage()

  {

    $this->_view->title_ = "Login";

    $this->_view->renderizar('login', 'user', false);

  }





  public function ResetPassword()

  {

    $this->_view->title_ = "Recuperar clave";

    $this->_view->renderizar('resetpassword', 'user', false);

  }





  public function HomePage()

  {

    $this->_view->title_ = "Home";

    $this->_view->renderizar('homepage', 'index');

  }





  public function CheckConnection()

  {

    echo "Connected";

  }



  public function SavePhoto($PhotoFile)

  {

    $randomNum = rand();

    $ImgName = substr(base64_encode($randomNum), -15);

    $PhotoFile["name"] = "Photo_" . $ImgName . "_" . rand() . ".png";

    if (file_exists("public/img/camera/" . $PhotoFile["name"])) {

      echo $PhotoFile["name"] . " already exists. ";

    } else {

      move_uploaded_file($PhotoFile["tmp_name"], "public/img/camera/" . $PhotoFile["name"]);

    }

    $PhotoUrl = "public/img/camera/" . $PhotoFile["name"];



    $imageBaseUrl = $_SERVER['HTTP_HOST'];



    $PhotoUrl = "http://" . $imageBaseUrl . "/TeellaHomeVisits/" . $PhotoUrl;



    return $PhotoUrl;

  }





  public function registerNewVisit()

  {

    $VisitDate         = $_POST['VisitDate'];

    $VisitNo           = $_POST['VisitNo'];

    $ApplicantName     = $_POST['ApplicantName'];

    $ApplicantDocument = $_POST['ApplicantDocument'];

    $ApplicantPhone    = $_POST['ApplicantPhone'];

    $ApplicantARL      = $_POST['ApplicantARL'];

    $ApplicantEPS      = $_POST['ApplicantEPS'];

    $ApplicantRUT      = $_POST['ApplicantRUT'];



    $ApplicantPhotoUrl = "";



    if (isset($_FILES['ApplicantPhotoUrl'])) {

      $ApplicantPhotoUrl = $this->SavePhoto($_FILES["ApplicantPhotoUrl"]);

    } else {

      $ApplicantPhotoUrl = "";

    }



    $ApplicantPhotoExterior = "";



    if (isset($_FILES['ApplicantPhotoExterior'])) {

      $ApplicantPhotoExterior = $this->SavePhoto($_FILES["ApplicantPhotoExterior"]);

    } else {

      $ApplicantPhotoExterior = "";

    }



    $ApplicantPhotoInterior1 = "";



    if (isset($_FILES['ApplicantPhotoInterior1'])) {

      $ApplicantPhotoInterior1 = $this->SavePhoto($_FILES["ApplicantPhotoInterior1"]);

    } else {

      $ApplicantPhotoInterior1 = "";

    }





    $ApplicantPhotoInterior2 = "";



    if (isset($_FILES['ApplicantPhotoInterior2'])) {

      $ApplicantPhotoInterior2 = $this->SavePhoto($_FILES["ApplicantPhotoInterior2"]);

    } else {

      $ApplicantPhotoInterior2 = "";

    }



    $VisitState                      = $_POST['VisitState'];

    $ApplicantMotivation             = $_POST['ApplicantMotivation'];

    $WhoAttendsVisit                 = $_POST['WhoAttendsVisit'];

    $WhoAttendsRelationship          = $_POST['WhoAttendsRelationship'];

    $WhoAttendsAddress               = $_POST['WhoAttendsAddress'];

    $WhoAttendsNeighborhood          = $_POST['WhoAttendsNeighborhood'];

    $WhoAttendsCellphone             = $_POST['WhoAttendsCellphone'];

    $WhoAttendsHousePhone            = $_POST['WhoAttendsHousePhone'];

    $WhoAttendsCivilState            = $_POST['WhoAttendsCivilState'];

    $WhoAttendsBornDate              = $_POST['WhoAttendsBornDate'];

    $Familiar1SamePlace              = $_POST['Familiar1SamePlace'];

    $Familiar1Name                   = $_POST['Familiar1Name'];

    $Familiar1CivilState             = $_POST['Familiar1CivilState'];

    $Familiar1BornDate               = $_POST['Familiar1BornDate'];

    $Familiar1Schoolarity            = $_POST['Familiar1Schoolarity'];

    $Familiar1WorkState              = $_POST['Familiar1WorkState'];

    $Familiar2SamePlace              = $_POST['Familiar2SamePlace'];

    $Familiar2Name                   = $_POST['Familiar2Name'];

    $Familiar2CivilState             = $_POST['Familiar2CivilState'];

    $Familiar2BornDate               = $_POST['Familiar2BornDate'];

    $Familiar2Schoolarity            = $_POST['Familiar2Schoolarity'];

    $Familiar2WorkState              = $_POST['Familiar2WorkState'];

    $Familiar3SamePlace              = $_POST['Familiar3SamePlace'];

    $Familiar3Name                   = $_POST['Familiar3Name'];

    $Familiar3CivilState             = $_POST['Familiar3CivilState'];

    $Familiar3BornDate               = $_POST['Familiar3BornDate'];

    $Familiar3Schoolarity            = $_POST['Familiar3Schoolarity'];

    $Familiar3WorkState              = $_POST['Familiar3WorkState'];

    $Familiar4SamePlace              = $_POST['Familiar4SamePlace'];

    $Familiar4Name                   = $_POST['Familiar4Name'];

    $Familiar4CivilState             = $_POST['Familiar4CivilState'];

    $Familiar4BornDate               = $_POST['Familiar4BornDate'];

    $Familiar4Schoolarity            = $_POST['Familiar4Schoolarity'];

    $Familiar4WorkState              = $_POST['Familiar4WorkState'];

    $FamilyGroup                     = $_POST['FamilyGroup'];

    $HouseType                       = $_POST['HouseType'];

    $PlaceWhereLive                  = $_POST['PlaceWhereLive'];

    $PlaceLocation                   = $_POST['PlaceLocation'];

    $FamiliesGroupsAmmount           = $_POST['FamiliesGroupsAmmount'];

    $PeopleAmmountLivingAtHouse      = $_POST['PeopleAmmountLivingAtHouse'];

    $HouseSizeForPeople              = $_POST['HouseSizeForPeople'];

    $HouseConditions                 = $_POST['HouseConditions'];

    $HouseConditionExplanaiton       = $_POST['HouseConditionExplanaiton'];

    $ApplicantBeforeOwnHouse         = $_POST['ApplicantBeforeOwnHouse'];

    $OwnerName                       = $_POST['OwnerName'];

    $OwnerRelationship               = $_POST['OwnerRelationship'];

    $OwnerHouseType                  = $_POST['OwnerHouseType'];

    $OwnerTimeAgo                    = $_POST['OwnerTimeAgo'];

    $OwnerPlaceWhereWas              = $_POST['OwnerPlaceWhereWas'];

    $OwnerSellReason                 = $_POST['OwnerSellReason'];

    $FamilyBasicsNeededs             = $_POST['FamilyBasicsNeededs'];

    $AnotherFamiliarHaveExtraWork    = $_POST['AnotherFamiliarHaveExtraWork'];

    $AnotherFamiliarWorkExplanation  = $_POST['AnotherFamiliarWorkExplanation'];

    $AnotherFamiliarWorkName         = $_POST['AnotherFamiliarWorkName'];

    $AnotherFamiliarWorkActivityName = $_POST['AnotherFamiliarWorkActivityName'];

    $AnotherFamiliarWorkMoney        = $_POST['AnotherFamiliarWorkMoney'];

    $HomeEconomyState                = $_POST['HomeEconomyState'];

    $PendingDocument                 = $_POST['PendingDocument'];

    $PendingDocumentDescription      = $_POST['PendingDocumentDescription'];

    $PendingDocumentDate             = $_POST['PendingDocumentDate'];

    $PrewiewVisitInfo                = $_POST['PrewiewVisitInfo'];

    $PreviewVisitDescription         = $_POST['PreviewVisitDescription'];

    $TrulyInfoOnVisit                = $_POST['TrulyInfoOnVisit'];

    $InqueryOnVisit                  = $_POST['InqueryOnVisit'];

    $SocialEvaluation                = $_POST['SocialEvaluation'];



    $this->_model->registerNewVisit(

      $VisitDate,

      $VisitNo,

      $ApplicantName,

      $ApplicantDocument,

      $ApplicantPhone,

      $ApplicantARL,

      $ApplicantEPS,

      $ApplicantRUT,

      $ApplicantPhotoUrl,

      $ApplicantPhotoExterior,

      $ApplicantPhotoInterior1,

      $ApplicantPhotoInterior2,

      $VisitState,

      $ApplicantMotivation,

      $WhoAttendsVisit,

      $WhoAttendsRelationship,

      $WhoAttendsAddress,

      $WhoAttendsNeighborhood,

      $WhoAttendsCellphone,

      $WhoAttendsHousePhone,

      $WhoAttendsCivilState,

      $WhoAttendsBornDate,

      $Familiar1SamePlace,

      $Familiar1Name,

      $Familiar1CivilState,

      $Familiar1BornDate,

      $Familiar1Schoolarity,

      $Familiar1WorkState,

      $Familiar2SamePlace,

      $Familiar2Name,

      $Familiar2CivilState,

      $Familiar2BornDate,

      $Familiar2Schoolarity,

      $Familiar2WorkState,

      $Familiar3SamePlace,

      $Familiar3Name,

      $Familiar3CivilState,

      $Familiar3BornDate,

      $Familiar3Schoolarity,

      $Familiar3WorkState,

      $Familiar4SamePlace,

      $Familiar4Name,

      $Familiar4CivilState,

      $Familiar4BornDate,

      $Familiar4Schoolarity,

      $Familiar4WorkState,

      $FamilyGroup,

      $HouseType,

      $PlaceWhereLive,

      $PlaceLocation,

      $FamiliesGroupsAmmount,

      $PeopleAmmountLivingAtHouse,

      $HouseSizeForPeople,

      $HouseConditions,

      $HouseConditionExplanaiton,

      $ApplicantBeforeOwnHouse,

      $OwnerName,

      $OwnerRelationship,

      $OwnerHouseType,

      $OwnerTimeAgo,

      $OwnerPlaceWhereWas,

      $OwnerSellReason,

      $FamilyBasicsNeededs,

      $AnotherFamiliarHaveExtraWork,

      $AnotherFamiliarWorkExplanation,

      $AnotherFamiliarWorkName,

      $AnotherFamiliarWorkActivityName,

      $AnotherFamiliarWorkMoney,

      $HomeEconomyState,

      $PendingDocument,

      $PendingDocumentDescription,

      $PendingDocumentDate,

      $PrewiewVisitInfo,

      $PreviewVisitDescription,

      $TrulyInfoOnVisit,

      $InqueryOnVisit,

      $SocialEvaluation

    );

    //

  }





  public function getCitiesLocations()

  {

    $data = $_POST;

      $responseDB = $this->_model->getCitiesLocations();

      

      parent::json_jwt_response($responseDB, 200);
 

  }



}

