-- --------------------------------------------------------
-- Host:                         127.0.0.1
-- Versión del servidor:         10.4.6-MariaDB - mariadb.org binary distribution
-- SO del servidor:              Win64
-- HeidiSQL Versión:             10.2.0.5599
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;

-- Volcando datos para la tabla teella.AcademicLevel: ~8 rows (aproximadamente)
/*!40000 ALTER TABLE `AcademicLevel` DISABLE KEYS */;
INSERT INTO `AcademicLevel` (`academicLevelId`, `name`) VALUES
	(2, 'Bachillerato'),
	(8, 'Doctorados'),
	(6, 'Especializaciones '),
	(7, 'Maestrías'),
	(1, 'Primaria'),
	(5, 'Profesional '),
	(3, 'Técnico'),
	(4, 'Tecnológico ');
/*!40000 ALTER TABLE `AcademicLevel` ENABLE KEYS */;

-- Volcando datos para la tabla teella.Address: ~0 rows (aproximadamente)
/*!40000 ALTER TABLE `Address` DISABLE KEYS */;
INSERT INTO `Address` (`addressId`, `User_userId`, `addressNameType`, `address`, `City_addressCityId`, `addressInfoAdditional`, `longitude`, `latitude`, `plusCodes`, `StatusAddress_statusAddressId`, `createdAt`) VALUES
	(1, 1, '', 'Calle 24 # 40 - 58 ', 1, 'Frente ...', '', '', '', 1, 1571946299);
/*!40000 ALTER TABLE `Address` ENABLE KEYS */;

-- Volcando datos para la tabla teella.Assistant: ~0 rows (aproximadamente)
/*!40000 ALTER TABLE `Assistant` DISABLE KEYS */;
/*!40000 ALTER TABLE `Assistant` ENABLE KEYS */;

-- Volcando datos para la tabla teella.Avatar: ~4 rows (aproximadamente)
/*!40000 ALTER TABLE `Avatar` DISABLE KEYS */;
INSERT INTO `Avatar` (`avatarId`, `name`) VALUES
	(3, 'adultMan'),
	(4, 'adultWoman'),
	(1, 'elderMan'),
	(2, 'elderWoman');
/*!40000 ALTER TABLE `Avatar` ENABLE KEYS */;

-- Volcando datos para la tabla teella.Cancellation: ~0 rows (aproximadamente)
/*!40000 ALTER TABLE `Cancellation` DISABLE KEYS */;
/*!40000 ALTER TABLE `Cancellation` ENABLE KEYS */;

-- Volcando datos para la tabla teella.City: ~0 rows (aproximadamente)
/*!40000 ALTER TABLE `City` DISABLE KEYS */;
INSERT INTO `City` (`cityId`, `name`) VALUES
	(1, 'Bogotá D.C.');
/*!40000 ALTER TABLE `City` ENABLE KEYS */;

-- Volcando datos para la tabla teella.Company: ~3 rows (aproximadamente)
/*!40000 ALTER TABLE `Company` DISABLE KEYS */;
INSERT INTO `Company` (`companyId`, `name`, `StatusCompany_statusCompanyId`) VALUES
	(1, 'Innlab Company', 1),
	(2, 'Teella', 1),
	(3, 'Fundación ABC', 1);
/*!40000 ALTER TABLE `Company` ENABLE KEYS */;

-- Volcando datos para la tabla teella.Coupon: ~0 rows (aproximadamente)
/*!40000 ALTER TABLE `Coupon` DISABLE KEYS */;
/*!40000 ALTER TABLE `Coupon` ENABLE KEYS */;

-- Volcando datos para la tabla teella.CurrencyCode: ~0 rows (aproximadamente)
/*!40000 ALTER TABLE `CurrencyCode` DISABLE KEYS */;
INSERT INTO `CurrencyCode` (`currencyCodeId`, `name`) VALUES
	(1, 'COP');
/*!40000 ALTER TABLE `CurrencyCode` ENABLE KEYS */;

-- Volcando datos para la tabla teella.Device: ~0 rows (aproximadamente)
/*!40000 ALTER TABLE `Device` DISABLE KEYS */;
/*!40000 ALTER TABLE `Device` ENABLE KEYS */;

