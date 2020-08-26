var sexArray = [];
var docTypesArray = [];
var academicLevelsArray = [];

var cropper;

var currentRutPath;

$(document).ready(function () {
  $("#create-location-company-modal-button").click(function () {
    var locationid;
    locationid = $("#idCompanyLocation").val();

    createCompanyLocations();
  });

  $(".money").change(function (event) {
    var n = $(".money").val();
    n = n.replace("CA$", "");
    n = n.replace(",", "");
    // setup formatters
    const formatter = new Intl.NumberFormat("en-us", {
      style: "currency",
      currency: "CAD",
    });
   

    $(".money").val(formatter.format(n));
  });

  $("#CompanyHaveLoan").change(function () {
    let estado;
    estado = $("#CompanyHaveLoan").val();
    if (estado == 0) {
      $("#CompanyLoanMaxAmmount").prop("disabled", "disabled");
    } else {
      $("#CompanyLoanMaxAmmount").prop("disabled", false);
    }
  });

  $("#Fee").change(function () {
    let estado;
    estado = $("#Fee").val();

    if (estado == 0 || estado == "") {
      $("#PayablePaysFee").prop("disabled", "disabled");
    } else {
      $("#PayablePaysFee").prop("disabled", false);
    }
  });

  $("#home-tab").on("click", function (e) {
    e.preventDefault();
    $(this).tab("show");
    loadCompanies();
  });

  $("#contact-tab").on("click", function (e) {
    e.preventDefault();
    $(this).tab("show");
    loadCompanies();
    loadTypes();
  });

  $("#main-menu-company").addClass("text-primary");
  formValidator.Validate("#create-company-form", createCompany);
  loadCompanies();
  loadStatus();
  loadTypes();
  loadLocationsStatus();

  $(document).on("change", "#create-company-img-file", function () {
    loadCompanySelectedImage(this);
  });

  $(document).on("click", ".create-company-link", function (ev) {
    ev.preventDefault();
    openCompanyFormModal();
  });

  $(document).on("click", ".edit-company-link", function (ev) {
    ev.preventDefault();
    openCompanyFormModal($(this).data("company"));
  });

  $(document).on("click", ".create-location-company-link", function (ev) {
    ev.preventDefault();
    openLocationCompanyFormModal();
  });

  $(document).on("click", ".edit-location-company-link", function (ev) {
    ev.preventDefault();
    openLocationCompanyFormModal($(this).data("company"));
  });

  $("#open-create-location").click(function () {
    //  $("#create-location-company-modal").modal();
  });

  $(document).on("hidden.bs.modal", "#create-company-modal", function () {
    var form = $("#create-company-form");
    form.validate().resetForm(); // clear out the validation errors
    form[0].reset();
    $(".card-img-top").prop("src", USER_IMAGE_PLACEHOLDER);
    $(".card-img-top").prop("src", USER_IMAGE_PLACEHOLDER);
    $("#companyId").val("");
    $("#CompanyName").prop("disabled", false);
    $("#companyCurrentMoney").prop("disabled", true);
    $("#companyDebt").prop("disabled", true);
    $("#PaymentPeriod option:first").prop("selected", true).trigger("change");

    $(".list").html("");
  });

  $(document).on(
    "hidden.bs.modal",
    "#create-location-company-modal",
    function () {
      var form = $("#create-location-company-form");
      form.validate().resetForm();
      form[0].reset();
    }
  );

  $(document).on("keyup", "#searchCompanyInput", function () {
    $(".company-list-thumb").addClass("d-none").removeClass("d-flex");
    $(".company-list-thumb:contains('" + $(this).val() + "')")
      .removeClass("d-none")
      .addClass("d-flex");
  });

  $(document).on("click", "#show-company-rut-button", function (ev) {
    ev.preventDefault();
    if (currentRutPath != "") {
      window.open(currentRutPath);
    }
  });
});

var openCompanyFormModal = function (company = false) {
  var title = company
    ? "<i class='iconsminds-file-edit with-rotate-icon'></i> Edit "
    : "<i class='iconsminds-add-user'></i> New Company";

  var label = company ? "Save" : "Create";

  $("#create-company-modal-title").html(title);
  $("#create-company-modal-button").html(label);

  if (company) setEditCompanyFormValues(company);
  $("#create-company-modal").modal();


};

