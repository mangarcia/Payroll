using System;
public class Assistant {
	private int assistantId;
	private String basicDataDocNumber;
	private String basicDataFirstName;
	private String basicDataLastName;
	private String basicDataPhoto;
	private Date basicDataBirthDate;
	private String basicDataBirthPlaceCity;
	private Boolean basicDataDisabilityHave;
	private String basicDataDisabilityWhich;
	private String personalDataTelephone;
	private String personalDataCellphone;
	private String personalDataAddress;
	private String personalDataAddressLocality;
	private String personalDataAddressCity;
	private Float deviceVersion;
	private String professionalJobTitle;
	private Integer professionalSalaryAspiration;
	private String professionalResume;
	private Boolean experienceAlzheimer;
	private Boolean experienceParkinson;
	private Boolean experienceACV;
	private Boolean experiencePsychiatric;
	private Boolean experienceDisability;
	private Boolean experienceOther;
	private Boolean verificationBackgroundFiscalia;
	private Boolean verificationBackgroundPolicia;
	private Boolean verificationBackgroundProcuraduria;
	private String socialBenefitsHealth;
	private String socialBenefitsPension;
	private String socialBenefitsARL;
	private Boolean testPersonalityTest;
	private Boolean testWartegg;
	private Boolean testFiguraHuman;
	private Boolean capacitationsTeellaTraining;
	private Boolean capacitationsUniversityTraining;
	private Boolean domiciliaryVisit;
	private String observation;
	private Boolean suitableService;
	private String professionalTeellaPhoto;
	private String professionalTeellaProfessionalProfile;
	private Boolean professionalTeellaCaregiverTypeProfessional;
	private Boolean professionalTeellaCaregiverTypeBasic;
	private Boolean professionalTeellaCaregiverTypeOther;

	private Provieded[] provieded;
	private ProfessionalSpecialty[] professionalSpecialty;
	private Cancellation[] cancellation;

	private AcademicLevel educationAcademicLevel;
	private User user_Assistant;
	private DeviceOS deviceOS_deviceOS;
	private Sex sex_sex;
	private DocType docType_DocType;
	private CurrencyCode currencyCode_professionalCurrencyCodeSalaryAspiration;

}
