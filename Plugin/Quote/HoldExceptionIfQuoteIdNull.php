<?php

namespace GoMage\SuperLightCheckout\Plugin\Quote;

use GoMage\SuperLightCheckout\Model\Config\CheckoutConfigurationsProvider;
use Magento\Quote\Api\Data\CartInterface;
use Magento\Quote\Model\QuoteRepository;

class HoldExceptionIfQuoteIdNull
{
    /**
     * @var CheckoutConfigurationsProvider
     */
    private $checkoutConfigurationsProvider;

    /**
     * @var CartInterface
     */
    private $cart;

    /**
     * @param CheckoutConfigurationsProvider $checkoutConfigurationsProvider
     * @param CartInterface $cart
     */
    public function __construct(
        CheckoutConfigurationsProvider $checkoutConfigurationsProvider,
        CartInterface $cart
    ) {
        $this->checkoutConfigurationsProvider = $checkoutConfigurationsProvider;
        $this->cart = $cart;
    }

    /**
     * @param QuoteRepository $subject
     * @param \Closure $proceed
     * @param $cartId
     * @param array $sharedStoreIds
     *
     * @return array|mixed
     */
    public function aroundGetActive(
        QuoteRepository $subject,
        \Closure $proceed,
        $cartId,
        array $sharedStoreIds = []
    ) {
        if ($cartId !== null) {
            return $proceed($cartId, $sharedStoreIds);
        }

        return $this->cart;
    }

    /**
     * @param QuoteRepository $subject
     * @param \Closure $proceed
     * @param $cartId
     * @param array $sharedStoreIds
     *
     * @return array|mixed
     */
    public function aroundGet(
        QuoteRepository $subject,
        \Closure $proceed,
        $cartId,
        array $sharedStoreIds = []
    ) {
        if ($cartId !== null) {
            return $proceed($cartId, $sharedStoreIds);
        }

        return $this->cart;
    }
}
