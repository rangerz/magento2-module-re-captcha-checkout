<?php

namespace Rangerz\ReCaptchaCheckout\Helper;

use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Framework\App\Helper\Context;
use Magento\Framework\Validation\ValidationResult;
use Magento\ReCaptchaUi\Model\ErrorMessageConfigInterface;
use Magento\ReCaptchaValidationApi\Api\Data\ValidationConfigInterface;
use Magento\ReCaptchaValidationApi\Api\ValidatorInterface;
use Magento\ReCaptchaValidationApi\Model\ValidationErrorMessagesProvider;
use Psr\Log\LoggerInterface;

class Data extends AbstractHelper
{
    // config: recaptcha_frontend/type_for and recaptcha_backend/type_for
    const FRONTEND_RECAPTCHA_KEYS = [
        'place_order',
        'customer_login',
        'customer_forgot_password',
        'customer_create',
        'customer_edit',
        'contact',
        'product_review',
        'newsletter',
        'sendfriend',
        'paypal_payflowpro'
    ];
    const BACKEND_RECAPTCHA_KEYS = [
        'user_login',
        'user_forgot_password'
    ];

    private $captchaValidator;
    private $validationErrorMessagesProvider;
    private $logger;

    public function __construct(
        Context $context,
        ValidatorInterface $captchaValidator,
        ValidationErrorMessagesProvider $validationErrorMessagesProvider,
        LoggerInterface $logger
    ) {
        $this->captchaValidator = $captchaValidator;
        $this->validationErrorMessagesProvider = $validationErrorMessagesProvider;
        $this->logger = $logger;
        parent::__construct($context);
    }

    // TODO: Use global
    // Integration for frontend and adminhtml recaptcha setting
    public function getCaptchaTypeFor(string $key): ?string
    {
        $type = null;

        $om = \Magento\Framework\App\ObjectManager::getInstance();
        if (in_array($key, self::FRONTEND_RECAPTCHA_KEYS)) {
            $type = $om->get(\Magento\ReCaptchaFrontendUi\Model\CaptchaTypeResolver::class)->getCaptchaTypeFor($key);
        } else if (in_array($key, self::BACKEND_RECAPTCHA_KEYS)) {
            $type = $om->get(\Magento\ReCaptchaAdminUi\Model\CaptchaTypeResolver::class)->getCaptchaTypeFor($key);
        }

        // Return: null, recaptcha, invisible, or recaptcha_v3
        return $type;
    }

    public function getUiConfig(string $key): array
    {
        $config = [];
        $type = $this->getCaptchaTypeFor($key);

        $uiConfigProviders = [
            'frontend' => [
                'recaptcha' => \Magento\ReCaptchaVersion2Checkbox\Model\Frontend\UiConfigProvider\Proxy\Proxy::class,
                'invisible' => \Magento\ReCaptchaVersion2Invisible\Model\Frontend\UiConfigProvider\Proxy::class,
                'recaptcha_v3' => \Magento\ReCaptchaVersion3Invisible\Model\Frontend\UiConfigProvider\Proxy::class
            ],
            'adminhtml' => [
                'recaptcha' => \Magento\ReCaptchaVersion2Checkbox\Model\Adminhtml\UiConfigProvider\Proxy::class,
                'invisible' => \Magento\ReCaptchaVersion2Invisible\Model\Adminhtml\UiConfigProvider\Proxy::class,
                'recaptcha_v3' => \Magento\ReCaptchaVersion3Invisible\Model\Adminhtml\UiConfigProvider\Proxy::class
            ]
        ];

        $om = \Magento\Framework\App\ObjectManager::getInstance();
        if (in_array($key, self::FRONTEND_RECAPTCHA_KEYS)) {
            $config = $om->get($uiConfigProviders['frontend'][$type])->get();
        } else if (in_array($key, self::BACKEND_RECAPTCHA_KEYS)) {
            $config = $om->get($uiConfigProviders['adminhtml'][$type])->get();
        }

        return $config;
    }

