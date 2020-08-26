
var sexArray = [];
var docTypesArray = [];
var academicLevelsArray = [];

var cropper;


$(document).ready(function ()
{
    $("#main-menu-assistant").addClass("text-primary");

    formValidator.Validate("#create-assistant-form", createAssistant);
    //formValidator.Validate("#edit-availability-form", editAvailability);
    loadAssistants();

    $(document).on("change", "#create-assistant-img-file", function () { loadAssistantSelectedImage(this); });

    $(document).on("click", ".create-assistant-link", function (ev)
    {
        ev.preventDefault();
        openAssistanFormModal();
    });

    $(document).on("click", ".edit-assistant-link", function (ev)
    {
        ev.preventDefault();
        openAssistanFormModal($(this).data("assistant"));
    });

    $(document).on("hidden.bs.modal", "#create-assistant-modal", function ()
    {
        if (typeof cropper !== 'undefined' && cropper !== null)
            cropper.destroy();

        $("#create-assistant-img").prop("src", USER_IMAGE_PLACEHOLDER);
        formValidator.ResetForm("#create-assistant-form");
    });

    $(document).on("keyup", "#searchAssistantInput", function ()
    {
        $(".assistant-list-thumb").addClass("d-none").removeClass("d-flex");
        $(".assistant-list-thumb:contains('" + $(this).val() + "')").removeClass("d-none").addClass("d-flex");
    });
});


var openAssistanFormModal = function (assistant = false)
{
    var title = assistant ? "<i class='iconsminds-file-edit with-rotate-icon'></i> Editar Asistente" :
        "<i class='iconsminds-add-user'></i> Crear Nuevo Asistente";

    var label = assistant ? "Editar" : "Crear";

    $("#create-assistant-modal-title").html(title);
    $("#create-assistant-modal-button").html(label);

    if (assistant) setEditAssistantFormValues(assistant);
    $("#create-assistant-modal").modal();
};


var setEditAssistantFormValues = function (assistant)
{
    $.each(assistant, function (key, value)
    {
        $("#create-assistant-form [name='" + key + "']").val(value);
        $("#create-assistant-form input[type='checkbox'][name='" + key + "']").prop("checked", value == 1);
        $("#create-assistant-form textarea[name='" + key + "']").html(value);
    });

    $("#create-assistant-img").attr("src", assistant.basicDataPhoto);
}


var loadAssistantSelectedImage = async function (input)
{
    if (typeof cropper !== 'undefined' && cropper !== null)
        cropper.destroy();

    var loadedImage = await getInputImageData(input);
    $("#create-assistant-img").prop("src", loadedImage);

    var image = document.querySelector('#create-assistant-img');
    cropper = new Cropper(image, { aspectRatio: 1, movable: true, dragMode: "move", viewMode: 2 });
}


var loadAssistants = async function ()
{
    let assistants = await webClient.RequestAsync("assistant/getAssistants", "", webClient.ContentType.DEFAULT);

    if (assistants.status === REQUEST_STATUS.ERROR) return;
    if (typeof assistants.data === "undefined" || assistants.data === '' || assistants.data.length === 0) return;

    showAssistants(assistants.data);
};

var showAssistants = function (assistants)
{
    var assistantsHtml = "";

    $.each(assistants, function (index, assistant)
    {
        assistantsHtml += "<div class='card mb-3 assistant-list-thumb'>"
            + "<div class='d-flex flex-row'>"
                + getAssistantThumbImageHtml(assistant)
                + getAssistantBasicDataHtml(assistant)
                + "<div class='d-flex p-4'>"
                    + "<button class='btn btn-outline-theme-3 icon-button rotate-icon-click collapsed align-self-center'"
                    + " type='button' data-toggle='collapse' data-target='#assistant-data-collapse-container-" + index 
                    + "' aria-expanded='false' aria-controls='q2'>"
                        + "<i class='simple-icon-arrow-down with-rotate-icon'></i>"
                    + "</button>"
                + "</div>"
            + "</div>"
            + getAssistantCollapsedDataHtml(index, assistant)
        + "</div>";
    });

    $("#assistants-list-container").html(assistantsHtml);
    initAssistantsCalendars();
}; 

