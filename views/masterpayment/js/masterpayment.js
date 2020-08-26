var sexArray = [];
var docTypesArray = [];
var academicLevelsArray = [];

var cropper;

var currentRutPath;

$(document).ready(function() {

    $("#CompanyPaymentAmmount").change(function (event) {
console.log(1);
        var n = $("#CompanyPaymentAmmount").val();
        n = n.replace("CA$", "");
        n = n.replace(",", "");
        n = n.replace(",", "");
        n = n.replace(",", "");
        // setup formatters
        const formatter = new Intl.NumberFormat("en-us", {
          style: "currency",
          currency: "CAD", 
        });
       
    
        $("#CompanyPaymentAmmount").val(formatter.format(n));
      });


    $("#dataTablesCsv").click(function() {
        var year = (new Date());

        exportTableToCSV(year + 'payment.csv');
    });


    function downloadCSV(csv, filename) {
        var csvFile;
        var downloadLink;

        // CSV file
        csvFile = new Blob([csv], { type: "text/csv" });

        // Download link
        downloadLink = document.createElement("a");

        // File name
        downloadLink.download = filename;

        // Create a link to the file
        downloadLink.href = window.URL.createObjectURL(csvFile);

        // Hide download link
        downloadLink.style.display = "none";

        // Add the link to DOM
        document.body.appendChild(downloadLink);

        // Click download link
        downloadLink.click();
    }


    function exportTableToCSV(filename) {
        var csv = [];
        var rows = document.querySelectorAll("table tr");

        for (var i = 0; i < rows.length; i++) {
            var row = [],
                cols = rows[i].querySelectorAll("td, th");

            for (var j = 0; j < cols.length; j++)
                row.push(cols[j].innerText);

            csv.push(row.join(","));
        }

        // Download CSV file
        downloadCSV(csv.join("\n"), filename);
    }
    $("#main-menu-user").addClass("text-primary");

    formValidator.Validate("#create-user-form", createUser);
    loadUsers();
    loadRoles();
    loadStatus();
    loadCompanies();

    $(document).on("change", "#create-user-img-file", function() { loadUserSelectedImage(this); });

    $(document).on("click", ".create-user-link", function(ev) {
        ev.preventDefault();
        openUserFormModal();

    });



    $(document).on("click", ".edit-user-link", function(ev) {
        ev.preventDefault();
        openUserFormModal($(this).data("user"));

    });

    $(document).on("hidden.bs.modal", "#create-user-modal", function() {
        $("#create-user-img").prop("src", USER_IMAGE_PLACEHOLDER);


        formValidator.ResetForm("#create-user-form");
    });

    $(document).on("keyup", "#searchUserInput", function() {
        $(".user-list-thumb").addClass("d-none").removeClass("d-flex");
        $(".user-list-thumb:contains('" + $(this).val() + "')").removeClass("d-none").addClass("d-flex");
    });


    $(document).on("click", "#pageLengthPlansTable div a", function() { dTable.page.len(parseInt($(this).html())).draw(); });
    $('#searchDatatable').on('keyup', function() { dTable.search(this.value).draw(); });

});


var openUserFormModal = function(user = false) {

    var title = user ? "<i class='iconsminds-dollar'></i> Edit Master Payment" :
        "<i class='iconsminds-dollar'></i> Create New Master Payment";

    var label = user ? "Save" : "Create";

    $("#create-user-modal-title").html(title);
    $("#create-user-modal-button").html(label);

    if (user) setEdituserFormValues(user);
    $("#create-user-modal").modal();
};