    public function getValidationConfig(string $key): ValidationConfigInterface
    {
        $config = null;
        $type = $this->getCaptchaTypeFor($key);
        $configProviders = [
            'frontend' => [
                'recaptcha' => \Magento\ReCaptchaVersion2Checkbox\Model\Frontend\ValidationConfigProvider\Proxy::class,
                'invisible' => \Magento\ReCaptchaVersion2Invisible\Model\Frontend\ValidationConfigProvider\Proxy::class,
                'recaptcha_v3' => \Magento\ReCaptchaVersion3Invisible\Model\Frontend\ValidationConfigProvider\Proxy::class
            ],
            'adminhtml' => [
                'recaptcha' => \Magento\ReCaptchaVersion2Checkbox\Model\Adminhtml\ValidationConfigProvider\Proxy::class,
                'invisible' => \Magento\ReCaptchaVersion2Invisible\Model\Adminhtml\ValidationConfigProvider\Proxy::class,
                'recaptcha_v3' => \Magento\ReCaptchaVersion3Invisible\Model\Adminhtml\ValidationConfigProvider\Proxy::class
            ]
        ];

        $om = \Magento\Framework\App\ObjectManager::getInstance();
        if (in_array($key, self::FRONTEND_RECAPTCHA_KEYS)) {
            $config = $om->get($configProviders['frontend'][$type])->get();
        } else if (in_array($key, self::BACKEND_RECAPTCHA_KEYS)) {
            $config = $om->get($configProviders['adminhtml'][$type])->get();
        }

        return $config;
    }

    public function getErrorMessageConfig(string $key): ?ErrorMessageConfigInterface
    {
        $config = null;

        $om = \Magento\Framework\App\ObjectManager::getInstance();
        if (in_array($key, self::FRONTEND_RECAPTCHA_KEYS)) {
            $config = $om->get(\Magento\ReCaptchaFrontendUi\Model\ErrorMessageConfig::class);
        } else if (in_array($key, self::BACKEND_RECAPTCHA_KEYS)) {
            $config = $om->get(\Magento\ReCaptchaAdminUi\Model\ErrorMessageConfig::class);
        }

        return $config;
    }

    public function areKeysConfigured(string $key): bool
    {
        $uiConfig = $this->getUiConfig($key);
        $validationConfig = $this->getValidationConfig($key);

        return $validationConfig->getPrivateKey() && !empty($uiConfig['rendering']['sitekey']);
    }

    public function isCaptchaEnabledFor(string $key): bool
    {
        return (null !== $this->getCaptchaTypeFor($key)) && $this->areKeysConfigured($key);
    }

    public function isValid(string $reCaptchaResponse, string $key): ValidationResult
    {
        $validationConfig = $this->getValidationConfig($key);
        $validationResult = $this->captchaValidator->isValid($reCaptchaResponse, $validationConfig);
        return $validationResult;
    }

    public function getErrorMessage(array $errorMessages, string $key): string
    {
        $errorMessageConfig = $this->getErrorMessageConfig($key);
        $validationErrorText = $errorMessageConfig->getValidationFailureMessage();
        $technicalErrorText = $errorMessageConfig->getTechnicalFailureMessage();
        $message = $errorMessages ? $validationErrorText : $technicalErrorText;
        foreach ($errorMessages as $errorMessageCode => $errorMessageText) {
            if (!$this->isValidationError($errorMessageCode)) {
                $message = $technicalErrorText;
                $this->logger->error(
                    __(
                        'reCAPTCHA \'%1\' form error: %2',
                        $key,
                        $errorMessageText
                    )
                );
            }
        }

        return $message;
    }

    private function isValidationError(string $errorMessageCode): bool
    {
        return $errorMessageCode !== $this->validationErrorMessagesProvider->getErrorMessage($errorMessageCode);
    }
}
