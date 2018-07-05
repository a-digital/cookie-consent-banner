<?php
/**
 * Cookie Consent Banner plugin for Craft CMS 3.x
 *
 * Add a configurable cookie consent banner to the website.
 *
 * @link      https://adigital.agency
 * @copyright Copyright (c) 2018 Mark @ A Digital
 */

namespace adigital\cookieconsentbanner\assetbundles\CookieConsentBanner;

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
    public function init()
    {
        // define the path that your publishable resources live
        $this->sourcePath = "@adigital/cookieconsentbanner/assetbundles/cookieconsentbanner/dist";
        
        $settings = \adigital\cookieconsentbanner\CookieConsentBanner::getInstance()->getSettings();

        $jsAsset = array("js/cookieconsent.min.js");
        
        if($settings->async_js) {
	      $jsAsset["async"] = "async";
        }
        
        if($settings->defer_js) {
	      $jsAsset["defer"] = "defer";
        }
        
        $cssAsset = array("css/cookieconsent.min.css");
        
        if($settings->preload_css) {
	      $cssAsset["rel"] = "preload";
        }
        

        // define the relative path to CSS/JS files that should be registered with the page
        // when this asset bundle is registered
        $this->js = [
            $jsAsset,
        ];

        $this->css = [
            $cssAsset,
        ];

        parent::init();
    }
}