var setEdituserFormValues = function(user) {
    var userName = "";

    $.each(user, function(key, value) {
        $("#create-user-form [name='" + key + "']").val(value);
        $("#create-user-form input[type='checkbox'][name='" + key + "']").prop("checked", value == 1);
        $("#create-user-form textarea[name='" + key + "']").html(value);

        if (key == "SystemUserStatus_idSystemUserStatus") {

            $("#SystemUserStatus_idSystemUserStatus option[value='" + value + "']").attr("selected", true).trigger('change');
        }

        if (key == "UserRol_idUserRol") {

            $("#UserRol_idUserRol option[value='" + value + "']").attr("selected", true).trigger('change');

        }

        if (key == "Company_idCompany") {

            $("#Company_idCompany option[value='" + value + "']").attr("selected", true).trigger('change');

        }



        if (key == "name") {
            userName = value;
        }

    });


    $("#create-user-img").attr("src", user.companyPhotoUrl);
}

var loadCompanies = async function() {
    let Companies = await webClient.RequestAsync("company/getAllCompany", "", webClient.ContentType.DEFAULT);
    if (Companies.status === REQUEST_STATUS.ERROR) return;
    if (typeof Companies.data === "undefined" || Companies.data === '' || Companies.data.length === 0) return;

    showCompanies(Companies.data);

};
var showCompanies = function(Companies) {
    $.each(Companies, function(index, company) {

        var drow = JSON.stringify(company);

        $('#Company_idCompany').append('<option value= "' + company.idCompany + '">' + company.CompanyName + '</option>');

    });

};

var loadUserSelectedImage = async function(input) {
    if (typeof cropper !== 'undefined' && cropper !== null)
        cropper.destroy();

    var loadedImage = await getInputImageData(input);
    $("#create-user-img").prop("src", loadedImage);

    var image = document.querySelector('#create-user-img');
    cropper = new Cropper(image, { aspectRatio: 1, movable: true, dragMode: "move", viewMode: 2 });
}


var loadUsers = async function() {
    let Users = await webClient.RequestAsync("MasterPayment/getAllMasterPayment", "", webClient.ContentType.DEFAULT);
    if (Users.status === REQUEST_STATUS.ERROR) return;
    if (typeof Users.data === "undefined" || Users.data === '' || Users.data.length === 0) return;

    showUsers(Users.data);
};


var loadRoles = async function() {
    let Roles = await webClient.RequestAsync("user/getalluserrol", "", webClient.ContentType.DEFAULT);
    if (Roles.status === REQUEST_STATUS.ERROR) return;
    if (typeof Roles.data === "undefined" || Roles.data === '' || Roles.data.length === 0) return;

    showRoles(Roles.data);

};

var loadStatus = async function() {
    let Status = await webClient.RequestAsync("user/getalluserstatus", "", webClient.ContentType.DEFAULT);
    if (Status.status === REQUEST_STATUS.ERROR) return;
    if (typeof Status.data === "undefined" || Status.data === '' || Status.data.length === 0) return;
    showStatus(Status.data);

};


var showRoles = function(Roles) {
    //$("#insertar").append(" <label>State Single</label><select class='form-control select2-single'  id='roles' data-width='100%'></select>.");
    $.each(Roles, function(index, rol) {

        var drow = JSON.stringify(rol);


        $('#UserRol_idUserRol').append('<option value= "' + rol.idUserRol + '">' + rol.UserRolDescription + '</option>');

    });




};
var showStatus = function(Status) {
    //$("#insertar").append(" <label>State Single</label><select class='form-control select2-single'  id='roles' data-width='100%'></select>.");
    $.each(Status, function(index, state) {

        var drow = JSON.stringify(state);

        $('#SystemUserStatus_idSystemUserStatus').append('<option value= "' + state.idSystemUserStatus + '">' + state.SystemUserStatusDescription + '</option>');

    });




};




