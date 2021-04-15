<?php
declare(strict_types=1);

namespace Rangerz\ReCaptchaCheckout\Block\LayoutProcessor\Checkout;

use Magento\Checkout\Block\Checkout\LayoutProcessorInterface;
use Magento\Framework\Exception\InputException;
use Magento\ReCaptchaUi\Model\IsCaptchaEnabledInterface;
use Magento\ReCaptchaUi\Model\UiConfigResolverInterface;

/**
 * Checkout layout processor
 */
class Onepage implements LayoutProcessorInterface
{
    /**
     * @var UiConfigResolverInterface
     */
    private $captchaUiConfigResolver;

    /**
     * @var IsCaptchaEnabledInterface
     */
    private $isCaptchaEnabled;

    /**
     * @param UiConfigResolverInterface $captchaUiConfigResolver
     * @param IsCaptchaEnabledInterface $isCaptchaEnabled
     */
    public function __construct(
        UiConfigResolverInterface $captchaUiConfigResolver,
        IsCaptchaEnabledInterface $isCaptchaEnabled
    ) {
        $this->captchaUiConfigResolver = $captchaUiConfigResolver;
        $this->isCaptchaEnabled = $isCaptchaEnabled;
    }

    /**
     * {@inheritdoc}
     *
     * @param array $jsLayout
     * @return array
     * @throws InputException
     */
    public function process($jsLayout)
    {
        $key = 'place_order';
        if ($this->isCaptchaEnabled->isCaptchaEnabledFor($key)) {
            $jsLayout['components']['checkout']['children']['steps']['children']
                ['billing-step']['children']['payment']['children']['afterMethods']['children']
                ['recaptcha']['settings'] = $this->captchaUiConfigResolver->get($key);
        } else {
            if (isset($jsLayout['components']['checkout']['children']['steps']['children']
                ['billing-step']['children']['payment']['children']['afterMethods']['children']['recaptcha'])) {
                unset($jsLayout['components']['checkout']['children']['steps']['children']
                    ['billing-step']['children']['payment']['children']['afterMethods']['children']['recaptcha']);
            }
        }
        return $jsLayout;
    }
}
