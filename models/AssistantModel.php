<?php

use \Firebase\JWT\JWT;
 

class AssistantModel extends Database
{
  public function __construct()
  {
    
    parent::__construct();
  }

  private function issetData($var = null)
  {
    return (isset($var)) ? $var : NULL;
  }


  public function validateCheckbox($value)
  {
       if($value=="on")
      {
          return 1;
      }
      else
      {
        return 0;
      }
  }

 public function updateImageUrl(array $data,$companyName)
  {

    $endJSON["status"] = "";
    $endJSON["data"] = "";

    $AssistantId        = $this->issetData($data['assistantId']);
    $basicDataPhoto      = $this->issetData($data['basicDataPhoto']);
   if($basicDataPhoto!=null)
    {
      $basicDataPhoto   = $this->getImageUrl($basicDataPhoto,$companyName,$data['basicDataDocNumber']);
    }
    else
    {
       $endJSON['status'] = 'error';
       $endJSON["message"] = "Missing Image";
      return;
    }

    $query= "update Assistant set basicDataPhoto='".$basicDataPhoto."' where assistantId='".$AssistantId."'";
     $QueryCallback = $this->ExecuteSql($query);
        if ($QueryCallback) {
           $endJSON["status"] = "success";
          $endJSON["message"] = "Imagen actualizada correctamente";
        } else {

            $endJSON['status'] = 'error';
           $endJSON["message"] = "Image Couldnt Save";

        }

        return $endJSON;
  }


public function updateAssistantAvailability(array $data)
{

    $endJSON["status"] = "";
    $endJSON["data"] = "";

    $AssistantId        = $this->issetData($data['assistantId']);
    $AvailabilityDate   = $this->issetData($data['AvailabilityDate']);
    $morningStatus      = $this->issetData($data['morningStatus']);
    $eveningStatus      = $this->issetData($data['eveningStatus']);
    $requestId          = $this->issetData($data['requestId']);

    if($AssistantId==null && $AvailabilityDate==null)
    {
      $endJSON['status'] = 'error';
      $endJSON["data"] = "Invalid params for method";
      return $endJSON;
    }

    if($morningStatus==null && $eveningStatus==null)
    {
       $endJSON['status'] = 'error';
       $endJSON["data"] = "Invalid params for method";
       return $endJSON;
    }

     if($AssistantId==null && $AvailabilityDate==null)
    {
      $endJSON['status'] = 'error';
      $endJSON["data"] = "Invalid params for methdd";
      return $endJSON;
    }

    $morningId=$this->GetOne("select idAvailability from Availability where AvailabilityDate='$AvailabilityDate' and AvailabilityJourney='Mañana' and Availability_AssistantId='$AssistantId'");

     $lateId=$this->GetOne("select idAvailability from Availability where AvailabilityDate='$AvailabilityDate' and AvailabilityJourney='Noche' and Availability_AssistantId='$AssistantId'");

     if($morningStatus)
     {
          if($morningId!="No registry found")
        {
          if($requestId)
          {
            $query= "update Availability set Availability_Request_Id='$requestId' ,AvailabilityStatus='Ocupado' where idAvailability='$morningId'";
          }
          else
          {
            $query= "update Availability set AvailabilityStatus='$morningStatus' where idAvailability='$morningId'";
          }
          
        }
        else
        {
          $query= "insert into Availability values(NULL,'$AvailabilityDate','Mañana','$morningStatus','$AssistantId',NULL)";
        } 

        $morningQuery=$this->ExecuteSql($query);
        
          if(!$morningQuery)
          {
              $endJSON['status'] = 'error';
              $endJSON["data"] = "Query Error ";
             
           }
           else
           {
              $endJSON["status"] = "success";
              $endJSON["data"] = "Se modifico la informacion de disponibilidad";
           }
        }
    
    if($eveningStatus)
    {
       if($lateId!="No registry found")
        {
          if($requestId)
          {
            $query= "update Availability set Availability_Request_Id='$requestId' ,AvailabilityStatus='Ocupado' where idAvailability='$lateId'";
          }
          else
          {
            $query= "update Availability set AvailabilityStatus='$eveningStatus' where idAvailability='$lateId'";
          }
          
        }
        else
        {
          $query= "insert into Availability values(NULL,'$AvailabilityDate','Noche','$eveningStatus','$AssistantId',NULL)";
        }

         $eveningQuery=$this->ExecuteSql($query);
          if(!$eveningQuery)
          {
              $endJSON['status'] = 'error';
              $endJSON["data"] = "Query Error ";
             
           }
           else
           {
              $endJSON["status"] = "success";
              $endJSON["data"] = "Se modifico la informacion de disponibilidad";
           }
    }

   return $endJSON;

}


 public function updateDocumentUrl(array $data,$companyName)
  {

    $endJSON["status"] = "";
    $endJSON["data"] = "";

    $AssistantId        = $this->issetData($data['assistantId']);
    $professionalHVUrl      = $this->issetData($data['professionalHVUrl']);
   if($professionalHVUrl!=null)
    {
      $professionalHVUrl   = $this->getFileUrl($basicDataPhoto,$companyName,$data['basicDataDocNumber']);
    }
    else
    {
       $endJSON['status'] = 'error';
       $endJSON["message"] = "Missing Document";
      return;
    }

    $query= "update Assistant set professionalHVUrl='".$professionalHVUrl."' where assistantId='".$AssistantId."'";
     $QueryCallback = $this->ExecuteSql($query);
        if ($QueryCallback) {
           $endJSON["status"] = "success";
          $endJSON["message"] = "Documento actualizado correctamente";
        } else {

            $endJSON['status'] = 'error';
           $endJSON["message"] = "El documento no se pudo actualizar";

        }

        return $endJSON;
  }



