var sexArray = [];
var docTypesArray = [];
var academicLevelsArray = [];

var cropper;

var currentRutPath;

$(document).ready(function() {


    $("#main-menu-user").addClass("text-primary");

    formValidator.Validate("#create-user-form", createUser);
    loadUsers();
    loadRoles();
    loadStatus();

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

    var title = user ? "<i class='iconsminds-file-edit with-rotate-icon'></i> Edit user" :
        "<i class='iconsminds-add-user'></i> Create New User";

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

        if (key == "SystemUserPassword") {

            $("#SystemUserPassword").val("");
        }




        if (key == "name") {
            userName = value;
        }

    });


    $("#create-user-img").attr("src", user.companyPhotoUrl);
}


var loadUserSelectedImage = async function(input) {
    if (typeof cropper !== 'undefined' && cropper !== null)
        cropper.destroy();

    var loadedImage = await getInputImageData(input);
    $("#create-user-img").prop("src", loadedImage);

    var image = document.querySelector('#create-user-img');
    cropper = new Cropper(image, { aspectRatio: 1, movable: true, dragMode: "move", viewMode: 2 });
}


var loadUsers = async function() {
    let Users = await webClient.RequestAsync("user/getAllUser", "", webClient.ContentType.DEFAULT);
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

    /* $.each(Users.TeellaUsers, function(index, user) {

         UsersHtml += "<div class='card mb-3 user-list-thumb'>" +
             "<div class='d-flex flex-row'>" +
             getUserThumbImageHtml(user) +
             getUserBasicDataHtml(user) +
             "<div class='d-flex p-4'>"

         +
         "<a class='edit-user-link d-flex align-items-center' data-user='" + JSON.stringify(user) + "' href=''>" +
             "Editar Usuario <h2 class='m-0'><i class='iconsminds-file-edit with-rotate-icon'></i></h2>" +
             "</a>"
             /*  + "<button class='btn btn-outline-theme-3 icon-button rotate-icon-click collapsed align-self-center'"
               + " type='button' data-toggle='collapse' data-target='#company-data-collapse-container-" + index 
               + "' aria-expanded='false' aria-controls='q2'>"
                   + "<i class='simple-icon-arrow-down with-rotate-icon'></i>"
               + "</button>"*/
    +
    /*    "</div>" +
            "</div>" +
            getUserCollapsedDataHtml(index, user) +
            "</div>";
    });*/

    $.each(Users.CompanyUsers, function(index, user) {
        UsersHtml += "<div class='card mb-3 user-list-thumb'>" +
            "<div class='d-flex flex-row'>" +
            // getUserThumbImageHtml(user) +
            getUserBasicDataHtml(user) +
            "<div class='d-flex p-4'>" +
            "<a class='edit-user-link d-flex align-items-center' data-user='" + JSON.stringify(user) + "' href=''>" +
            "Edit<h2 class='m-0'><i class='iconsminds-file-edit with-rotate-icon'></i></h2>" +
            "</a>"
            /*  + "<button class='btn btn-outline-theme-3 icon-button rotate-icon-click collapsed align-self-center'"
              + " type='button' data-toggle='collapse' data-target='#company-data-collapse-container-" + index 
              + "' aria-expanded='false' aria-controls='q2'>"
                  + "<i class='simple-icon-arrow-down with-rotate-icon'></i>"
              + "</button>"*/
            +
            "</div>" +
            "</div>" +
            getUserCollapsedDataHtml(index, user) +
            "</div>";
    });
    $("#users-list-container").html(UsersHtml);


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

var getUserThumbImageHtml = function(user) {
    var imageHtml = "<div class='border-right list-thumbnail card-img-left h-auto d-none d-lg-block' " +
        "style='background: url(" + user.companyPhotoUrl + ") center no-repeat; background-size: cover; width: 8%;'></div>" +
        "<div class='border-right list-thumbnail card-img-left w-20 h-auto d-lg-none' " +
        "style='background: url(" + user.companyPhotoUrl + ") center no-repeat; background-size: cover;'></div>";

    return imageHtml;

};

var getUserBasicDataHtml = function(user) {
    var roleId = $('.RoleId').val();
    if (roleId != 8) {
        $("#CompanyName").prop('disabled', true);

    }

    var basicDataHtml = "<div class='d-flex flex-grow-1 min-width-zero'>" +
        "<div class='card-body align-self-center d-flex flex-column flex-lg-row justify-content-between min-width-zero align-items-lg-center'>" +
        "<a href='' class='w-20 w-sm-100'><p class='list-item-heading mb-0 truncate'>" + user.SystemUserName + "</p></a>" +
        "<p class='mb-0 text-muted w-40 w-sm-100'>" +
        "<span class='glyph-icon iconsminds-id-card align-text-top' style='font-size: 25px;'></span> " +
        "<span class='align-middle'>" + user.SystemUserEmail + "</span>" +
        "</p>" +
        "<p class='mb-0 text-muted w-15 w-sm-100'>" +
        "<span class='glyph-icon iconsminds-smartphone-4 align-text-top' style='font-size: 25px;'></span> " +
        "<span class='align-middle'>" + user.SystemUserPhone + "</span>" +
        "</p>" +
        "<div class='mb-2 d-md-none'></div>"

    +"</div>" +
    "</div>";

    //+ getAssistantStatusSelectHtml()
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
        "<a class='edit-user-link d-flex align-items-center' data-user='" + JSON.stringify(user) + "' href=''>" +
        "Editar Usuario <h2 class='m-0'><i class='iconsminds-file-edit with-rotate-icon'></i></h2>" +
        "</a>" +
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

    showLoading();


    var result = await webClient.RequestAsync("user/registerWebUser", userFormData, webClient.ContentType.DEFAULT);
    if (result.status === REQUEST_STATUS.ERROR) { hideLoading(); return; }

    hideLoading();

    if (result.status === "success") {
        $("#create-user-modal").modal("hide");
        await loadUsers();
    }
};