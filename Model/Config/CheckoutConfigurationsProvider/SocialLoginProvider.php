<?php

namespace GoMage\SuperLightCheckout\Model\Config\CheckoutConfigurationsProvider;

use Magento\Framework\App\Config\ScopeConfigInterface;

class SocialLoginProvider
{
    // @codingStandardsIgnoreStart
    /**#@+
     * Light Checkout configuration Social Login Facebook.
     */
    const XML_PATH_SUPER_LIGHT_CHECKOUT_SOCIAL_FACEBOOK_ENABLE = 'gomage_super_light_checkout_configuration/social_login_facebook/enable';
    const XML_PATH_SUPER_LIGHT_CHECKOUT_SOCIAL_FACEBOOK_APP_ID = 'gomage_super_light_checkout_configuration/social_login_facebook/app_id';
    const XML_PATH_SUPER_LIGHT_CHECKOUT_SOCIAL_FACEBOOK_APP_SECRET = 'gomage_super_light_checkout_configuration/social_login_facebook/app_secret';
    const XML_PATH_SUPER_LIGHT_CHECKOUT_SOCIAL_FACEBOOK_REDIRECT_URL = 'gomage_super_light_checkout_configuration/social_login_facebook/redirect_url';
    /**#@-*/

    /**#@+
     * Light Checkout configuration Social Login Google.
     */
    const XML_PATH_SUPER_LIGHT_CHECKOUT_SOCIAL_GOOGLE_ENABLE = 'gomage_super_light_checkout_configuration/social_login_google/enable';
    const XML_PATH_SUPER_LIGHT_CHECKOUT_SOCIAL_GOOGLE_APP_ID = 'gomage_super_light_checkout_configuration/social_login_google/app_id';
    const XML_PATH_SUPER_LIGHT_CHECKOUT_SOCIAL_GOOGLE_APP_SECRET = 'gomage_super_light_checkout_configuration/social_login_google/app_secret';
    const XML_PATH_SUPER_LIGHT_CHECKOUT_SOCIAL_GOOGLE_REDIRECT_URL = 'gomage_super_light_checkout_configuration/social_login_google/redirect_url';
    /**#@-*/

    /**#@+
     * Light Checkout configuration Social Login Twitter.
     */
    const XML_PATH_SUPER_LIGHT_CHECKOUT_SOCIAL_TWITTER_ENABLE = 'gomage_super_light_checkout_configuration/social_login_twitter/enable';
    const XML_PATH_SUPER_LIGHT_CHECKOUT_SOCIAL_TWITTER_APP_ID = 'gomage_super_light_checkout_configuration/social_login_twitter/app_id';
    const XML_PATH_SUPER_LIGHT_CHECKOUT_SOCIAL_TWITTER_APP_SECRET = 'gomage_super_light_checkout_configuration/social_login_twitter/app_secret';
    const XML_PATH_SUPER_LIGHT_CHECKOUT_SOCIAL_TWITTER_REDIRECT_URL = 'gomage_super_light_checkout_configuration/social_login_twitter/redirect_url';
    /**#@-*/
    // @codingStandardsIgnoreEnd

    /**
     * @var ScopeConfigInterface
     */
    private $scopeConfig;

    /**
     * @param ScopeConfigInterface $scopeConfig
     */
    public function __construct(ScopeConfigInterface $scopeConfig)
    {
        $this->scopeConfig = $scopeConfig;
    }

    public function getIsSocialLoginFacebookEnabled()
    {
        return $this->scopeConfig->getValue(self::XML_PATH_SUPER_LIGHT_CHECKOUT_SOCIAL_FACEBOOK_ENABLE);
    }

    public function getSocialLoginFacebookAppId()
    {
        return $this->scopeConfig->getValue(self::XML_PATH_SUPER_LIGHT_CHECKOUT_SOCIAL_FACEBOOK_APP_ID);
    }

    public function getSocialLoginFacebookAppSecret()
    {
        return $this->scopeConfig->getValue(self::XML_PATH_SUPER_LIGHT_CHECKOUT_SOCIAL_FACEBOOK_APP_SECRET);
    }

    public function getSocialLoginFacebookAppRedirectUrl()
    {
        return $this->scopeConfig->getValue(self::XML_PATH_SUPER_LIGHT_CHECKOUT_SOCIAL_FACEBOOK_REDIRECT_URL);
    }

    public function getIsSocialLoginGoogleEnabled()
    {
        return $this->scopeConfig->getValue(self::XML_PATH_SUPER_LIGHT_CHECKOUT_SOCIAL_GOOGLE_ENABLE);
    }

    public function getSocialLoginGoogleAppId()
    {
        return $this->scopeConfig->getValue(self::XML_PATH_SUPER_LIGHT_CHECKOUT_SOCIAL_GOOGLE_APP_ID);
    }

    public function getSocialLoginGoogleAppSecret()
    {
        return $this->scopeConfig->getValue(self::XML_PATH_SUPER_LIGHT_CHECKOUT_SOCIAL_GOOGLE_APP_SECRET);
    }

    public function getSocialLoginGoogleAppRedirectUrl()
    {
        return $this->scopeConfig->getValue(self::XML_PATH_SUPER_LIGHT_CHECKOUT_SOCIAL_GOOGLE_REDIRECT_URL);
    }

    public function getIsSocialLoginTwitterEnabled()
    {
        return $this->scopeConfig->getValue(self::XML_PATH_SUPER_LIGHT_CHECKOUT_SOCIAL_TWITTER_ENABLE);
    }

    public function getSocialLoginTwitterAppId()
    {
        return $this->scopeConfig->getValue(self::XML_PATH_SUPER_LIGHT_CHECKOUT_SOCIAL_TWITTER_APP_ID);
    }

    public function getSocialLoginTwitterAppSecret()
    {
        return $this->scopeConfig->getValue(self::XML_PATH_SUPER_LIGHT_CHECKOUT_SOCIAL_TWITTER_APP_SECRET);
    }

    public function getSocialLoginTwitterAppRedirectUrl()
    {
        return $this->scopeConfig->getValue(self::XML_PATH_SUPER_LIGHT_CHECKOUT_SOCIAL_TWITTER_REDIRECT_URL);
    }
}
