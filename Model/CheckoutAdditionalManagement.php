<?php

namespace GoMage\SuperLightCheckout\Model;

use GoMage\SuperLightCheckout\Api\CheckoutAdditionalManagementInterface;
use Magento\Checkout\Model\Session;

class CheckoutAdditionalManagement implements CheckoutAdditionalManagementInterface
{
    /**
     * @var Session
     */
    private $checkoutSession;

    /**
     * @var CheckoutCustomerSubscriber
     */
    private $checkoutCustomerSubscriber;

    /**
     * @param Session $checkoutSession
     * @param CheckoutCustomerSubscriber $checkoutCustomerSubscriber
     */
    public function __construct(
        Session $checkoutSession,
        CheckoutCustomerSubscriber $checkoutCustomerSubscriber
    ) {
        $this->checkoutSession = $checkoutSession;
        $this->checkoutCustomerSubscriber = $checkoutCustomerSubscriber;
    }

    /**
     * @inheritdoc
     */
    public function saveAdditionalInformation($additionInformation)
    {
        $this->checkoutSession->setAdditionalInformation($additionInformation);

        if (isset($additionInformation['subscribe'])) {
            $email = null;
            if (isset($additionInformation['customerEmail']) && $additionInformation['customerEmail']) {
                $email = $additionInformation['customerEmail'];
            }

            $this->checkoutCustomerSubscriber->execute($email);
        }

        return true;
    }
}
