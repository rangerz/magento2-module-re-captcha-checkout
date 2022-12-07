<h1 align="center">Rangerz_ReCaptchaCheckout</h1>


<div align="center">
  <p>Add Google reCaptcha for place order against carding attack</p>
  <img src="https://img.shields.io/badge/magento-2.4-brightgreen.svg?logo=magento&longCache=true" alt="Supported Magento Versions" />
  <a href="https://packagist.org/packages/rangerz/magento2-module-re-captcha-checkout" target="_blank"><img src="https://img.shields.io/packagist/v/rangerz/magento2-module-re-captcha-checkout.svg" alt="Latest Stable Version" /></a>
  <a href="https://packagist.org/packages/rangerz/magento2-module-re-captcha-checkout" target="_blank"><img src="https://poser.pugx.org/rangerz/magento2-module-re-captcha-checkout/downloads" alt="Composer Downloads" /></a>
  <a href="https://github.com/rangerz/magento2-module-re-captcha-checkout/graphs/commit-activity" target="_blank"><img src="https://img.shields.io/badge/maintained%3F-yes-brightgreen.svg" alt="Maintained - Yes" /></a>
  <a href="https://opensource.org/licenses/MIT" target="_blank"><img src="https://img.shields.io/badge/license-MIT-blue.svg" /></a>
</div>



## Summary

- Because [Carding Attack](https://github.com/magento/magento2/issues/28614) serious issue, this repo was born.

- Tested against forged place order [attack script](https://gist.github.com/magenx/bdc56bf568caa3c23b2217055aef17b2), follow by the rest url and place order method
  - /V1/guest-carts/:cartId/payment-information
    -  `Magento\Checkout\Model\GuestPaymentInformationManagement::savePaymentInformationAndPlaceOrder()`
  - /V1/carts/mine/payment-information
    -  `Magento\Checkout\Model\PaymentInformationManagement::savePaymentInformationAndPlaceOrder()`

- Support Magento 2.4.1 and upper.

- Wrok for `Braintree Credit Card` with `Google reCaptcha v2 invisible` **ONLY**. No test other cases.

![recaptcha-placeorder](https://i.imgur.com/ACBeBse.png)

- This module will be replaced by future version of [magento/security-package](https://github.com/magento/security-package).



## Installation

```
composer require rangerz/magento2-module-re-captcha-checkout
bin/magento module:enable Rangerz_ReCaptchaCheckout
bin/magento setup:upgrade
```



## Usage

### Google reCaptcha v2 invisible

- Apply reCaptcha v2 invisible by [Google](https://www.google.com/recaptcha/admin)

- Fille website and secret key
- Configuration -> SECURITY -> Google reCAPTCHA Storefront -> reCAPTCHA v2 Invisible

![reCaptcha v2 invisible](https://i.imgur.com/RiF9RIE.png)



### Enable for Place Order

- Configuration -> SECURITY -> Google reCAPTCHA Storefront -> Storefront

![Enable for Place Order](https://i.imgur.com/rlDEoNe.png)



## License

[MIT](https://opensource.org/licenses/MIT)
