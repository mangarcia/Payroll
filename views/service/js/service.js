
var dTable;


$(document).ready(function ()
{
    $("#main-menu-service").addClass("text-primary");
    //formValidator.Validate("#create-service-form", createService);

    loadServices();

    //$(document).on("click", ".create-service-link", function (ev)
    //{
    //    ev.preventDefault();
    //    openServiceFormModal($(this).data("service"));
    //});

    $(document).on("hidden.bs.modal", "#create-service-modal", function ()
    {
        formValidator.ResetForm("#create-service-form");
    });

    $(document).on("click", "#pageLengthSercivesTable div a", function () { dTable.page.len(parseInt($(this).html())).draw(); });
    $('#searchDatatable').on('keyup', function () { dTable.search(this.value).draw(); });

    $(document).on("click", ".edit-service-link", function (ev)
    {
        ev.preventDefault();
        openServiceFormModal($(this).data("service"));
    });
});


var loadServices = async function ()
{
    let result = await webClient.RequestAsync("request/getAllRequest", {}, webClient.ContentType.DEFAULT);
    if (result.data.length === 0) return;

    $("#service-list-container").html(getServicesHtml(result.data));
    dTable = $("#services-data-tablet").DataTable({ "sDom": "tipr" });
};


var getServicesHtml = function (services)
{
    var servicesHtml = "<div class='data-table-rows data-tables-hide-filter'><table id='services-data-tablet' class='responsive nowrap'>"
        + "<thead><tr><th>Plan</th><th>Paciente</th><th>Fecha Inicio</th><th>Fecha Fin</th><th>Costo Total</th><th>Dirección</th><th class='empty'>&nbsp;</th></tr></thead>"
        + "<tbody>";

    $.each(services, function (index, service)
    {
        var patientName = (typeof service.Familiars.basicDataFirstName === "undefined" ? "" : service.Familiars.basicDataFirstName) 
            + (service.Familiars.basicDataLastName === "undefined" ? "" : " " + service.Familiars.basicDataLastName);

        

        servicesHtml += "<tr>"
                + "<td><p class='text-break'>Nombe plan</p></td>"
                + "<td><p class='text-break'>" + patientName + "</p></td>"
                + "<td><p class='text-break'>" + service.serviceDateTimeStart + "</p></td>"
                + "<td><p class='text-break'>" + service.serviceDateTimeEnd + "</p></td>"
                + "<td><p class='text-break'>" + service.serviceTotalAmount + "</p></td>"
                + "<td><p class='text-break'>" + service.temp_address + " " + service.temp_addressInfo + "</p></td>"
                //+ "<td><p class='text-break'>" + service.userRequestObservations + "</p></td>"
                + "<td>"
                    //+ "<button class='btn btn-outline-theme-3 icon-button rotate-icon-click align-self-center collapsed' type='button' data-toggle='collapse' "
                    //    + "data-target='#assistant-data-collapse-container-0' aria-expanded='false' aria-controls='q2'>"
                    //    + "<i class='simple-icon-arrow-down with-rotate-icon'></i>"
                    //+ "</button>"
                    + "<div class='p-3 border-top collapse show' id='assistant-data-collapse-container-0' style=''>jj</div>"
                    //+ "<div class='shadow-sm border p-2 rounded-lg'>"
                    //    + "<a class='edit-service-link d-flex align-items-center' data-service='" + JSON.stringify(service) + "' href=''>"
                    //        + "<h2 class='m-0'><i class='iconsminds-file-edit with-rotate-icon'></i></h2> Editar"
                    //    + "</a>"
                    //+ "</div>"
                + "</td>"
            + "</tr>";
    });

    servicesHtml += "</tbody></table></div>";
    return servicesHtml;
};


var openServiceFormModal = function (service)
{
    var title = service ? "<i class='iconsminds-file-edit with-rotate-icon'></i> Editar Servicio" :
        "<i class='iconsminds-add'></i> Crear Nuevo Servicio";

    var label = service ? "Editar" : "Crear";

    $("#create-service-modal-title").html(title);
    $("#create-service-modal-button").html(label);

    //if (service) setEditPlanFormValues(service);

    $("#create-service-modal").modal();
};


//var createService = function ()
//{

//};