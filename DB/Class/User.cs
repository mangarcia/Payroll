using System;
public class User {
	private int userId;
	private Integer lastConnectionDateTime;
	private String accountEmail;
	private String accountPassword;
	private String accountCellphone;
	private String accountName;
	private Boolean isEmailVerified;
	private Boolean isNewUser;
	private Boolean isOnline;
	private String photoURL;
	private String tokenFacebook;
	private String tokenGoogle;
	private int createdAt;
	private String tempPasswordToken;
	private Integer tokenPasswordExpiredAt;

	private Family[] family;
	private MessageChat[] messageChat;
	private Support[] support;
	private MessageSupport[] messageSupport;
	private Assistant assistant;
	private Notification[] notification;
	private Address[] address;
	private Coupon[] coupon;
	private Device[] device;

	private Rol rol_rol;
	private StatusUser status_status;
	private Company company_company;

}
