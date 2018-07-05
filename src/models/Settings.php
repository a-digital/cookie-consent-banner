<?php
/**
 * Cookie Consent Banner plugin for Craft CMS 3.x
 *
 * Add a configurable cookie consent banner to the website.
 *
 * @link      https://adigital.agency
 * @copyright Copyright (c) 2018 Mark @ A Digital
 */

namespace adigital\cookieconsentbanner\models;

use adigital\cookieconsentbanner\CookieConsentBanner;

use Craft;
use craft\base\Model;

/**
 * CookieConsentBanner Settings Model
 *
 * This is a model used to define the plugin's settings.
 *
 * Models are containers for data. Just about every time information is passed
 * between services, controllers, and templates in Craft, it’s passed via a model.
 *
 * https://craftcms.com/docs/plugins/models
 *
 * @author    Mark @ A Digital
 * @package   CookieConsentBanner
 * @since     1.0.0
 */
class Settings extends Model
{
    // Public Properties
    // =========================================================================

    /**
     * Some field model attribute
     *
     * @var string
     */
    public $position = 'bottom';
    public $layout = 'block';
    public $palette = 'default';
    public $palette_banner = '#000000';
    public $palette_button = '#f1d600';
    public $palette_banner_text = '#ffffff';
    public $palette_button_text = '#000000';
    public $learn_more_link = 'http://cookiesandyou.com/';
    public $message = 'This website uses cookies to ensure you get the best experience on our website.';
    public $dismiss = 'Got it!';
    public $learn = 'Learn More';
    public $preload_css = false;
    public $async_js = false;
    public $defer_js = false;

    // Public Methods
    // =========================================================================

    /**
     * Returns the validation rules for attributes.
     *
     * Validation rules are used by [[validate()]] to check if attribute values are valid.
     * Child classes may override this method to declare different validation rules.
     *
     * More info: http://www.yiiframework.com/doc-2.0/guide-input-validation.html
     *
     * @return array
     */
    public function rules()
    {
        return [
	        // these attributes are required
	        [['position', 'layout', 'palette', 'palette_banner', 'palette_button', 'palette_banner_text', 'palette_button_text', 'learn_more_link', 'message', 'dismiss', 'learn'], 'required', 'message' => 'Please complete all required fields'],
            ['position', 'in', 'range' => ['top', 'toppush', 'bottom', 'left', 'right', 'bottom-left', 'bottom-right'], 'strict' => true, 'allowArray' => false],
            ['layout', 'in', 'range' => ['block', 'classic', 'edgeless', 'wire'], 'strict' => true, 'allowArray' => false],
            ['palette', 'in', 'range' => ['default', 'ice', 'cleanblue', 'greenblack', 'pink', 'purple', 'blue', 'red', 'white', 'graygreen', 'orange', 'whitegreen'], 'strict' => true, 'allowArray' => false],
        ];
    }
}