var openLocationCompanyFormModal = function (company = false) {
  var title = company
    ? "<i class='iconsminds-file-edit with-rotate-icon'></i> Edit "
    : "<i class='iconsminds-add-user'></i> New Location Company";

  var label = company ? "Save" : "Create";

  $("#create-location-company-modal-title").html(title);
  $("#create-location-company-modal-button").html(label);

  if (company) setEditLocationCompanyFormValues(company);
  $("#create-location-company-modal").modal();
};

var setEditLocationCompanyFormValues = function (company) {
  var companyName = "";

  $.each(company, function (key, value) {
    if (key == "CompanyLocationDescription") {
      $("#CompanyLocationDescription").val(value);
    }
    if (key == "CompanyLocationAddress") {
      $("#CompanyLocationAddress").val(value);
    }
    if (key == "idCompanyLocation") {
      $("#idCompanyLocation").val(value);
    }
    if (key == "CompanyLocationStatusId") {
      $("#CompanyLocationStatusId option[value='" + value + "']")
        .attr("selected", true)
        .trigger("change");
    }
   


 
  });
};

var setEditCompanyFormValues = function (company) {
  var companyName = "";

  $.each(company.cities, function (index, key, value) {
    $(".list").append(
      " <tr value=" +
        company.cities[index].City_idCity +
        "><td>" +
        company.cities[index].cityName +
        "</td><td>" +
        company.cities[index].DeliveryValue +
        "</td><td><button type=button class='btn btn-outline-danger mb-1'> Eliminar</button></tr>"
    );
  });

  $.each(company, function (key, value) {
    $("#create-company-form [name='" + key + "']").val(value);
    $("#create-company-form input[type='checkbox'][name='" + key + "']").prop(
      "checked",
      value == 1
    );
    $("#create-company-form textarea[name='" + key + "']").html(value);

    if (key == "StatusCompany_statusCompanyId") {
      $("#companyStatus_id option[value='" + value + "']")
        .attr("selected", true)
        .trigger("change");
    }


    if (key == "CompanyLoanMaxAmmount") {
            
       const formatter = new Intl.NumberFormat("en-us", {
         style: "currency",
         currency: "CAD",
       });
      
   
         $("#CompanyLoanMaxAmmount").val(formatter.format(value));
       }

    if (key == "CompanyTypeId") {
      $("#CompanyTypeId option[value='" + value + "']")
        .attr("selected", true)
        .trigger("change");
    }

    if (key == "CompanyName") {
      $("#CompanyName").prop("disabled", true);
    }

    if (key == "PaymentPeriod") {
      $("#PaymentPeriod option[value='" + value + "']")
        .attr("selected", true)
        .trigger("change");
    }

    if (key == "PayablePaysFee") {
      $("#PayablePaysFee option[value='" + value + "']")
        .attr("selected", true)
        .trigger("change");
    }

    if (key == "CompanyType") {
      $("#types option[value='" + value + "']")
        .attr("selected", true)
        .trigger("change");
    }

    if (key == "companyDebt") {
      const formatter = new Intl.NumberFormat("en-us", {
        style: "currency",
        currency: "CAD",
      });
  
  
        $("#companyDebt").val(formatter.format(value));

      $("#companyDebt").prop("disabled", true);
    }
    if (key == "Fee") {
      if (value == 0 || value == "") {
        $("#PayablePaysFee").prop("disabled", "disabled");
      } else {
        $("#PayablePaysFee").prop("disabled", false);
      }
    }
    if (key == "companyCurrentMoney") {
    //  $("#companyCurrentMoney").val(value);

      const formatter = new Intl.NumberFormat("en-us", {
        style: "currency",
        currency: "CAD",
      });
  
  
        $("#companyCurrentMoney").val(formatter.format(value));

      $("#companyCurrentMoney").prop("disabled", true);
    }

    if (key == "CompanyHaveLoan") {
      $("#CompanyHaveLoan option[value='" + value + "']")
        .attr("selected", true)
        .trigger("change");

      $("#CompanyLoanMaxAmmount").prop("disabled", "disabled");

      if (value == 1) {
        $("#CompanyLoanMaxAmmount").prop("disabled", false);
      }

    }

    if (key == "name") {
      companyName = value;
      document.getElementById("companyNameLabel").innerHTML = companyName;
    }

    if (key == "nit") {
      document.getElementById("companyNitLabel").innerHTML = "NIT.  " + value;
    }
    if (key == "rutURL") {
      if (value == "") {
        currentRutPath = "";
        document.getElementById("show-company-rut-button").style.visibility =
          "hidden";
      } else {
        currentRutPath = value;
        document.getElementById("show-company-rut-button").style.visibility =
          "visible";
        document.getElementById("Rut_input_name").innerHTML =
          "Rut_" + companyName + ".pdf";
      }
    }
  });

  $("#create-company-img").attr("src", company.CompanyImageUrl);
  $("#create-AppDisabled-img").attr("src", company.imageAppDisabled);
  $("#create-AppEnabled-img").attr("src", company.imageAppEnabled);

  loadCompanyLocations();
};

