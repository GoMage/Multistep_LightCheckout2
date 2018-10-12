<?php

namespace GoMage\SuperLightCheckout\Model\Config;

use GoMage\SuperLightCheckout\Model\Config\CheckoutConfigurationsProvider\GeneralConfigurationsProvider;

class CheckoutConfigurationsProvider
{
    /**
     * @var GeneralConfigurationsProvider
     */
    private $generalConfigurationsProvider;

    /**
     * @param GeneralConfigurationsProvider $generalConfigurationsProvider
     */
    public function __construct(GeneralConfigurationsProvider $generalConfigurationsProvider)
    {
        $this->generalConfigurationsProvider = $generalConfigurationsProvider;
    }

    public function getGeneralConfigurations()
    {
        return $this->generalConfigurationsProvider;
    }
}
