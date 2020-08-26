
$(document).ready(function ()
{
    formValidator.Validate("#forgot-password-form", sendResetPasswordMessage);
});


var sendResetPasswordMessage = async function ()
{
    var params = $("#forgot-password-form").serialize();
    var result = await webClient.RequestAsync("user/recoveryPassword", params, webClient.ContentType.DEFAULT, true);

    switch (result.status)
    {
        case REQUEST_STATUS.SUCCESS:
            $("#forgot-password-form")[0].reset();
        break;
    }
};