var loadCompanySelectedImage = async function (input) {
  var loadedImage = await getInputImageData(input);
  $("#create-company-img").prop("src", loadedImage);

  var image = document.querySelector("#create-company-img-file");
  encodeImageFileAsURL(image);

  function encodeImageFileAsURL(element) {
    var file = element.files[0];
    var reader = new FileReader();
    reader.onloadend = function () {
      cropper = reader.result;
    };
    reader.readAsDataURL(file);
  }
};

var loadStatus = async function () {
  let Status = await webClient.RequestAsync(
    "company/getCompanyStatus",
    "",
    webClient.ContentType.DEFAULT
  );
  if (Status.status === REQUEST_STATUS.ERROR) return;
  if (
    typeof Status.data === "undefined" ||
    Status.data === "" ||
    Status.data.length === 0
  )
    return;

  showStatus(Status.data);
};

var loadLocationsStatus = async function () {
  let Status = await webClient.RequestAsync(
    "Company/getCompanyLocationsStatus",
    "",
    webClient.ContentType.DEFAULT
  );
  if (Status.status === REQUEST_STATUS.ERROR) return;
  if (
    typeof Status.data === "undefined" ||
    Status.data === "" ||
    Status.data.length === 0
  )
    return;

  showLocationsStatus(Status.data);
};

var showLocationsStatus = function (Status) {
  $.each(Status, function (index, stat) {
    var drow = JSON.stringify(stat);

    $("#CompanyLocationStatusId").append(
      '<option value= "' +
        stat.IdCompanyLocationStatus +
        '">' +
        stat.CompanyLocationStatusDescription +
        "</option>"
    );
  });
};

var loadTypes = async function () {
  let Types = await webClient.RequestAsync(
    "Company/getCompanyTypes",
    "",
    webClient.ContentType.DEFAULT
  );

  if (Types.status === REQUEST_STATUS.ERROR) return;
  if (
    typeof Types.data === "undefined" ||
    Types.data === "" ||
    Types.data.length === 0
  )
    return;

  showTypes(Types.data);
};

var loadCompanies = async function () {
  let Companies = await webClient.RequestAsync(
    "company/getAllCompany",
    "",
    webClient.ContentType.DEFAULT
  );
  if (Companies.status === REQUEST_STATUS.ERROR) return;
  if (
    typeof Companies.data === "undefined" ||
    Companies.data === "" ||
    Companies.data.length === 0
  )
    return;

  showCompanies(Companies.data);
};

var showStatus = function (Status) {
  $.each(Status, function (index, stat) {
    $("#companyStatus_id").append(
      '<option value= "' +
        stat.idCompanyStatus +
        '">' +
        stat.CompanyStatusDescription +
        "</option>"
    );
  });
};

var showTypes = function (Types) {
  $.each(Types, function (index, type) {
    $("#CompanyTypeId").append(
      '<option value= "' +
        type.idCompanyType +
        '">' +
        type.CompanyTypeDescription +
        "</option>"
    );
  });
};

var loadCompanyLocations = async function () {
  var companyId;
  companyId += "&companyId=" + $("#idCompany").val();

  let Companies = await webClient.RequestAsync(
    "Company/getCompanyLocations",
    companyId,
    webClient.ContentType.DEFAULT
  );
  if (Companies.status === REQUEST_STATUS.ERROR) return;
  if (
    typeof Companies.data === "undefined" ||
    Companies.data === "" ||
    Companies.data.length === 0
  )
    return;

  showCompanyLocations(Companies.data);
};

var showCompanyLocations = function (Status) {
  $(".list").html("");
  $.each(Status, function (index, stat) {
    $(".list").append(
      " <tr> <td  style='display:none'>" +
        stat.idCompanyLocation +
        "</td><td>" +
        stat.CompanyLocationDescription +
        "</td><td>" +
        stat.CompanyLocationAddress +
        "</td><td> " +
        stat.CompanyLocationStatusDescription +
        "</td><td>" +
        "<a class='edit-location-company-link d-flex align-items-center' data-company='" +
        JSON.stringify(stat) +
        "' href=''>" +
        "Edit Location <h2 class='m-0'><i class='iconsminds-file-edit with-rotate-icon'></i></h2>" +
        "</a>" +
        "</td>"
    );
  });
};

