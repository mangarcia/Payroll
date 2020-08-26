<?php
class PlanModel extends Database
{
  public function __construct()
  {
    parent::__construct();
  }

 
  public function calculatePrice(array $data)
  { 

    error_reporting(0);

    $endJSON["status"] = "";
    $endJSON["data"] = "";

    $fromDate=strtotime($data['fromDate']);
    $toDate=strtotime($data['toDate']);
    $cityId=$data['cityId'];
    $pacients= $data['pacients'];
    if($data['meridianValue']==3)
    {
      $days = 2*(($toDate - $fromDate)/60/60/24+1);
    }
    else
    {
      $days = ($toDate - $fromDate)/60/60/24+1;
    }

    

    try {
      if (isset($data['companyId']) ) {

        $companyId = $data['companyId'];

        $PlansQuery="SELECT DISTINCT Plan.*,Plan_has_PlanDays.NumberOfDays as 'NumberOfDays' from Plan,PlanDays,Plan_has_PlanDays,CityPlanDay WHERE PlanDays.idPlanDays=Plan_has_PlanDays.PlanDays_idPlanDays  AND CityPlanDay.Plan_idPlan=Plan.idPlan and CityPlanDay.City_idCity1=$cityId AND Plan.companyId=$companyId AND Plan.PacientsAmmount=$pacients"; 

        $PlansQueryCallback = $this->GetAll($PlansQuery);


        foreach ($PlansQueryCallback as &$valor) 
        {
            $idPlan= $valor["idPlan"];
            
            $planDaysQuery="SELECT DISTINCT PlanDays.* FROM PlanDays,Plan_has_PlanDays WHERE Plan_has_PlanDays.PlanDays_idPlanDays=PlanDays.idPlanDays and Plan_has_PlanDays.Plan_idPlan=$idPlan";
            $PlansDaysQueryCallback = $this->GetAll($planDaysQuery);
            
            $planFounded=false;
            $foundedOne=false;

          
            foreach ($PlansDaysQueryCallback as &$planDay) 
            {
                $cardinality=$planDay["idPlanDays"];
                $planDaysAmmount=$planDay["NumberOfDays"];

                switch ($cardinality) 
                {
                    case 1:
                       if($days<$planDaysAmmount)
                        {
                          if(!$foundedOne)
                          {
                             $planFounded=true;
                          } 
                        }
                        else
                        {
                          $planFounded=false;
                        }
                        $foundedOne=true;
                       
                        break;
                    case 2:
                        if($days<=$planDaysAmmount)
                        {
                          if(!$foundedOne)
                          {
                             $planFounded=true;
                          } 
                        }
                        else
                        {
                          $planFounded=false;
                        }
                        $foundedOne=true;
                        break;
                    case 3:
                        if($days==$planDaysAmmount)
                        {
                          if(!$foundedOne)
                          {
                             $planFounded=true;
                          } 
                        }
                        else
                        {
                          $planFounded=false;
                        }
                        $foundedOne=true;
                        break;
                    case 4:
                         if($days>=$planDaysAmmount)
                        {
                          if(!$foundedOne)
                          {
                             $planFounded=true;
                          } 
                        }
                        else
                        {
                          $planFounded=false;
                        }
                        $foundedOne=true;
                        break;
                    case 5:
                        if($days>$planDaysAmmount)
                        {
                          if(!$foundedOne)
                          {
                             $planFounded=true;
                          } 
                        }
                        else
                        {
                          $planFounded=false;
                        }
                        $foundedOne=true;
                        break;
                }
            }

            if($planFounded)
            {
              $holidayValue=$valor["HolidayPrice"];
              $normalValue=$valor["WorkDayPrice"];
              
              $HolidaysQuery="select count(DISTINCT Holiday.HolidayId) as DaysCount FROM Holiday,Country,State,City WHERE State.Country_idCountry=Country.idCountry AND State.idState=City.State_idState AND Holiday.Country_idCountry=Country.idCountry AND City.idCity=$cityId and HolidayDate between '".$data['fromDate']."' and '". $data['toDate']."'";
              $HolidaysCount = $this->GetOne($HolidaysQuery);

              $workingDays=$days-$HolidaysCount;

              $serviceValue= $holidayValue*$HolidaysCount+$normalValue*$workingDays;

              
              $endJSON["status"] = "Ok";

              $PlanName=$valor["PlanName"];
              $planId=$valor["idPlan"];
              $endJSON['data']=array('PlanId'=>$planId,'PlanName'=>$PlanName,'WorkDayValue'=>$normalValue,'HoliDayValue'=>$holidayValue, 'ServiceValue'=>$serviceValue,'temp_address'=>$data['temp_address']);
              
              //echo json_encode($endJSON);
              break;
            }
        }


/*
        if ($QueryCallback) {
          $endJSON["status"] = "Ok";
          $endJSON["data"] = $QueryCallback ;
        } else {
          throw new Exception("Error: SQL deleteProduct", 1);
        }
        */
      } else {
        throw new Exception('{"Error":"No data params Company Id"}', 1);
      }
    } catch (Exception $e) {
      $endJSON['status'] = 'error';
      http_response_code(400);
      die($e->getMessage());
    }
    
    return $endJSON;;
  }


