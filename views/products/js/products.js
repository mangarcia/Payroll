var sexArray = [];
var docTypesArray = [];
var academicLevelsArray = [];

var cropper;

var currentRutPath;

$(document).ready(function() {


    $("#main-menu-product").addClass("text-primary");

    formValidator.Validate("#create-product-form", createProduct);
    loadProducts();
    loadCompanies();
    loadTypes();

    $(document).on("change", "#create-product-img-file", function() { loadProductSelectedImage(this); });

    $(document).on("click", ".create-product-link", function(ev) {
        ev.preventDefault();
        openProductFormModal();

    });

    $(document).on("click", ".edit-product-link", function(ev) {
        ev.preventDefault();
        openProductFormModal($(this).data("product"));

    });

    $(document).on("hidden.bs.modal", "#create-product-modal", function() {
        $("#create-product-img").prop("src", USER_IMAGE_PLACEHOLDER);


        formValidator.ResetForm("#create-product-form");
    });

    $(document).on("keyup", "#searchProductInput", function() {
        $(".product-list-thumb").addClass("d-none").removeClass("d-flex");
        $(".product-list-thumb:contains('" + $(this).val() + "')").removeClass("d-none").addClass("d-flex");
    });


    $(document).on("click", "#pageLengthPlansTable div a", function() { dTable.page.len(parseInt($(this).html())).draw(); });
    $('#searchDatatable').on('keyup', function() { dTable.search(this.value).draw(); });

});


var openProductFormModal = function(product = false) {

    var title = product ? "<i class='iconsminds-file-edit with-rotate-icon'></i> Editar producto" :
        "<i class='iconsminds-add-product'></i> Crear Nuevo Usuario";

    var label = product ? "Guardar" : "Crear";

    $("#create-product-modal-title").html(title);
    $("#create-product-modal-button").html(label);

    if (product) setEditproductFormValues(product);
    $("#create-product-modal").modal();
};


var setEditproductFormValues = function(product) {
    console.log("PRODUCTO A EDITAR ==> ", product);
    var productName = "";

    $.each(product, function(key, value) {
        $("#create-product-form [name='" + key + "']").val(value);
        $("#create-product-form input[type='checkbox'][name='" + key + "']").prop("checked", value == 1);
        $("#create-product-form textarea[name='" + key + "']").html(value);



        if (key == "Company_companyId") {

            $("#companyId option[value='" + value + "']").attr("selected", true).trigger('change');

        }


        if (key == "TypeProduct_typeProductId") {

            $("#typeProductId option[value='" + value + "']").attr("selected", true).trigger('change');


        }


        if (key == "name") {
            productName = value;
        }

    });


    $("#create-product-img").attr("src", product.ImageUrl);
}


var loadProductSelectedImage = async function(input) {


    var loadedImage = await getInputImageData(input);
    $("#create-product-img").prop("src", loadedImage);

    var image = document.querySelector('#create-product-img-file');
    encodeImageFileAsURL(image);


    function encodeImageFileAsURL(element) {
        var file = element.files[0];
        var reader = new FileReader();
        reader.onloadend = function() {
            console.log(reader.result)

            cropper = reader.result;

        }
        reader.readAsDataURL(file);
    }
}


var loadProducts = async function() {
    let Products = await webClient.RequestAsync("product/getProducts", "", webClient.ContentType.DEFAULT);
    if (Products.status === REQUEST_STATUS.ERROR) return;
    if (typeof Products.data === "undefined" || Products.data === '' || Products.data.length === 0) return;

    showProducts(Products.data);
};





var loadCompanies = async function() {
    let Companies = await webClient.RequestAsync("company/getallcompany", "", webClient.ContentType.DEFAULT);
    if (Companies.status === REQUEST_STATUS.ERROR) return;
    if (typeof Companies.data === "undefined" || Companies.data === '' || Companies.data.length === 0) return;

    showCompanies(Companies.data);

};

var loadTypes = async function() {
    let Types = await webClient.RequestAsync("product/getProductType", "", webClient.ContentType.DEFAULT);
    if (Types.status === REQUEST_STATUS.ERROR) return;
    if (typeof Types.data === "undefined" || Types.data === '' || Types.data.length === 0) return;

    showTypes(Types.data);

};


var showTypes = function(Types) {
    //$("#insertar").append(" <label>State Single</label><select class='form-control select2-single'  id='roles' data-width='100%'></select>.");
    $.each(Types, function(index, type) {

        var drow = JSON.stringify(type);


        $('#typeProductId').append('<option value= "' + type.typeProductId + '">' + type.name + '</option>');

    });

};





var showCompanies = function(Companies) {
    //$("#insertar").append(" <label>State Single</label><select class='form-control select2-single'  id='roles' data-width='100%'></select>.");
    $.each(Companies, function(index, company) {

        var drow = JSON.stringify(company);


        $('#companyId').append('<option value= "' + company.companyId + '">' + company.name + '</option>');

    });




};