  public function registerNewAssistant(array $data,$companyId,$companyName,$assistantHV,$profileImage)
  {
    //echo "Cantidad de variables post ".count($data)."\n\r".json_encode($data);

    $endJSON["status"] = "";
    $endJSON["data"] = "";
    try {
        
        $userId                                                  = $this->issetData($data['userId']);
        $DocType_DocTypeId                                       = $this->issetData($data['DocType_DocTypeId']);
        $basicDataDocNumber                                      = $this->issetData($data['basicDataDocNumber']);
        $basicDataFirstName                                      = $this->issetData($data['basicDataFirstName']);
        $basicDataLastName                                       = $this->issetData($data['basicDataLastName']);
        $basicDataPhoto                                          = $this->issetData($data['basicDataPhoto']);
        $assistantHV                                             = $this->issetData($data['professionalHVUrl']);
        $defaultWorkTime                                         = $this->issetData($data['defaultWorkTime']);
       
        if($basicDataPhoto!=null)
        {
          $basicDataPhoto   = $this->getImageUrl($basicDataPhoto,$companyName,$basicDataDocNumber);
        }


        $Sex_sexId                                               = $this->issetData($data['Sex_sexId']);  
        $personalDataTelephone                                   = $this->issetData($data['personalDataTelephone']);
        $personalDataCellphone                                   = $this->issetData($data['personalDataCellphone']);


        $personalDataAddress                                     = $this->issetData($data['personalDataAddress']);
        $personalDataAddressComplement                           = $this->issetData($data['personalDataAddressComplement']);
        $personalDataEmailAddress                                = $this->issetData($data['personalDataEmailAddress']);



        $personalDataAddressCity                                 = $this->issetData($data['personalDataAddressCity']);
        $personalDataAddressLocality                             = $this->issetData($data['personalDataAddressLocality']);
        $basicDataBirthDate                                      = $this->issetData($data['basicDataBirthDate']);

        $basicDataBirthPlaceCity                                 = $this->issetData($data['basicDataBirthPlaceCity']);

       
        $socialBenefitsARL                                       = $this->issetData($data['socialBenefitsARL']);
        $socialBenefitsHealth                                    = $this->issetData($data['socialBenefitsHealth']);
        $socialBenefitsPension                                   = $this->issetData($data['socialBenefitsPension']);


        $educationAcademicLevel                                  = $this->issetData($data['educationAcademicLevel']);
        $professionalJobTitle                                    = $this->issetData($data['professionalJobTitle']);
        $professionalSalaryAspiration                            = $this->issetData($data['professionalSalaryAspiration']);
        $professionalResume                                      = $this->issetData($data['professionalResume']);


       if($Sex_sexId==null)
        {
          $endJSON['status'] = 'error';
          $endJSON["message"] = "Debe seleccionar un Genero";
          return $endJSON;
        }


        if($educationAcademicLevel==null)
        {
          $endJSON['status'] = 'error';
          $endJSON["message"] = "Debe seleccionar un nivel de escolaridad";
          return $endJSON;
        }

         if($professionalSalaryAspiration==null)
        {
          $endJSON['status'] = 'error';
          $endJSON["message"] = "Debe ingresar la aspiracion salarial";
          return $endJSON;
        }

         if($educationAcademicLevel==null)
        {
          $endJSON['status'] = 'error';
          $endJSON["message"] = "Debe seleccionar un nivel de escolaridad";
          return $endJSON;
        }

        if(isset($data['paymentFifteen']))
        {
           $paymentFifteen                                          = $this->validateCheckbox($data['paymentFifteen']);
        }
        else
        {
           $paymentFifteen =0;
        }

         if(isset($data['experienceMovility']))
        {
            $experienceMovility                                      = $this->validateCheckbox($data['experienceMovility']);
        }
         else
        {
           $experienceMovility =0;
        }
       
        if(isset($data['experienceCateter']))
        {
            $experienceCateter                                      = $this->validateCheckbox($data['experienceCateter']);
        }
         else
        {
           $experienceCateter =0;
        }

         if(isset($data['experienceTraqueo']))
        {
            $experienceTraqueo                                      = $this->validateCheckbox($data['experienceTraqueo']);
        }
         else
        {
           $experienceTraqueo =0;
        }

         if(isset($data['experienceIntraVain']))
        {
            $experienceIntraVain                                      = $this->validateCheckbox($data['experienceIntraVain']);
        }
         else
        {
           $experienceIntraVain =0;
        }

        $professionalHVUrl                                       = $this->issetData($assistantHV);

        if($professionalHVUrl!=null)
        {
          $professionalHVUrl   = $this->getFileUrl($professionalHVUrl,$companyName,$basicDataDocNumber."_". $basicDataFirstName."_".$basicDataLastName);
        }

     
        $bossObservation                                         = $this->issetData($data['bossObservation']);
        $companyBeginDate                                        = $this->issetData($data['companyBeginDate']);

        if($companyId=="")
        {
          $query = "insert into Assistant(DocType_DocTypeId,basicDataDocNumber,basicDataFirstName,basicDataLastName,basicDataPhoto,Sex_sexId,personalDataTelephone,personalDataCellphone,personalDataAddress,personalDataAddressComplement,personalDataEmailAddress,personalDataAddressCity,personalDataAddressLocality,basicDataBirthDate,basicDataBirthPlaceCity,socialBenefitsARL,socialBenefitsHealth,socialBenefitsPension,educationAcademicLevel,professionalJobTitle,professionalSalaryAspiration,professionalResume,paymentFifteen,professionalHVUrl,experienceMovility,bossObservation,experienceCateter,experienceTraqueo,experienceIntraVain,companyBeginDate,registeredByUserId,companyId,defaultWorkTime)
          values
          ('".$DocType_DocTypeId."','".$basicDataDocNumber."','".$basicDataFirstName."','".$basicDataLastName."','".$basicDataPhoto."','".$Sex_sexId."','".$personalDataTelephone."','".$personalDataCellphone."','".$personalDataAddress."','".$personalDataAddressComplement."','".$personalDataEmailAddress."','".$personalDataAddressCity."','".$personalDataAddressLocality."','".$basicDataBirthDate."','".$basicDataBirthPlaceCity."','".$socialBenefitsARL."','".$socialBenefitsHealth."','".$socialBenefitsPension."','".$educationAcademicLevel."','".$professionalJobTitle."','".$professionalSalaryAspiration."','".$professionalResume."','".$paymentFifteen."','".$professionalHVUrl."','".$experienceMovility."','".$bossObservation."','".$experienceCateter."','".$experienceTraqueo."','".$experienceIntraVain."','".$companyBeginDate."','".$userId."',NULL,'".$defaultWorkTime."')";
          
        }
        else
        {
          $query = "insert into Assistant(DocType_DocTypeId,basicDataDocNumber,basicDataFirstName,basicDataLastName,basicDataPhoto,Sex_sexId,personalDataTelephone,personalDataCellphone,personalDataAddress,personalDataAddressComplement,personalDataEmailAddress,personalDataAddressCity,personalDataAddressLocality,basicDataBirthDate,basicDataBirthPlaceCity,socialBenefitsARL,socialBenefitsHealth,socialBenefitsPension,educationAcademicLevel,professionalJobTitle,professionalSalaryAspiration,professionalResume,paymentFifteen,professionalHVUrl,experienceMovility,bossObservation,experienceCateter,experienceTraqueo,experienceIntraVain,companyBeginDate,registeredByUserId,companyId,defaultWorkTime)
          values
          ('".$DocType_DocTypeId."','".$basicDataDocNumber."','".$basicDataFirstName."','".$basicDataLastName."','".$basicDataPhoto."','".$Sex_sexId."','".$personalDataTelephone."','".$personalDataCellphone."','".$personalDataAddress."','".$personalDataAddressComplement."','".$personalDataEmailAddress."','".$personalDataAddressCity."','".$personalDataAddressLocality."','".$basicDataBirthDate."','".$basicDataBirthPlaceCity."','".$socialBenefitsARL."','".$socialBenefitsHealth."','".$socialBenefitsPension."','".$educationAcademicLevel."','".$professionalJobTitle."','".$professionalSalaryAspiration."','".$professionalResume."','".$paymentFifteen."','".$professionalHVUrl."','".$experienceMovility."','".$bossObservation."','".$experienceCateter."','".$experienceTraqueo."','".$experienceIntraVain."','".$companyBeginDate."','".$userId."','".$companyId."','".$defaultWorkTime."')";
          
        }
       
//echo $query;
        $QueryCallback = $this->ExecuteSql($query);
        if ($QueryCallback) {

          $AssistantId=$this->GetOne("SELECT LAST_INSERT_ID();");
          date_default_timezone_set('America/Bogota');
          $currDay= date("d");
          $currMonth=date("m");
          $currYear=date("Y");
          $daysInMonth= cal_days_in_month(CAL_GREGORIAN,$currMonth,$currYear);


          for ($i = $currDay; $i <= $daysInMonth; $i++)
           {
             
            $currDate=$currYear."-".$currMonth."-".$i;
            $query="Insert into Availability values(NULL,'$currDate','$defaultWorkTime','Disponible','$AssistantId',NULL)";
            $QueryCallback = $this->ExecuteSql($query);
          }

           $endJSON["status"] = "success";
          $endJSON["message"] = "Asistente creada correctamente";
        } else {
          throw new Exception('{"Error":"SQL registerNewAssistant"}', 1);
        }
      
    } catch (Exception $e) {
      http_response_code(400);
      $endJSON['status'] = 'error';
      die($e->getMessage());
    }
    return $endJSON;
  }