  public function readCardinality()
  {
    $query="SELECT * FROM PlanDays";
    $PlansDaysQueryCallback = $this->GetAll($query);
    $endJSON["status"] = "ok";
    $endJSON["data"] = $PlansDaysQueryCallback ;
    return $endJSON;
  }


 public function readPlans(array $data)
  {
     $endJSON["status"] = "";
      $endJSON["data"] = "";
      $companyId=Session::get("companyId");
      try {
        $query = "select DISTINCT Plan.*,Plan_has_PlanDays.NumberOfDays,idPlanDays ,PlanDays.Cardinality from Plan,PlanDays,Plan_has_PlanDays where Plan.idPlan=Plan_has_PlanDays.Plan_idPlan and PlanDays.idPlanDays=Plan_has_PlanDays.PlanDays_idPlanDays and Plan.companyId=$companyId";
        $SelectAllResult = $this->GetAll($query);

        if ($SelectAllResult) {
           $endJSON["status"] = "success";
         
          foreach ($SelectAllResult as &$key) {
            $citiesQuery="Select City.idCity  as 'value', City.CityName as 'Text' from City,CityPlanDay where City.idCity=CityPlanDay.City_idCity1 and CityPlanDay.Plan_idPlan='".$key['idPlan']."'";
            $key["cities"]=$this->GetAll($citiesQuery);
            //echo json_encode($key);
          }
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


   public function createPlan(array $data)
  {
     $endJSON["status"] = "";
      $endJSON["data"] = "";
      $companyId=Session::get("companyId");
      try {

        $PlanName=$data["PlanName"];
        $WorkDayPrice=$data["WorkDayPrice"];
        $HolidayPrice=$data["HolidayPrice"];
        $PacientsAmmount=$data["PacientsAmmount"];
        $idPlanDays=$data["idPlanDays"];
        $NumberOfDays=$data["NumberOfDays"];
        $companyId=Session::get("companyId");

        $cities=explode(",", $data["cities"]);

        $query = "INSERT INTO `Plan` (`idPlan`, `PlanName`, `PacientsAmmount`, `HolidayPrice`, `WorkDayPrice`, `companyId`) VALUES (NULL, '$PlanName', '$PacientsAmmount', ' $HolidayPrice', '$WorkDayPrice', '$companyId');";
        $SelectAllResult = $this->ExecuteSql($query);
        if ($SelectAllResult) {

          $planId="Select LAST_INSERT_ID()";
          $planId = $this->GetOne($planId);

          $insertPlanCardinality="INSERT INTO `Plan_has_PlanDays` (`IdPlanHasDays`, `Plan_idPlan`, `PlanDays_idPlanDays`, `NumberOfDays`) VALUES (NULL, '$planId', '$idPlanDays', '$NumberOfDays');";

          $insertPlanCardinality = $this->ExecuteSql($insertPlanCardinality);


          if (!$insertPlanCardinality) 
          {
              throw new Exception("Error: Cant insert cardinality", 1);
          }


          foreach ($cities as $cityId) 
          {
              $insertPlanCities="INSERT INTO `CityPlanDay` VALUES (NULL,'$planId','$cityId')";
              $insertPlanCities = $this->ExecuteSql($insertPlanCities);
          }

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


  public function updatePlan(array $data)
  {
     $endJSON["status"] = "";
      $endJSON["data"] = "";
      $companyId=Session::get("companyId");
      try {

        $PlanName=$data["PlanName"];
        $WorkDayPrice=$data["WorkDayPrice"];
        $HolidayPrice=$data["HolidayPrice"];
        $PacientsAmmount=$data["PacientsAmmount"];
        $idPlanDays=$data["idPlanDays"];
        $NumberOfDays=$data["NumberOfDays"];
        $companyId=Session::get("companyId");

        $cities=explode(",", $data["cities"]);

        $query = "INSERT INTO `Plan` (`idPlan`, `PlanName`, `PacientsAmmount`, `HolidayPrice`, `WorkDayPrice`, `companyId`) VALUES (NULL, '$PlanName', '$PacientsAmmount', ' $HolidayPrice', '$WorkDayPrice', '$companyId');";
        $SelectAllResult = $this->ExecuteSql($query);
        if ($SelectAllResult) {

          $planId="Select LAST_INSERT_ID()";
          $planId = $this->GetOne($planId);

          $insertPlanCardinality="INSERT INTO `Plan_has_PlanDays` (`IdPlanHasDays`, `Plan_idPlan`, `PlanDays_idPlanDays`, `NumberOfDays`) VALUES (NULL, '$planId', '$idPlanDays', '$NumberOfDays');";

          $insertPlanCardinality = $this->ExecuteSql($insertPlanCardinality);


          if (!$insertPlanCardinality) 
          {
              throw new Exception("Error: Cant insert cardinality", 1);
          }


          foreach ($cities as $cityId) 
          {
              $insertPlanCities="INSERT INTO `CityPlanDay` VALUES (NULL,'$planId','$cityId')";
              $insertPlanCities = $this->ExecuteSql($insertPlanCities);
          }

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

}