var showUsers = function(Users) {
    var UsersHtml = "";
    
    $.each(Users, function(index, user) {
        /*  UsersHtml += "<div class='card mb-3 user-list-thumb'>" +
              "<div class='d-flex flex-row'>" +
              getUserBasicDataHtml(user) +
              /* "<div class='d-flex p-4'>" +
               "<a class='edit-user-link d-flex align-items-center' data-user='" + JSON.stringify(user) + "' href=''>" +
               "Editar Usuario <h2 class='m-0'><i class='iconsminds-file-edit with-rotate-icon'></i></h2>" +
               "</a>"
               /*  + "<button class='btn btn-outline-theme-3 icon-button rotate-icon-click collapsed align-self-center'"
                 + " type='button' data-toggle='collapse' data-target='#company-data-collapse-container-" + index 
                 + "' aria-expanded='false' aria-controls='q2'>"
                     + "<i class='simple-icon-arrow-down with-rotate-icon'></i>"
                 + "</button>"
               +
               "</div>" +
              "</div>" +
              getUserCollapsedDataHtml(index, user) +
              "</div>";*/
           
              const formatter = new Intl.NumberFormat("en-us", {
                style: "currency",
                currency: "CAD",
              });
        
          

        $(".list").append(" <tr> <td  >" + user.CompanyPaymentReference + "</td><td>" + user.CompanyName + "</td><td>" + formatter.format(user.CompanyPaymentAmmount) + "</td><td>" + formatter.format(user.CompanyPaymentTotal) + "</td><td>" + user.CompanyPaymentDate + "</td>");

    });






    $("#users-list-container").append(UsersHtml);


    $('.user-data-smart-wizard').smartWizard({
        selected: null,
        toolbarSettings: { toolbarPosition: "none", showNextButton: false, showPreviousButton: false },
        anchorSettings: { anchorClickable: true, enableAllAnchors: true }
    });

    var now = moment().format("YYYY-MM-DD");

    $(".user-availability-calendar").fullCalendar({
        header: { left: 'prev,next today', center: 'title', right: 'month,agendaWeek,agendaDay,listWeek' },
        defaultDate: now,
        navLinks: true,
        eventLimit: true,
        events: [
            { title: 'Front-End Conference', start: now, end: now },
            { title: 'Hair stylist with Mike', start: now, allDay: true },
            { title: 'Car mechanic', start: '2018-11-14T09:00:00', end: '2018-11-14T11:00:00' },
            { title: 'Dinner with Mike', start: '2018-11-21T19:00:00', end: '2018-11-21T22:00:00' },
            { title: 'Chillout', start: '2018-11-15', allDay: true },
            { title: 'Vacation', start: '2018-11-23', end: '2018-11-29' }
        ]
    });
};



var getUserBasicDataHtml = function(user) {

    var basicDataHtml = "<div class='d-flex flex-grow-1 min-width-zero'>" +
        "<div class='card-body align-self-center d-flex flex-column flex-lg-row justify-content-between min-width-zero align-items-lg-center'>" +
        "<a href='' class='w-10 w-sm-100'><p class='list-item-heading mb-0 truncate'>" + user.CompanyPaymentReference + "</p></a>" +
        "<p class='mb-0 text-muted w-20 w-sm-100'>" +
        "<span class='glyph-icon iconsminds-cash-register-2 align-text-top' style='font-size: 25px;'></span> " +
        "<span class='align-middle'>" + user.CompanyName + "</span>" +
        "</p>" +
        "<p class='mb-0 text-muted w-10 w-sm-100'>" +
        "<span class='glyph-icon iconsminds-cash-register-2 align-text-top' style='font-size: 25px;'></span> " +
        "<span class='align-middle'>" + user.CompanyPaymentAmmount + "</span>" +
        "</p>" +
        "<p class='mb-0 text-muted w-10 w-sm-100'>" +
        "<span class='glyph-icon iconsminds-calendar-4 align-text-top' style='font-size: 25px;'></span> " +
        "<span class='align-middle'>" + user.Fee + "</span>" +
        "</p>" +
        "<p class='mb-0 text-muted w-10 w-sm-100'>" +
        "<span class='glyph-icon iconsminds-calendar-4 align-text-top' style='font-size: 25px;'></span> " +
        "<span class='align-middle'>" + user.CompanyPaymentTotal + "</span>" +
        "</p>" +

        "<p class='mb-0 text-muted w-15 w-sm-100'>" +
        "<span class='glyph-icon iconsminds-calendar-4 align-text-top' style='font-size: 25px;'></span> " +
        "<span class='align-middle'>" + user.CompanyPaymentDate + "</span>" +
        "</p>" +
        "<div class='mb-2 d-md-none'></div>"

    +"</div>" +
    "</div>";

    return basicDataHtml;
};