var initAssistantsCalendars = function ()
{
    $.each($(".assistant-availability-calendar"), function (index, calendar)
    {
        var assistant = $(calendar).data("assistant");

        $(calendar).fullCalendar
        ({
            header: { left: 'prev,next today', center: 'title', right: 'month,agendaWeek,agendaDay,listWeek' },
            defaultDate: moment().format("YYYY-MM-DD"),
            navLinks: true,
            eventLimit: true,
            events: assistant.Events,
            dayClick: function (date, jsEvent, view)
            {
                console.log("DAY SELECTED ==> ", date, jsEvent, view);
                $("#edit-availability-date-title").html(moment(date).format("MMM DD YYYY"));
                $("#assistant-availability-name").html(assistant.Name);
                $("#edit-availability-form [name='assistantId']").val(assistant.Id);
                $("#edit-availability-form [name='AvailabilityDate']").val(moment(date).format("YYYY-MM-DD"));
                $("#edit-availability-modal").modal();
            }
        });
    });
};

var getAssistantThumbImageHtml = function (assistant)
{
    var imageHtml = "<div class='border-right list-thumbnail card-img-left h-auto d-none d-lg-block' "
        + "style='background: url(" + assistant.basicDataPhoto + ") center no-repeat; background-size: cover; width: 8%;'></div>"
        + "<div class='border-right list-thumbnail card-img-left w-20 h-auto d-lg-none' "
        + "style='background: url(" + assistant.basicDataPhoto + ") center no-repeat; background-size: cover;'></div>";

    return imageHtml;
};

var getAssistantBasicDataHtml = function (assistant)
{
    var assistantName = assistant.basicDataFirstName + " " + assistant.basicDataLastName;

    var basicDataHtml = "<div class='d-flex flex-grow-1 min-width-zero'>"
        + "<div class='card-body align-self-center d-flex flex-column flex-lg-row justify-content-between min-width-zero align-items-lg-center'>"
            + "<a href='' class='w-20 w-sm-100'><p class='list-item-heading mb-0 truncate'>" + assistantName + "</p></a>"
            + "<p class='mb-0 text-muted w-15 w-sm-100'>" 
                + "<span class='glyph-icon iconsminds-id-card align-text-top' style='font-size: 25px;'></span> " 
                + "<span class='align-middle'>" + assistant.basicDataDocNumber + "</span>"
            + "</p>"
            + "<p class='mb-0 text-muted w-15 w-sm-100'>"
                + "<span class='glyph-icon iconsminds-smartphone-4 align-text-top' style='font-size: 25px;'></span> " 
                + "<span class='align-middle'>" + assistant.personalDataCellphone + "</span>"
            + "</p>"
            + "<div class='mb-2 d-md-none'></div>"
            
        + "</div>"
    + "</div>";

    //+ getAssistantStatusSelectHtml()
    return basicDataHtml;
};

var getAssistantStatusSelectHtml = function ()
{
    var assistantStatusSelect = "<div class='w-15 w-sm-100 form-group m-0'>"
            + "<select id='inputState' class='form-control'>"
                + "<option value='1'>Activo</option>"
            + "</select >"
        + "</div > ";

    return assistantStatusSelect;
};

