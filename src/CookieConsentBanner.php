<?php
/**
 * Cookie Consent Banner plugin for Craft CMS 4.x
 *
 * Add a configurable cookie consent banner to the website.
 *
 * @link      https://adigital.agency
 * @copyright Copyright (c) 2018 A Digital
 */

namespace adigital\cookieconsentbanner;

use adigital\cookieconsentbanner\services\CookieConsentBannerService;
use adigital\cookieconsentbanner\variables\CookieConsentBannerVariable;
use adigital\cookieconsentbanner\models\Settings;

use Craft;
use craft\base\Plugin;
use craft\events\TemplateEvent;
use craft\web\twig\variables\CraftVariable;
use craft\web\View;
use craft\db\Query;
use craft\db\Table;
use craft\elements\Entry;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;
use yii\base\Event;
use yii\base\Exception;

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
 * @author    A Digital
 * @package   CookieConsentBanner
 * @since     1.0.0
 *
 * @property  CookieConsentBannerService $cookieConsentBannerService
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

     /**
     * @var true|null
     */
    public static $commercePlugin;

    // Public Properties
    // =========================================================================

    /**
     * To execute your plugin’s migrations, you’ll need to increase its schema version.
     *
     * @var string
     */
    public string $schemaVersion = '1.0.2';

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
    public function init() : void
    {
        parent::init();
        self::$plugin = $this;
        // Determine if Craft Commerce is installed & enabled
        $commerce = Craft::$app->getPlugins()->getPlugin('commerce');
        if ($commerce) {
            self::$commercePlugin = true;
        }

        $this->setComponents([
            'cookieConsentBannerService' => CookieConsentBannerService::class,
        ]);

        // Register our variables
        Event::on(
            CraftVariable::class,
            CraftVariable::EVENT_INIT,
            static function (Event $event) {
                /** @var CraftVariable $variable */
                $variable = $event->sender;
                $variable->set('cookieConsentBanner', CookieConsentBannerVariable::class);
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


        Event::on(
            View::class,
            View::EVENT_BEFORE_RENDER_TEMPLATE,
            function (TemplateEvent $e) {
                if ($e->template === 'settings/plugins/_settings' && $e->variables['plugin'] === $this) {
                    // Add the tabs
                    $e->variables['tabs'] = [
                        ['label' => 'Display Options', 'url' => '#settings-tab-display-options'],
                        ['label' => 'Banner Text', 'url' => '#settings-tab-banner-text'],
                        ['label' => 'Include Options', 'url' => '#settings-tab-include-options'],
                        ['label' => 'Cookie Options', 'url' => '#settings-tab-cookie-options'],
                    ];
                }
            }
        );

        $settings = $this->getSettings();
        if (!$settings->auto_inject) {
            return;
        }
        if (!$this->cookieConsentBannerService->validateRequestType()) {
            return;
        }
        if ($this->cookieConsentBannerService->validateCookieConsentSet()) {
            return;
        }

        // Load JS/CSS before template is rendered
        Event::on(
            View::class,
            View::EVENT_BEFORE_RENDER_TEMPLATE,
            function (TemplateEvent $event) {
                $validResponseType = $this->cookieConsentBannerService->validateResponseType();
                if (!$validResponseType) {
                    return $event;
                }
                if (!empty($event->variables['statusCode']) && $event->variables['statusCode'] >= 400) {
                    return $event;
                }

                $categories = array_key_exists("category", $event->variables);
                $entries = array_key_exists("entry", $event->variables);
                if (!$categories && !$entries) {
                    $this->cookieConsentBannerService->renderCookieConsentBanner();
                    return $event;
                }

                $settings = $this->getSettings();
                if ($categories) {
                    if (empty($settings->excluded_categories)) {
                        $this->cookieConsentBannerService->renderCookieConsentBanner();
                        return $event;
                    }
                    if (!in_array($event->variables['category']->uid, $settings->excluded_categories)) {
                        $this->cookieConsentBannerService->renderCookieConsentBanner();
                        return $event;
                    }
                }

                if ($entries) {
                    if (empty($settings->excluded_entry_types)) {
                        $this->cookieConsentBannerService->renderCookieConsentBanner();
                        return $event;
                    }
                    if ($event->variables['entry'] instanceof Entry) {
                        $entryTypeUid = (new Query())
                            ->select(['uid'])
                            ->from([Table::ENTRYTYPES])
                            ->where('id = '.$event->variables['entry']->typeId)
                            ->one();
                        if ($entryTypeUid && !in_array($entryTypeUid['uid'], $settings->excluded_entry_types)) {
                            $this->cookieConsentBannerService->renderCookieConsentBanner();
                            return $event;
                        }
                    }
                }
              
                if (self::$commercePlugin) {
                    if (isset($event->variables['product']) && $event->variables['product']::class == "craft\\commerce\\elements\\Product") {
                        $productTypeUid = (new Query())
                            ->select(['uid'])
                            ->from('{{%commerce_producttypes}}')
                            ->where('id = '.$event->variables['product']->typeId)
                            ->one();
                    }

                    if ($this->cookieConsentBannerService->validateResponseType() && (empty($event->variables['statusCode']) || $event->variables['statusCode'] < 400) && (!array_key_exists("product", $event->variables)) || (array_key_exists("product", $event->variables) && (empty($settings->excluded_product_types) || (!empty($settings->excluded_product_types) && (isset($productTypeUid) && !in_array($productTypeUid['uid'], $settings->excluded_product_types)))))) {
                        $this->cookieConsentBannerService->renderCookieConsentBanner();
                        return $event;
                    }
                }

                return $event;
            }
        );
    }

    // Protected Methods
    // =========================================================================

    /**
     * Creates and returns the model used to store the plugin’s settings.
     *
     * @return Settings
     */
    protected function createSettingsModel() : Settings
    {
        return new Settings();
    }

    /**
     * Returns the rendered settings HTML, which will be inserted into the content
     * block on the settings page.
     *
     * @return string The rendered settings HTML
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     * @throws Exception
     */
    protected function settingsHtml(): string
    {
        // Get and pre-validate the settings
        $settings = $this->getSettings();
        $settings->validate();

        return Craft::$app->view->renderTemplate(
            'cookie-consent-banner/settings',
            [
                'settings' => $this->getSettings(),
                'overrides' => array_keys(Craft::$app->getConfig()->getConfigFromFile(strtolower($this->handle))),
            ]
        );
    }
}
