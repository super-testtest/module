define(
    [
        'ko',
        'uiComponent',
        'underscore',
        'Magento_Checkout/js/model/step-navigator',
        'Magento_Customer/js/model/customer',
        'Magento_Customer/js/customer-data',
        'jquery'

    ],
    function (
        ko,
        Component,
        _,
        stepNavigator,
        customer,
        customerData,
        $
    ) {
        'use strict';
        
        //$(window).hashchange('signup');
        
           
        return Component.extend({
            defaults: {
                template: 'Tecksky_Checkoutsignup/check-login'
            },

            //add here your logic to display step,
            isVisible: ko.observable(true),
            isLogedIn: customer.isLoggedIn(),
            //step code will be used as step content id in the component template
            stepCode: 'signup',
            //step title value
            stepTitle: 'Sign Up',

            /**
            *
            * @returns {*}
            */
            initialize: function () {
                this._super();
                // register your step
                if(customer.isLoggedIn()==false){
                stepNavigator.registerStep(
                    this.stepCode,
                    //step alias
                    null,
                    this.stepTitle,
                    //observable property with logic when display step or hide step
                    this.isVisible,

                    _.bind(this.navigate, this),

                    /**
                    * sort order value
                    * 'sort order value' < 10: step displays before shipping step;
                    * 10 < 'sort order value' < 20 : step displays between shipping and payment step
                    * 'sort order value' > 20 : step displays after payment step
                    */
                    8
                );
                
            }
                return this;
            },


            /**
            * The navigate() method is responsible for navigation between checkout step
            * during checkout. You can add custom logic, for example some conditions
            * for switching to your custom step
            */
            navigate: function () {

            },

            /**
            * @returns void
            */
            navigateToNextStep: function () {
                stepNavigator.next();
            }


        });
    }
);