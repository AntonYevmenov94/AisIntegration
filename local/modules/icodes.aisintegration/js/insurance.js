(function (window) {
    'use strict';

    if (window.AisIntegrationExport)
        return;

    window.AisIntegrationExport = function (arParams) {
        alert('AAA');
        this.errorCode = 0;
        // this.enum_options = arParams.enum_options;
        // if (arParams.enum_options === undefined) {
        //     this.errorCode = -1;
        // }
        if (this.errorCode === 0) {
            BX.ready(BX.delegate(this.init, this));
        }
    }

    window.AisIntegrationExport.prototype = {

        init: function () {
            // let option, propReference;
            //
            // let obj = this;
            // propReference = this.enum_options;
            //
            //
            // for (option in this.enum_options)
            // {
            //     let selector = "[name='"+option+"']"
            //     document.querySelector(selector).addEventListener('change',function (){
            //
            //         var test = this;
            //
            //         obj.getOptionElements(propReference, this.name, this.value);
            //     })
            // }
        },

        getOptionElements: function (propReference, name, value) {
            // let subSelect;
            // let request = BX.ajax.runAction('icodes:aisintegration.api.ajaxhandler.getenumlist', {
            //     data: {
            //         propName: value
            //     }
            // })
            // request.then(function(response){
            //     for (subSelect in propReference[name]){
            //         let subSelector = "[name='"+propReference[name][subSelect]+"']"
            //         document.querySelector(subSelector).innerHTML = response.data;
            //     }
            // })
        }
    }
})(window);