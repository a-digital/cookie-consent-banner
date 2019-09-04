<?php
/**
 * Cookie Consent Banner plugin for Craft CMS 3.x
 *
 * Add a configurable cookie consent banner to the website.
 *
 * @link      https://adigital.agency
 * @copyright Copyright (c) 2018 Mark @ A Digital
 */

namespace adigital\cookieconsentbanner\services;

use adigital\cookieconsentbanner\CookieConsentBanner;
use adigital\cookieconsentbanner\assetbundles\cookieconsentbanner\CookieConsentBannerAsset;

use Craft;
use craft\base\Component;
/**
 * CookieConsentBannerService Service
 *
 * All of your plugin’s business logic should go in services, including saving data,
 * retrieving data, etc. They provide APIs that your controllers, template variables,
 * and other plugins can interact with.
 *
 * https://craftcms.com/docs/plugins/services
 *
 * @author    Mark @ A Digital
 * @package   Eventbrite
 * @since     1.0.0
 */
class CookieConsentBannerService extends Component
{
  // Public Methods
  // =========================================================================
  /**
   * This function can literally be anything you want, and you can have as many service
   * functions as you want
   *
   * From any other plugin file, call it like this:
   *
   *     CookieConsentBanner::$plugin->cookieConsentBannerService->exampleService()
   *
   * @return mixed
   */
  public function renderCookieConsentBanner()
  {
	$settings = CookieConsentBanner::$plugin->getSettings();
	
	Craft::$app->getView()->registerAssetBundle(CookieConsentBannerAsset::class);
    $script = '
        window.addEventListener("load", function(){
            window.cookieconsent.initialise({
                "palette": {
                    "popup": {
                        "background": "'. $settings->palette_banner .'",
                        "text": "'. $settings->palette_banner_text .'"
                    },
                    "button": {
                        "background":  "'. $settings->layout .'" === "wire" ? "transparent" :  "'. $settings->palette_button .'",
                        "text": "'. $settings->layout .'" === "wire" ? "'. $settings->palette_button .'" : "'. $settings->palette_button_text .'",
                        "border":  "'. $settings->layout .'" === "wire" ? "'. $settings->palette_button .'" : undefined
                    }
                },
                "position": "'. $settings->position .'" === "toppush" ? "top" : "'. $settings->position .'",
                "static": "'. $settings->position .'" === "toppush",
                "theme": "'. $settings->layout .'",
                "content": {
                    "message": "'. Craft::t('cookie-consent-banner', str_replace(array("\n", "\r"), "", nl2br($settings->message))) .'&nbsp;",
                    "dismiss": "'. Craft::t('cookie-consent-banner', $settings->dismiss) .'",
                    "link": "'. Craft::t('cookie-consent-banner', $settings->learn) .'",
                    "href": "'. $settings->learn_more_link .'"
                }
            })});
    ';
    Craft::$app->getView()->registerScript($script, 1, array(), "cookie-consent-banner");
    
    return true;
  }
  
  public function validateRequestType()
  {
	if(Craft::$app->request->getIsCpRequest() || Craft::$app->request->getIsConsoleRequest() || (Craft::$app->request->hasMethod("getIsAjax") && Craft::$app->request->getIsAjax()) || (Craft::$app->request->hasMethod("getIsLivePreview") && (Craft::$app->request->getIsLivePreview() && CookieConsentBanner::$plugin->getSettings()->disable_in_live_preview))) {
      return false;
	}
	
	return true;
  }
  
  public function validateCookiesAccepted() {
	if(isset($_COOKIE['cookieconsent_status']) && $_COOKIE['cookieconsent_status'] == "dismiss") {
      return true;
	}
	
	return false;
  }
  
  public function validateResponseType() {
	if(strpos(Craft::$app->response->headers['content-type'], "text/html") !== false) {
	  return true;
	}
	
	return false;
  }
}