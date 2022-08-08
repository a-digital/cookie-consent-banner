<?php
/**
 * Cookie Consent Banner plugin for Craft CMS 3.x
 *
 * Add a configurable cookie consent banner to the website.
 *
 * @link      https://adigital.agency
 * @copyright Copyright (c) 2018 Mark @ A Digital
 */

namespace adigital\cookieconsentbanner\assetbundles\cookieconsentbanner;

use Craft;
use craft\web\AssetBundle;
//use craft\web\assets\cp\CpAsset;

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
 * @author    Mark @ A Digital
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

        $settings = \adigital\cookieconsentbanner\CookieConsentBanner::getInstance()->getSettings();

        $jsOptions = [];

        if($settings->async_js) {
	        $jsOptions["async"] = "async";
        }

        if($settings->defer_js) {
	        $jsOptions["defer"] = "defer";
        }

        $cssOptions = [];

        if($settings->preload_css) {
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
