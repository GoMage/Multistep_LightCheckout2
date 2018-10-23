<?php

namespace GoMage\SuperLightCheckout\Model;

use GoMage\SuperLightCheckout\Model\Config\CheckoutConfigurationsProvider;
use Magento\Checkout\Model\ConfigProviderInterface;
use Magento\Checkout\Model\Session as CheckoutSession;
use Magento\Customer\Model\Url;
use Magento\Quote\Api\Data\CartInterface;
use Magento\Quote\Api\PaymentMethodManagementInterface;
use Magento\Quote\Model\Cart\ShippingMethodConverter;
use Magento\Directory\Helper\Data as DirectoryHelper;
use Magento\Quote\Model\Quote\TotalsCollector;

class ConfigProvider implements ConfigProviderInterface
{
    /**
     * @var CheckoutSession
     */
    private $checkoutSession;

    /**
     * @var CheckoutConfigurationsProvider
     */
    private $checkoutConfigurationsProvider;

    /**
     * @var PaymentMethodManagementInterface
     */
    private $paymentMethodManagement;

    /**
     * @var ShippingMethodConverter
     */
    private $shippingMethodConverter;

    /**
     * @var DirectoryHelper
     */
    private $directoryHelper;

    /**
     * @var TotalsCollector
     */
    private $totalsCollector;

    /**
     * @var Url
     */
    private $url;

    /**
     * @param CheckoutSession $session
     * @param CheckoutConfigurationsProvider $checkoutConfigurationsProvider
     * @param PaymentMethodManagementInterface $paymentMethodManagement
     * @param ShippingMethodConverter $shippingMethodConverter
     * @param DirectoryHelper $directoryHelper
     * @param TotalsCollector $totalsCollector
     * @param Url $url
     */
    public function __construct(
        CheckoutSession $session,
        CheckoutConfigurationsProvider $checkoutConfigurationsProvider,
        PaymentMethodManagementInterface $paymentMethodManagement,
        ShippingMethodConverter $shippingMethodConverter,
        DirectoryHelper $directoryHelper,
        TotalsCollector $totalsCollector,
        Url $url
    ) {
        $this->checkoutSession = $session;
        $this->checkoutConfigurationsProvider = $checkoutConfigurationsProvider;
        $this->paymentMethodManagement = $paymentMethodManagement;
        $this->shippingMethodConverter = $shippingMethodConverter;
        $this->directoryHelper = $directoryHelper;
        $this->totalsCollector = $totalsCollector;
        $this->url = $url;
    }

    /**
     * @inheritdoc
     */
    public function getConfig()
    {
        $config = [
            'general' => $this->getGeneralConfig()
        ];

        return $config;
    }

    /**
     * @param CartInterface $quote
     *
     * @return string|null
     */
    private function getDefaultPaymentMethod(CartInterface $quote)
    {
        $defaultActivePaymentMethod = null;

        if (!$quote->getPayment()->getMethod()) {
            $defaultPaymentMethod = $this->checkoutConfigurationsProvider->getGeneralConfigurations()
                ->getDefaultPaymentMethod();

            $paymentMethods = $this->paymentMethodManagement->getList($quote->getId());

            foreach ($paymentMethods as $paymentMethod) {
                if ($paymentMethod->getCode() === $defaultPaymentMethod) {
                    $defaultActivePaymentMethod = $defaultPaymentMethod;
                    break;
                }
            }
        }

        return $defaultActivePaymentMethod;
    }

    /**
     * @param CartInterface $quote
     *
     * @return string|null
     */
    private function getDefaultShippingMethod(CartInterface $quote)
    {
        $defaultActiveShippingMethod = null;

        /** @var \Magento\Quote\Model\Quote\Address $shippingAddress */
        $shippingAddress = $quote->getShippingAddress();

        if (!$shippingAddress->getCountryId()) {
            $defaultCountryId = $this->directoryHelper->getDefaultCountry();
            $shippingAddress->setCountryId($defaultCountryId)->setCollectShippingRates(true);
        }

        if (!$shippingAddress->getShippingMethod() && !$quote->getIsVirtual()) {
            $defaultShippingMethod = $this->checkoutConfigurationsProvider->getGeneralConfigurations()
                ->getDefaultShippingMethod();

            if ($defaultShippingMethod) {
                $this->totalsCollector->collectAddressTotals($quote, $shippingAddress);

                $allowedShippingMethods = $this->getAllowedShippingMethodsByQuote($quote);

                if (in_array($defaultShippingMethod, $allowedShippingMethods)) {
                    $defaultActiveShippingMethod = $defaultShippingMethod;
                }
            }
        }

        return $defaultActiveShippingMethod;
    }

    /**
     * @param CartInterface $quote
     *
     * @return array
     */
    private function getAllowedShippingMethodsByQuote(CartInterface $quote)
    {
        $allowedShippingMethods = [];
        $currencyCode = $quote->getQuoteCurrencyCode();

        /** @var \Magento\Quote\Model\Quote\Address $shippingAddress */
        $shippingAddress = $quote->getShippingAddress();
        $shippingRates = $shippingAddress->getGroupedAllShippingRates();

        foreach ($shippingRates as $carrierRates) {
            foreach ($carrierRates as $rate) {
                $shippingMethod = $this->shippingMethodConverter->modelToDataObject($rate, $currencyCode);
                $allowedShippingMethods[] = $shippingMethod->getCarrierCode() . '_' . $shippingMethod->getMethodCode();
            }
        }

        return $allowedShippingMethods;
    }

    /**
     * @return array
     */
    private function getGeneralConfig()
    {
        return [
            'pageTitle' => $this->checkoutConfigurationsProvider->getGeneralConfigurations()->getPageTitle(),
            'pageContent' => $this->checkoutConfigurationsProvider->getGeneralConfigurations()->getPageContent(),
            'defaultPaymentMethod' => $this->getDefaultPaymentMethod($this->checkoutSession->getQuote()),
            'defaultShippingMethod' => $this->getDefaultShippingMethod($this->checkoutSession->getQuote()),
            'billingAndShippingAreTheSameChecked' => $this->checkoutConfigurationsProvider->getGeneralConfigurations()
                ->getBillingAndShippingAreTheSameCheckboxChecked(),
        ];
    }
}
