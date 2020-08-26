var sexArray = [];
var docTypesArray = [];
var academicLevelsArray = [];

var cropper;

var currentRutPath;

$(document).ready(function() {



    $("#pdf").click(function() {
        loadimprimir();
    });


    $("#dataTablesCsv").click(function() {
        var year = (new Date());

        exportTableToCSV(year + 'payment.csv');
    });
    $("#CreateNote").click(function() {
        var userFormData = "";

        userFormData += "&Payment_idPayment=" + $("#idPayment").val();

        userFormData += "&PaymentNoteDescription=" + $("#PaymentNoteDescription").val();

        showLoading();

        var result = webClient.RequestAsync("PaymentNote/createPaymentNote", userFormData, webClient.ContentType.DEFAULT);
        if (result.status === REQUEST_STATUS.ERROR) { hideLoading(); return; }
        $("#note").html("");
        $("#PaymentNoteDescription").val("");

        loadNotes();

        hideLoading();

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
    // loadRoles();
    //loadStatus();
    //  loadCompanies();

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
        $("#note").html("");

        $("#signatureImage").prop("src", "");


        formValidator.ResetForm("#create-user-form");
    });

    $(document).on("keyup", "#searchUserInput", function() {
        $(".user-list-thumb").addClass("d-none").removeClass("d-flex");
        $(".user-list-thumb:contains('" + $(this).val() + "')").removeClass("d-none").addClass("d-flex");
    });


    $(document).on("click", "#pageLengthPlansTable div a", function() { dTable.page.len(parseInt($(this).html())).draw(); });
    $('#searchDatatable').on('keyup', function() { dTable.search(this.value).draw(); });

});

var statuspago = "";


$('#signatureImage').on('load', function() {
    if (statuspago == 1) {
        $("#create-user-modal-button").show();
    } else {
        alert("The company doesnt have money sufficient for pay this");

    }
})
var companyCurrentMoney = null;

var openUserFormModal = function(user = false) {
    statuspago = 0;
    var title = user ? "<i class='iconsminds-financial'></i>Cashing Out" :
        "<i class='iconsminds-financial'></i> Cashing Ouy";

    var label = user ? "Pay" : "Create";

    $("#create-user-modal-title").html(title);
    $("#create-user-modal-button").html(label);
    $("#create-user-modal-button").hide();

    $("#MoneyAvailable").css("color", "");



    if (user) setEdituserFormValues(user);

    var PaymentValue = parseFloat(user.PaymentValue);
    var idCompanyLocation = parseFloat(user.idCompanyLocation);
    var paymentstatus = parseInt(user.idPaymentStatus);
    // var companyCurrentMoney = parseFloat(user.companyCurrentMoney);

    loadCompanyBalance(idCompanyLocation, PaymentValue, paymentstatus);



    if (user.idPaymentStatus == 2) {
        $('.firma').show();
        $('.quitar').hide();

        $("#PaymentDocumentSign").prop("src", user.PaymentDocumentSign);
    } else {
        $('.firma').hide();
        $('.quitar').show();


    }





    $("#create-user-modal").modal();
};

var idPayment;

