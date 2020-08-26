CREATE TABLE `User` (
  userId                 int(10) NOT NULL AUTO_INCREMENT, 
  lastConnectionDateTime int(10), 
  accountEmail           varchar(255) NOT NULL UNIQUE, 
  accountPassword        varchar(255) NOT NULL, 
  accountCellphone       varchar(15), 
  accountName            varchar(255) NOT NULL, 
  isEmailVerified        tinyint(1), 
  isNewUser              tinyint(1), 
  isOnline               tinyint(1), 
  photoURL               varchar(255), 
  Rol_rolId              int(1) NOT NULL, 
  Status_statusId        int(1) NOT NULL, 
  tokenFacebook          varchar(255), 
  tokenGoogle            varchar(255), 
  createdAt              int(10) NOT NULL, 
  tempPasswordToken      varchar(255), 
  tokenPasswordExpiredAt int(10), 
  Company_companyId      int(4), 
  PRIMARY KEY (userId), 
  UNIQUE INDEX (userId), 
  UNIQUE INDEX (accountCellphone), 
  INDEX (isEmailVerified)) ENGINE=InnoDB;
CREATE TABLE Family (
  memberId                                    int(10) NOT NULL AUTO_INCREMENT, 
  User_userId                                 int(10) NOT NULL, 
  Relationship_accountRelationshipId          int(2) NOT NULL, 
  basicDataBirthDate                          date comment 'YYYY-MM-DD', 
  basicDataDisability                         varchar(100), 
  DocType_docTypeId                           int(1) NOT NULL, 
  basicDataDocNumber                          varchar(15) UNIQUE, 
  basicDataFirstName                          varchar(100), 
  basicDataLastName                           varchar(100), 
  Sex_sexId                                   int(1) NOT NULL, 
  Mobility_basicDataMobiilityId               int(1) NOT NULL, 
  basicDataHeight                             varchar(3) comment 'CM', 
  basicDataWeight                             varchar(3), 
  personalDataCellphone                       varchar(15), 
  emergencyContactCellphone                   varchar(15), 
  emergencyContactNamePerson                  varchar(100), 
  userEpsName                                 varchar(100), 
  userObservations                            varchar(255), 
  StatusUser_statusUserId                     int(1) NOT NULL, 
  Rol_rolId                                   int(1) NOT NULL, 
  Avatar_avatarId                             int(1) NOT NULL, 
  Relationship_emergencyContactRelationshipId int(2) NOT NULL, 
  PRIMARY KEY (memberId)) ENGINE=InnoDB;
CREATE TABLE Rol (
  rolId int(1) NOT NULL AUTO_INCREMENT, 
  name  varchar(255), 
  PRIMARY KEY (rolId)) ENGINE=InnoDB;
CREATE TABLE StatusUser (
  statusUserId int(1) NOT NULL AUTO_INCREMENT, 
  name         varchar(255), 
  PRIMARY KEY (statusUserId)) ENGINE=InnoDB;
CREATE TABLE Request (
  requestId                                             int(10) NOT NULL AUTO_INCREMENT, 
  StatusService_serviceStatusId                         int(1) NOT NULL, 
  Family_userServiceId                                  int(10) NOT NULL, 
  Address_addressId                                     int(10), 
  Service_serviceId                                     int(10) NOT NULL, 
  requestDateTime                                       int(10), 
  serviceDateTimeEnd                                    int(10), 
  serviceDuration                                       int(10), 
  serviceTotalAmount                                    int(10), 
  CurrencyCode_serviceTotalAmountCurrencyCode           int(1) NOT NULL, 
  serviceDateTimeStart                                  int(10), 
  userRequestObservations                               varchar(255), 
  temp_address                                          varchar(255), 
  temp_addressInfo                                      varchar(255), 
  TransactionPaymentGateway_transactionPaymentGatewayId int(1), 
  PRIMARY KEY (requestId)) ENGINE=InnoDB;
CREATE TABLE StatusPayment (
  statusPaymentId int(1) NOT NULL AUTO_INCREMENT, 
  name            varchar(100) NOT NULL UNIQUE, 
  status          varchar(100) NOT NULL, 
  description     varchar(255) NOT NULL, 
  PRIMARY KEY (statusPaymentId)) ENGINE=InnoDB;
CREATE TABLE StatusService (
  statusServiceId int(1) NOT NULL AUTO_INCREMENT, 
  name            varchar(100) NOT NULL UNIQUE, 
  nameDisplay     varchar(50), 
  PRIMARY KEY (statusServiceId)) ENGINE=InnoDB;
CREATE TABLE Avatar (
  avatarId int(1) NOT NULL AUTO_INCREMENT, 
  name     varchar(100) NOT NULL UNIQUE, 
  PRIMARY KEY (avatarId)) ENGINE=InnoDB;
CREATE TABLE Sex (
  sexId int(1) NOT NULL AUTO_INCREMENT, 
  name  varchar(100) NOT NULL UNIQUE, 
  PRIMARY KEY (sexId)) ENGINE=InnoDB;
CREATE TABLE DocType (
  docTypeId int(1) NOT NULL AUTO_INCREMENT, 
  name      varchar(100) NOT NULL UNIQUE, 
  PRIMARY KEY (docTypeId)) ENGINE=InnoDB;
CREATE TABLE Relationship (
  relationshipId int(2) NOT NULL AUTO_INCREMENT, 
  name           varchar(100) NOT NULL UNIQUE, 
  PRIMARY KEY (relationshipId)) ENGINE=InnoDB;
CREATE TABLE Service (
  serviceId                 int(10) NOT NULL AUTO_INCREMENT, 
  Company_companyId         int(4) NOT NULL, 
  name                      varchar(100) NOT NULL UNIQUE, 
  costAmount                decimal(10, 3), 
  TypeService_typeServiceId int(10) NOT NULL, 
  serviceDetail             varchar(255), 
  isDayHoliday              tinyint(1), 
  durationService           int(10), 
  PRIMARY KEY (serviceId)) ENGINE=InnoDB;