  public function updateNewAssistant(array $data,$companyId,$companyName,$assistantHV,$profileImage)
  {
    //echo "Cantidad de variables post ".count($data)."\n\r".json_encode($data);

    $endJSON["status"] = "";
    $endJSON["data"] = "";

    $query ="update Assistant set ";
    try {
        

        $DocType_DocTypeId                                       = $this->issetData($data['DocTypeId']);
        if($DocType_DocTypeId!=null)
        {
           $query .="DocType_DocTypeId='$DocType_DocTypeId',";
        }



        $basicDataDocNumber                                      = $this->issetData($data['basicDataDocNumber']);
        if($basicDataDocNumber!=null)
        {
           $query .="basicDataDocNumber='$basicDataDocNumber',";
        }

        
        $defaultWorkTime                                      = $this->issetData($data['defaultWorkTime']);
        if($defaultWorkTime!=null)
        {
           $query .="defaultWorkTime='$defaultWorkTime',";
        }


        
        $basicDataFirstName                                      = $this->issetData($data['basicDataFirstName']);
        if($basicDataFirstName!=null)
        {
           $query .="basicDataFirstName='$basicDataFirstName',";
        }


        $basicDataLastName                                       = $this->issetData($data['basicDataLastName']);
        if($basicDataLastName!=null)
        {
           $query .="basicDataLastName='$basicDataLastName',";
        }



        $basicDataPhoto                                          = $this->issetData($data['basicDataPhoto']);
        if($basicDataPhoto!=null)
        {
          $basicDataPhoto   = $this->getImageUrl($basicDataPhoto,$companyName,$basicDataDocNumber);
          $query .="basicDataPhoto='$basicDataPhoto',";
        }


        $Sex_sexId                                               = $this->issetData($data['Sex_sexId']);  


        $personalDataTelephone                                   = $this->issetData($data['personalDataTelephone']);
        if($personalDataTelephone!=null)
        {
          $query .="personalDataTelephone='$personalDataTelephone',";
        }



        $personalDataCellphone                                   = $this->issetData($data['personalDataCellphone']);
        if($personalDataCellphone!=null)
        {
          $query .="personalDataCellphone='$personalDataCellphone',";
        }

        $personalDataAddress                                     = $this->issetData($data['personalDataAddress']);
          if($personalDataAddress!=null)
        {
          $query .="personalDataAddress='$personalDataAddress',";
        }


        $personalDataAddressComplement                           = $this->issetData($data['personalDataAddressComplement']);
          if($personalDataAddressComplement!=null)
        {
          $query .="personalDataAddressComplement='$personalDataAddressComplement',";
        }



        $personalDataEmailAddress                                = $this->issetData($data['personalDataEmailAddress']);
          if($personalDataEmailAddress!=null)
        {
          $query .="personalDataEmailAddress='$personalDataEmailAddress',";
        }



        $personalDataAddressCity                                 = $this->issetData($data['personalDataAddressCity']);
          if($personalDataAddressCity!=null)
        {
          $query .="personalDataAddressCity='$personalDataAddressCity',";
        }


        $personalDataAddressLocality                             = $this->issetData($data['personalDataAddressLocality']);
          if($personalDataAddressLocality!=null)
        {
          $query .="personalDataAddressLocality='$personalDataAddressLocality',";
        }


        $basicDataBirthDate                                      = $this->issetData($data['basicDataBirthDate']);
          if($basicDataBirthDate!=null)
        {
          $query .="basicDataBirthDate='$basicDataBirthDate',";
        }



        $basicDataBirthPlaceCity                                 = $this->issetData($data['basicDataBirthPlaceCity']);
        if($basicDataBirthPlaceCity!=null)
        {
          $query .="basicDataBirthPlaceCity='$basicDataBirthPlaceCity',";
        }

       
        $socialBenefitsARL                                       = $this->issetData($data['socialBenefitsARL']);
          if($socialBenefitsARL!=null)
        {
          $query .="socialBenefitsARL='$socialBenefitsARL',";
        }


        $socialBenefitsHealth                                    = $this->issetData($data['socialBenefitsHealth']);
          if($socialBenefitsHealth!=null)
        {
          $query .="socialBenefitsHealth='$socialBenefitsHealth',";
        }


        $socialBenefitsPension                                   = $this->issetData($data['socialBenefitsPension']);
  if($socialBenefitsPension!=null)
        {
          $query .="socialBenefitsPension='$socialBenefitsPension',";
        }



        $educationAcademicLevel                                  = $this->issetData($data['educationAcademicLevel']);
          if($educationAcademicLevel!=null)
        {
          $query .="educationAcademicLevel='$educationAcademicLevel',";
        }


        $professionalJobTitle                                    = $this->issetData($data['professionalJobTitle']);
          if($professionalJobTitle!=null)
        {
          $query .="professionalJobTitle='$professionalJobTitle',";
        }


        $professionalSalaryAspiration                            = $this->issetData($data['professionalSalaryAspiration']);
          if($professionalSalaryAspiration!=null)
        {
          $query .="professionalSalaryAspiration='$professionalSalaryAspiration',";
        }


        $professionalResume                                      = $this->issetData($data['professionalResume']);
  if($professionalResume!=null)
        {
          $query .="professionalResume='$professionalResume',";
        }


       if($Sex_sexId==null)
        {
          $endJSON['status'] = 'error';
          $endJSON["message"] = "Debe seleccionar un Genero";
          return $endJSON;
        }
        else
        {
          $query .="Sex_sexId='$Sex_sexId',";
        }


        if($educationAcademicLevel==null)
        {
          $endJSON['status'] = 'error';
          $endJSON["message"] = "Debe seleccionar un nivel de escolaridad";
          return $endJSON;
        }
         else
        {
          $query .="educationAcademicLevel='$educationAcademicLevel',";
        }

         if($professionalSalaryAspiration==null)
        {
          $endJSON['status'] = 'error';
          $endJSON["message"] = "Debe ingresar la aspiracion salarial";
          return $endJSON;
        }
         else
        {
          $query .="professionalSalaryAspiration='$professionalSalaryAspiration',";
        }

         if($educationAcademicLevel==null)
        {
          $endJSON['status'] = 'error';
          $endJSON["message"] = "Debe seleccionar un nivel de escolaridad";
          return $endJSON;
        }
         else
        {
          $query .="educationAcademicLevel='$educationAcademicLevel',";
        }


        if(isset($data['paymentFifteen']))
        {
           $paymentFifteen                                          = $this->validateCheckbox($data['paymentFifteen']);
        }
        else
        {
           $paymentFifteen =0;
        }

        $query .="paymentFifteen='$paymentFifteen',";


         if(isset($data['experienceMovility']))
        {
            $experienceMovility                                      = $this->validateCheckbox($data['experienceMovility']);
        }
         else
        {
           $experienceMovility =0;
        }

        $query .="experienceMovility='$experienceMovility',";


       
        if(isset($data['experienceCateter']))
        {
            $experienceCateter                                      = $this->validateCheckbox($data['experienceCateter']);
        }
         else
        {
           $experienceCateter =0;
        }
         $query .="experienceCateter='$experienceCateter',";



         if(isset($data['experienceTraqueo']))
        {
            $experienceTraqueo                                      = $this->validateCheckbox($data['experienceTraqueo']);
        }
         else
        {
           $experienceTraqueo =0;
        }

         $query .="experienceTraqueo='$experienceTraqueo',";



         if(isset($data['experienceIntraVain']))
        {
            $experienceIntraVain                                      = $this->validateCheckbox($data['experienceIntraVain']);
        }
         else
        {
           $experienceIntraVain =0;
        }

         $query .="experienceIntraVain='$experienceIntraVain',";


         if(isset($data['professionalHVUrl']))
         {
           $professionalHVUrl                                       = $data['professionalHVUrl'];
         
       

            if($professionalHVUrl!=null)
            {
              $professionalHVUrl   = $this->getFileUrl($professionalHVUrl,$companyName,$basicDataDocNumber."_". $basicDataFirstName."_".$basicDataLastName);
               $query .="professionalHVUrl='$professionalHVUrl',";
            }
        }
     
        $bossObservation                                         = $this->issetData($data['bossObservation']);
          if($bossObservation!=null)
        {
          $query .="bossObservation='$bossObservation',";
        }


        $companyBeginDate                                        = $this->issetData($data['companyBeginDate']);
          if($companyBeginDate!=null)
        {
          $query .="companyBeginDate='$companyBeginDate',";
        }

        $query=rtrim($query,",");

        $query .= " where assistantId='".$data['assistantId']."'";

//echo $query;
        $QueryCallback = $this->ExecuteSql($query);
        if ($QueryCallback) {
           $endJSON["status"] = "success";
          $endJSON["message"] = "Asistente Actualizada correctamente";
        } else {
          throw new Exception('{"Error":"SQL registerNewAssistant"}', 1);
        }
      
    } catch (Exception $e) {
      http_response_code(400);
      $endJSON['status'] = 'error';
      die($e->getMessage());
    }
    return $endJSON;
  }


