
/**
 * Class For Queries
 */
function WebClient()
{
    const Method = { GET: "GET", POST: "POST" };
    const DataType = { HTML: "html", JSON: "json", XML: "xml" };

    this.ContentType =
    {
        DEFAULT: "application/x-www-form-urlencoded",
        JSON: "application/json; charset=UTF-8",
        NONE: false
    };


    this.RequestAsync = async function (requestUrl, params, contentType, isLoading = false)
    {
        return new Promise((resolve, reject) =>
        {
            var parameters = contentType === this.ContentType.JSON ? JSON.stringify(params) : params;
            var requestHeaders = { KEY_CONTENT_TYPE: contentType } 

            $.ajax
            ({
                async: true,
                type: Method.POST,
                dataType: DataType.JSON,
                headers: requestHeaders,
                url: "/" + requestUrl,
                data: parameters,
                processData: contentType !== this.ContentType.NONE, 
                beforeSend: function ()
                {
                    if (isLoading) showLoading();
                },
                success: function (response, textStatus, request)
                {
                    if (isLoading) hideLoading();
                    showResponseMessage(response, textStatus, request);
                    resolve(response);
                },
                error: function (response, textStatus, errorThrown)
                {
                    if (isLoading) hideLoading();
                    console.log("REQUEST ASYNC ==> ", textStatus, response, errorThrown);
                    reject(response);
                }
            });
        });
    };


    function showResponseMessage(response, textStatus, request)
    {
        console.log("REQUEST ASYNC ==> ", textStatus, response, request.getAllResponseHeaders());
        if (typeof response.message === 'undefined' || response.message === '') return;
        
        switch (response.status)
        {
            case REQUEST_STATUS.ERROR:
                alertPop.show(alertPop.Types.DANGER, "Error", response.message);
            break;

            case REQUEST_STATUS.SUCCESS:                
            case REQUEST_STATUS.OK:                
                alertPop.show(alertPop.Types.SUCCESS, "Ok", response.message);
            break;
        }
    }
}

var webClient = new WebClient();