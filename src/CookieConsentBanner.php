<?php
/**
 * Cookie Consent Banner plugin for Craft CMS 3.x
 *
 * Add a configurable cookie consent banner to the website.
 *
 * @link      https://adigital.agency
 * @copyright Copyright (c) 2018 Mark @ A Digital
 */

namespace adigital\cookieconsentbanner;

use adigital\cookieconsentbanner\assetbundles\cookieconsentbanner\CookieConsentBannerAsset;
use adigital\cookieconsentbanner\models\Settings;

use Craft;
use craft\base\Plugin;
use craft\services\Plugins;
use craft\events\PluginEvent;
use craft\events\TemplateEvent;
use craft\web\View;

use yii\base\Event;

/**
 * Craft plugins are very much like little applications in and of themselves. We’ve made
 * it as simple as we can, but the training wheels are off. A little prior knowledge is
 * going to be required to write a plugin.
 *
 * For the purposes of the plugin docs, we’re going to assume that you know PHP and SQL,
 * as well as some semi-advanced concepts like object-oriented programming and PHP namespaces.
 *
 * https://craftcms.com/docs/plugins/introduction
 *
 * @author    Mark @ A Digital
 * @package   CookieConsentBanner
 * @since     1.0.0
 *
 * @property  Settings $settings
 * @method    Settings getSettings()
 */
class CookieConsentBanner extends Plugin
{
    // Static Properties
    // =========================================================================

    /**
     * Static property that is an instance of this plugin class so that it can be accessed via
     * CookieConsentBanner::$plugin
     *
     * @var CookieConsentBanner
     */
    public static $plugin;

    // Public Properties
    // =========================================================================

    /**
     * To execute your plugin’s migrations, you’ll need to increase its schema version.
     *
     * @var string
     */
    public $schemaVersion = '1.0.0';

    // Public Methods
    // =========================================================================

    /**
     * Set our $plugin static property to this class so that it can be accessed via
     * CookieConsentBanner::$plugin
     *
     * Called after the plugin class is instantiated; do any one-time initialization
     * here such as hooks and events.
     *
     * If you have a '/vendor/autoload.php' file, it will be loaded for you automatically;
     * you do not need to load it in your init() method.
     *
     */
    public function init()
    {
        parent::init();
        self::$plugin = $this;
        
        if (Craft::$app->request->getIsCpRequest() || Craft::$app->request->getIsAjax() || (isset($_COOKIE['cookieconsent_status']) && $_COOKIE['cookieconsent_status'] == "dismiss")) {
	      return false;
	    }

        // Load JS/CSS before template is rendered
        Event::on(
	      View::class,
	      View::EVENT_BEFORE_RENDER_TEMPLATE,
	      function (TemplateEvent $event) {
		    Craft::$app->getView()->registerAssetBundle(CookieConsentBannerAsset::class);
            $script = '
              window.addEventListener("load", function(){
              window.cookieconsent.initialise({
                "palette": {
                  "popup": {
                    "background": "'. $this->getSettings()->palette_banner .'",
                    "text": "'. $this->getSettings()->palette_banner_text .'"
                  },
                  "button": {
                    "background":  "'. $this->getSettings()->layout .'" === "wire" ? "transparent" :  "'. $this->getSettings()->palette_button .'",
                    "text": "'. $this->getSettings()->layout .'" === "wire" ? "'. $this->getSettings()->palette_button .'" : "'. $this->getSettings()->palette_button_text .'",
                    "border":  "'. $this->getSettings()->layout .'" === "wire" ? "'. $this->getSettings()->palette_button .'" : undefined
                  }
                },
                "position": "'. $this->getSettings()->position .'" === "toppush" ? "top" : "'. $this->getSettings()->position .'",
                "static": "'. $this->getSettings()->position .'" === "toppush",
                "theme": "'. $this->getSettings()->layout .'",
                "content": {
                  "message": "'. $this->getSettings()->message .'",
                  "dismiss": "'. $this->getSettings()->dismiss .'",
                  "link": "'. $this->getSettings()->learn .'",
                  "href": "'. $this->getSettings()->learn_more_link .'"
                }
              })});
            ';
            Craft::$app->getView()->registerScript($script, 1);
	      }
        );

/**
 * Logging in Craft involves using one of the following methods:
 *
 * Craft::trace(): record a message to trace how a piece of code runs. This is mainly for development use.
 * Craft::info(): record a message that conveys some useful information.
 * Craft::warning(): record a warning message that indicates something unexpected has happened.
 * Craft::error(): record a fatal error that should be investigated as soon as possible.
 *
 * Unless `devMode` is on, only Craft::warning() & Craft::error() will log to `craft/storage/logs/web.log`
 *
 * It's recommended that you pass in the magic constant `__METHOD__` as the second parameter, which sets
 * the category to the method (prefixed with the fully qualified class name) where the constant appears.
 *
 * To enable the Yii debug toolbar, go to your user account in the AdminCP and check the
 * [] Show the debug toolbar on the front end & [] Show the debug toolbar on the Control Panel
 *
 * http://www.yiiframework.com/doc-2.0/guide-runtime-logging.html
 */
        Craft::info(
            Craft::t(
                'cookie-consent-banner',
                '{name} plugin loaded',
                ['name' => $this->name]
            ),
            __METHOD__
        );
    }

    // Protected Methods
    // =========================================================================

    /**
     * Creates and returns the model used to store the plugin’s settings.
     *
     * @return \craft\base\Model|null
     */
    protected function createSettingsModel()
    {
        return new Settings();
    }

    /**
     * Returns the rendered settings HTML, which will be inserted into the content
     * block on the settings page.
     *
     * @return string The rendered settings HTML
     */
    protected function settingsHtml(): string
    {
        return Craft::$app->view->renderTemplate(
            'cookie-consent-banner/settings',
            [
                'settings' => $this->getSettings()
            ]
        );
    }
}
