define([
    'jquery',
    'mage/validation',
    'Magento_ReCaptchaFrontendUi/js/registry'
], function ($, validation, registry) {
    'use strict';

    return {
        validate: function () {
            var reCaptchaId = 'recaptcha-placeorder';
            var captchaList = registry.captchaList();
            var reCaptchaIndex = registry.ids().indexOf(reCaptchaId);
            var widgetId;

            if (-1 !== reCaptchaIndex) {
                widgetId = captchaList[reCaptchaIndex];
                if ('' === grecaptcha.getResponse(widgetId)) {
                    grecaptcha.execute(widgetId);
                    /* Wait for reCaptchaCallback in reCaptcha-mixin.js */
                    return false;
                }
            }

            return true;
        }
    };
});
