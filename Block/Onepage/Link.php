<?php

namespace GoMage\SuperLightCheckout\Block\Onepage;

use GoMage\SuperLightCheckout\Model\Config\CheckoutConfigurationsProvider;
use GoMage\Core\Helper\Data;

class Link extends \Magento\Checkout\Block\Onepage\Link
{
    /**
     * @var CheckoutConfigurationsProvider
     */
    private $checkoutConfigurationsProvider;

    /**
     * @var Data
     */
    private $helper;

    /**
     * @param \Magento\Framework\View\Element\Template\Context $context
     * @param \Magento\Checkout\Model\Session $checkoutSession
     * @param \Magento\Checkout\Helper\Data $checkoutHelper
     * @param CheckoutConfigurationsProvider $checkoutConfigurationsProvider
     * @param Data $helper
     * @param array $data
     */
    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \Magento\Checkout\Model\Session $checkoutSession,
        \Magento\Checkout\Helper\Data $checkoutHelper,
        CheckoutConfigurationsProvider $checkoutConfigurationsProvider,
        Data $helper,
        array $data = []
    ) {
        parent::__construct($context, $checkoutSession, $checkoutHelper, $data);

        $this->checkoutConfigurationsProvider = $checkoutConfigurationsProvider;
        $this->helper = $helper;
    }

    public function setTemplate($template)
    {
        if (!$this->checkoutConfigurationsProvider->getGeneralConfigurations()->isEnabledSuperLightCheckout()
            || !$this->helper->isA(CheckoutConfigurationsProvider::MODULE_NAME)
        ) {
            $template = 'Magento_Checkout::' . $template;
        }

        return parent::setTemplate($template);
    }
}