CREATE TABLE Cancellation (
  cancellationId         int(10) NOT NULL AUTO_INCREMENT, 
  cancellationDateTime   int(10), 
  cancellationNumWeekISO int(10), 
  cancellationYear       int(10), 
  Request_requestId      int(10) NOT NULL, 
  Assistant_assistantId  int(10) NOT NULL, 
  PRIMARY KEY (cancellationId)) ENGINE=InnoDB;
CREATE TABLE Provieded (
  proviededId                     int(10) NOT NULL AUTO_INCREMENT, 
  serviceDateTimeOnTheRoadStarted int(10), 
  serviceLatePenalty              tinyint(1), 
  serviceNumWeekISO               int(10), 
  serviceDateTimeArrival          int(10), 
  serviceDateTimeDeparture        int(10), 
  serviceRequestPayForAssistant   int(10), 
  serviceRequestPenaltyCost       int(10), 
  serviceYear                     int(10), 
  Request_requestId               int(10) NOT NULL, 
  Assistant_assistantId           int(10) NOT NULL, 
  controlObservations             varchar(255), 
  PRIMARY KEY (proviededId)) ENGINE=InnoDB;
CREATE TABLE StatusRoomChat (
  statusRoomChatId int(1) NOT NULL AUTO_INCREMENT, 
  name             varchar(100) NOT NULL UNIQUE, 
  PRIMARY KEY (statusRoomChatId)) ENGINE=InnoDB;
CREATE TABLE RoomChat (
  roomChatId                      int(10) NOT NULL AUTO_INCREMENT, 
  roomDateTimeStarted             int(10), 
  Provieded_proviededId           int(10) NOT NULL, 
  StatusRoomChat_statusRoomChatId int(1) NOT NULL, 
  PRIMARY KEY (roomChatId)) ENGINE=InnoDB;
CREATE TABLE MessageChat (
  User_fromId         int(10) NOT NULL, 
  sendedDateTime      int(10), 
  message             varchar(255), 
  RoomChat_roomChatId int(10) NOT NULL, 
  seen                tinyint(1)) ENGINE=InnoDB;
CREATE TABLE Support (
  supportId                   int(10) NOT NULL AUTO_INCREMENT, 
  supportDateTimeStarted      int(10), 
  User_AssistantId            int(10) NOT NULL, 
  StatusRoomChat_statusRoomId int(1) NOT NULL, 
  PRIMARY KEY (supportId)) ENGINE=InnoDB;
CREATE TABLE MessageSupport (
  Support_supportId int(10) NOT NULL, 
  User_fromId       int(10) NOT NULL, 
  sendedDateTime    int(10), 
  seen              tinyint(1), 
  message           varchar(255)) ENGINE=InnoDB;
CREATE TABLE Assistant (
  assistantId                                             int(10) NOT NULL AUTO_INCREMENT, 
  User_AssistantId                                        int(10) NOT NULL, 
  DocType_DocTypeId                                       int(1) NOT NULL, 
  basicDataDocNumber                                      varchar(15), 
  basicDataFirstName                                      varchar(50), 
  basicDataLastName                                       varchar(50), 
  Sex_sexId                                               int(1) NOT NULL, 
  basicDataPhoto                                          varchar(255), 
  basicDataBirthDate                                      date, 
  basicDataBirthPlaceCity                                 varchar(255), 
  basicDataDisabilityHave                                 tinyint(1), 
  basicDataDisabilityWhich                                varchar(255), 
  personalDataTelephone                                   varchar(50), 
  personalDataCellphone                                   varchar(50), 
  personalDataAddress                                     varchar(255), 
  personalDataAddressLocality                             varchar(255), 
  personalDataAddressCity                                 varchar(255), 
  educationAcademicLevel                                  int(1), 
  DeviceOS_deviceOSId                                     int(1), 
  deviceVersion                                           float, 
  professionalJobTitle                                    varchar(255), 
  CurrencyCode_professionalCurrencyCodeSalaryAspirationId int(1) NOT NULL, 
  professionalSalaryAspiration                            int(10), 
  professionalResume                                      varchar(255) comment 'Path Storage', 
  experienceAlzheimer                                     tinyint(1), 
  experienceParkinson                                     tinyint(1), 
  experienceACV                                           tinyint(1), 
  experiencePsychiatric                                   tinyint(1), 
  experienceDisability                                    tinyint(1), 
  experienceOther                                         tinyint(1), 
  verificationBackgroundFiscalia                          tinyint(1), 
  verificationBackgroundPolicia                           tinyint(1), 
  verificationBackgroundProcuraduria                      tinyint(1), 
  socialBenefitsHealth                                    varchar(255), 
  socialBenefitsPension                                   varchar(255), 
  socialBenefitsARL                                       varchar(255), 
  testPersonalityTest                                     tinyint(1), 
  testWartegg                                             tinyint(1), 
  testFiguraHuman                                         tinyint(1), 
  capacitationsTeellaTraining                             tinyint(1), 
  capacitationsUniversityTraining                         tinyint(1), 
  domiciliaryVisit                                        tinyint(1) comment 'Visita Domiciliaria', 
  observation                                             varchar(255), 
  suitableService                                         tinyint(1) comment 'APTO para prestar servicio', 
  professionalTeellaPhoto                                 varchar(255) comment 'Path Storage', 
  professionalTeellaProfessionalProfile                   varchar(255), 
  professionalTeellaCaregiverTypeProfessional             tinyint(1), 
  professionalTeellaCaregiverTypeBasic                    tinyint(1), 
  professionalTeellaCaregiverTypeOther                    tinyint(1), 
  PRIMARY KEY (assistantId)) ENGINE=InnoDB;
CREATE TABLE AcademicLevel (
  academicLevelId int(1) NOT NULL AUTO_INCREMENT, 
  name            varchar(50) UNIQUE, 
  PRIMARY KEY (academicLevelId)) ENGINE=InnoDB;
CREATE TABLE DeviceOS (
  deviceOSId int(1) NOT NULL AUTO_INCREMENT, 
  name       varchar(100) NOT NULL UNIQUE, 
  PRIMARY KEY (deviceOSId)) ENGINE=InnoDB;