-- Volcando datos para la tabla teella.DeviceOS: ~2 rows (aproximadamente)
/*!40000 ALTER TABLE `DeviceOS` DISABLE KEYS */;
INSERT INTO `DeviceOS` (`deviceOSId`, `name`) VALUES
	(1, 'Android'),
	(2, 'iOS');
/*!40000 ALTER TABLE `DeviceOS` ENABLE KEYS */;

-- Volcando datos para la tabla teella.Device_Notification: ~0 rows (aproximadamente)
/*!40000 ALTER TABLE `Device_Notification` DISABLE KEYS */;
/*!40000 ALTER TABLE `Device_Notification` ENABLE KEYS */;

-- Volcando datos para la tabla teella.DocType: ~3 rows (aproximadamente)
/*!40000 ALTER TABLE `DocType` DISABLE KEYS */;
INSERT INTO `DocType` (`docTypeId`, `name`) VALUES
	(1, 'Cédula de Ciudadanía'),
	(2, 'Cédula de Extranjería'),
	(3, 'Tarjeta de Identidad');
/*!40000 ALTER TABLE `DocType` ENABLE KEYS */;

-- Volcando datos para la tabla teella.Family: ~0 rows (aproximadamente)
/*!40000 ALTER TABLE `Family` DISABLE KEYS */;
/*!40000 ALTER TABLE `Family` ENABLE KEYS */;

-- Volcando datos para la tabla teella.Frequency: ~0 rows (aproximadamente)
/*!40000 ALTER TABLE `Frequency` DISABLE KEYS */;
/*!40000 ALTER TABLE `Frequency` ENABLE KEYS */;

-- Volcando datos para la tabla teella.MessageChat: ~0 rows (aproximadamente)
/*!40000 ALTER TABLE `MessageChat` DISABLE KEYS */;
/*!40000 ALTER TABLE `MessageChat` ENABLE KEYS */;

-- Volcando datos para la tabla teella.MessageSupport: ~0 rows (aproximadamente)
/*!40000 ALTER TABLE `MessageSupport` DISABLE KEYS */;
/*!40000 ALTER TABLE `MessageSupport` ENABLE KEYS */;

-- Volcando datos para la tabla teella.Mobility: ~3 rows (aproximadamente)
/*!40000 ALTER TABLE `Mobility` DISABLE KEYS */;
INSERT INTO `Mobility` (`mobilityId`, `name`) VALUES
	(3, 'Nula'),
	(2, 'Parcial'),
	(1, 'Total');
/*!40000 ALTER TABLE `Mobility` ENABLE KEYS */;

-- Volcando datos para la tabla teella.Notification: ~0 rows (aproximadamente)
/*!40000 ALTER TABLE `Notification` DISABLE KEYS */;
/*!40000 ALTER TABLE `Notification` ENABLE KEYS */;

-- Volcando datos para la tabla teella.Pillbox: ~0 rows (aproximadamente)
/*!40000 ALTER TABLE `Pillbox` DISABLE KEYS */;
/*!40000 ALTER TABLE `Pillbox` ENABLE KEYS */;

-- Volcando datos para la tabla teella.Product: ~0 rows (aproximadamente)
/*!40000 ALTER TABLE `Product` DISABLE KEYS */;
/*!40000 ALTER TABLE `Product` ENABLE KEYS */;

-- Volcando datos para la tabla teella.ProfessionalSpecialty: ~0 rows (aproximadamente)
/*!40000 ALTER TABLE `ProfessionalSpecialty` DISABLE KEYS */;
/*!40000 ALTER TABLE `ProfessionalSpecialty` ENABLE KEYS */;

-- Volcando datos para la tabla teella.Provieded: ~0 rows (aproximadamente)
/*!40000 ALTER TABLE `Provieded` DISABLE KEYS */;
/*!40000 ALTER TABLE `Provieded` ENABLE KEYS */;

-- Volcando datos para la tabla teella.PurchaseOrder: ~0 rows (aproximadamente)
/*!40000 ALTER TABLE `PurchaseOrder` DISABLE KEYS */;
/*!40000 ALTER TABLE `PurchaseOrder` ENABLE KEYS */;

