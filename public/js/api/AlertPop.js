
function AlertPop()
{
    const ICONS =
    {
        SUCCESS: "glyph-icon simple-icon-check",
        DANGER:  "glyph-icon iconsminds-danger",
        WARNING: "glyph-icon iconsminds-danger",
        INFO   : "glyph-icon iconsminds-information"
    };


    this.Types = { SUCCESS: "success", WARNING: "warning", DANGER: "danger", INFO: "info" };


    this.show = function (type, title, message, callback = false)
    {
        var parent = 'body';

        switch (type)
        {
            case this.Types.DANGER:
                showAlert(ICONS.DANGER, type, title, message, parent);
            break;

            case this.Types.INFO:
                showAlert(ICONS.INFO, type, title, message, parent);
            break;

            case this.Types.SUCCESS:
                showAlert(ICONS.SUCCESS, type, title, message, parent);
            break;

            case this.Types.WARNING:
                showAlert(ICONS.WARNING, type, title, message, parent);
            break;
        }
    };


    function showAlert (alertPopIcon, alertPopType, alertPopTitle, alertPopMessage, alerPopParent)
    {
        var options = { icon: alertPopIcon, title: alertPopTitle, message: alertPopMessage, target: "_blank" };
        
        var settings =
        {
            element: alerPopParent,
            position: null,
            type: alertPopType,
            allow_dismiss: true,
            newest_on_top: false,
            showProgressbar: false,
            icon_type: 'class',
            placement: { from: "top", align: "right" },
            animate: { enter: 'animated fadeInLeft', exit: 'animated fadeOutRight' },
            offset: 15,
            spacing: 10,
            z_index: 2048,
            delay: 4000,
            timer: 80,
            template:
                "<div data-notify='container' class='col-11 col-sm-3 alert alert-{0} border-{0} rounded-lg shadow-sm' role='alert'>"
                    + "<button type='button' aria-hidden='true' class='close' data-notify='dismiss'>×</button>" 
                    + "<div class='row'>"
                        + "<div class='col-xs-1 p-0'>"
                            + "<h2 class='no-margin'><span data-notify='icon'></span></h2>"
                        + "</div>"
                        + "<div class='col-xs-11'>"
                            + "<span data-notify='title'>{1}</span> "
                            + "<span data-notify='message'>{2}</span>"
                            + "<div class='progress' data-notify='progressbar'>"
                            + "<div class='progress-bar progress-bar-{0}' role='progressbar' aria-valuenow='0' "
                            + "aria-valuemin='0' aria-valuemax='100' style='width: 0%;'></div>"
                            + "</div>"
                            + "<a href='{3}' target='{4}' data-notify='url'></a>" 
                        + "</div>"
                    + "</div>" 
                + "</div>"
        };

        $.notify(options, settings);
    };
}

var alertPop = new AlertPop();