var config = {
    config: {
        mixins: {
            'Magento_ReCaptchaFrontendUi/js/reCaptcha': {
                'Rangerz_ReCaptchaCheckout/js/reCaptcha-mixin': true
            },
            'Magento_Checkout/js/action/place-order': {
                'Rangerz_ReCaptchaCheckout/js/model/place-order-mixin': true
            }
        }
    }
};
