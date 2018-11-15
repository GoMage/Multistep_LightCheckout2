<?php

namespace GoMage\SuperLightCheckout\Block\Onepage;

use GoMage\SuperLightCheckout\Model\Config\CheckoutConfigurationsProvider;

class Link extends \Magento\Checkout\Block\Onepage\Link
{
    private $checkoutConfigurationsProvider;

    /**
     * @param \Magento\Framework\View\Element\Template\Context $context
     * @param \Magento\Checkout\Model\Session $checkoutSession
     * @param \Magento\Checkout\Helper\Data $checkoutHelper
     * @param CheckoutConfigurationsProvider $checkoutConfigurationsProvider
     * @param array $data
     */
    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \Magento\Checkout\Model\Session $checkoutSession,
        \Magento\Checkout\Helper\Data $checkoutHelper,
        CheckoutConfigurationsProvider $checkoutConfigurationsProvider,
        array $data = []
    ) {
        parent::__construct($context, $checkoutSession, $checkoutHelper, $data);

        $this->checkoutConfigurationsProvider = $checkoutConfigurationsProvider;
    }

    public function setTemplate($template)
    {
        if (!$this->checkoutConfigurationsProvider->getGeneralConfigurations()->isEnabledSuperLightCheckout()) {
            $template = 'Magento_Checkout::' . $template;
        }

        return parent::setTemplate($template);
    }
}
