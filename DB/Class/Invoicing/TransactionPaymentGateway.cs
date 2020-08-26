using System;
namespace Invoicing {
	public class TransactionPaymentGateway {
		private int transactionPaymentGatewayId;
		private String refNumber;
		private String orderIdentifier;
		private Integer creationDateTime;
		private Integer updateDateTime;
		private String orderDescription;
		private String method;
		private String checkoutURL;

		private Request request;
		private Product.PurchaseOrder[] purchaseOrder;

		private StatusPayment statusPayment_paymentStatus;

	}

}
