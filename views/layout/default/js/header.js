$(document).ready(function() {
    setInterval(showMenuItems, 800);

    $(document).on("click", ".close-session-link", function(ev) {
        ev.preventDefault();
        logout();
    });
});


var showMenuItems = function() {
    $("rect").removeAttr("hidden");
    $(".menu").removeAttr("hidden");
};


var logout = async function() {
    console.log(123);
    let result = await webClient.RequestAsync("user/signOutWebUser", {}, webClient.ContentType.DEFAULT, true);

    location.reload();
    //if (result.status === REQUEST_STATUS.SUCCESS || result.status === REQUEST_STATUS.OK)
        
};