CREATE TABLE CurrencyCode (
  currencyCodeId int(1) NOT NULL AUTO_INCREMENT, 
  name           varchar(100) NOT NULL UNIQUE, 
  PRIMARY KEY (currencyCodeId)) ENGINE=InnoDB;
CREATE TABLE ProfessionalSpecialty (
  professionalSpecialtyId       int(10) NOT NULL AUTO_INCREMENT, 
  Assistant_assistantId         int(10) NOT NULL, 
  SpecialtyName                 varchar(255), 
  SpecialtyYearsExperience      int(10), 
  AssistantprofessionalJobTitle varchar(255), 
  PRIMARY KEY (professionalSpecialtyId)) ENGINE=InnoDB;
CREATE TABLE Notification (
  notificationId                      int(10) NOT NULL AUTO_INCREMENT, 
  User_userId                         int(10) NOT NULL, 
  sendedDateTime                      int(10), 
  title                               varchar(255), 
  subtitle                            varchar(255), 
  message                             text, 
  imagePath                           text, 
  lanuchURL                           text, 
  additionalData                      text, 
  actionsButtons                      text, 
  seen                                tinyint(1), 
  UUIDNotification                    varchar(255), 
  TypeNotification_typeNotificationId int(1) NOT NULL, 
  PRIMARY KEY (notificationId)) ENGINE=InnoDB;
CREATE TABLE Address (
  addressId                     int(10) NOT NULL AUTO_INCREMENT, 
  User_userId                   int(10) NOT NULL, 
  addressNameType               varchar(255), 
  address                       varchar(255), 
  City_addressCityId            int(1) NOT NULL, 
  addressInfoAdditional         varchar(255), 
  longitude                     varchar(10), 
  latitude                      varchar(10), 
  plusCodes                     varchar(10), 
  StatusAddress_statusAddressId int(1) NOT NULL, 
  createdAt                     int(10), 
  PRIMARY KEY (addressId)) ENGINE=InnoDB;
CREATE TABLE Company (
  companyId                     int(4) NOT NULL AUTO_INCREMENT, 
  name                          varchar(100) NOT NULL UNIQUE, 
  StatusCompany_statusCompanyId int(1) NOT NULL, 
  PRIMARY KEY (companyId)) ENGINE=InnoDB;
CREATE TABLE StatusCompany (
  statusCompanyId int(1) NOT NULL AUTO_INCREMENT, 
  name            varchar(100) NOT NULL UNIQUE, 
  PRIMARY KEY (statusCompanyId)) ENGINE=InnoDB;
CREATE TABLE Coupon (
  couponId                    int(10) NOT NULL AUTO_INCREMENT, 
  code                        varchar(100), 
  createdDateTime             int(10), 
  expiredDateTime             int(10), 
  StatusCoupon_statusCouponId int(1) NOT NULL, 
  User_userId                 int(10) NOT NULL, 
  PRIMARY KEY (couponId)) ENGINE=InnoDB;
CREATE TABLE StatusCoupon (
  statusCouponId int(1) NOT NULL AUTO_INCREMENT, 
  name           varchar(100) NOT NULL UNIQUE, 
  PRIMARY KEY (statusCouponId)) ENGINE=InnoDB;
CREATE TABLE Device_Notification (
  Deviceid       int(10) NOT NULL, 
  Notificationid int(10) NOT NULL, 
  PRIMARY KEY (Deviceid, 
  Notificationid)) ENGINE=InnoDB;
CREATE TABLE TypeService (
  typeServiceId int(10) NOT NULL AUTO_INCREMENT, 
  name          varchar(100) NOT NULL UNIQUE, 
  PRIMARY KEY (typeServiceId)) ENGINE=InnoDB;
CREATE TABLE Mobility (
  mobilityId int(1) NOT NULL AUTO_INCREMENT, 
  name       varchar(100) NOT NULL UNIQUE, 
  PRIMARY KEY (mobilityId)) ENGINE=InnoDB;
CREATE TABLE Device (
  deviceId                    int(10) NOT NULL AUTO_INCREMENT, 
  User_userId                 int(10) NOT NULL, 
  UUID                        varchar(100) NOT NULL UNIQUE, 
  StatusDevice_statusDeviceId int(1) NOT NULL, 
  subscriptionDateTime        int(10), 
  lastConnectionDateTime      int(10), 
  PRIMARY KEY (deviceId)) ENGINE=InnoDB;
CREATE TABLE StatusAddress (
  statusAddressId int(1) NOT NULL AUTO_INCREMENT, 
  name            varchar(100) NOT NULL UNIQUE, 
  PRIMARY KEY (statusAddressId)) ENGINE=InnoDB;
CREATE TABLE StatusDevice (
  StatusDeviceId int(1) NOT NULL AUTO_INCREMENT, 
  name           varchar(100) NOT NULL UNIQUE, 
  PRIMARY KEY (StatusDeviceId)) ENGINE=InnoDB;
CREATE TABLE City (
  cityId int(1) NOT NULL AUTO_INCREMENT, 
  name   varchar(100) NOT NULL UNIQUE, 
  PRIMARY KEY (cityId)) ENGINE=InnoDB;
CREATE TABLE Pillbox (
  pillboxId                 int(10) NOT NULL AUTO_INCREMENT, 
  name                      varchar(100), 
  quantity                  varchar(50), 
  frequency                 int(10), 
  typePillbox               varchar(10), 
  Family_familyId           int(10) NOT NULL, 
  Frequency_frequencyId     int(1), 
  TypePillbox_typePillboxId int(1), 
  startedAt                 time, 
  PRIMARY KEY (pillboxId)) ENGINE=InnoDB;
CREATE TABLE TypePillbox (
  typePillboxId int(1) NOT NULL AUTO_INCREMENT, 
  name          varchar(100) NOT NULL UNIQUE, 
  PRIMARY KEY (typePillboxId)) ENGINE=InnoDB;
