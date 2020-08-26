$(document).ready(function() {
    $(document).on("hidden.bs.modal", "#change-password-modal", function() { $("#change-password-form")[0].reset(); });

    formValidator.Validate("#login-form", login);
    formValidator.Validate("#change-password-form", changePassword);
});


var login = async function() {

    var params = $("#login-form").serialize();
    let result = await webClient.RequestAsync("user/loginUserWeb", params, webClient.ContentType.DEFAULT, true);

    console.log(result.status);


    switch (result.status) {

        case 'success':
            location.reload();
            console.log(123);
            break;

        case REQUEST_STATUS.SUCCESS:
            location.reload();
            break;

        case REQUEST_STATUS.RECOVER_PSWD:
            $("#change-password-modal").modal();
            break;
    }


};


var changePassword = async function() {
    var newPassword = $("#change-password-form input[name='password']").val();
    var confirmPassword = $("#change-password-form input[name='confirmpassword']").val();

    if (newPassword !== confirmPassword) {
        alertPop.show(alertPop.Types.WARNING, "Cambiar contraseña", "Las contraseñas no coinciden");
        return;
    }

    $("#change-password-form input[name='email']").val($("#login-form input[name='email']").val());

    var params = $("#change-password-form").serialize();
    $("#change-password-modal").modal("hide");
    await webClient.RequestAsync("user/updatePasswordUser", params, webClient.ContentType.DEFAULT, true);
};