var getAssistantCollapsedDataHtml = function (index, assistant)
{
    var assistantAddress = assistant.personalDataAddress + " " + assistant.personalDataAddressComplement + " " + assistant.personalDataAddressLocality;
    var assistantName = assistant.basicDataFirstName + " " + assistant.basicDataLastName;
    var assistantCalendarData = { Name: assistantName, Id: assistant.assistantId, Events: assistant.Availability };

    var assistatntDataHtml = "<div class='collapse p-3 border-top' id='assistant-data-collapse-container-" + index + "'>"
            + "<div class='row'>"
                + "<div class='col-sm-5'>"
                    + "<div class='card'>"
                        + "<div class='card-body'>"
                            + "<h4>Información personal</h4>"
                            + "<a class='edit-assistant-link position-absolute border p-2 rounded-lg' href='' style='top: 15px; right: 15px;' data-assistant='" + JSON.stringify(assistant) + "'>"
                                + "<i class='iconsminds-file-edit with-rotate-icon'></i>Editar"
                            + "</a>"
                            + "<div class='overflow-auto'><table class='table table-sm table-striped'>"
                                + "<tr><th>Dirección:</th><td>" + assistantAddress + "<td></tr>"
                                + "<tr><th>Email:</th><td>" + assistant.personalDataEmailAddress + "<td></tr>"
                                + "<tr><th>Teléfono:</th><td>" + assistant.personalDataTelephone + "<td></tr>"
                                + "<tr><th>Sexo:</th><td>" + assistant.sex + "<td></tr>"
                            + "</table></div>"
                            + "<h4>Información profesional</h4>"
                            + "<div class='overflow-auto'><table class='table table-sm table-striped'>"
                                + "<tr><th>Estudios:</th><td>" + assistant.School + "</td></tr>"
                                + "<tr><th>Título:</th><td>" + assistant.professionalJobTitle + "</td></tr>"
                                + "<tr><th>Compañía:</th><td>" + assistant.CompanyName + "</td></tr>"
                                + "<tr><th>Fecha ingreso:</th><td>" + assistant.companyBeginDate + "</td></tr>"
                                + "<tr><th>Aspiración salarial:</th><td>" + assistant.professionalSalaryAspiration + "</td></tr>"
                                + "<tr><th>Resumen profesional</th><td>" + assistant.professionalResume + "</td></tr>"
                                + "<tr><th>Valoración del jefe</th><td>" + assistant.bossObservation + "</td></tr>"
                                + getAvailableSkillStatus("Pago quincenal", assistant.paymentFifteen)
                                + getAvailableSkillStatus("Técnica de movilización", assistant.experienceMovility)
                            + "</table></div>"
                            + "<h4>Habilidades</h4>"
                            + "<div class='overflow-auto'><table class='table table-sm table-striped m-0'>"
                                + getAvailableSkillStatus("Cateterismo Vesical", assistant.experienceCateter)
                                + getAvailableSkillStatus("Traqueotomía", assistant.experienceTraqueo)
                                + getAvailableSkillStatus("Medicamentos Intravenosos", assistant.experienceIntraVain)
                            + "</table></div>"
                        + "</div>"
                    + "</div>"
                + "</div>"
                + "<div class='col-sm-7'>"
                    + "<h4>Disponibilidad</h4>"
                    + "<div class='card'>"
                        + "<div class='card-body'><div class='assistant-availability-calendar' data-assistant='" + JSON.stringify(assistantCalendarData) + "'></div></div>"
                    + "</div>"
                + "</div>"
            + "</div>"
        + "</div>";

    return assistatntDataHtml;
};

var getAvailableSkillStatus = function (skillName, skillValue)
{
    var skillStatus = skillValue === "1" ? "<span class='glyph-icon simple-icon-check text-primary' style='font-size: 22px;'></span>"
        : "<span class='glyph-icon simple-icon-close text-danger' style='font-size: 22px;'></span>";

    var skill = "<tr><th>" + skillName + "</th><td>" + skillStatus + "</td></tr>";
    return skill;
}


var createAssistant = async function ()
{
    var vhInputFile = $('#curriculum-vitae-input-file').get(0);
    var assistantFormData = $("#create-assistant-form").serialize();
    showLoading();
    
    if (typeof cropper !== "undefined")
        assistantFormData += "&basicDataPhoto=" + cropper.getCroppedCanvas().toDataURL();

    if (vhInputFile.files.length > 0)
    {
        var professionalHV = await getInputImageData(vhInputFile);
        assistantFormData += "&professionalHVUrl=" + professionalHV;
    }

    var result = await webClient.RequestAsync("Assistant/createAssistant", assistantFormData, webClient.ContentType.DEFAULT);
    if (result.status === REQUEST_STATUS.ERROR) { hideLoading(); return; }
    hideLoading();

    if (result.status === REQUEST_STATUS.SUCCESS)
    {
        $("#create-assistant-modal").modal("hide");
        await loadAssistants();
    }
};