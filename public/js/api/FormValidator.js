
function FormValidator()
{
    var validators = {};

    this.Validate = function (formSelector, callback = false)
    {
        var validationMessages = {};

        $(formSelector + " input").each(function ()
        {
            var elementName = $(this).prop("name");
            validationMessages[elementName] = { required: "Este campo es requerido" };

            if ($(this).prop("type") === "email")
                validationMessages[elementName]["email"] = "Se debe ingresar una cuenta de correo válida";
        });

        registerFormSelects($(formSelector)[0]);

        validators[formSelector] = $(formSelector).validate
        ({
            messages: validationMessages,
            errorElement: "em",
            errorClass: "text-danger",
            submitHandler: function (form)
            {
                var validated = validateFormSelects(form);
                if (validated && callback) callback();
            }
        });
    };

    this.ResetForm = function (formSelector)
    {
        if (typeof validators[formSelector] === "undefined")
            return;

        $(formSelector)[0].reset();
        validators[formSelector].resetForm();
    };

    function registerFormSelects (form)
    {
        var requiredSelects = $(form).find(".form-select-required");

        $.each(requiredSelects, function (index, select)
        { $(select).find("select").on("change", function () { validateSelect($(select)) }); });
    }

    function validateFormSelects (form)
    {
        var requiredSelects = $(form).find(".form-select-required");
        var selectsValid = true;

        if (requiredSelects.length > 0)
        {
            $.each(requiredSelects, function (index, select)
            {
                if (!validateSelect($(select)))
                    selectsValid = false;
            });
        }

        return selectsValid;
    }

    function validateSelect(selectContainer)
    {
        if ($(selectContainer).find("select").val() === "" || $(selectContainer).find("select").val() === "0")
        {
            $(selectContainer).find(".invalid-label").removeClass("d-none");
            $(selectContainer).find("select").focus();
            return false;
        }

        $(selectContainer).find(".invalid-label").addClass("d-none");
        return true;
    }
}

var formValidator = new FormValidator();