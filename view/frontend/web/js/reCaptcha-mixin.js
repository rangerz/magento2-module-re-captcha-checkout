define(['jquery'], function ($) {
    'use strict';

    var mixin = {
        reCaptchaCallback: function (token) {
            if ('recaptcha-placeorder' === this.getReCaptchaId()) {
                /* Click place order button */
                $('.actions-toolbar button.checkout:visible:not([disabled])').trigger('click');
                return;
            }

            this._super();
        }
    };

    return function (target) {
        return target.extend(mixin);
    };
});
