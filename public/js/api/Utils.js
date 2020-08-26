
var showLoading = function ()
{
    $("body").append("<div class='loading-frame'></div>");
    $("body").addClass("show-spinner");
};


var hideLoading = function ()
{
    $("body").removeClass("show-spinner");
    $(".loading-frame").remove();
};


var getInputImageData = async function (input)
{
    return new Promise((resolve, reject) =>
    {
        if (input.files && input.files[0])
        {
            var reader = new FileReader();

            reader.onload = function (e)
            {
                resolve(e.target.result);
            };
            reader.onerror = function ()
            {
                reader.abort();
                alertPop.show(alertPop.Types.WARNING, "Cargar archivo", "Error al cargar el archivo");
                reject();
            };

            reader.readAsDataURL(input.files[0]);
        }
    });
};


var getBase64File = file => new Promise((resolve, reject) =>
{
    var reader = new FileReader();

    reader.onload = (function (theFile)
    {
        return function (e)
        {
            var binaryData = e.target.result;
            var base64String = window.btoa(binaryData);
            resolve(base64String);
        };
    })(file);

    reader.onerror = error => reject(error);
    reader.readAsBinaryString(file);
});