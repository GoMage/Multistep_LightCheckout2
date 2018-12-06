<?php

namespace GoMage\SuperLightCheckout\Block\Cart;

use GoMage\Core\Helper\Data;
use GoMage\SuperLightCheckout\Model\Config\CheckoutConfigurationsProvider;

class Sidebar extends \Magento\Checkout\Block\Cart\Sidebar
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
     * @param \Magento\Customer\Model\Session $customerSession
     * @param \Magento\Checkout\Model\Session $checkoutSession
     * @param \Magento\Catalog\Helper\Image $imageHelper
     * @param \Magento\Customer\CustomerData\JsLayoutDataProviderPoolInterface $jsLayoutDataProvider
     * @param CheckoutConfigurationsProvider $checkoutConfigurationsProvider
     * @param Data $helper
     * @param array $data
     */
    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \Magento\Customer\Model\Session $customerSession,
        \Magento\Checkout\Model\Session $checkoutSession,
        \Magento\Catalog\Helper\Image $imageHelper,
        \Magento\Customer\CustomerData\JsLayoutDataProviderPoolInterface $jsLayoutDataProvider,
        CheckoutConfigurationsProvider $checkoutConfigurationsProvider,
        Data $helper,
        array $data = []
    ) {
        parent::__construct($context, $customerSession, $checkoutSession, $imageHelper, $jsLayoutDataProvider, $data);

        $this->checkoutConfigurationsProvider = $checkoutConfigurationsProvider;
        $this->helper = $helper;
    }

    /**
     * @inheritdoc
     */
    public function setTemplate($template)
    {
        if (!$this->checkoutConfigurationsProvider->getGeneralConfigurations()->isEnabledSuperLightCheckout()
            || !$this->helper->isA(CheckoutConfigurationsProvider::MODULE_NAME)
        ) {
            $template = 'Magento_Checkout::' . $template;
        }

        return parent::setTemplate($template);
    }

    /**
     * @inheritdoc
     */
    public function getJsLayout()
    {
        if ($this->checkoutConfigurationsProvider->getGeneralConfigurations()->isEnabledSuperLightCheckout()
            && $this->helper->isA(CheckoutConfigurationsProvider::MODULE_NAME)
        ) {
            $this->jsLayout['components']['minicart_content']['config']['template']
                = 'GoMage_SuperLightCheckout/minicart/content';
        }

        return json_encode($this->jsLayout);
    }
}