  public function getAllAssistant()
  {
    $endJSON["status"] = "";
    $endJSON["data"] = "";
    try {
      $companyId=Session::get("companyId");
      $query = "SELECT * FROM Assistant ORDER BY assistantId where companyId= $companyId;";
      $SelectAllResult = $this->GetAll($query);
      if ($SelectAllResult) {
         $endJSON["status"] = "success";
        $endJSON["data"] = $SelectAllResult;
      } else {
        throw new Exception("Error: SQL getAllAssistant", 1);
      }
    } catch (Exception $e) {
      http_response_code(400);
      $endJSON['status'] = 'error';
      die($e->getMessage());
    }
    return $endJSON;
  }


  public function  getAssistants (array $data)
  {
    $endJSON["status"] = "";
    $endJSON["data"] = "";
    try {
      if (isset($data['userId'])) {
        $userId = $data['userId'];

        if(Session::get("RoleId")==8)
        {
          $query="SELECT DISTINCT Assistant.*, Sex.name as sex, AcademicLevel.name as 'School',Company.name as CompanyName FROM Assistant,User,Sex,Company,AcademicLevel WHERE User.Company_companyId=Assistant.companyId and Sex.sexId=Sex_sexId and AcademicLevel.academicLevelId=educationAcademicLevel and Company.companyId=Assistant.companyId  ORDER BY `Assistant`.`assistantId` ASC";
        }
        else
        {
           $query="SELECT DISTINCT Assistant.*, Sex.name as sex, AcademicLevel.name as 'School',Company.name as CompanyName FROM Assistant,User,Sex,Company,AcademicLevel WHERE User.Company_companyId=Assistant.companyId and Sex.sexId=Sex_sexId and AcademicLevel.academicLevelId=educationAcademicLevel and Company.companyId=Assistant.companyId and User.userId = '$userId'  ORDER BY `Assistant`.`assistantId` ASC";
        }

        $SelectAllResult = $this->GetAll($query);
        if ($SelectAllResult) {
           $endJSON["status"] = "success";

          if(!is_array($SelectAllResult))
          {
            return $endJSON;
          }
          $ocupiedColor="#FF4000";
          $availableColor="#298A08";
          $noWorkColor="#848484";

          foreach ($SelectAllResult as &$value)
          {
            $AssistantId=$value['assistantId'];
            $availabelQuery="select * from Availability where Availability_AssistantId=$AssistantId and Availability_Request_Id is null";
            $responseQuery=$this->GetAll($availabelQuery);

            $endResponse=array();



            if(is_array($responseQuery) || is_object($responseQuery))
            {
              foreach ($responseQuery as &$currAvailability)
              {
                 if($currAvailability["AvailabilityStatus"]=="No Labora")
                {
                    $currentColor=$noWorkColor;
                }
                else
                {
                    $currentColor=$availableColor;
                }
                 
                  $title=$currAvailability["AvailabilityJourney"]." ".$currAvailability["AvailabilityStatus"];
                  $start=$currAvailability["AvailabilityDate"];
                  $end=$currAvailability["AvailabilityDate"];
                  
                  array_push($endResponse,array("title"=>$title,"start"=>$start,"end"=>$end,"backgroundColor"=>$currentColor));
              }
            }
                    
         $availabelQuery="SELECT DISTINCT concat(Family.basicDataFirstName,' ',Family.basicDataLastName) as 'pacient', Availability.* FROM `Availability`,Family,Request WHERE Family.memberId=Request.Family_userServiceId and Request.requestId=Availability.Availability_Request_Id and Availability.Availability_AssistantId=$AssistantId";
          $responseQuery=$this->GetAll($availabelQuery);


          if(is_array($responseQuery) || is_object($responseQuery))
            {
              foreach ($responseQuery as &$currAvailability)
              {
               
 $currentColor=$ocupiedColor;
                 
                  $title=$currAvailability["AvailabilityJourney"]." ".$currAvailability["pacient"];
                  $start=$currAvailability["AvailabilityDate"];
                  $end=$currAvailability["AvailabilityDate"];
                 
                  array_push($endResponse,array("title"=>$title,"start"=>$start,"end"=>$end,"backgroundColor"=>$currentColor));
              }
            }
           


           

            $value["Availability"]=$endResponse;
          }
          $endJSON["data"] = $SelectAllResult;
        } else {
          throw new Exception("Error: SQL getAssistant id", 1);
        }
      } 
    } catch (Exception $e) {
      http_response_code(400);
      $endJSON['status'] = 'error';
      die($e->getMessage());
    }
    return $endJSON;
  }