CREATE TABLE Frequency (
  frequencyId int(1) NOT NULL AUTO_INCREMENT, 
  name        varchar(100) NOT NULL UNIQUE, 
  PRIMARY KEY (frequencyId)) ENGINE=InnoDB;
CREATE TABLE Product (
  productId                 int(1) NOT NULL AUTO_INCREMENT, 
  nameProduct               varchar(255) NOT NULL, 
  costProduct               int(10), 
  description               text, 
  TypeProduct_typeProductId int(1) NOT NULL, 
  Company_companyId         int(4) NOT NULL, 
  PRIMARY KEY (productId)) ENGINE=InnoDB;
CREATE TABLE TypeProduct (
  typeProductId int(1) NOT NULL AUTO_INCREMENT, 
  name          varchar(100) NOT NULL UNIQUE, 
  PRIMARY KEY (typeProductId)) ENGINE=InnoDB;
CREATE TABLE TransactionPaymentGateway (
  transactionPaymentGatewayId   int(1) NOT NULL AUTO_INCREMENT, 
  refNumber                     varchar(20) NOT NULL, 
  orderIdentifier               varchar(50) NOT NULL, 
  creationDateTime              int(10) NOT NULL, 
  updateDateTime                int(10), 
  orderDescription              varchar(255), 
  method                        varchar(20), 
  checkoutURL                   text NOT NULL, 
  StatusPayment_statusPaymentId int(1) NOT NULL, 
  PRIMARY KEY (transactionPaymentGatewayId)) ENGINE=InnoDB;
CREATE TABLE PurchaseOrder (
  purchaseOrderId                                       int(1) NOT NULL AUTO_INCREMENT, 
  Address_addressId                                     int(10) NOT NULL, 
  Product_productId                                     int(1) NOT NULL, 
  quantity                                              int(10), 
  productTotalAmount                                    int(10), 
  TransactionPaymentGateway_transactionPaymentGatewayId int(1) NOT NULL, 
  temp_address                                          varchar(255), 
  temp_addressInfo                                      varchar(255), 
  purchaseOrderDateTime                                 int(10), 
  CurrencyCode_productTotalAmount                       int(1) NOT NULL, 
  PRIMARY KEY (purchaseOrderId)) ENGINE=InnoDB;
CREATE TABLE TypeNotification (
  typeNotificationId int(1) NOT NULL AUTO_INCREMENT, 
  name               varchar(100) NOT NULL UNIQUE, 
  PRIMARY KEY (typeNotificationId)) ENGINE=InnoDB;
CREATE VIEW viewRequest AS
SELECT
	Request.requestId,
	Request.requestDateTime,
	Request.serviceDateTimeEnd,
	Request.serviceDateTimeStart,
	Request.serviceDuration,
	Request.serviceTotalAmount,
	Request.userRequestObservations,
	StatusService.name AS serviceStatus,
	`User`.userId AS userAccountId,
	StatusUser.name AS UserAccountStatus,
	Rol.name AS userAccountRol,
	Family.memberId AS userServiceId,
	Family.basicDataFirstName AS userServiceFirstName,
	Family.basicDataLastName AS userServiceLastName,
	Relationship.name AS userServiceRelationshipUserAccount,
	RolFamily.name AS userServiceRol,
	StatusUserFamily.name AS userServiceStatus,
	Avatar.name AS userServiceAvatar,
	Request.temp_addressInfo,
	RelationshipEmergencyContact.name AS userServiceRelationshipEmergencyContact,
	Service.name AS serviceName,
	TypeService.name AS serviceType,
	Address.address,
	Address.longitude,
	Address.latitude,
	Sex.name AS sexName,
	Service.serviceDetail,
	StatusService.nameDisplay AS statusServiceDisplayName,
	StatusPayment.name AS statusPaymentName,
	StatusPayment.status AS statusPaymentStatus,
	StatusPayment.description AS statusPaymentDescription,
	TransactionPaymentGateway.transactionPaymentGatewayId AS transactionPaymentGatewayId,
	TransactionPaymentGateway.refNumber AS transactionPaymentGatewayRefNumber,
	TransactionPaymentGateway.orderIdentifier AS transactionPaymentGatewayOrderIdentifer,
	TransactionPaymentGateway.creationDateTime AS transactionPaymentGatewayCreationDateTime,
	TransactionPaymentGateway.updateDateTime AS transactionPaymentGatewayUpdateDateTime,
	TransactionPaymentGateway.orderDescription AS transactionPaymentGatewayOrderDescription,
	TransactionPaymentGateway.method AS transactionPaymentGatewayMethod,
	Request.temp_address,
	TransactionPaymentGateway.checkoutURL AS transactionPaymentGatewayCheckoutURL
FROM
	Service INNER JOIN
	Request ON Service.serviceId = Request.Service_serviceId INNER JOIN
	TypeService ON Service.TypeService_typeServiceId = TypeService.typeServiceId INNER JOIN
	TransactionPaymentGateway ON Request.TransactionPaymentGateway_transactionPaymentGatewayId = TransactionPaymentGateway.transactionPaymentGatewayId INNER JOIN
	StatusService ON Request.StatusService_serviceStatusId = StatusService.statusServiceId INNER JOIN
	Family ON Request.Family_userServiceId = Family.memberId INNER JOIN
	StatusPayment ON TransactionPaymentGateway.StatusPayment_statusPaymentId = StatusPayment.statusPaymentId INNER JOIN
	Relationship ON Family.Relationship_accountRelationshipId = Relationship.relationshipId INNER JOIN
	Relationship AS RelationshipEmergencyContact ON Family.Relationship_emergencyContactRelationshipId = RelationshipEmergencyContact.relationshipId INNER JOIN
	StatusUser AS StatusUserFamily ON Family.StatusUser_statusUserId = StatusUserFamily.statusUserId INNER JOIN
	Rol AS RolFamily ON Family.Rol_rolId = RolFamily.rolId INNER JOIN
	Sex ON Family.Sex_sexId = Sex.sexId INNER JOIN
	Avatar ON Family.Avatar_avatarId = Avatar.avatarId INNER JOIN
	`User` ON Family.User_userId = `User`.userId INNER JOIN
	StatusUser ON `User`.Status_statusId = StatusUser.statusUserId INNER JOIN
	Rol ON `User`.Rol_rolId = Rol.rolId INNER JOIN
	Address ON `User`.userId = Address.User_userId
