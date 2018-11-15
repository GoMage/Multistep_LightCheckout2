<?php

namespace GoMage\SuperLightCheckout\Model;

use GoMage\SuperLightCheckout\Api\QuoteItemManagementInterface;
use GoMage\SuperLightCheckout\Model\QuoteItemManagement\QuoteMaskedIdResponseInterface;
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
use Magento\Checkout\Model\Session as CheckoutSession;
use Magento\Quote\Model\QuoteIdMaskFactory;

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
     * @var CheckoutSession
     */
    private $checkoutSession;

    /**
     * @var QuoteIdMaskFactory
     */
    private $quoteIdMaskFactory;

    /**
     * @var QuoteMaskedIdResponseInterface
     */
    private $quoteMaskedIdResponse;

    /**
     * @param CartRepositoryInterface $quoteRepository
     * @param ResponseDataInterfaceFactory $responseDataFactory
     * @param PaymentMethodManagementInterface $paymentMethodManagement
     * @param CartTotalRepositoryInterface $cartTotalRepository
     * @param UrlInterface $url
     * @param ShippingMethodsProvider $shippingMethodsProvider
     * @param Config\CheckoutConfigurationsProvider $checkoutConfigurationsProvider
     * @param CheckoutSession $checkoutSession
     * @param QuoteIdMaskFactory $quoteIdMaskFactory
     * @param QuoteMaskedIdResponseInterface $quoteMaskedIdResponse
     */
    public function __construct(
        CartRepositoryInterface $quoteRepository,
        ResponseDataInterfaceFactory $responseDataFactory,
        PaymentMethodManagementInterface $paymentMethodManagement,
        CartTotalRepositoryInterface $cartTotalRepository,
        UrlInterface $url,
        ShippingMethodsProvider $shippingMethodsProvider,
        Config\CheckoutConfigurationsProvider $checkoutConfigurationsProvider,
        CheckoutSession $checkoutSession,
        QuoteIdMaskFactory $quoteIdMaskFactory,
        QuoteMaskedIdResponseInterface $quoteMaskedIdResponse
    ) {
        $this->quoteRepository = $quoteRepository;
        $this->responseDataFactory = $responseDataFactory;
        $this->paymentMethodManagement = $paymentMethodManagement;
        $this->cartTotalsRepository = $cartTotalRepository;
        $this->url = $url;
        $this->shippingMethodsProvider = $shippingMethodsProvider;
        $this->checkoutConfigurationsProvider = $checkoutConfigurationsProvider;
        $this->checkoutSession = $checkoutSession;
        $this->quoteIdMaskFactory = $quoteIdMaskFactory;
        $this->quoteMaskedIdResponse = $quoteMaskedIdResponse;
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
     * @inheritdoc
     */
    public function getActiveQuoteInformation($cartId = null)
    {
        if ($cartId === null) {
            $quote = $this->checkoutSession->getQuote();
        } else {
            /** @var Quote $quote */
            $quote = $this->quoteRepository->getActive($cartId);
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

        if ($quote->getHasError() || !$quote->validateMinimumAmount()) {
            $responseData->setRedirectUrl($this->url->getUrl('checkout/cart'));
        } else {
            if ($quote->getShippingAddress()->getCountryId()) {
                $responseData->setShippingMethods($this->shippingMethodsProvider->get($quote));
            }
            $responseData->setPaymentMethods($this->paymentMethodManagement->getList($quote->getId()));
            $responseData->setTotals($this->cartTotalsRepository->get($quote->getId()));
            if ($this->checkoutSession->getQuote()->getId()) {
                $responseData->setQuote($this->checkoutSession->getQuote());
                if (!$quote->getCustomerId()) {
                    $this->quoteMaskedIdResponse->setQuoteMaskedId($this->getQuoteMaskedId($quote)->getMaskedId());
                    $responseData->setQuoteMaskedId($this->quoteMaskedIdResponse);
                }
            }

        }

        return $responseData;
    }

    /**
     * @param $quote
     *
     * @return \Magento\Quote\Model\QuoteIdMask|string
     */
    private function getQuoteMaskedId($quote)
    {
        $quoteIdMask = '';
        if (!$quote->getCustomer()->getId()) {
            /** @var $quoteIdMask \Magento\Quote\Model\QuoteIdMask */
            $quoteIdMask = $this->quoteIdMaskFactory->create();
            $quote->setId($quoteIdMask->load(
                $this->checkoutSession->getQuote()->getId(),
                'quote_id'
            )->getMaskedId());
        }

        return $quoteIdMask;
    }
}