var setEdituserFormValues = function(user) {
    var userName = "";


    $.each(user, function(key, value) {

        $("#create-user-form [name='" + key + "']").val(value);
        $("#create-user-form input[type='checkbox'][name='" + key + "']").prop("checked", value == 1);
        $("#create-user-form textarea[name='" + key + "']").html(value);

        if (key == "idPayment") {
            idPayment = value;
            loadNotes();
        }



        if (key == "Company_idCompany") {

            $("#Company_idCompany option[value='" + value + "']").attr("selected", true).trigger('change');

        }

        if (key == "idPaymentStatus") {

            if (value == 2) {
                $('.ocultar').hide();

            } else {
                $('.ocultar').show();

            }
        }

        if (key == "CompanyName") {

            $("#CompanyName ").text(value);

        }
        if (key == "CompanyLocationDescription") {

            $("#locationName").text(value);

        }
        if (key == "companyCurrentMoney") {


        }

        if (key == "EmployeeDocNumber") {

            $("#EmployeeDocNumber").text(value);

        }
        if (key == "EmployeePhoneNumber") {

            $("#EmployeePhoneNumber").text(value);

        }
        if (key == "EmployeName") {

            $("#EmployeName").text(value);

        }
        if (key == "EmployeeLastName") {

            $("#EmployeName").append(" " + value);

        }

        if (key == "PaymentHours") {

            $("#PaymentHours").text(value);

        }
        if (key == "Fee") {

            $("#Fee").text(value + "%");

        }

        if (key == "FeeValue") {

            //   $("#FeeValue").text("$ " + value);

            const formatter = new Intl.NumberFormat("en-us", {
                style: "currency",
                currency: "CAD",
            });

            $("#FeeValue").text(formatter.format(value));


        }
        if (key == "CompanyPays") {

            $("#CompanyPays").text(value);

        }


        if (key == "PaymentTotalValue") {

            // $("#PaymentTotalValue").text("$" + value);
            const formatter = new Intl.NumberFormat("en-us", {
                style: "currency",
                currency: "CAD",
            });

            $("#PaymentTotalValue").text(formatter.format(value));


        }

        if (key == "PaymentValue") {

            $("#PaymentValue").text("$ " + value);

        }


        if (key == "PaymentFromDate") {

            $("#PaymentPeriod").text("(" + value + ")");

        }



        if (key == "PaymentToDate") {

            $("#PaymentPeriod").append(" - (" + value + ")");

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

var loadimprimir = function() {

    var baseImage = new Image;
    baseImage.setAttribute('crossOrigin', 'anonymous');
    baseImage.src = $("PaymentDocumentSign").attr("src");





    function getBase64Image(img) {
        var canvas = document.createElement("canvas");
        canvas.width = img.width;
        canvas.height = img.height;
        var ctx = canvas.getContext("2d");
        ctx.drawImage(img, 0, 0);
        var dataURL = canvas.toDataURL("image/png");
        return dataURL.replace(/^data:image\/(png|jpg);base64,/, "");
    }
    $("#signatureImage").attr("src");




    var base64 = getBase64Image(document.getElementById("PaymentDocumentSign"));

    console.log(base64);
    if (base64 == "data:,") {
        base64 = $("#signatureImage").attr("src");
        var imgData = (base64);

    } else {
        var imgData = 'data:image/jpeg;base64,' + (base64);
    }

    console.log(base64);





    // var doc = new jsPDF('p', 'mm', [80, 200]);
    var doc = new jsPDF();

    var hoy = new Date();
    var fecha = hoy.getDate() + '-' + (hoy.getMonth() + 1) + '-' + hoy.getFullYear();

    var hora = hoy.getHours() + ':' + hoy.getMinutes() + ':' + hoy.getSeconds();

    var dia = fecha + ' ' + hora;



    doc.setFontSize(30);
    doc.setFont('courier')
    doc.text(35, 20, 'Receipt of payment #' + $("#idPayment").val());

    doc.output('datauri');

    doc.setFont('courier')
    doc.setFontSize(15)
    doc.setFontType('normal')
    doc.text(5, 40, 'NAME')

    doc.text(20, 40, $("#EmployeName").text())

    doc.text(5, 50, 'PAYMENT PERIOD')
    doc.text(60, 50, $("#PaymentPeriod").text())

    doc.text(5, 60, 'TOTAL HOURS')
    doc.text(45, 60, $("#PaymentHours").text())

    doc.text(5, 70, 'TAZ RESPONSIBLE')
    doc.text(55, 70, $("#CompanyPays").text())

    doc.text(5, 80, 'Fee')
    doc.text(45, 80, $("#Fee").text() + " (" + $("#FeeValue").text() + ")")



    doc.text(5, 90, 'PAYOUT')
    doc.text(45, 90, $("#PaymentTotalValue").text())

    doc.text(5, 100, 'SIGNATURE')

    doc.addImage(imgData, 'JPEG', 100, 60, 100, 100);
    doc.setFontSize(10);

    doc.text(100, 160, 'DATE')
    doc.text(125, 160, dia)

    doc.output('dataurlnewwindow');

    doc.save('# Order ' + $("#idPayment").val() + '_' + $("#EmployeName").text() + '_' + fecha + '.pdf')






};
var showCompanies = function(Companies) {
    $.each(Companies, function(index, company) {

        var drow = JSON.stringify(company);

        $('#Company_idCompany').append('<option value= "' + company.idCompany + '">' + company.CompanyName + '</option>');

    });

};




var loadCompanyBalance = async function(idCompanyLocation, PaymentValue, PaymentStatus) {
    var id = "";
    id += "idCompanyLocation=" + idCompanyLocation;

    let Balances = await webClient.RequestAsync("Company/getcompanyammount", id, webClient.ContentType.DEFAULT);
    if (Balances.status === REQUEST_STATUS.ERROR) return;
    if (typeof Balances.data === "undefined" || Balances.data === '' || Balances.data.length === 0) return;

    showBalances(Balances, PaymentValue, PaymentStatus);
};
var showBalances = function(Balances, PaymentValue, PaymentStatus) {
    $.each(Balances.data, function(index, balance) {




        if (index == "MoneyAvailable") {



            // $("#MoneyAvailable").text(balance);


            //  $("#MoneyAvailable").text("(" + value + ")");
            const formatter = new Intl.NumberFormat("en-us", {
                style: "currency",
                currency: "CAD",
            });

            $("#MoneyAvailable").text(formatter.format(balance));





            companyCurrentMoney = balance;


            if (companyCurrentMoney > PaymentValue) {

                statuspago = 1;
                $("#MoneyAvailable").css("color", "");



            } else {
                if (PaymentStatus != 2) {
                    alert("The company doesnt have money sufficient for pay this");
                }
                $("#create-user-modal-button").hide();
                $("#MoneyAvailable").css("color", "red");
                $('.ocultar').hide();



            }


        }
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


var loadUsers = async function() {
    var data = "";
    data += "pageNumber=1";
    data += "&pageSize=2000";
    data += "&orderBy=desc"


    let Users = await webClient.RequestAsync("Cashier/GetAvailablePayments", data, webClient.ContentType.DEFAULT);
    if (Users.status === REQUEST_STATUS.ERROR) return;
    if (typeof Users.data === "undefined" || Users.data === '' || Users.data.length === 0) return;

    showUsers(Users.data);

};
var showUsers = function(Users) {
    var UsersHtml = "";
    $(".list").html("");
    $.each(Users, function(index, key, val) {

        const formatter = new Intl.NumberFormat("en-us", {
            style: "currency",
            currency: "CAD",
        });



        $(".list").append(" <tr> <td  >" + key.PaymentDateTime + "</td><td>" + key.EmployeName + " " + key.EmployeeLastName + "</td><td>" + key.CompanyName + "</td><td>" + key.CompanyLocationDescription + "</td><td>" + formatter.format(key.PaymentValue) + "</td><td>" + key.nameUserCreator + "</td><td>" + key.PaymentStatusDescription + "</td><td>" +
            "<div class='d-flex '>" +
            "<a class='edit-user-link d-flex align-items-center' data-user='" + JSON.stringify(key) + "' href=''>" +
            "<h2 class='m-0'><i class='iconsminds-coins'></i></h2>" +
            "</a>" +
            "</div></td>"
        );
    });

    $('#exportable').DataTable({
        responsive: true,

        "pagingType": "numbers",

        dom: 'Bfrtip',
        buttons: [
            'copyHtml5',
            'excelHtml5',
            'csvHtml5',
            'pdfHtml5'
        ]
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
    userFormData += "&sign=" + $("#signatureImage").attr('src');
    userFormData += "$FeeValue=" + $("#FeeValue").text();
    userFormData += "$Fee=" + $("#Fee").text();
    userFormData += "$Taxreponsible=" + $("#CompanyPays").text();


    showLoading();

    var result = await webClient.RequestAsync("Cashier/UpdatePayment", userFormData, webClient.ContentType.DEFAULT);
    if (result.status === REQUEST_STATUS.ERROR) { hideLoading(); return; }

    hideLoading();
    $(".list").html("");

    if (result.status === "success") {
        $("#create-user-modal").modal("hide");
        $(".list").html("");
        loadimprimir();

        await loadUsers();
    }
};