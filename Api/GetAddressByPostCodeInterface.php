<?php

namespace GoMage\SuperLightCheckout\Api;

interface GetAddressByPostCodeInterface
{
    /**
     * @param string $postcode
     *
     * @return \GoMage\SuperLightCheckout\Model\GetAddressByPostCode\ResponseDataInterface
     */
    public function execute($postcode);
}
