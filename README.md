<h1 align="center">Rangerz_ReCaptchaCheckout</h1>



<div align="center">
  <p>Add Google reCaptcha for place order against carding attack</p>
  <img src="https://img.shields.io/badge/magento-2.4-brightgreen.svg?logo=magento&longCache=true&style=flat-square" alt="Supported Magento Versions" />
  <a href="https://packagist.org/packages/rangerz/magento2-module-re-captcha-checkout" target="_blank"><img src="https://img.shields.io/packagist/v/rangerz/magento2-module-re-captcha-checkout.svg?style=flat-square" alt="Latest Stable Version" /></a>
  <a href="https://packagist.org/packages/rangerz/magento2-module-re-captcha-checkout" target="_blank"><img src="https://poser.pugx.org/rangerz/magento2-module-re-captcha-checkoutplate/downloads" alt="Composer Downloads" /></a>
  <a href="https://GitHub.com/Naereen/StrapDown.js/graphs/commit-activity" target="_blank"><img src="https://img.shields.io/badge/maintained%3F-yes-brightgreen.svg?style=flat-square" alt="Maintained - Yes" /></a>
  <a href="https://opensource.org/licenses/MIT" target="_blank"><img src="https://img.shields.io/badge/license-MIT-blue.svg" /></a>
</div>



## Summary

- Because [Carding Attack](https://github.com/magento/magento2/issues/28614) serious issue, this repo was born.
- Support Magento 2.4.1 and upper.
- Should wrok `Braintree Credit Card` with `Google reCaptcha v2 invisible` **ONLY**. No test other cases.
- ![recaptcha-placeorder](https://i.imgur.com/ACBeBse_d.webp?maxwidth=1520&fidelity=grand)

- Hope [magento/security-package](https://github.com/magento/security-package) release soon, and then use the official reCaptcha for place order.



## Installation

```
composer require rangerz/magento2-module-re-captcha-checkout
bin/magento module:enable Rangerz_ReCaptchaCheckout
bin/magento setup:upgrade
```



## License

[MIT](https://opensource.org/licenses/MIT)