
var citiesTagsInput;
var dTable;


$(document).ready(function ()
{
    $("#main-menu-plan").addClass("text-primary");
    formValidator.Validate("#create-plan-form", createPlan);

    initCitiesTagsInput();
    loadPlans();

    $(document).on("click", ".create-plan-link", function (ev)
    {
        ev.preventDefault();
        openPlanFormModal();
    });

    $(document).on("hidden.bs.modal", "#create-plan-modal", function ()
    {
        formValidator.ResetForm("#create-plan-form");
        citiesTagsInput.tagsinput('removeAll');
    });

    $(document).on("click", ".edit-plan-link", function (ev)
    {
        ev.preventDefault();
        openPlanFormModal($(this).data("plan"));
    });

    $(document).on("click", "#pageLengthPlansTable div a", function () { dTable.page.len(parseInt($(this).html())).draw(); });
    $('#searchDatatable').on('keyup', function () { dTable.search(this.value).draw(); });
});


var loadPlans = async function ()
{
    let result = await webClient.RequestAsync("Plan/readPlans", {}, webClient.ContentType.DEFAULT, true);
    if (result.data.length === 0) return;

    $("#plan-list-container").html(getPlansTableHtml(result.data));
    dTable = $("#plans-data-tablet").DataTable({ "sDom": "tipr" });
};

var getPlansTableHtml = function (plans)
{
    var tableHtml = "<div class='data-table-rows data-tables-hide-filter'><table id='plans-data-tablet' class='responsive nowrap'>"
        + "<thead><tr><th>Nombre</th><th>Cardinalidad</th><th>Festivo</th><th>Día hábil</th><th>Ciudades</th><th class='empty'>&nbsp;</th></tr></thead><tbody>";

    $.each(plans, function (index, plan)
    {
        var cities = getPlanCitiesTags(plan.cities);

        tableHtml += "<tr>"
                + "<td><p class='text-break'>" + plan.PlanName + "</p></td>"
                + "<td><p class='text-muted'>" + plan.Cardinality + " " + plan.idPlanDays + "</p></td>"
                + "<td><p class='text-muted'>" + plan.HolidayPrice + "</p></td>"
                + "<td><p class='text-muted'>" + plan.WorkDayPrice + "</p></td>"
                + "<td><p class='text-muted'>" + cities + "</p></td>"
                + "<td>"
                    + "<div class='shadow-sm border p-2 rounded-lg'>"
                        + "<a class='edit-plan-link d-flex align-items-center' data-plan='" + JSON.stringify(plan) + "' href=''>"
                            + "<h2 class='m-0'><i class='iconsminds-file-edit with-rotate-icon'></i></h2> Editar"
                        + "</a>"
                    + "</div>"
                + "</td>"
            + "</tr>";
    });

    tableHtml += "</tbody></table></div>";
    return tableHtml;
};

var getPlanCitiesTags = function (cities)
{
    var citiesTags = "";

    $.each(cities, function (index, city)
    { citiesTags += "<span class='badge badge-pill badge-outline-info'>" + city.Text + "</span>"; });

    return citiesTags;
};


var initCitiesTagsInput = function ()
{
    $(document).on("keydown", ".bootstrap-tagsinput", function (ev) { ev.preventDefault(); });

    citiesTagsInput = $("#plan-cities-tags-input");
    citiesTagsInput.tagsinput({ itemValue: "value", itemText: "text", placeholder: 'Ingresar ciudades', });

    $(document).on("change", "#plan-cities-select", function ()
    {
        var value = $(this).val();
        var text = $(this).find("option:selected").html();

        if (value == 0) return;
        citiesTagsInput.tagsinput("add", { value, text });
    });
};


var openPlanFormModal = function (plan = false)
{
    var title = plan ? "<i class='iconsminds-file-edit with-rotate-icon'></i> Editar Plan" :
        "<i class='iconsminds-add'></i> Crear Nuevo Plan";

    var label = plan ? "Editar" : "Crear";

    $("#create-plan-modal-title").html(title);
    $("#create-plan-modal-button").html(label);

    if (plan) setEditPlanFormValues(plan);
    $("#create-plan-modal").modal();
};

var setEditPlanFormValues = function (plan)
{
    $.each(plan, function (key, value)
    {
        $("#create-plan-form [name='" + key + "']").val(value);
        $("#create-plan-form input[type='checkbox'][name='" + key + "']").prop("checked", value == 1);
        $("#create-plan-form textarea[name='" + key + "']").html(value);
    });

    $.each(plan.cities, function (index, city)
    {
        var value = city.value;
        var text = city.Text;
        citiesTagsInput.tagsinput("add", { value, text });
    });
};


var createPlan = async function ()
{
    var cities = $("#plan-cities-tags-input").val();
    var form = $("#create-plan-form").serialize();

    if (cities === "")
    {
        alert("Es necesario indicar al menos una ciudad");
        reuturn;
    }

    console.log("PLAN TO SEND ==> ", form);
    let result = await webClient.RequestAsync("Plan/createPlan", form, webClient.ContentType.DEFAULT, true);

    if (result.status === REQUEST_STATUS.SUCCESS)
    {
        loadPlans();
        $("#create-plan-modal").modal("hide");
    }
};