var getUserStatusSelectHtml = function() {
    var userStatusSelect = "<div class='w-15 w-sm-100 form-group m-0'>" +
        "<select id='inputState' class='form-control'>" +
        "<option value='1'>Activo</option>" +
        "</select >" +
        "</div > ";
    return userStatusSelect;

};

var getUserCollapsedDataHtml = function(index, user) {
    var wizardId = "wizard-info-user-" + index;

    var userCollapsedData = "<div class='collapse p-2 border-top' id='user-data-collapse-container-" + index + "'>" +
        "<div class='d-flex justify-content-end mb-2'>" +
        "<div class='shadow-sm border p-2 rounded-lg'>" +
        // "<a class='edit-user-link d-flex align-items-center' data-user='" + JSON.stringify(user) + "' href=''>" +
        //   "Editar Usuario <h2 class='m-0'><i class='iconsminds-file-edit with-rotate-icon'></i></h2>" +
        //   "</a>" +
        "</div>" +
        "</div>" +
        "<div class='card'>" +
        "<div class='user-data-smart-wizard'>" +
        "<ul class='card-header'>" +
        "<li class='position-relative'><a href='#" + wizardId + "-1'>Info personal<br><small></small></a></li>" +
        "<li class='position-relative'><a href='#" + wizardId + "-2'>Info profesional<br><small></small></a></li>" +
        "<li class='position-relative'><a href='#" + wizardId + "-3'>Habilidades<br><small></small></a></li>" +
        "<li class='position-relative'><a href='#" + wizardId + "-4'>Disponibilidad<br><small></small></a></li>" +
        "</ul>" +
        "<div class='border-top'>" +
        "<div id='" + wizardId + "-1' class='p-3'>" +
        "<h3 class='pb-3'>Información personal</h3>" +
        "</div>" +
        "<div id='" + wizardId + "-2' class='p-3'>" +
        "<h3 class='pb-3'>Información profesional</h3>" +
        "<div></div>" +
        "</div>" +
        "<div id='" + wizardId + "-3' class='p-3'>" +
        "<h3 class='pb-3'>Habilidades</h3>" +
        "<div></div>" +
        "</div>" +
        "<div id='" + wizardId + "-4' class='card-body p-4'>" +
        "<h3 class='pb-3'>Disponibilidad</h3>" +
        "<div class='row bg-light no-gutters p-3'>" +
        "<div class='col-xl-6 col-lg-12'>" +
        "<div class='card'>" +
        "<div class='card-body'><div class='user-availability-calendar'></div></div>" +
        "</div>" +
        "</div>" +
        "</div>" +
        "</div>" +
        "</div>" +
        "</div>" +
        "</div>" +
        "</div>";

    return userCollapsedData;
};


var createUser = async function() {
   var userFormData = "";

    
    userFormData += "&Company_idCompany=" +  $("#Company_idCompany").val();
    userFormData += "&CompanyPaymentReference=" +  $("#CompanyPaymentReference").val();
    var n = $("#CompanyPaymentAmmount").val();
    n = n.replace("CA$", "");
    n = n.replace(",", "");
    n = n.replace(",", "");
    n = n.replace(",", "");

    userFormData += "&CompanyPaymentAmmount=" + n;

    console.log(userFormData);
    showLoading();


    var result = await webClient.RequestAsync("MasterPayment/createMasterPayment", userFormData, webClient.ContentType.DEFAULT);
    if (result.status === REQUEST_STATUS.ERROR) { hideLoading(); return; }

    hideLoading();


    if (result.status === "success") {
        $("#create-user-modal").modal("hide");
        await loadUsers();
    }
};