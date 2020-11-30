/**
 * Created by Linh on 6/8/2016.
 */
define(
    [
        'jquery',
        'Magento_Checkout/js/view/payment/default',
        'mage/url'
    ],
     function ($, Component, url){
        'use strict';
         
         return Component.extend({
             redirectAfterPlaceOrder: false,

             defaults: {
                 template: 'PL_Migs/payment/migs-hosted'
             },

             initialize: function() {
                 this._super();
                 self = this;
             },

             getCode: function() {
                 return 'migs_hosted';
             },

             getData: function() {
                 var data = {
                     'method': this.getCode()
                 };
                 return data;
             },

             afterPlaceOrder: function () {
                 window.location.replace(url.build('migs/hosted/redirect'));
             }

         });
    }
);