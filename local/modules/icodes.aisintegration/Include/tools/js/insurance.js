(function (window) {
    'use strict';

    if (window.AisIntegrationExport)
        return;

    window.AisIntegrationExport = function (dealId) {
        let errorCode = 0;

        if (dealId === undefined) {
            errorCode = -1;
        }
        if (errorCode === 0) {
            let request = BX.ajax.runAction('icodes:aisintegration.api.ajaxhandler.insuranceApply', {
                data: {
                    dealId: dealId
                }
            })
            request.then(function(response){
                console.log(response)
            })
        }
    }

})(window);