var showProducts = function(Products) {
    var ProductsHtml = "";

    $.each(Products, function(index, product) {

        ProductsHtml += "<div class='card mb-3 product-list-thumb'>" +
            "<div class='d-flex flex-row'>" +
            getProductThumbImageHtml(product) +
            getProductBasicDataHtml(product) +
            "<div class='d-flex p-4'>"

        +
        "<a class='edit-product-link d-flex align-items-center' data-product='" + JSON.stringify(product) + "' href=''>" +
            "Editar Producto <h2 class='m-0'><i class='iconsminds-file-edit with-rotate-icon'></i></h2>" +
            "</a>"
            /*  + "<button class='btn btn-outline-theme-3 icon-button rotate-icon-click collapsed align-self-center'"
              + " type='button' data-toggle='collapse' data-target='#company-data-collapse-container-" + index 
              + "' aria-expanded='false' aria-controls='q2'>"
                  + "<i class='simple-icon-arrow-down with-rotate-icon'></i>"
              + "</button>"*/
            +
            "</div>" +
            "</div>" +
            getProductCollapsedDataHtml(index, product) +
            "</div>";
    });


    $("#products-list-container").html(ProductsHtml);


    $('.product-data-smart-wizard').smartWizard({
        selected: null,
        toolbarSettings: { toolbarPosition: "none", showNextButton: false, showPreviousButton: false },
        anchorSettings: { anchorClickable: true, enableAllAnchors: true }
    });


};

var getProductThumbImageHtml = function(product) {
    var imageHtml = "<div class='border-right list-thumbnail card-img-left h-auto d-none d-lg-block' " +
        "style='background: url(" + product.ImageUrl + ") center no-repeat; background-size: cover; width: 8%;'></div>" +
        "<div class='border-right list-thumbnail card-img-left w-20 h-auto d-lg-none' " +
        "style='background: url(" + product.ImageUrl + ") center no-repeat; background-size: cover;'></div>";

    return imageHtml;

};

var getProductBasicDataHtml = function(product) {
    var roleId = $('.RoleId').val();
    if (roleId != 8) {
        $("#CompanyName").prop('disabled', true);

    }

    var basicDataHtml = "<div class='d-flex flex-grow-1 min-width-zero'>" +
        "<div class='card-body align-self-center d-flex flex-column flex-lg-row justify-content-between min-width-zero align-items-lg-center'>" +
        "<a href='' class='w-100 w-sm-100'><p class='list-item-heading mb-0 truncate'>" + product.nameProduct + "</p></a>" +

        /*  "<p class='mb-0 text-muted w-15 w-sm-100'>" +
          "<span class='glyph-icon iconsminds-smartphone-4 align-text-top' style='font-size: 25px;'></span> " +
          "<span class='align-middle'>" + product.Company_companyId + "</span>" +
          "</p>" +*/
        "<div class='mb-2 d-md-none'></div>"

    +"</div>" +
    "</div>";

    //+ getAssistantStatusSelectHtml()
    return basicDataHtml;
};

var getProductStatusSelectHtml = function() {
    var productStatusSelect = "<div class='w-15 w-sm-100 form-group m-0'>" +
        "<select id='inputState' class='form-control'>" +
        "<option value='1'>Activo</option>" +
        "</select >" +
        "</div > ";
    return productStatusSelect;

};

var getProductCollapsedDataHtml = function(index, product) {
    var wizardId = "wizard-info-product-" + index;

    var productCollapsedData = "<div class='collapse p-2 border-top' id='product-data-collapse-container-" + index + "'>" +
        "<div class='d-flex justify-content-end mb-2'>" +
        "<div class='shadow-sm border p-2 rounded-lg'>" +
        "<a class='edit-product-link d-flex align-items-center' data-product='" + JSON.stringify(product) + "' href=''>" +
        "Editar Usuario <h2 class='m-0'><i class='iconsminds-file-edit with-rotate-icon'></i></h2>" +
        "</a>" +
        "</div>" +
        "</div>" +
        "<div class='card'>" +
        "<div class='product-data-smart-wizard'>" +
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
        "<div class='card-body'><div class='product-availability-calendar'></div></div>" +
        "</div>" +
        "</div>" +
        "</div>" +
        "</div>" +
        "</div>" +
        "</div>" +
        "</div>" +
        "</div>";

    return productCollapsedData;
};


var createProduct = async function() {
    var productFormData = $("#create-product-form").serialize();
    console.log(productFormData);
    showLoading();

    productFormData += "&basicDataPhoto=" + cropper;



    var result = await webClient.RequestAsync("product/createProduct", productFormData, webClient.ContentType.DEFAULT);
    if (result.status === REQUEST_STATUS.ERROR) { hideLoading(); return; }

    hideLoading();


    if (result.status === REQUEST_STATUS.SUCCESS) {
        $("#create-product-modal").modal("hide");
        await loadProducts();
    }
};