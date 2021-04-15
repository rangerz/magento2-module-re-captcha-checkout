define([
    'jquery',
    'mage/utils/wrapper',
    'Magento_ReCaptchaFrontendUi/js/registry'
], function ($, wrapper, registry) {
    'use strict';

    return function (placeOrderAction) {
        return wrapper.wrap(placeOrderAction, function (originalAction, paymentData, messageContainer) {
            var reCaptchaId = 'recaptcha-placeorder';
            var captchaList = registry.captchaList();
            var reCaptchaIndex = registry.ids().indexOf(reCaptchaId);
            var widgetId;

            if (-1 !== reCaptchaIndex) {
                if (!paymentData.additional_data) {
                    paymentData.additional_data = {};
                }
                widgetId = captchaList[reCaptchaIndex];
                paymentData.additional_data.token = grecaptcha.getResponse(widgetId);
            }

            return originalAction(paymentData, messageContainer).fail(function () {
                if (-1 !== reCaptchaIndex) {
                    widgetId = captchaList[reCaptchaIndex];
                    grecaptcha.reset(widgetId);
                }
            });
        });
    };
});
