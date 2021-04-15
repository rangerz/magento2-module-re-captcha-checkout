define([
    'uiComponent',
    'Magento_Checkout/js/model/payment/additional-validators',
    'Rangerz_ReCaptchaCheckout/js/model/recaptcha'
], function (Component, additionalValidators, recaptchaValidation) {
    'use strict';
    additionalValidators.registerValidator(recaptchaValidation);
    return Component.extend({});
});
