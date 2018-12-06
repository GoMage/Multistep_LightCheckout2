<?php

namespace GoMage\SuperLightCheckout\Api;

/**
 * Check if given email is subscribed for newsletter in given website.
 * @api
 */
interface IsCustomerSubscribedForNewsletterInterface
{
    /**
     * @param string $customerEmail
     *
     * @return bool
     */
    public function execute($customerEmail);
}