ORDER BY
	Request.requestDateTime ASC;
CREATE VIEW viewAssistant AS
SELECT
	StatusUser.name AS statusUser,
	`User`.userId,
	`User`.createdAt,
	`User`.lastConnectionDateTime,
	`User`.accountEmail,
	`User`.accountPassword,
	`User`.isEmailVerified,
	`User`.isNewUser,
	`User`.isOnline,
	`User`.photoURL,
	`User`.Rol_rolId,
	Rol.name,
	`User`.Status_statusId,
	Assistant.assistantId,
	Assistant.User_AssistantId,
	Assistant.basicDataDocNumber,
	Assistant.basicDataFirstName,
	Assistant.basicDataPhoto,
	Assistant.basicDataLastName,
	Assistant.basicDataBirthDate,
	Assistant.basicDataBirthPlaceCity,
	Assistant.basicDataDisabilityHave,
	Assistant.basicDataDisabilityWhich,
	Assistant.personalDataTelephone,
	Assistant.personalDataCellphone,
	Assistant.personalDataAddress,
	Assistant.personalDataAddressLocality,
	Assistant.personalDataAddressCity,
	Assistant.educationAcademicLevel,
	AcademicLevel.name AS educationAcademicLeve,
	Assistant.DeviceOS_deviceOSId,
	DeviceOS.name AS deviceOS,
	Assistant.deviceVersion,
	Assistant.professionalJobTitle,
	CurrencyCode.name AS professionalcurrencyCodeSalaryAspiration,
	Assistant.professionalSalaryAspiration,
	Assistant.professionalResume,
	Assistant.experienceAlzheimer,
	Assistant.experienceParkinson,
	Assistant.experienceACV,
	Assistant.experiencePsychiatric,
	Assistant.experienceDisability,
	Assistant.experienceOther,
	Assistant.verificationBackgroundFiscalia,
	Assistant.verificationBackgroundPolicia,
	Assistant.verificationBackgroundProcuraduria,
	Assistant.socialBenefitsHealth,
	Assistant.socialBenefitsPension,
	Assistant.socialBenefitsARL,
	Assistant.testPersonalityTest,
	Assistant.testWartegg,
	Assistant.testFiguraHuman,
	Assistant.capacitationsTeellaTraining,
	Assistant.capacitationsUniversityTraining,
	Assistant.domiciliaryVisit,
	Assistant.observation,
	Assistant.suitableService,
	Assistant.professionalTeellaPhoto,
	Assistant.professionalTeellaProfessionalProfile,
	Assistant.professionalTeellaCaregiverTypeProfessional,
	Assistant.professionalTeellaCaregiverTypeBasic,
	Assistant.professionalTeellaCaregiverTypeOther,
	Assistant.Sex_sexId,
	Sex.name AS basicDataSex,
	Assistant.DocType_DocTypeId,
	DocType.name AS basicDataDocType
FROM
	`User` INNER JOIN
	Assistant ON `User`.userId = Assistant.User_AssistantId INNER JOIN
	Rol ON `User`.Rol_rolId = Rol.rolId INNER JOIN
	StatusUser ON `User`.Status_statusId = StatusUser.statusUserId INNER JOIN
	Sex ON Assistant.Sex_sexId = Sex.sexId INNER JOIN
	DocType ON Assistant.DocType_DocTypeId = DocType.docTypeId INNER JOIN
	AcademicLevel ON Assistant.educationAcademicLevel = AcademicLevel.academicLevelId INNER JOIN
	DeviceOS ON Assistant.DeviceOS_deviceOSId = DeviceOS.deviceOSId CROSS JOIN
	CurrencyCode;
CREATE VIEW viewCancellation AS
SELECT
	Cancellation.cancellationId,
	Cancellation.cancellationDateTime,
	Cancellation.cancellationNumWeekISO,
	Cancellation.cancellationYear,
	Cancellation.Request_requestId,
	Request.requestId,
	Request.requestDateTime,
	Request.serviceDateTimeEnd,
	Request.serviceDateTimeStart,
	Request.serviceDuration,
	Request.serviceTotalAmount,
	Request.CurrencyCode_serviceTotalAmountCurrencyCode,
	Request.userRequestObservations,
	StatusPayment.name,
	`User`.userId,
	`User`.Status_statusId,
	`User`.Rol_rolId,
	Rol.name AS rol,
	StatusUser.name AS statusUserAccount,
	Request.StatusService_serviceStatusId,
	StatusService.name AS statusService,
	Request.Family_userServiceId,
	Request.Service_serviceId,
	Service.name AS serviceName,
	Family.memberId,
	Family.basicDataFirstName,
	Family.basicDataLastName,
	Relationship.name AS relationship,
	Family.Relationship_accountRelationshipId,
	Family.Rol_rolId AS rolFamilyId,
	RolFamily.name AS rolFamily,
	Family.StatusUser_statusUserId AS statusFamilyId,
	StatusUserFamily.name AS statusFamily,
	Cancellation.Assistant_assistantId,
	Assistant.assistantId,
	UserAssistant.userId AS userAssistantId,
	UserAssistant.Rol_rolId AS rolAssistantId,
	RolAssistant.name AS rolAssistant,
	UserAssistant.Status_statusId AS statusUserAssistantId,
	StatusUserAssistant.name AS statusUserAssistant
