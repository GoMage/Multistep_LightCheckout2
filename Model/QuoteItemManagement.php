<?php

namespace GoMage\SuperLightCheckout\Model;

use GoMage\SuperLightCheckout\Api\QuoteItemManagementInterface;
use GoMage\SuperLightCheckout\Model\QuoteItemManagement\ResponseDataInterface;
use GoMage\SuperLightCheckout\Model\QuoteItemManagement\ResponseDataInterfaceFactory;
use GoMage\SuperLightCheckout\Model\QuoteItemManagement\ShippingMethodsProvider;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\UrlInterface;
use Magento\Quote\Api\CartRepositoryInterface;
use Magento\Quote\Api\CartTotalRepositoryInterface;
use Magento\Quote\Api\Data\TotalsItemInterface;
use Magento\Quote\Api\PaymentMethodManagementInterface;
use Magento\Quote\Model\Quote;

class QuoteItemManagement implements QuoteItemManagementInterface
{
    /**
     * @var CartRepositoryInterface
     */
    private $quoteRepository;

    /**
     * @var ResponseDataInterfaceFactory
     */
    private $responseDataFactory;

    /**
     * @var PaymentMethodManagementInterface
     */
    private $paymentMethodManagement;

    /**
     * @var CartTotalRepositoryInterface
     */
    private $cartTotalsRepository;

    /**
     * @var UrlInterface
     */
    private $url;

    /**
     * @var ShippingMethodsProvider
     */
    private $shippingMethodsProvider;

    /**
     * @var Config\CheckoutConfigurationsProvider
     */
    private $checkoutConfigurationsProvider;

    /**
     * @param CartRepositoryInterface $quoteRepository
     * @param ResponseDataInterfaceFactory $responseDataFactory
     * @param PaymentMethodManagementInterface $paymentMethodManagement
     * @param CartTotalRepositoryInterface $cartTotalRepository
     * @param UrlInterface $url
     * @param ShippingMethodsProvider $shippingMethodsProvider
     * @param Config\CheckoutConfigurationsProvider $checkoutConfigurationsProvider
     */
    public function __construct(
        CartRepositoryInterface $quoteRepository,
        ResponseDataInterfaceFactory $responseDataFactory,
        PaymentMethodManagementInterface $paymentMethodManagement,
        CartTotalRepositoryInterface $cartTotalRepository,
        UrlInterface $url,
        ShippingMethodsProvider $shippingMethodsProvider,
        Config\CheckoutConfigurationsProvider $checkoutConfigurationsProvider
    ) {
        $this->quoteRepository = $quoteRepository;
        $this->responseDataFactory = $responseDataFactory;
        $this->paymentMethodManagement = $paymentMethodManagement;
        $this->cartTotalsRepository = $cartTotalRepository;
        $this->url = $url;
        $this->shippingMethodsProvider = $shippingMethodsProvider;
        $this->checkoutConfigurationsProvider = $checkoutConfigurationsProvider;
    }

    /**
     * @param int $cartId
     * @param TotalsItemInterface $item
     *
     * @return ResponseDataInterface
     */
    public function updateItemQty($cartId, TotalsItemInterface $item)
    {
        $generalConfig = $this->checkoutConfigurationsProvider->getGeneralConfigurations();
        $isAllowed = $generalConfig->getIsAllowedToChangeQty();

        /** @var Quote $quote */
        $quote = $this->quoteRepository->getActive($cartId);

        if (!$isAllowed) {
            return $this->getResponseData($quote);
        }

        $itemId = $item->getItemId();
        $itemQty = $item->getQty();

        if ($itemQty <= 0) {
            return $this->removeItemById($cartId, $itemId);
        }

        $quoteItem = $quote->getItemById($itemId);
        if (!$quoteItem) {
            throw new NoSuchEntityException(
                __('Cart item %1 doesn\'t exist.', $itemId)
            );
        }

        try {
            $quoteItem->setQty($itemQty)->save();
            $this->quoteRepository->save($quote);
        } catch (\Exception $e) {
            throw new CouldNotSaveException(__('Could not update item from quote'));
        }

        return $this->getResponseData($quote);
    }

    /**
     * @inheritdoc
     */
    public function removeItemById($cartId, $itemId)
    {
        $generalConfig = $this->checkoutConfigurationsProvider->getGeneralConfigurations();
        $isAllowed = $generalConfig->getIsAllowedToRemoveItemFromCheckout();

        /** @var Quote $quote */
        $quote = $this->quoteRepository->getActive($cartId);

        if (!$isAllowed) {
            return $this->getResponseData($quote);
        }

        $quoteItem = $quote->getItemById($itemId);
        if (!$quoteItem) {
            throw new NoSuchEntityException(
                __('Cart item %1 doesn\'t exist.', $itemId)
            );
        }

        try {
            $quote->removeItem($itemId);
            $this->quoteRepository->save($quote);
        } catch (\Exception $e) {
            throw new CouldNotSaveException(__('Could not remove item from quote'));
        }

        return $this->getResponseData($quote);
    }

    /**
     * @param Quote $quote
     *
     * @return ResponseDataInterface
     */
    private function getResponseData(Quote $quote)
    {
        /** @var ResponseDataInterface $responseData */
        $responseData = $this->responseDataFactory->create();

        if (!$quote->hasItems() || $quote->getHasError() || !$quote->validateMinimumAmount()) {
            $responseData->setRedirectUrl($this->url->getUrl('checkout/cart'));
        } else {
            if ($quote->getShippingAddress()->getCountryId()) {
                $responseData->setShippingMethods($this->shippingMethodsProvider->get($quote));
            }
            $responseData->setPaymentMethods($this->paymentMethodManagement->getList($quote->getId()));
            $responseData->setTotals($this->cartTotalsRepository->get($quote->getId()));
        }

        return $responseData;
    }
}