-- Volcando datos para la tabla teella.Relationship: ~16 rows (aproximadamente)
/*!40000 ALTER TABLE `Relationship` DISABLE KEYS */;
INSERT INTO `Relationship` (`relationshipId`, `name`) VALUES
	(8, 'Abuela'),
	(7, 'Abuelo'),
	(16, 'Amiga'),
	(15, 'Amigo'),
	(6, 'Hermana'),
	(5, 'Hermano'),
	(4, 'Hija'),
	(3, 'Hijo'),
	(2, 'Madre'),
	(1, 'Padre'),
	(12, 'Prima'),
	(11, 'Primo'),
	(10, 'Tía'),
	(9, 'Tío'),
	(14, 'Yerna'),
	(13, 'Yerno');
/*!40000 ALTER TABLE `Relationship` ENABLE KEYS */;

-- Volcando datos para la tabla teella.Request: ~0 rows (aproximadamente)
/*!40000 ALTER TABLE `Request` DISABLE KEYS */;
/*!40000 ALTER TABLE `Request` ENABLE KEYS */;

-- Volcando datos para la tabla teella.Rol: ~7 rows (aproximadamente)
/*!40000 ALTER TABLE `Rol` DISABLE KEYS */;
INSERT INTO `Rol` (`rolId`, `name`) VALUES
	(1, 'Admin'),
	(2, 'RRHH'),
	(3, 'Support'),
	(4, 'Provider'),
	(5, 'Assistant'),
	(6, 'Client'),
	(7, 'Member');
/*!40000 ALTER TABLE `Rol` ENABLE KEYS */;

-- Volcando datos para la tabla teella.RoomChat: ~0 rows (aproximadamente)
/*!40000 ALTER TABLE `RoomChat` DISABLE KEYS */;
/*!40000 ALTER TABLE `RoomChat` ENABLE KEYS */;

-- Volcando datos para la tabla teella.Service: ~16 rows (aproximadamente)
/*!40000 ALTER TABLE `Service` DISABLE KEYS */;
INSERT INTO `Service` (`serviceId`, `Company_companyId`, `name`, `costAmount`, `TypeService_typeServiceId`, `serviceDetail`, `isDayHoliday`, `durationService`) VALUES
	(1, 2, 'basicCare 4Hrs LV', 3125.000, 1, 'Teella Esencial 4 Horas Dias laborales', 0, 4),
	(2, 2, 'basicCare 6Hrs LV', 2604.167, 1, 'Teella Esencial 6 Horas Dias laborales', 0, 6),
	(3, 2, 'basicCare 8Hrs LV', 2322.635, 1, 'Teella Esencial 8 Horas Días laborales', 0, 8),
	(4, 2, 'basicCare 12Hrs LV', 1944.444, 1, 'Teella Esencial 12 Horas Días laborales', 0, 12),
	(5, 2, 'basicCare 4Hrs DF', 3906.250, 1, 'Teella Esencial 4 Horas Días no laborales', 1, 4),
	(6, 2, 'basicCare 6Hrs DF', 3255.208, 1, 'Teella Esencial 6 Horas Días no laborales', 1, 6),
	(7, 2, 'basicCare 8Hrs DF', 2903.294, 1, 'Teella Esencial 8 Horas Días no laborales', 1, 8),
	(8, 2, 'basicCare 12Hrs DF', 2430.556, 1, 'Teella Esencial 12 Horas Días no laborales', 1, 12),
	(9, 2, 'profesionalCare 4Hrs LV', 4017.857, 2, 'Teella Profesional 4 Horas Dias laborales', 0, 4),
	(10, 2, 'profesionalCare 6Hrs LV', 3182.870, 2, 'Teella Profesional 6 Horas Dias laborales', 0, 6),
	(11, 2, 'profesionalCare 8Hrs LV', 2744.932, 2, 'Teella Profesional 8 Horas Dias laborales', 0, 8),
	(12, 2, 'profesionalCare 12Hrs LV', 2222.222, 2, 'Teella Profesional 12 Horas Dias laborales', 0, 12),
	(13, 2, 'profesionalCare 4Hrs DF', 5022.321, 2, 'Teella Profesional 4 Horas Dias no laborales', 1, 4),
	(14, 2, 'profesionalCare 6Hrs DF', 3978.588, 2, 'Teella Profesional 6 Horas Dias no laborales', 1, 6),
	(15, 2, 'profesionalCare 8Hrs DF', 3431.166, 2, 'Teella Profesional 8 Horas Dias no laborales', 1, 8),
	(16, 2, 'profesionalCare 12Hrs DF', 2777.778, 2, 'Teella Profesional 12 Horas Dias no laborales', 1, 12);
