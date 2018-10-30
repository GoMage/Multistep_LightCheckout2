<?php

namespace GoMage\SuperLightCheckout\Model\Config\CheckoutConfigurationsProvider;

use Magento\Framework\App\Config\ScopeConfigInterface;

class HelpMessagesProvider
{
    // @codingStandardsIgnoreStart
    /**#@+
     * Light Checkout configuration Help Messages.
     */
    const XML_PATH_SUPER_LIGHT_CHECKOUT_HELP_MESSAGES = 'gomage_super_light_checkout_configuration/help_messages/message';
    /**#@-*/
    // @codingStandardsIgnoreEnd

    /**
     * @var ScopeConfigInterface
     */
    private $scopeConfig;

    /**
     * @param ScopeConfigInterface $scopeConfig
     */
    public function __construct(ScopeConfigInterface $scopeConfig)
    {
        $this->scopeConfig = $scopeConfig;
    }

    public function getHelpMessages()
    {
        return $this->scopeConfig->getValue(self::XML_PATH_SUPER_LIGHT_CHECKOUT_HELP_MESSAGES);
    }
}