  public function updateAssistant(array $data)
  {
    $endJSON["status"] = "";
    $endJSON["data"] = "";
    try {
      if (isset($data['userId'])) {

        if (isset($data['email'])) {
          $endJSON = $this->updateEmailAssistant($data);
        }

        if (isset($data['password'])) {
          $endJSON = $this->updatePasswordAssistant($data);
        }

        if (isset($data['isOnline'])) {
          $endJSON = $this->updateIsOnline($data);
        }

        if (isset($data['rolId'])) {
          $endJSON = $this->updateRol($data);
        }

        if (isset($data['photoURL'])) {
          $endJSON = $this->updatePhotoURL($data);
        }

        if (isset($data['statusId'])) {
          $endJSON = $this->updateStatusAssistant($data);
        }

        if (isset($data['tokenFacebook'])) {
          $endJSON = $this->updateTokenFacebook($data);
        }

        if (isset($data['tokenGoogle'])) {
          $endJSON = $this->updateTokenGoogle($data);
        }

        // else {
        //   throw new Exception("Error: invalid parameters for without data", 1);
        // }
      } else {
        throw new Exception('{"Error":"No data user ID"}', 1);
      }
    } catch (Exception $e) {
      $endJSON['status'] = 'error';
      http_response_code(400);
      die($e->getMessage());
    }
    return $endJSON;
  }

