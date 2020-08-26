var sexArray = [];
var docTypesArray = [];
var academicLevelsArray = [];

var cropper;

var currentRutPath;

$(document).ready(function() {

    $("#CreateNote").click(function() {
        var userFormData = "";

        userFormData += "&Payment_idPayment=" + $("#idPayment").val();

        userFormData += "&PaymentNoteDescription=" + $("#PaymentNoteDescription").val();


        showLoading();

        var result = webClient.RequestAsync("PaymentNote/createPaymentNote", userFormData, webClient.ContentType.DEFAULT);
        if (result.status === REQUEST_STATUS.ERROR) { hideLoading(); return; }
        $("#note").html("");
        loadNotes();

        hideLoading();
        $("#PaymentNoteDescription").val("");
    });

    $("#formuploadajax").on("submit", function(e) {
        e.preventDefault();
        var f = $(this);
        var formData = new FormData(document.getElementById("formuploadajax"));
        formData.append("dato", "valor");
        //formData.append(f.attr("name"), $(this)[0].files[0]);
        $.ajax({
                url: "Payment/loadPaymentsFromCsv",
                type: "post",
                dataType: "html",
                data: formData,
                cache: false,
                contentType: false,
                processData: false
            })
            .done(function(res) {
                $("#mensaje").html("Respuesta: " + res);
            });
    });



    $("#PaymentFromDate").change(function() {
        var startdate = $('#PaymentFromDate').val();
        var enddate = $('#PaymentToDate').val();
        var resultado = "";


        if (startdate != "" && enddate != "") {
            if (startdate <= enddate) {
                resultado = startdate - enddate;
            } else {
                $('#PaymentToDate').val("");

                alert("end date must be greater than or equal to start date");
            }
        }

    });


    $("#PaymentValue").change(function(event) {
        var n = $("#PaymentValue").val();
        n = n.replace("CA$", "");
        n = n.replace(",", "");
        // setup formatters
        const formatter = new Intl.NumberFormat("en-us", {
            style: "currency",
            currency: "CAD",
        });


        $("#PaymentValue").val(formatter.format(n));
    });



    $("#PaymentToDate").change(function() {
        var startdate = $('#PaymentFromDate').val();
        var enddate = $('#PaymentToDate').val();

        if (startdate != "" && enddate != "") {

            if (startdate <= enddate) {
                resultado = startdate - enddate;
            } else {
                $('#PaymentToDate').val("");
                alert("end date must be greater than or equal to start date");

            }
        }

    });



    $("#idCompanyPayment").change(function() {
        var idCompanyPayment = $("#idCompanyPayment").val();
        //  var vacio = null;
        var vacio = [null, null]


        loadCompanyLocations(vacio, idCompanyPayment);
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
    $('#main-menu-user').addClass("text-primary");

    formValidator.Validate("#create-user-form", createUser);
    loadUsers();
    loadStatus();
    loadEmployee();
    loadCompanies();

    $(document).on("change", "#create-user-img-file", function() { loadUserSelectedImage(this); });

    $(document).on("click", ".create-user-link", function(ev) {
        ev.preventDefault();
        openUserFormModal();
        $('.ocultar').hide();


        $("#PaymentStatus_idPaymentStatus").attr('disabled', 'disabled');
    });



    $(document).on("click", ".edit-user-link", function(ev) {
        ev.preventDefault();
        openUserFormModal($(this).data("user"));
        $("#PaymentStatus_idPaymentStatus").removeAttr('disabled');
        $("#Employee_idEmployee").attr('disabled', 'disabled');


    });

    $(document).on("hidden.bs.modal", "#create-user-modal", function() {
        $("#create-user-img").prop("src", USER_IMAGE_PLACEHOLDER);

        var form = $("#create-company-modal");

        //form.validate().resetForm(); // clear out the validation errors
        $(this).removeData('bs.modal');
        $('form')[0].reset();
        $("#note").html("");
        $("#idCompanyPayment").prop("selectedIndex", 0);
        $("#idCompanyPayment").prop('selected', true).trigger("change");
        $("#idCompanyPayment option:first").attr('selected', 'selected');
        $("#paymentLocationId option").remove();
        $("#idCompanyPayment option").remove();
        $("#idCompanyPayment").append('<option value="">-- select an option --</option>');

        loadCompanies();
        $("#create-user-form")[0].reset();
        $('#PaymentStatus_idPaymentStatus option:first').prop('selected', true).trigger("change");
        $('#idCompanyPayment option:first').prop('selected', true).trigger("change");
        $('#Employee_idEmployee option:first').prop('selected', true).trigger("change");
        $('#paymentLocationId option:first').prop('selected', true).trigger("change");

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

    var title = user ? "<i class='iconsminds-file-edit with-rotate-icon'></i> Edit Payment" :
        "<i class='iconsminds-add-user'></i> Create New User";

    var label = user ? "Save" : "Create";

    $("#create-user-modal-title").html(title);
    $("#create-user-modal-button").html(label);
    if (user.PaymentStatus_idPaymentStatus == 2) {
        $('.quitar').hide();
        $("input").prop('disabled', true);

        $("select").prop('disabled', true);

        $("#create-user-modal-button").prop('disabled', true);

    } else {
        $('.quitar').show();

        $("input").prop('disabled', false);

        $("select").prop('disabled', false);
        $("#create-user-modal-button").prop('disabled', false);

    }
    if (user) setEdituserFormValues(user);

    $("#create-user-modal").modal();
};


var setEdituserFormValues = function(user) {
    $('.ocultar').show();

    var idCompanyPayment = user.idCompanyPayment;
    var paymentLocationId = user.paymentLocationId
    var compan = [idCompanyPayment, paymentLocationId]
    loadCompanyLocations(compan);


    var userName = "";

    $.each(user, function(key, value) {

        $("#create-user-form [name='" + key + "']").val(value);
        $("#create-user-form input[type='checkbox'][name='" + key + "']").prop("checked", value == 1);
        $("#create-user-form textarea[name='" + key + "']").html(value);
        loadNotes

        if (key == "idPayment") {
            loadNotes();
        }

        if (key == "PaymentValue") {
            const formatter = new Intl.NumberFormat("en-us", {
                style: "currency",
                currency: "CAD",
            });
            $("#PaymentValue").val(formatter.format(value));
        }
        if (key == "idCompanyPayment") {


            $("#idCompanyPayment option[value='" + value + "']").attr("selected", true).trigger('change');

        }

        if (key == "Employee_idEmployee") {

            $("#Employee_idEmployee option[value='" + value + "']").attr("selected", true).trigger('change');

        }

        if (key == "paymentLocationId") {


            $("#paymentLocationId option[value='" + value + "']").attr("selected", true).trigger('change');


        }

        if (key == "PaymentStatus_idPaymentStatus") {

            $("#PaymentStatus_idPaymentStatus option[value='" + value + "']").attr("selected", true).trigger('change');
            if (value = 2) {
                $("#PaymentStatus_idPaymentStatus").attr('disabled', 'disabled');

            }


        }


        if (key == "name") {
            userName = value;
        }

    });


    $("#create-user-img").attr("src", user.companyPhotoUrl);
}


var loadEmployee = async function() {
    let Employees = await webClient.RequestAsync("employee/getAllEmployee", "", webClient.ContentType.DEFAULT);
    if (Employees.status === REQUEST_STATUS.ERROR) return;
    if (typeof Employees.data === "undefined" || Employees.data === '' || Employees.data.length === 0) return;

    showEmployee(Employees.data);

};

var showEmployee = function(Employees) {
    $.each(Employees, function(index, employee) {


        var drow = JSON.stringify(employee);

        $('#Employee_idEmployee').append('<option value= "' + employee.idEmployee + '">' + employee.EmployeeDocNumber + " - " + employee.EmployeeLastName + " " + employee.EmployeName + '</option>');

    });

};

var loadCompanies = async function() {

    let Companies = await webClient.RequestAsync("company/getAllCompany", "", webClient.ContentType.DEFAULT);
    if (Companies.status === REQUEST_STATUS.ERROR) return;
    if (typeof Companies.data === "undefined" || Companies.data === '' || Companies.data.length === 0) return;

    showCompanies(Companies.data);

};
var showCompanies = function(Companies) {
    $.each(Companies, function(index, company) {


        var drow = JSON.stringify(company);

        $('#idCompanyPayment').append('<option value= "' + company.idCompany + '">' + company.CompanyName + '</option>');

    });

};


var loadCompanyLocations = async function(companyId2, companyempy = null) {



    var companyempy = parseInt(companyempy);



    if (!Number.isInteger(companyId2[0]) && companyId2[0] != null) {
        return;
    } else {
        var companyId = companyId2[0];
        companyId += "&companyId=" + companyId;
    }


    if (!Number.isInteger(companyempy)) {

        return;
    } else {
        var companyId = companyempy;
        companyId += "&companyId=" + companyId;

    }


    let Companies = await webClient.RequestAsync("Company/getCompanyLocations", companyId, webClient.ContentType.DEFAULT);
    if (Companies.status === REQUEST_STATUS.ERROR) return;
    if (typeof Companies.data === "undefined" || Companies.data === '' || Companies.data.length === 0) return;



    showCompanyLocations(Companies.data, companyId2[1]);

};

var showCompanyLocations = function(Companies, id) {

    $('#paymentLocationId').html("");
    $.each(Companies, function(index, company) {


        var drow = JSON.stringify(company);

        $('#paymentLocationId').append('<option value= "' + company.idCompanyLocation + '">' + company.CompanyLocationDescription + '</option>');

    });

    $("#paymentLocationId option[value='" + id + "']").attr("selected", true).trigger('change');

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
    /*  let Users = await webClient.RequestAsync("payment/getPayments", "", webClient.ContentType.DEFAULT);
      if (Users.status === REQUEST_STATUS.ERROR) return;
      if (typeof Users.data === "undefined" || Users.data === '' || Users.data.length === 0) return;
 
      showUsers(Users.data);*/
    $.getJSON("payment/getPayments", function(payment) {


        var html = "";
        $.each(payment.data, function(index, key, val) {


            const formatter = new Intl.NumberFormat("en-us", {
                style: "currency",
                currency: "CAD",
            });



            html += "<tr><td>" + key.idPayment + "</td><td>" + key.EmployeeDocNumber + "</td><td>" + key.EmployeeLastName + " " + key.EmployeName + "</td><td>" + formatter.format(key.PaymentValue) + "</td><td>" + key.CompanyName + " (" + key.CompanyLocationDescription + ")" + "</td><td>" + key.PaymentStatusDescription + "</td><td>" + key.PaymentGeneratedDateTime + "</td>" +
                "<td><div class='d-flex p-1'>" +
                "<a class='edit-user-link d-flex align-items-center' data-user='" + JSON.stringify(key) + "' href=''>" +
                "<h2 class='m-0'><i class='iconsminds-file-edit with-rotate-icon'></i></h2>" +
                "</a></td>" +
                "<td><div class='d-flex p-1'>" +
                "<a class='Print' >" +
                "<h2 class='m-0'><i class='iconsminds-printer'></i></h2>" +
                "</a></td>";

        });
        $(".list").append(html);
    });



};


var loadStatus = async function() {
    /*  let Users = await webClient.RequestAsync("payment/getPayments", "", webClient.ContentType.DEFAULT);
      if (Users.status === REQUEST_STATUS.ERROR) return;
      if (typeof Users.data === "undefined" || Users.data === '' || Users.data.length === 0) return;
 
      showUsers(Users.data);*/
    $.getJSON("Payment/GetPaymentStatus", function(payment) {
        $.each(payment.data, function(index, key, val) {


            console.log(payment);
            $('#PaymentStatus_idPaymentStatus').append('<option value= "' + key.idPaymentStatus + '">' + key.PaymentStatusDescription + '</option>');
        });
    });




};

var loadNotes = async function() {
    var data = "";
    data += "&Payment_idPayment=" + $("#idPayment").val();


    let Notes = await webClient.RequestAsync("PaymentNote/listPaymentNote", data, webClient.ContentType.DEFAULT);
    if (Notes.status === REQUEST_STATUS.ERROR) return;
    if (typeof Notes.data === "undefined" || Notes.data === '' || Notes.data.length === 0) return;

    showNotes(Notes.data);

};


var showNotes = function(Note) {
    var UsersHtml = "";

    $(".note").html = ("");
    $.each(Note, function(index, key, value) {

        $(".note").append(" <tr> <td>" + key.PaymentNoteDate + "</td><td>" + key.PaymentNoteDescription + "</td><td>" + key.SystemUserNickName + "</td>");

    });



};

var showUsers = function(Users) {
    var UsersHtml = "";

    $.each(Users, function(index, user) {
        console.log(123);


        $(".list").append(" <tr> <td  >" + user.CompanyPaymentReference + "</td><td>" + user.CompanyName + "</td><td>" + user.CompanyPaymentAmmount + "</td><td>" + user.CompanyPaymentTotal + "</td><td>" + user.CompanyPaymentDate + "</td>");

    });


    $("#users-list-container").append(UsersHtml);

    $('.user-data-smart-wizard').smartWizard({
        selected: null,
        toolbarSettings: { toolbarPosition: "none", showNextButton: false, showPreviousButton: false },
        anchorSettings: { anchorClickable: true, enableAllAnchors: true }
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
    var userFormData = $("#create-user-form").serialize();

    var n = $("#PaymentValue").val();
    n = n.replace("CA$", "");
    n = n.replace(",", "");
    n = n.replace(",", "");
    n = n.replace(",", "");
    n = n.replace(",", "");
    userFormData += "&PaymentValue=" + n;

    console.log(userFormData);
    showLoading();

    var result = await webClient.RequestAsync("Payment/createPayment", userFormData, webClient.ContentType.DEFAULT);
    if (result.status === REQUEST_STATUS.ERROR) { hideLoading(); return; }

    hideLoading();


    if (result.status === "success") {
        $("#create-user-modal").modal("hide");
        $('.list').html("");

        await loadUsers();
    }
};