FROM
	`User` INNER JOIN
	Family ON `User`.userId = Family.User_userId INNER JOIN
	Rol ON `User`.Rol_rolId = Rol.rolId INNER JOIN
	StatusUser ON `User`.Status_statusId = StatusUser.statusUserId INNER JOIN
	Request ON Family.memberId = Request.Family_userServiceId INNER JOIN
	Rol AS RolFamily ON Family.Rol_rolId = RolFamily.rolId INNER JOIN
	StatusUser AS StatusUserFamily ON Family.StatusUser_statusUserId = StatusUserFamily.statusUserId INNER JOIN
	Relationship ON Family.Relationship_accountRelationshipId = Relationship.relationshipId INNER JOIN
	Cancellation ON Request.requestId = Cancellation.Request_requestId INNER JOIN
	Service ON Request.Service_serviceId = Service.serviceId INNER JOIN
	StatusService ON Request.StatusService_serviceStatusId = StatusService.statusServiceId INNER JOIN
	Assistant ON Cancellation.Assistant_assistantId = Assistant.assistantId INNER JOIN
	`User` AS UserAssistant ON Assistant.User_AssistantId = UserAssistant.userId INNER JOIN
	Rol AS RolAssistant ON UserAssistant.Rol_rolId = RolAssistant.rolId INNER JOIN
	StatusUser AS StatusUserAssistant ON UserAssistant.Status_statusId = StatusUserAssistant.statusUserId CROSS JOIN
	StatusPayment;
CREATE VIEW viewCompany AS
SELECT
	Company.companyId,
	Company.name,
	StatusCompany.name AS statusCompany
FROM
	Company INNER JOIN
	StatusCompany ON Company.StatusCompany_statusCompanyId = StatusCompany.statusCompanyId;
CREATE VIEW viewFamily AS
SELECT
	Family.memberId,
	Family.User_userId,
	Family.basicDataBirthDate,
	Family.basicDataDisability,
	Family.basicDataDocNumber,
	Family.basicDataFirstName,
	Family.basicDataHeight,
	Family.basicDataLastName,
	Family.basicDataWeight,
	Family.personalDataCellphone,
	Family.emergencyContactCellphone,
	Family.emergencyContactNamePerson,
	Family.userEpsName,
	Family.userObservations,
	Family.StatusUser_statusUserId,
	statusUserFamily.name AS statusFamilyName,
	Family.Rol_rolId,
	rolFfamily.name AS rolName,
	Family.Avatar_avatarId,
	Avatar.name AS avatarName,
	Family.Sex_sexId,
	Sex.name AS sexName,
	Family.DocType_docTypeId,
	DocType.name AS docTypeName,
	Family.Relationship_accountRelationshipId,
	Relationship.name AS relationshipAccountName,
	Family.Relationship_emergencyContactRelationshipId,
	relationshipEmergencyContact.name AS relationshipEmergencyContactName,
	Family.Mobility_basicDataMobiilityId,
	Mobility.name AS mobilityName
FROM
	`User` INNER JOIN
	Family ON `User`.userId = Family.User_userId INNER JOIN
	Rol ON `User`.Rol_rolId = Rol.rolId INNER JOIN
	StatusUser ON `User`.Status_statusId = StatusUser.statusUserId INNER JOIN
	Avatar ON Family.Avatar_avatarId = Avatar.avatarId INNER JOIN
	Sex ON Family.Sex_sexId = Sex.sexId INNER JOIN
	DocType ON Family.DocType_docTypeId = DocType.docTypeId INNER JOIN
	Relationship ON Family.Relationship_accountRelationshipId = Relationship.relationshipId INNER JOIN
	Relationship AS relationshipEmergencyContact ON Family.Relationship_emergencyContactRelationshipId = relationshipEmergencyContact.relationshipId INNER JOIN
	Rol AS rolFfamily ON Family.Rol_rolId = rolFfamily.rolId INNER JOIN
	StatusUser AS statusUserFamily ON Family.StatusUser_statusUserId = statusUserFamily.statusUserId INNER JOIN
	Mobility ON Family.Mobility_basicDataMobiilityId = Mobility.mobilityId;
CREATE VIEW viewUserAccount AS
SELECT
	`User`.userId,
	`User`.accountEmail,
	`User`.accountPassword,
	`User`.accountCellphone,
	`User`.accountName,
	`User`.isEmailVerified,
	`User`.isNewUser,
	`User`.isOnline,
	`User`.photoURL,
	`User`.Rol_rolId AS rolUserAccountId,
	Rol.name AS rol,
	`User`.Status_statusId AS statusUserAccountId,
	StatusUser.name AS statusUserAccount
FROM
	`User` INNER JOIN
	Rol ON `User`.Rol_rolId = Rol.rolId INNER JOIN
	StatusUser ON `User`.Status_statusId = StatusUser.statusUserId;
CREATE VIEW viewAddress AS
SELECT
	Address.addressId,
	Address.User_userId,
	Address.addressNameType,
	Address.address,
	Address.City_addressCityId AS addressCityId,
	City.name AS addressCity,
	Address.addressInfoAdditional,
	Address.longitude,
	Address.latitude,
	Address.plusCodes,
	Address.StatusAddress_statusAddressId AS statusAddressId,
	StatusAddress.name AS statusAddress
FROM
	Address INNER JOIN
	StatusAddress ON Address.StatusAddress_statusAddressId = StatusAddress.statusAddressId INNER JOIN
	City ON Address.City_addressCityId = City.cityId;
CREATE VIEW viewService AS
SELECT
	Service.serviceId,
	Service.Company_companyId AS companyId,
	Company.name AS companyName,
	Company.StatusCompany_statusCompanyId AS statusCompanyId,
	StatusCompany.name AS statusCompanyName,
	Service.name AS serviceName,
	Service.costAmount,
	Service.TypeService_typeServiceId AS typeServiceId,
	TypeService.name AS typeServiceName,
	Service.serviceDetail,
	Service.isDayHoliday,
	Service.durationService
FROM
	Service INNER JOIN
	Company ON Service.Company_companyId = Company.companyId INNER JOIN
	TypeService ON Service.TypeService_typeServiceId = TypeService.typeServiceId INNER JOIN
	StatusCompany ON Company.StatusCompany_statusCompanyId = StatusCompany.statusCompanyId;