  public function updateEmailAssistant(array $data)
  {
    $endJSON["status"] = "";
    $endJSON["data"] = "";
    try {
      if (
        isset($data['userId']) &&
        isset($data['email'])
      ) {
        $userId = $data['userId'];
        $accountEmail = $data['email'];
        $query = "UPDATE `Assistant` SET accountEmail = '$accountEmail' WHERE userId = '$userId';";
        $QueryCallback = $this->ExecuteSql($query);
        if ($QueryCallback) {
          $endJSON["status"] = "Success updated email";
          $endJSON["data"] = $data;
        } else {
          throw new Exception("Error: SQL updateEmailAssistant", 1);
        }
      } else {
        throw new Exception("Error: invalid parameters for Assistant", 1);
      }
    } catch (Exception $e) {
      $endJSON['status'] = 'error';
      http_response_code(400);
      die($e->getMessage());
    }
    return $endJSON;
  }


  public function updatePasswordAssistant(array $data)
  {
    $endJSON["status"] = "";
    $endJSON["data"] = "";
    try {
      if (
        isset($data['userId']) &&
        isset($data['password'])
      ) {
        $userId = $data['userId'];
        $accountPassword = $this->generateHashPass($data['password']);
        $query = "UPDATE `Assistant` SET accountPassword = '$accountPassword' WHERE userId = '$userId';";
        $QueryCallback = $this->ExecuteSql($query);
        if ($QueryCallback) {
          $endJSON["status"] = "Success updated password";
          unset($data['password']);
          $endJSON["data"] = $data;
        } else {
          throw new Exception("Error: SQL updatePasswordAssistant", 1);
        }
      } else {
        throw new Exception("Error: invalid parameters for Assistant", 1);
      }
    } catch (Exception $e) {
      $endJSON['status'] = 'error';
      http_response_code(400);
      die($e->getMessage());
    }
    return $endJSON;
  }

  public function updateCellphoneAssistant(array $data)
  {
    $endJSON["status"] = "";
    $endJSON["data"] = "";
    try {
      if (
        isset($data['userId']) &&
        isset($data['email'])
      ) {
        $userId = $data['userId'];
        $accountCellphone = $data['cellphone'];
        $query = "UPDATE `Assistant` SET accountCellphone = '$accountCellphone' WHERE userId = '$userId';";
        $QueryCallback = $this->ExecuteSql($query);
        if ($QueryCallback) {
          $endJSON["status"] = "Success updated cellphone";
          $endJSON["data"] = $data;
        } else {
          throw new Exception("Error: SQL updateCellPhoneAssistant", 1);
        }
      } else {
        throw new Exception("Error: invalid parameters for Assistant", 1);
      }
    } catch (Exception $e) {
      $endJSON['status'] = 'error';
      http_response_code(400);
      die($e->getMessage());
    }
    return $endJSON;
  }

