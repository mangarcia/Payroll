using System;
public class Request {
	private int requestId;
	private Integer requestDateTime;
	private Integer serviceDateTimeEnd;
	private Integer serviceDateTimeStart;
	private Integer serviceDuration;
	private Integer serviceTotalAmount;
	private String userRequestObservations;
	private String temp_address;
	private String temp_addressInfo;

	private Cancellation[] cancellation;
	private Provieded[] provieded;

	private StatusService statusService_serviceStatus;
	private Family family_userService;
	private Service service_service;
	private CurrencyCode currencyCode_serviceTotalAmountCurrencyCode;
	private Address address_address;
	private Invoicing.TransactionPaymentGateway transactionPaymentGateway_transactionPaymentGateway;

}
