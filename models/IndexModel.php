<?php


class IndexModel extends Database
{
    public function __construct()
    {
        parent::__construct();
    }




public function registerNewVisit($VisitDate,
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
)
{
  // $imageBaseUrl= $_SERVER['HTTP_HOST'];

   //$endPhotoUrl="http://".$imageBaseUrl."/TeellaHomeVisits/".$photoUrl;

$query="insert into visits(VisitDate,
VisitNo,
ApplicantName,
ApplicantDocument,
ApplicantPhone,
ApplicantARL,
ApplicantEPS,
ApplicantRUT,
ApplicantPhotoUrl,
ApplicantPhotoExterior,
ApplicantPhotoInterior1,
ApplicantPhotoInterior2,
VisitState,
ApplicantMotivation,
WhoAttendsVisit,
WhoAttendsRelationship,
WhoAttendsAddress,
WhoAttendsNeighborhood,
WhoAttendsCellphone,
WhoAttendsHousePhone,
WhoAttendsCivilState,
WhoAttendsBornDate,
Familiar1SamePlace,
Familiar1Name,
Familiar1CivilState,
Familiar1BornDate,
Familiar1Schoolarity,
Familiar1WorkState,
Familiar2SamePlace,
Familiar2Name,
Familiar2CivilState,
Familiar2BornDate,
Familiar2Schoolarity,
Familiar2WorkState,
Familiar3SamePlace,
Familiar3Name,
Familiar3CivilState,
Familiar3BornDate,
Familiar3Schoolarity,
Familiar3WorkState,
Familiar4SamePlace,
Familiar4Name,
Familiar4CivilState,
Familiar4BornDate,
Familiar4Schoolarity,
Familiar4WorkState,
FamilyGroup,
HouseType,
PlaceWhereLive,
PlaceLocation,
FamiliesGroupsAmmount,
PeopleAmmountLivingAtHouse,
HouseSizeForPeople,
HouseConditions,
HouseConditionExplanaiton,
ApplicantBeforeOwnHouse,
OwnerName,
OwnerRelationship,
OwnerHouseType,
OwnerTimeAgo,
OwnerPlaceWhereWas,
OwnerSellReason,
FamilyBasicsNeededs,
AnotherFamiliarHaveExtraWork,
AnotherFamiliarWorkExplanation,
AnotherFamiliarWorkName,
AnotherFamiliarWorkActivityName,
AnotherFamiliarWorkMoney,
HomeEconomyState,
PendingDocument,
PendingDocumentDescription,
PendingDocumentDate,
PrewiewVisitInfo,
PreviewVisitDescription,
TrulyInfoOnVisit,
InqueryOnVisit,
SocialEvaluation) 

values

(

'$VisitDate',
'$VisitNo',
'$ApplicantName',
'$ApplicantDocument',
'$ApplicantPhone',
'$ApplicantARL',
'$ApplicantEPS',
'$ApplicantRUT',
'$ApplicantPhotoUrl',
'$ApplicantPhotoExterior',
'$ApplicantPhotoInterior1',
'$ApplicantPhotoInterior2',
'$VisitState',
'$ApplicantMotivation',
'$WhoAttendsVisit',
'$WhoAttendsRelationship',
'$WhoAttendsAddress',
'$WhoAttendsNeighborhood',
'$WhoAttendsCellphone',
'$WhoAttendsHousePhone',
'$WhoAttendsCivilState',
'$WhoAttendsBornDate',
'$Familiar1SamePlace',
'$Familiar1Name',
'$Familiar1CivilState',
'$Familiar1BornDate',
'$Familiar1Schoolarity',
'$Familiar1WorkState',
'$Familiar2SamePlace',
'$Familiar2Name',
'$Familiar2CivilState',
'$Familiar2BornDate',
'$Familiar2Schoolarity',
'$Familiar2WorkState',
'$Familiar3SamePlace',
'$Familiar3Name',
'$Familiar3CivilState',
'$Familiar3BornDate',
'$Familiar3Schoolarity',
'$Familiar3WorkState',
'$Familiar4SamePlace',
'$Familiar4Name',
'$Familiar4CivilState',
'$Familiar4BornDate',
'$Familiar4Schoolarity',
'$Familiar4WorkState',
'$FamilyGroup',
'$HouseType',
'$PlaceWhereLive',
'$PlaceLocation',
'$FamiliesGroupsAmmount',
'$PeopleAmmountLivingAtHouse',
'$HouseSizeForPeople',
'$HouseConditions',
'$HouseConditionExplanaiton',
'$ApplicantBeforeOwnHouse',
'$OwnerName',
'$OwnerRelationship',
'$OwnerHouseType',
'$OwnerTimeAgo',
'$OwnerPlaceWhereWas',
'$OwnerSellReason',
'$FamilyBasicsNeededs',
'$AnotherFamiliarHaveExtraWork',
'$AnotherFamiliarWorkExplanation',
'$AnotherFamiliarWorkName',
'$AnotherFamiliarWorkActivityName',
'$AnotherFamiliarWorkMoney',
'$HomeEconomyState',
'$PendingDocument',
'$PendingDocumentDescription',
'$PendingDocumentDate',
'$PrewiewVisitInfo',
'$PreviewVisitDescription',
'$TrulyInfoOnVisit',
'$InqueryOnVisit',
'$SocialEvaluation'
)
";

$this->ExecuteSql($query);

return $this;
}

  public function getCitiesLocations()
  {
     $query = "SELECT * FROM Country;";
     $Countries = $this->GetAll($query);

     $query = "SELECT * FROM City;";
     $Cities = $this->GetAll($query);

     $query = "SELECT * FROM State;";
     $states = $this->GetAll($query);
    
     $endJSON["status"]="success";

     $endJSON['data']=array('Countries'=>$Countries,'States'=>$states,'Cities'=>$Cities);

     return $endJSON;
  }


  
}