var showCompanies = function (companies) {
  var CompaniesHtml = "";

  $.each(companies, function (index, company) {
    CompaniesHtml +=
      "<div class='card mb-3 company-list-thumb'>" +
      "<div class='d-flex flex-row'>" +
      getCompanyThumbImageHtml(company) +
      getCompanyBasicDataHtml(company) +
      "<div class='d-flex p-4'>" +
      "<a class='edit-company-link d-flex align-items-center' data-company='" +
      JSON.stringify(company) +
      "' href=''>" +
      "Edit Company <h2 class='m-0'><i class='iconsminds-file-edit with-rotate-icon'></i></h2>" +
      "</a>" +
      "</div>" +
      "</div>" +
      getCompanyCollapsedDataHtml(index, company) +
      "</div>";
  });

  $("#companies-list-container").html(CompaniesHtml);

  $(".company-data-smart-wizard").smartWizard({
    selected: null,
    toolbarSettings: {
      toolbarPosition: "none",
      showNextButton: false,
      showPreviousButton: false,
    },
    anchorSettings: { anchorClickable: true, enableAllAnchors: true },
  });

  var now = moment().format("YYYY-MM-DD");

  $(".company-availability-calendar").fullCalendar({
    header: {
      left: "prev,next today",
      center: "title",
      right: "month,agendaWeek,agendaDay,listWeek",
    },
    defaultDate: now,
    navLinks: true,
    eventLimit: true,
    events: [
      { title: "Front-End Conference", start: now, end: now },
      { title: "Hair stylist with Mike", start: now, allDay: true },
      {
        title: "Car mechanic",
        start: "2018-11-14T09:00:00",
        end: "2018-11-14T11:00:00",
      },
      {
        title: "Dinner with Mike",
        start: "2018-11-21T19:00:00",
        end: "2018-11-21T22:00:00",
      },
      { title: "Chillout", start: "2018-11-15", allDay: true },
      { title: "Vacation", start: "2018-11-23", end: "2018-11-29" },
    ],
  });
};

var getCompanyThumbImageHtml = function (company) {
  var imageHtml =
    "<div class='border-right list-thumbnail card-img-left h-auto d-none d-lg-block' " +
    "style='background: url(" +
    company.CompanyImageUrl +
    ") center no-repeat; background-size: cover; width: 8%;'></div>" +
    "<div class='border-right list-thumbnail card-img-left w-20 h-auto d-lg-none' " +
    "style='background: url(" +
    company.CompanyImageUrl +
    ") center no-repeat; background-size: cover;'></div>";

  return imageHtml;
};

var getCompanyBasicDataHtml = function (company) {
  var basicDataHtml =
    "<div class='d-flex flex-grow-1 min-width-zero'>" +
    "<div class='card-body align-self-center d-flex flex-column flex-lg-row justify-content-between min-width-zero align-items-lg-center'>" +
    "<a href='' class='w-20 w-sm-100'><p class='list-item-heading mb-0 truncate'>" +
    company.CompanyName +
    "</p></a>" +
    "<p class='mb-0 text-muted w-15 w-sm-100'>" +
    "<span class='glyph-icon iconsminds-id-card align-text-top' style='font-size: 25px;'></span> " +
    "<span class='align-middle'>" +
    company.ContactName +
    "</span>" +
    "</p>" +
    "<p class='mb-0 text-muted w-15 w-sm-100'>" +
    "<span class='glyph-icon iconsminds-smartphone-4 align-text-top' style='font-size: 25px;'></span> " +
    "<span class='align-middle'>" +
    company.ContactPhone +
    "</span>" +
    "</p>" +
    "<p class='mb-0 text-muted w-15 w-sm-100'>" +
    "<span class='align-middle'>" +
    company.Status +
    "</span>" +
    "</p>" +
    "<div class='mb-2 d-md-none'></div>" +
    "</div>" +
    "</div>";

  //+ getAssistantStatusSelectHtml()
  return basicDataHtml;
};

var getCompanyStatusSelectHtml = function () {
  var companyStatusSelect =
    "<div class='w-15 w-sm-100 form-group m-0'>" +
    "<select id='inputState' class='form-control'>" +
    "<option value='1'>Activo</option>" +
    "</select >" +
    "</div > ";

  return companyStatusSelect;
};

