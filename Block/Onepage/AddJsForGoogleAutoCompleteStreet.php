<?php

namespace GoMage\SuperLightCheckout\Block\Onepage;

use GoMage\SuperLightCheckout\Model\Config\CheckoutConfigurationsProvider;
use Magento\Framework\View\Element\Template;

class AddJsForGoogleAutoCompleteStreet extends \Magento\Framework\View\Element\Template
{
    /**
     * @var CheckoutConfigurationsProvider
     */
    private $checkoutConfigurationsProvider;

    /**
     * @param Template\Context $context
     * @param CheckoutConfigurationsProvider $checkoutConfigurationsProvider
     * @param array $data
     */
    public function __construct(
        Template\Context $context,
        CheckoutConfigurationsProvider $checkoutConfigurationsProvider,
        array $data = []
    ) {
        parent::__construct($context, $data);

        $this->checkoutConfigurationsProvider = $checkoutConfigurationsProvider;
    }

    /**
     * @return bool
     */
    public function isEnableStreetAutoComplete()
    {
        return $this->checkoutConfigurationsProvider->getGeneralConfigurations()->isEnabledSuperLightCheckout()
            && $this->checkoutConfigurationsProvider->getAutoCompleteByStreet()->getIsEnabledAutoCompleteByStreet();
    }

    /**
     * @return string
     */
    public function getGoogleApiKey()
    {
        return $this->checkoutConfigurationsProvider->getGeneralConfigurations()->isEnabledSuperLightCheckout()
            && $this->checkoutConfigurationsProvider->getAutoCompleteByStreet()->getAutoCompleteByStreetGoogleApiKey();
    }
}
