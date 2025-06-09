<?php
/**
 * Cookie Consent Banner plugin for Craft CMS 3.x
 *
 * Add a configurable cookie consent banner to the website.
 *
 * @link      https://adigital.agency
 * @copyright Copyright (c) 2018 Mark @ A Digital
 */

namespace adigital\cookieconsentbanner\variables;

use adigital\cookieconsentbanner\CookieConsentBanner;

use yii\base\InvalidConfigException;

/**
 * Cookie Consent Banner Variable
 *
 * Craft allows plugins to provide their own template variables, accessible from
 * the {{ craft }} global variable (e.g. {{ craft.cookieconsentbanner }}).
 *
 * https://craftcms.com/docs/plugins/variables
 *
 * @author    Mark @ A Digital
 * @package   CookieConsentBanner
 * @since     1.1.7
 */
class CookieConsentBannerVariable
{
    // Public Methods
    // =========================================================================
    /**
     * Whatever you want to output to a Twig template can go into a Variable method.
     * You can have as many variable functions as you want.  From any Twig template,
     * call it like this:
     *
     *     {{ craft.cookieconsentbanner.exampleVariable }}
     *
     * Or, if your variable requires parameters from Twig:
     *
     *     {{ craft.cookieconsentbanner.exampleVariable(twigValue) }}
     *
     * @return void
     * @throws InvalidConfigException
     */
    public function addBanner(): void
    {
        $settings = CookieConsentBanner::$plugin->getSettings();
        $cookieService = CookieConsentBanner::$plugin->cookieConsentBannerService;
        if (!$settings->auto_inject && $cookieService->validateRequestType() && !$cookieService->validateCookieConsentSet() && $cookieService->validateResponseType()) {
            $cookieService->renderCookieConsentBanner();
        }
    }
}