  public function validateAssistant($email, $password)
  {
    $query = "select * from Assistant where Document='$email'";
    $value = $this->GetRow($query);


    $query = "select * from PasswordRecovery where Document='$email'";

    $value2 = $this->GetRow($query);

    $result = array();

    if ($value["Password"] == "") {
      $result["State"] = "NoExist";
      return $result;
    }

    if (password_verify($password, $value["Password"])) {
      if ($value["AssistantStatus_id_AssistantStatus"] == 0) {
        $result["State"] = "Banned";
      } else {
        $result["State"] = "OK";
        $result["Assistant"] = $value;
      }
      if ($value2) {
        $query = "DELETE from PasswordRecovery where Document='$email'";
        $this->ExecuteSql($query);
      }
    } else {

      if (password_verify($password, $value2["PasswordRecover"])) {
        $result["State"] = "CanRecover";
        return $result;
      }

      $result["State"] = "WrongPass";
    }
    return $result;
  }

  public function forgetPassword($email/*$phone,*/)
  {

    $query = "SELECT accountEmail FROM Assistant WHERE accountEmail = '$email'";

    date_default_timezone_set('America/Bogota');
    $currDate = date('Y-m-d H:i:s');

    $exptime = strtotime($currDate . "+ 2days");
    // $exptime = date('Y-m-d H:i:s', $exptime);



    $result = $this->GetOne($query);


    if (!$result) {
      $result["State"] = "NoExist";
    } else {

      $password = dechex(rand(0x000000, 0xFFFFFF));
      $temppassword = $password;
      //$randomNum=rand(10000, 1000000);
      //dechex(rand(3e8, f4240));
      //bool mail('$email', 'Password de Recuperación', "$temppassword");


      $mail = "Hemos recibido una solicitud de cambio de contraseña \n si NO realizaste esta solicitud ignora este mensaje \n de lo contrario ingresa a nuestra plataforma con el siguiente codigo: " . $temppassword;
      //Titulo
      $titulo = "Recuperación de Contraseña";
      //cabecera
      $headers = "MIME-Version: 1.0\r\n";
      $headers .= "Content-type: text/html; charset=iso-8859-1\r\n";
      //dirección del remitente
      $headers .= "From: Recovery Piloz < recovery@piloz.com >\r\n";
      //Enviamos el mensaje a tu_dirección_email
      $bool = mail("recovery@piloz.com", $titulo, $mail, $headers);
      if ($bool) {
        echo "Mensaje enviado";
      } else {
        echo "Mensaje no enviado";
      }

      $pwd_hashed = $this->generateHashPass($password);
      echo $temppassword;
      $insertQuery = "INSERT INTO PasswordRecovery (`id_PasswordRecovery`, Document, PasswordRecover, `Exp_Time`) VALUES ('NULL', '$email', '$pwd_hashed', '$exptime')";

      $resultQuery = $this->ExecuteSql($insertQuery);
      echo $insertQuery;
      if (!$resultQuery) {
        $result["State"] = "NoExist";
      } else {
        $result["State"] = "OK";
      }
    }
    return $result;
  }

  public function updateLastConnectionDateAssistant(array $data)
  {
    $endJSON["status"] = "";
    $endJSON["data"] = "";
    try {
      if (isset($data['userId'])) {
        $userId = $data['userId'];
        $lastConnectionDateTime = parent::getTimestamp();
        $query = "UPDATE `Assistant` SET lastConnectionDateTime = '$lastConnectionDateTime' WHERE userId = '$userId';";
        $QueryCallback = $this->ExecuteSql($query);
        if ($QueryCallback) {
           $endJSON["status"] = "success";
          $endJSON["data"] = $data;
        } else {
          throw new Exception("Error: SQL updateLastConnectionDateAssistant", 1);
        }
      } else {
        throw new Exception("Error: invalid parameters for Assistant", 1);
      }
    } catch (Exception $e) {
      $endJSON['status'] = 'error';
      http_response_code(400);
      die($e->getMessage());
    }
    return $endJSON;
  }

  public function updateVerifiedEmail(array $data)
  {
    $endJSON["status"] = "";
    $endJSON["data"] = "";
    try {
      if (isset($data['userId'])) {
        $userId = $data['userId'];
        $isEmailVerified = 1;
        $query = "UPDATE `Assistant` SET isEmailVerified = '$isEmailVerified' WHERE userId = '$userId';";
        $QueryCallback = $this->ExecuteSql($query);
        if ($QueryCallback) {
           $endJSON["status"] = "success";
          $endJSON["data"] = $data;
        } else {
          throw new Exception("Error: SQL updateVerifiedEmail", 1);
        }
      } else {
        throw new Exception("Error: invalid parameters for Assistant", 1);
      }
    } catch (Exception $e) {
      $endJSON['status'] = 'error';
      http_response_code(400);
      die($e->getMessage());
    }
    return $endJSON;
  }

