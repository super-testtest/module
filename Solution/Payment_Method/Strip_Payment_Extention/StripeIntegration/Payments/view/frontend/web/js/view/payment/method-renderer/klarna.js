/*browser:true*/
/*global define*/
define(
    [
        'ko',
        'jquery',
        'StripeIntegration_Payments/js/view/payment/method-renderer/method',
        'StripeIntegration_Payments/js/action/get-klarna-payment-options',
        'mage/translate',
        'Magento_Checkout/js/model/payment/additional-validators',
        'Magento_Checkout/js/model/quote',
        'Magento_Customer/js/model/customer',
        'klarnapi'
    ],
    function (
        ko,
        $,
        Component,
        getKlarnaPaymentOptions,
        $t,
        additionalValidators,
        quote,
        customer
    ) {
        'use strict';

        return Component.extend({
            defaults: {
                self: this,
                template: 'StripeIntegration_Payments/payment/klarna',
                code: "klarna"
            },
            redirectAfterPlaceOrder: false,
            isLoading: ko.observable(false),
            errorMessage: ko.observable(null),
            paymentOptions: ko.observableArray(null),
            sourceId: ko.observable(null),
            rawPaymentOptions: null,

            initObservable: function()
            {
                this._super();

                this.observe(['selectedPaymentOption']);

                var self = this;
                this.isPlaceOrderDisabled = ko.computed(function()
                {
                    var allowed = self.isPlaceOrderActionAllowed();
                    var isPaymentOptionSelected = self.selectedPaymentOption();
                    return (!allowed || self.errorMessage() || !isPaymentOptionSelected);
                });

                this.showPaymentOptions = ko.computed(function()
                {
                    return !self.isLoading() && !self.errorMessage();
                });

                var currentBillingAddress = quote.billingAddress();
                var currentShippingAddress = quote.shippingAddress();

                quote.billingAddress.subscribe(function (billingAddress)
                {
                    if (billingAddress == null)
                        return;

                    // Because this may be called multiple times, check if the billingAddress has changed first
                    if ((self.sourceId() || this.isLoading()) && JSON.stringify(billingAddress) == JSON.stringify(currentBillingAddress))
                        return;

                    currentBillingAddress = billingAddress;

                    this.isLoading(true);

                    getKlarnaPaymentOptions(billingAddress, currentShippingAddress, quote.guestEmail, this.sourceId())
                        .done(this.onKlarnaPaymentOptions.bind(this))
                        .fail(this.onKlarnaPaymentOptionsFailed.bind(this));
                }
                , this);

                quote.shippingAddress.subscribe(function (shippingAddress)
                {
                    if (shippingAddress == null || currentBillingAddress == null)
                        return;

                    // Because this may be called multiple times, check if the shippingAddress has changed first
                    if ((self.sourceId() || this.isLoading()) && JSON.stringify(shippingAddress) == JSON.stringify(currentShippingAddress))
                        return;

                    currentShippingAddress = shippingAddress;

                    this.isLoading(true);

                    getKlarnaPaymentOptions(currentBillingAddress, shippingAddress, quote.guestEmail, this.sourceId())
                        .done(this.onKlarnaPaymentOptions.bind(this))
                        .fail(this.onKlarnaPaymentOptionsFailed.bind(this));
                }
                , this);

                return this;
            },

            showPaymentOption: function(key)
            {
                if (this.selectedPaymentOption() == key)
                    return true;

                return false;
            },

            onKlarnaPaymentOptions: function(data)
            {
                this.resetPaymentForm();

                if (typeof data == "string")
                {
                    try
                    {
                        data = JSON.parse(data);
                    }
                    catch (e)
                    {
                        this.errorMessage($t('The Klarna payment options could not be loaded.'));
                    }
                }

                Klarna.Payments.init({
                  client_token: data.clientToken
                });

                this.rawPaymentOptions = this.convertPaymentOptionsToArray(data.paymentOptions);
                this.paymentOptions(this.rawPaymentOptions); // Will trigger the template rendering

                if (!this.paymentOptions() || this.paymentOptions().length == 0)
                {
                    this.errorMessage($t('Sorry, there are no available payment options.'));
                    this.sourceId(null);
                }
                else
                {
                    this.sourceId(data.sourceId);
                    this.selectedPaymentOption(this.paymentOptions()[0].key);
                }

                this.onPaymentOptionsRendered();
            },

            onKlarnaPaymentOptionsFailed: function(response)
            {
                this.resetPaymentForm();
                this.errorMessage($t(response.responseJSON.message));
            },

            resetPaymentForm: function()
            {
                this.isLoading(false);
                this.paymentOptions(null);
                this.rawPaymentOptions = null;
                this.sourceId(null);
                this.errorMessage(null);
            },

            convertPaymentOptionsToArray: function(options)
            {
                var ret = [];

                for (var key in options)
                {
                    if (options.hasOwnProperty(key))
                    {
                        ret.push(options[key]);
                    }
                }

                return ret;
            },

            onPaymentOptionsRendered: function()
            {
                var containers = document.getElementsByClassName('klarna-payment-option-container');

                for (var i = 0; i < containers.length; i++)
                {
                    var category = containers[i].dataset.klarnaCategory;
                    var containerId = containers[i].id;

                    try
                    {
                        Klarna.Payments.load({
                            container: "#" + containerId,
                            payment_method_category: category,
                            instance_id : "klarna-payments-instance-" + category
                        });
                    }
                    catch (e)
                    {
                        console.warn(e.message);
                    }
                }
            },

            getData: function()
            {
                var data = {
                    'method': this.item.method,
                    'additional_data': {
                        'source_id': this.sourceId()
                    }
                };

                return data;
            },

            placeOrder: function()
            {
                if (!this.validate() || !additionalValidators.validate())
                    return false;

                this.isPlaceOrderActionAllowed(false);
                var self = this;
                var parentPlaceOrder = this._super.bind(this);

                Klarna.Payments.authorize({
                    instance_id : "klarna-payments-instance-" + this.selectedPaymentOption(),
                    payment_method_category : this.selectedPaymentOption()
                },
                function(res)
                {
                    if (res.approved)
                    {
                        parentPlaceOrder();
                        // hide form in case of server side exception?
                    }
                    else
                    {
                        if (res.error)
                        {
                            // Payment not authorized or an error has occurred
                            console.debug(res);
                            alert("Sorry, an error has occurred");
                            // recreate source?
                        }
                        else
                        {
                            // Klarna displays the error in this case
                            self.isPlaceOrderActionAllowed(true);
                        }
                    }
                });
            }

        });
    }
);
