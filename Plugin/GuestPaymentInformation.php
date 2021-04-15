<?php
declare(strict_types=1);

namespace Rangerz\ReCaptchaCheckout\Plugin;

use Rangerz\ReCaptchaCheckout\Helper\Data as reCaptchaHelper;
use Magento\Framework\Exception\CouldNotSaveException;

class GuestPaymentInformation
{
    private $helper;

    public function __construct(
        reCaptchaHelper $helper
    ) {
        $this->helper = $helper;
    }

    public function aroundSavePaymentInformationAndPlaceOrder(
        \Magento\Checkout\Model\GuestPaymentInformationManagement $subject, callable $proceed,
        $cartId,
        $email,
        \Magento\Quote\Api\Data\PaymentInterface $paymentMethod,
        \Magento\Quote\Api\Data\AddressInterface $billingAddress = null
    ) {
        $key = 'place_order';
        if (null !== $this->helper->getCaptchaTypeFor($key)) {
            $additionalData = $paymentMethod->getAdditionalData();
            $reCaptchaResponse = $additionalData['token'] ?? '';
            $validationResult = $this->helper->isValid($reCaptchaResponse, $key);
            if (false === $validationResult->isValid()) {
                $message = $this->helper->getErrorMessage($validationResult->getErrors(), $key);
                throw new CouldNotSaveException(__($message));
            }
        }

        return $proceed($cartId, $email, $paymentMethod, $billingAddress);
    }
}