CREATE VIEW viewPillbox AS
SELECT
	Pillbox.pillboxId,
	Pillbox.name,
	Pillbox.quantity,
	Pillbox.frequency,
	Pillbox.typePillbox,
	Pillbox.startedAt,
	Family.memberId,
	Relationship.name AS relationshipName,
	Family.basicDataFirstName,
	Family.basicDataLastName,
	Family.StatusUser_statusUserId AS statusMemberId,
	StatusUser.name AS statusMemberName,
	Family.User_userId AS userAccountId
FROM
	Family INNER JOIN
	Pillbox ON Family.memberId = Pillbox.Family_familyId INNER JOIN
	Relationship ON Family.Relationship_accountRelationshipId = Relationship.relationshipId INNER JOIN
	StatusUser ON Family.StatusUser_statusUserId = StatusUser.statusUserId
ORDER BY
	Pillbox.pillboxId ASC;
CREATE VIEW viewDevice AS
SELECT
	Device.deviceId,
	Device.UUID,
	Device.StatusDevice_statusDeviceId AS statusDeviceId,
	StatusDevice.name AS statusDeviceName,
	Device.subscriptionDateTime,
	Device.lastConnectionDateTime,
	`User`.userId,
	`User`.accountName,
	`User`.isOnline,
	Rol.name AS rolName
FROM
	`User` INNER JOIN
	Device ON `User`.userId = Device.User_userId INNER JOIN
	Rol ON `User`.Rol_rolId = Rol.rolId INNER JOIN
	StatusDevice ON Device.StatusDevice_statusDeviceId = StatusDevice.StatusDeviceId;
