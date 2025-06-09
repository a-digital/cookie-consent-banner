<?php
/**
 * Cookie Consent Banner plugin for Craft CMS 4.x
 *
 * Add a configurable cookie consent banner to the website.
 *
 * @link      https://adigital.agency
 * @copyright Copyright (c) 2018 A Digital
 */

namespace adigital\cookieconsentbanner\assetbundles\cookieconsentbanner;

use adigital\cookieconsentbanner\CookieConsentBanner;
use craft\web\AssetBundle;

/**
 * CookieConsentBannerAsset AssetBundle
 *
 * AssetBundle represents a collection of asset files, such as CSS, JS, images.
 *
 * Each asset bundle has a unique name that globally identifies it among all asset bundles used in an application.
 * The name is the [fully qualified class name](http://php.net/manual/en/language.namespaces.rules.php)
 * of the class representing it.
 *
 * An asset bundle can depend on other asset bundles. When registering an asset bundle
 * with a view, all its dependent asset bundles will be automatically registered.
 *
 * http://www.yiiframework.com/doc-2.0/guide-structure-assets.html
 *
 * @author    A Digital
 * @package   CookieConsentBanner
 * @since     1.0.0
 */
class CookieConsentBannerAsset extends AssetBundle
{
    // Public Methods
    // =========================================================================

    /**
     * Initializes the bundle.
     */
    public function init() : void
    {
        // define the path that your publishable resources live
        $this->sourcePath = "@adigital/cookieconsentbanner/assetbundles/cookieconsentbanner/dist";
        $settings = CookieConsentBanner::$plugin->getSettings();

        $jsOptions = [];
        if ($settings?->async_js) {
            $jsOptions["async"] = "async";
        }
        if ($settings?->defer_js) {
            $jsOptions["defer"] = "defer";
        }

        $cssOptions = [];
        if ($settings?->preload_css) {
            $cssOptions["rel"] = "preload";
            $cssOptions["as"] = "style";
        }


        // define the relative path to CSS/JS files that should be registered with the page
        // when this asset bundle is registered
        $this->js = [
            "js/cookieconsent.min.js",
        ];
        $this->jsOptions = $jsOptions;

        $this->css = [
            "css/cookieconsent.min.css",
        ];
        $this->cssOptions = $cssOptions;

        parent::init();
    }
}