/*!40000 ALTER TABLE `Service` ENABLE KEYS */;

-- Volcando datos para la tabla teella.Sex: ~2 rows (aproximadamente)
/*!40000 ALTER TABLE `Sex` DISABLE KEYS */;
INSERT INTO `Sex` (`sexId`, `name`) VALUES
	(2, 'Femenino'),
	(1, 'Masculino');
/*!40000 ALTER TABLE `Sex` ENABLE KEYS */;

-- Volcando datos para la tabla teella.StatusAddress: ~2 rows (aproximadamente)
/*!40000 ALTER TABLE `StatusAddress` DISABLE KEYS */;
INSERT INTO `StatusAddress` (`statusAddressId`, `name`) VALUES
	(1, 'Active'),
	(2, 'Inactive');
/*!40000 ALTER TABLE `StatusAddress` ENABLE KEYS */;

-- Volcando datos para la tabla teella.StatusCompany: ~2 rows (aproximadamente)
/*!40000 ALTER TABLE `StatusCompany` DISABLE KEYS */;
INSERT INTO `StatusCompany` (`statusCompanyId`, `name`) VALUES
	(1, 'Active'),
	(2, 'Inactive');
/*!40000 ALTER TABLE `StatusCompany` ENABLE KEYS */;

-- Volcando datos para la tabla teella.StatusCoupon: ~0 rows (aproximadamente)
/*!40000 ALTER TABLE `StatusCoupon` DISABLE KEYS */;
/*!40000 ALTER TABLE `StatusCoupon` ENABLE KEYS */;

-- Volcando datos para la tabla teella.StatusDevice: ~2 rows (aproximadamente)
/*!40000 ALTER TABLE `StatusDevice` DISABLE KEYS */;
INSERT INTO `StatusDevice` (`StatusDeviceId`, `name`) VALUES
	(1, 'Active'),
	(2, 'Inactive');
/*!40000 ALTER TABLE `StatusDevice` ENABLE KEYS */;

-- Volcando datos para la tabla teella.StatusPayment: ~7 rows (aproximadamente)
/*!40000 ALTER TABLE `StatusPayment` DISABLE KEYS */;
INSERT INTO `StatusPayment` (`statusPaymentId`, `name`, `status`, `description`) VALUES
	(1, 'CREADA', 'pending', 'Se ha creado el orden de compra.'),
	(2, 'EN PROCESO', 'pending', 'El cliente esta proceso de pago.'),
	(3, 'PENDIENTE', 'pending', 'Espera por el cliente realiza pago.'),
	(4, 'APROBADA', 'approved', 'Se ha recibido el pago correspondiente'),
	(5, 'VENCIDA', 'cancelled', 'No se ha recibido el pago correspondiente antes de la fecha de vencimiento'),
	(6, 'CANCELADA', 'cancelled', 'Se ha eliminado la orden de compra durante el proceso de pago'),
	(7, 'ANULADA', 'cancelled', 'Se ha realizado devolución del pago realizado');
/*!40000 ALTER TABLE `StatusPayment` ENABLE KEYS */;

-- Volcando datos para la tabla teella.StatusRoomChat: ~0 rows (aproximadamente)
/*!40000 ALTER TABLE `StatusRoomChat` DISABLE KEYS */;
/*!40000 ALTER TABLE `StatusRoomChat` ENABLE KEYS */;

