/*global define*/
define([
'ko',
'Magento_Ui/js/form/form',
'Magento_Checkout/js/model/step-navigator',
'mage/url',
'Magento_Customer/js/model/customer',
'Magento_Customer/js/customer-data',
'Magento_Ui/js/model/messageList',
'jquery'
], function(ko,Component,stepNavigator,url,customer,customerData,messageList,$) {
'use strict';

return Component.extend({

    initialize: function () {
        this._super();
        
        return this;
        
    },

    
    onSubmit: function() {
        
        this.source.set('params.invalid', false);
        this.source.trigger('customCheckoutForm.data.validate');

        
        
        
        if (!this.source.get('params.invalid')) {
            
            var formData = this.source.get('customCheckoutForm');
            var websiteurl = url.build('');
            
            

            jQuery.ajax({
                    url: websiteurl+'tecksky/index/index',
                    data: formData,
                    type: 'POST',
                    dataType: 'json',
                    beforeSend: function() {
                        $('body').trigger('processStart');
                    },
                    success: function(data, status, xhr) {
                        console.log(data);
                        $('body').trigger('processStop');
                        if(data.status == true){

                            window.location.replace(websiteurl+'checkout');
                        }else{
                            
                            messageList.addErrorMessage({ message: 'Customer is alerady exist with this email.' });
                        }
                    },
                    error: function (xhr, status, errorThrown) {

                        $('body').trigger('processStop');
                        messageList.addErrorMessage({ message: errorThrown });

                    }
                });

            
        }
    }
});
});