  public function updateIsOnline(array $data)
  {
    $endJSON["status"] = "";
    $endJSON["data"] = "";
    try {
      if (
        isset($data['userId']) &&
        isset($data['isOnline'])
      ) {
        $userId = $data['userId'];
        $isOnline = $data['isOnline'];
        $query = "UPDATE `Assistant` SET isOnline = '$isOnline' WHERE userId = '$userId';";
        $QueryCallback = $this->ExecuteSql($query);
        if ($QueryCallback) {
          $endJSON["status"] = "Success updated isOnline";
          $endJSON["data"] = $data;
        } else {
          throw new Exception("Error: SQL updateIsOnline", 1);
        }
      } else {
        throw new Exception("Error: invalid parameters for Assistant", 1);
      }
    } catch (Exception $e) {
      $endJSON['status'] = 'error';
      http_response_code(400);
      die($e->getMessage());
    }
    return $endJSON;
  }

  public function updateRol(array $data)
  {
    $endJSON["status"] = "";
    $endJSON["data"] = "";
    try {
      if (
        isset($data['userId']) &&
        isset($data['rolId'])
      ) {
        $userId = $data['userId'];
        $rolId = $data['rolId'];
        $query = "UPDATE `Assistant` SET Rol_rolId = '$rolId' WHERE userId = '$userId';";
        $QueryCallback = $this->ExecuteSql($query);
        if ($QueryCallback) {
           $endJSON["status"] = "success";
          $endJSON["data"] = $data;
        } else {
          throw new Exception("Error: SQL updateRol", 1);
        }
      } else {
        throw new Exception("Error: invalid parameters for Assistant", 1);
      }
    } catch (Exception $e) {
      $endJSON['status'] = 'error';
      http_response_code(400);
      die($e->getMessage());
    }
    return $endJSON;
  }

  public function updatePhotoURL(array $data)
  {
    $endJSON["status"] = "";
    $endJSON["data"] = "";
    try {
      if (
        isset($data['userId']) &&
        isset($data['photoURL'])
      ) {
        $userId = $data['userId'];
        $photoURL = $data['photoURL'];
        $query = "UPDATE `Assistant` SET photoURL = '$photoURL' WHERE userId = '$userId';";
        $QueryCallback = $this->ExecuteSql($query);
        if ($QueryCallback) {
           $endJSON["status"] = "success";
          $endJSON["data"] = $data;
        } else {
          throw new Exception("Error: SQL updatePhotoURL", 1);
        }
      } else {
        throw new Exception("Error: invalid parameters for Assistant", 1);
      }
    } catch (Exception $e) {
      $endJSON['status'] = 'error';
      http_response_code(400);
      die($e->getMessage());
    }
    return $endJSON;
  }

  public function updateStatusAssistant(array $data)
  {
    $endJSON["status"] = "";
    $endJSON["data"] = "";
    try {
      if (
        isset($data['userId']) &&
        isset($data['statusId'])
      ) {
        $userId = $data['userId'];
        $statusId = $data['statusId'];
        $query = "UPDATE `Assistant` SET Status_statusId = '$statusId' WHERE userId = '$userId';";
        $QueryCallback = $this->ExecuteSql($query);
        if ($QueryCallback) {
           $endJSON["status"] = "success";
          $endJSON["data"] = $data;
        } else {
          throw new Exception("Error: SQL updatePhotoURL", 1);
        }
      } else {
        throw new Exception("Error: invalid parameters for Assistant", 1);
      }
    } catch (Exception $e) {
      $endJSON['status'] = 'error';
      http_response_code(400);
      die($e->getMessage());
    }
    return $endJSON;
  }

  public function updateTokenFacebook(array $data)
  {
    $endJSON["status"] = "";
    $endJSON["data"] = "";
    try {
      if (
        isset($data['userId']) &&
        isset($data['tokenFacebook'])
      ) {
        $userId = $data['userId'];
        $tokenFacebook = $data['tokenFacebook'];
        $query = "UPDATE `Assistant` SET tokenFacebook = '$tokenFacebook' WHERE userId = '$userId';";
        $QueryCallback = $this->ExecuteSql($query);
        if ($QueryCallback) {
           $endJSON["status"] = "success";
          $endJSON["data"] = $data;
        } else {
          throw new Exception("Error: SQL updatePhotoURL", 1);
        }
      } else {
        throw new Exception("Error: invalid parameters for Assistant", 1);
      }
    } catch (Exception $e) {
      $endJSON['status'] = 'error';
      http_response_code(400);
      die($e->getMessage());
    }
    return $endJSON;
  }

  public function updateTokenGoogle(array $data)
  {
    $endJSON["status"] = "";
    $endJSON["data"] = "";
    try {
      if (
        isset($data['userId']) &&
        isset($data['tokenGoogle'])
      ) {
        $userId = $data['userId'];
        $token = $data['tokenGoogle'];
        $query = "UPDATE `Assistant` SET token = '$token' WHERE userId = '$userId';";
        $QueryCallback = $this->ExecuteSql($query);
        if ($QueryCallback) {
           $endJSON["status"] = "success";
          $endJSON["data"] = $data;
        } else {
          throw new Exception("Error: SQL updatePhotoURL", 1);
        }
      } else {
        throw new Exception("Error: invalid parameters for Assistant", 1);
      }
    } catch (Exception $e) {
      $endJSON['status'] = 'error';
      http_response_code(400);
      die($e->getMessage());
    }
    return $endJSON;
  }

  public function deleteAssistant(array $data)
  {
    $data['statusId'] = 2;
    return $this->updateStatusAssistant($data);
  }
}