ALTER TABLE Family ADD CONSTRAINT userAccount_family FOREIGN KEY (User_userId) REFERENCES `User` (userId);
ALTER TABLE `User` ADD CONSTRAINT rol_userAccount FOREIGN KEY (Rol_rolId) REFERENCES Rol (rolId);
ALTER TABLE `User` ADD CONSTRAINT statusUser_userAccount FOREIGN KEY (Status_statusId) REFERENCES StatusUser (statusUserId);
ALTER TABLE Family ADD CONSTRAINT statusUser_family FOREIGN KEY (StatusUser_statusUserId) REFERENCES StatusUser (statusUserId);
ALTER TABLE Family ADD CONSTRAINT rol_family FOREIGN KEY (Rol_rolId) REFERENCES Rol (rolId);
ALTER TABLE Family ADD CONSTRAINT avatar_family FOREIGN KEY (Avatar_avatarId) REFERENCES Avatar (avatarId);
ALTER TABLE Family ADD CONSTRAINT sex_family FOREIGN KEY (Sex_sexId) REFERENCES Sex (sexId);
ALTER TABLE Family ADD CONSTRAINT docType_family FOREIGN KEY (DocType_docTypeId) REFERENCES DocType (docTypeId);
ALTER TABLE Family ADD CONSTRAINT relationshipAccount_family FOREIGN KEY (Relationship_accountRelationshipId) REFERENCES Relationship (relationshipId);
ALTER TABLE Family ADD CONSTRAINT relationshipEmergencyContact_family FOREIGN KEY (Relationship_emergencyContactRelationshipId) REFERENCES Relationship (relationshipId);
ALTER TABLE TransactionPaymentGateway ADD CONSTRAINT statusPayment_request FOREIGN KEY (StatusPayment_statusPaymentId) REFERENCES StatusPayment (statusPaymentId);
ALTER TABLE Request ADD CONSTRAINT statusService_request FOREIGN KEY (StatusService_serviceStatusId) REFERENCES StatusService (statusServiceId);
ALTER TABLE Request ADD CONSTRAINT userService_request FOREIGN KEY (Family_userServiceId) REFERENCES Family (memberId);
ALTER TABLE Request ADD CONSTRAINT service_request FOREIGN KEY (Service_serviceId) REFERENCES Service (serviceId);
ALTER TABLE Cancellation ADD CONSTRAINT request_cancellation FOREIGN KEY (Request_requestId) REFERENCES Request (requestId);
ALTER TABLE Provieded ADD CONSTRAINT request_provieded FOREIGN KEY (Request_requestId) REFERENCES Request (requestId);
ALTER TABLE RoomChat ADD CONSTRAINT provieded_roomChat FOREIGN KEY (Provieded_proviededId) REFERENCES Provieded (proviededId);
ALTER TABLE RoomChat ADD CONSTRAINT statusRoomChat_roomChat FOREIGN KEY (StatusRoomChat_statusRoomChatId) REFERENCES StatusRoomChat (statusRoomChatId);
ALTER TABLE MessageChat ADD CONSTRAINT user_messageChat FOREIGN KEY (User_fromId) REFERENCES `User` (userId);
ALTER TABLE Support ADD CONSTRAINT userAssistant_support FOREIGN KEY (User_AssistantId) REFERENCES `User` (userId);
ALTER TABLE MessageChat ADD CONSTRAINT roomChat_messageChat FOREIGN KEY (RoomChat_roomChatId) REFERENCES RoomChat (roomChatId);
ALTER TABLE MessageSupport ADD CONSTRAINT user_messageSupport FOREIGN KEY (User_fromId) REFERENCES `User` (userId);
ALTER TABLE MessageSupport ADD CONSTRAINT support_messageSupport FOREIGN KEY (Support_supportId) REFERENCES Support (supportId);
ALTER TABLE Support ADD CONSTRAINT statusRoomChat_support FOREIGN KEY (StatusRoomChat_statusRoomId) REFERENCES StatusRoomChat (statusRoomChatId);
ALTER TABLE Assistant ADD CONSTRAINT educationAcademicLevel_Assistant FOREIGN KEY (educationAcademicLevel) REFERENCES AcademicLevel (academicLevelId);
ALTER TABLE Assistant ADD CONSTRAINT user_assistant FOREIGN KEY (User_AssistantId) REFERENCES `User` (userId);
ALTER TABLE Assistant ADD CONSTRAINT deviceOS_Assistant FOREIGN KEY (DeviceOS_deviceOSId) REFERENCES DeviceOS (deviceOSId);
ALTER TABLE Provieded ADD CONSTRAINT assistant_provieded FOREIGN KEY (Assistant_assistantId) REFERENCES Assistant (assistantId);
ALTER TABLE Request ADD CONSTRAINT currencyCode_requestCurrencyCodeTotalAmount FOREIGN KEY (CurrencyCode_serviceTotalAmountCurrencyCode) REFERENCES CurrencyCode (currencyCodeId);
ALTER TABLE Assistant ADD CONSTRAINT Sex_Assistant FOREIGN KEY (Sex_sexId) REFERENCES Sex (sexId);
ALTER TABLE Assistant ADD CONSTRAINT DocType_Assistant FOREIGN KEY (DocType_DocTypeId) REFERENCES DocType (docTypeId);
ALTER TABLE ProfessionalSpecialty ADD CONSTRAINT assistant_professionalSpecialty FOREIGN KEY (Assistant_assistantId) REFERENCES Assistant (assistantId);
ALTER TABLE Assistant ADD CONSTRAINT currencyCode_professionalCurrencyCodeSalaryAspiration FOREIGN KEY (CurrencyCode_professionalCurrencyCodeSalaryAspirationId) REFERENCES CurrencyCode (currencyCodeId);
ALTER TABLE Cancellation ADD CONSTRAINT Assistant_cancellation FOREIGN KEY (Assistant_assistantId) REFERENCES Assistant (assistantId);
ALTER TABLE Notification ADD CONSTRAINT user_notificacion FOREIGN KEY (User_userId) REFERENCES `User` (userId);
ALTER TABLE Address ADD CONSTRAINT user_address FOREIGN KEY (User_userId) REFERENCES `User` (userId);
ALTER TABLE Coupon ADD CONSTRAINT statusCoupon_coupon FOREIGN KEY (StatusCoupon_statusCouponId) REFERENCES StatusCoupon (statusCouponId);
ALTER TABLE Coupon ADD CONSTRAINT user_coupon FOREIGN KEY (User_userId) REFERENCES `User` (userId);
ALTER TABLE Request ADD CONSTRAINT address_request FOREIGN KEY (Address_addressId) REFERENCES Address (addressId);
ALTER TABLE Service ADD CONSTRAINT company_service FOREIGN KEY (Company_companyId) REFERENCES Company (companyId);
ALTER TABLE Company ADD CONSTRAINT statusCompany_company FOREIGN KEY (StatusCompany_statusCompanyId) REFERENCES StatusCompany (statusCompanyId);
ALTER TABLE Address ADD CONSTRAINT stateAddress_Address FOREIGN KEY (StatusAddress_statusAddressId) REFERENCES StatusAddress (statusAddressId);
ALTER TABLE Service ADD CONSTRAINT typeService_service FOREIGN KEY (TypeService_typeServiceId) REFERENCES TypeService (typeServiceId);
ALTER TABLE Device ADD CONSTRAINT user_device FOREIGN KEY (User_userId) REFERENCES `User` (userId);
ALTER TABLE Device ADD CONSTRAINT statusDevice_device FOREIGN KEY (StatusDevice_statusDeviceId) REFERENCES StatusDevice (StatusDeviceId);
ALTER TABLE Device_Notification ADD CONSTRAINT device_deviceNotification FOREIGN KEY (Deviceid) REFERENCES Device (deviceId);
ALTER TABLE Device_Notification ADD CONSTRAINT notification_deviceNotification FOREIGN KEY (Notificationid) REFERENCES Notification (notificationId);
ALTER TABLE Family ADD CONSTRAINT mobility_family FOREIGN KEY (Mobility_basicDataMobiilityId) REFERENCES Mobility (mobilityId);
ALTER TABLE Address ADD CONSTRAINT city_address FOREIGN KEY (City_addressCityId) REFERENCES City (cityId);
ALTER TABLE Pillbox ADD CONSTRAINT family_pillbox FOREIGN KEY (Family_familyId) REFERENCES Family (memberId);
ALTER TABLE Pillbox ADD CONSTRAINT frequency_pillbox FOREIGN KEY (Frequency_frequencyId) REFERENCES Frequency (frequencyId);
ALTER TABLE Pillbox ADD CONSTRAINT typePillbox_pillbox FOREIGN KEY (TypePillbox_typePillboxId) REFERENCES TypePillbox (typePillboxId);
ALTER TABLE Product ADD CONSTRAINT typeProduct_product FOREIGN KEY (TypeProduct_typeProductId) REFERENCES TypeProduct (typeProductId);
ALTER TABLE Product ADD CONSTRAINT company_product FOREIGN KEY (Company_companyId) REFERENCES Company (companyId);
ALTER TABLE Request ADD CONSTRAINT transactionPaymentGateway_request FOREIGN KEY (TransactionPaymentGateway_transactionPaymentGatewayId) REFERENCES TransactionPaymentGateway (transactionPaymentGatewayId);
ALTER TABLE `User` ADD CONSTRAINT company_user FOREIGN KEY (Company_companyId) REFERENCES Company (companyId);
ALTER TABLE PurchaseOrder ADD CONSTRAINT address_purcheseOrder FOREIGN KEY (Address_addressId) REFERENCES Address (addressId);
ALTER TABLE PurchaseOrder ADD CONSTRAINT product_purchaseOrder FOREIGN KEY (Product_productId) REFERENCES Product (productId);
ALTER TABLE PurchaseOrder ADD CONSTRAINT transactionPaymentGateway_purchaseOrder FOREIGN KEY (TransactionPaymentGateway_transactionPaymentGatewayId) REFERENCES TransactionPaymentGateway (transactionPaymentGatewayId);
ALTER TABLE PurchaseOrder ADD CONSTRAINT currencyCode_purchaseOrder FOREIGN KEY (CurrencyCode_productTotalAmount) REFERENCES CurrencyCode (currencyCodeId);
ALTER TABLE Notification ADD CONSTRAINT typeNotification_notification FOREIGN KEY (TypeNotification_typeNotificationId) REFERENCES TypeNotification (typeNotificationId);