var getCompanyCollapsedDataHtml = function (index, company) {
  var wizardId = "wizard-info-company-" + index;

  var companyCollapsedData =
    "<div class='collapse p-2 border-top' id='company-data-collapse-container-" +
    index +
    "'>" +
    "<div class='d-flex justify-content-end mb-2'>" +
    "<div class='shadow-sm border p-2 rounded-lg'>" +
    "<a class='edit-company-link d-flex align-items-center' data-company='" +
    JSON.stringify(company) +
    "' href=''>" +
    "Edit  <h2 class='m-0'><i class='iconsminds-file-edit with-rotate-icon'></i></h2>" +
    "</a>" +
    "</div>" +
    "</div>" +
    "<div class='card'>" +
    "<div class='company-data-smart-wizard'>" +
    "<ul class='card-header'>" +
    "<li class='position-relative'><a href='#" +
    wizardId +
    "-1'>Info personal<br><small></small></a></li>" +
    "<li class='position-relative'><a href='#" +
    wizardId +
    "-2'>Info profesional<br><small></small></a></li>" +
    "<li class='position-relative'><a href='#" +
    wizardId +
    "-3'>Habilidades<br><small></small></a></li>" +
    "<li class='position-relative'><a href='#" +
    wizardId +
    "-4'>Disponibilidad<br><small></small></a></li>" +
    "</ul>" +
    "<div class='border-top'>" +
    "<div id='" +
    wizardId +
    "-1' class='p-3'>" +
    "<h3 class='pb-3'>Información personal</h3>" +
    "</div>" +
    "<div id='" +
    wizardId +
    "-2' class='p-3'>" +
    "<h3 class='pb-3'>Información profesional</h3>" +
    "<div></div>" +
    "</div>" +
    "<div id='" +
    wizardId +
    "-3' class='p-3'>" +
    "<h3 class='pb-3'>Habilidades</h3>" +
    "<div></div>" +
    "</div>" +
    "<div id='" +
    wizardId +
    "-4' class='card-body p-4'>" +
    "<h3 class='pb-3'>Disponibilidad</h3>" +
    "<div class='row bg-light no-gutters p-3'>" +
    "<div class='col-xl-6 col-lg-12'>" +
    "<div class='card'>" +
    "<div class='card-body'><div class='company-availability-calendar'></div></div>" +
    "</div>" +
    "</div>" +
    "</div>" +
    "</div>" +
    "</div>" +
    "</div>" +
    "</div>" +
    "</div>";

  return companyCollapsedData;
};

var createCompany = async function () {
  var companyFormData = $("#create-company-form").serialize();


    var n = $("#CompanyLoanMaxAmmount").val();
    n = n.replace("CA$", "");
    n = n.replace(",", "");
    n = n.replace(",", "");
    n = n.replace(",", "");
    n = n.replace(",", "");


  showLoading();

  if (typeof cropper !== "undefined") {
    companyFormData += "&CompanyImageUrl=" + cropper;

    
  }

  companyFormData += "&CompanyLoanMaxAmmount=" + n;
  companyFormData += "&CompanyName=" + $("#CompanyName").val();

  companyFormData += "&CompanyName=" + $("#CompanyName").val();

  var result = await webClient.RequestAsync(
    "company/createCompany",
    companyFormData,
    webClient.ContentType.DEFAULT
  );
  if (result.status === REQUEST_STATUS.ERROR) {
    hideLoading();
    return;
  }
  hideLoading();

  if (result.status === REQUEST_STATUS.SUCCESS) {
    $("#create-company-modal").modal("hide");
    await loadCompanies();
  }
};

var createCompanyLocations = async function () {
  var companyFormData = "";

  showLoading();

  companyFormData +=
    "&CompanyLocationDescription=" + $("#CompanyLocationDescription").val();
  companyFormData += "&companyId=" + $("#idCompany").val();
  companyFormData +=
    "&CompanyLocationAddress=" + $("#CompanyLocationAddress").val();
  companyFormData +=
    "&CompanyLocationStatusId=" + $("#CompanyLocationStatusId").val();
  companyFormData +=
    "&CompanyLocationStatusId=" + $("#CompanyLocationStatusId").val();
  companyFormData += "&idCompanyLocation=" + $("#idCompanyLocation").val();



  var result = await webClient.RequestAsync(
    "Company/CreateCompanyLocations",
    companyFormData,
    webClient.ContentType.DEFAULT
  );
  if (result.status === REQUEST_STATUS.ERROR) {
    hideLoading();
    return;
  }
  hideLoading();
  if (result.status == "success") {
    //   $(".list").trigger('change');
    // $(".list").html("");

    $("#create-location-company-modal").modal("hide");

    loadCompanyLocations();
  }
};