-- Volcando datos para la tabla teella.StatusService: ~12 rows (aproximadamente)
/*!40000 ALTER TABLE `StatusService` DISABLE KEYS */;
INSERT INTO `StatusService` (`statusServiceId`, `name`, `nameDisplay`, `description`) VALUES
	(1, 'newFillRequest', '', NULL),
	(2, 'progressPayment', 'PAGO EN PROCESO', NULL),
	(3, 'pendingPayment', 'PENDIENTE DE PAGO', NULL),
	(4, 'searching', 'CONFIRMADO', NULL),
	(5, 'confirmed', 'CONFIRMADO', NULL),
	(6, 'onTheRoad', NULL, NULL),
	(7, 'started', 'INICIADO', NULL),
	(8, 'finished', 'REALIZADO', NULL),
	(9, 'canceled ', 'CANCELADO', ''),
	(10, 'canceledExpired', 'CANCELADO', 'CANCELADO POR VENCIDA'),
	(11, 'canceledRefund', 'CANCELADO', 'CANCELADO CON DEVOLUCIÓN'),
	(12, 'canceledPenalty', 'CANCELADO', 'CANCELADO CON SANCIÓN');
/*!40000 ALTER TABLE `StatusService` ENABLE KEYS */;

-- Volcando datos para la tabla teella.StatusUser: ~2 rows (aproximadamente)
/*!40000 ALTER TABLE `StatusUser` DISABLE KEYS */;
INSERT INTO `StatusUser` (`statusUserId`, `name`) VALUES
	(1, 'Active'),
	(2, 'Inactive');
/*!40000 ALTER TABLE `StatusUser` ENABLE KEYS */;

-- Volcando datos para la tabla teella.Support: ~0 rows (aproximadamente)
/*!40000 ALTER TABLE `Support` DISABLE KEYS */;
/*!40000 ALTER TABLE `Support` ENABLE KEYS */;

-- Volcando datos para la tabla teella.TransactionPaymentGateway: ~0 rows (aproximadamente)
/*!40000 ALTER TABLE `TransactionPaymentGateway` DISABLE KEYS */;
/*!40000 ALTER TABLE `TransactionPaymentGateway` ENABLE KEYS */;

-- Volcando datos para la tabla teella.TypeNotification: ~4 rows (aproximadamente)
/*!40000 ALTER TABLE `TypeNotification` DISABLE KEYS */;
INSERT INTO `TypeNotification` (`typeNotificationId`, `name`) VALUES
	(3, 'chatMessage'),
	(1, 'createdRequest'),
	(2, 'paymentCallback'),
	(4, 'singleNotification');
/*!40000 ALTER TABLE `TypeNotification` ENABLE KEYS */;

-- Volcando datos para la tabla teella.TypePillbox: ~0 rows (aproximadamente)
/*!40000 ALTER TABLE `TypePillbox` DISABLE KEYS */;
/*!40000 ALTER TABLE `TypePillbox` ENABLE KEYS */;

-- Volcando datos para la tabla teella.TypeProduct: ~0 rows (aproximadamente)
/*!40000 ALTER TABLE `TypeProduct` DISABLE KEYS */;
/*!40000 ALTER TABLE `TypeProduct` ENABLE KEYS */;

-- Volcando datos para la tabla teella.TypeService: ~2 rows (aproximadamente)
/*!40000 ALTER TABLE `TypeService` DISABLE KEYS */;
INSERT INTO `TypeService` (`typeServiceId`, `name`) VALUES
	(1, 'basicCare'),
	(2, 'professionalCare');
/*!40000 ALTER TABLE `TypeService` ENABLE KEYS */;

-- Volcando datos para la tabla teella.User: ~0 rows (aproximadamente)
/*!40000 ALTER TABLE `User` DISABLE KEYS */;
INSERT INTO `User` (`userId`, `lastConnectionDateTime`, `accountEmail`, `accountPassword`, `accountCellphone`, `accountName`, `isEmailVerified`, `isNewUser`, `isOnline`, `photoURL`, `Rol_rolId`, `Status_statusId`, `tokenFacebook`, `tokenGoogle`, `createdAt`, `tempPasswordToken`, `tokenPasswordExpiredAt`, `Company_companyId`) VALUES
	(1, 1576104514, 'mangarcia_901@hotmail.com', '', NULL, 'Manuel Garcia', 1, 0, 1, '', 6, 1, '10156215273509821', NULL, 0, NULL, NULL, NULL);
/*!40000 ALTER TABLE `User` ENABLE KEYS */;

/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IF(@OLD_FOREIGN_KEY_CHECKS IS NULL, 1, @OLD_FOREIGN_KEY_CHECKS) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
