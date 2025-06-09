<?php
/**
 * Cookie Consent Banner plugin for Craft CMS 4.x
 *
 * Add a configurable cookie consent banner to the website.
 *
 * @link      https://adigital.agency
 * @copyright Copyright (c) 2018 A Digital
 */

namespace adigital\cookieconsentbanner\services;

use adigital\cookieconsentbanner\CookieConsentBanner;
use adigital\cookieconsentbanner\assetbundles\cookieconsentbanner\CookieConsentBannerAsset;

use Craft;
use craft\base\Component;
use yii\base\InvalidConfigException;

/**
 * CookieConsentBannerService Service
 *
 * All of your plugin’s business logic should go in services, including saving data,
 * retrieving data, etc. They provide APIs that your controllers, template variables,
 * and other plugins can interact with.
 *
 * https://craftcms.com/docs/plugins/services
 *
 * @author    A Digital
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
     *     CookieConsentBanner::$plugin->cookieConsentBannerService->renderCookieConsentBanner()
     *
     * @return bool
     * @throws InvalidConfigException
     */
    public function renderCookieConsentBanner(): bool
    {
        $settings = CookieConsentBanner::$plugin->getSettings();

        Craft::$app->getView()->registerAssetBundle(CookieConsentBannerAsset::class);
        $script = '
            if ((navigator.doNotTrack != "1" && '. ($settings->honour_do_not_track_header ? $settings->honour_do_not_track_header : 0) .') || !'. ($settings->honour_do_not_track_header ? $settings->honour_do_not_track_header : 0) .') {
                window.addEventListener("load", function(){
                    window.cookieconsent.initialise({
                        "palette": {
                            "popup": {
                                "background": "'. (substr($settings->palette_banner, 0, 1) != "#" ? "#" : "") . $settings->palette_banner .'",
                                "text": "'. (substr($settings->palette_banner_text, 0, 1) != "#" ? "#" : "") . $settings->palette_banner_text .'",
                                "link": "'. (substr($settings->palette_link, 0, 1) != "#" ? "#" : "") . $settings->palette_link .'"
                            },
                            "button": {
                                "background":  "'. $settings->layout .'" === "wire" ? "transparent" :  "'. (substr($settings->palette_button, 0, 1) != "#" ? "#" : "") . $settings->palette_button .'",
                                "text": "'. $settings->layout .'" === "wire" ? "'. (substr($settings->palette_button, 0, 1) != "#" ? "#" : "") . $settings->palette_button .'" : "'. (substr($settings->palette_button_text, 0, 1) != "#" ? "#" : "") . $settings->palette_button_text .'",
                                "border":  "'. $settings->layout .'" === "wire" ? "'. (substr($settings->palette_button, 0, 1) != "#" ? "#" : "") . $settings->palette_button .'" : undefined
                            },
                            "highlight": {
                                "background":  "'. $settings->layout .'" === "wire" ? "transparent" :  "'. (substr($settings->palette_left_button_bg, 0, 1) != "#" ? "#" : "") . $settings->palette_left_button_bg .'",
                                "text": "'. $settings->layout .'" === "wire" ? "'. (substr($settings->palette_left_button_bg, 0, 1) != "#" ? "#" : "") . $settings->palette_left_button_bg .'" : "'. (substr($settings->palette_left_button_text, 0, 1) != "#" ? "#" : "") . $settings->palette_left_button_text .'",
                                "border":  "'. $settings->layout .'" === "wire" ? "'. (substr($settings->palette_left_button_bg, 0, 1) != "#" ? "#" : "") . $settings->palette_left_button_bg .'" : undefined
                            }
                        },
                        "position": "'. $settings->position .'" === "toppush" ? "top" : "'. $settings->position .'",
                        "static": "'. $settings->position .'" === "toppush",
                        "theme": "'. $settings->layout .'",
                        "type": "'. $settings->type .'",
                        "content": {
                            "message": "'. Craft::t('cookie-consent-banner', str_replace(array("\n", "\r"), "", nl2br($settings->message))) .'&nbsp;",
                            "dismiss": "'. Craft::t('cookie-consent-banner', $settings->dismiss) .'",
                            "link": "'. Craft::t('cookie-consent-banner', $settings->learn) .'",
                            "href": "'. Craft::t('cookie-consent-banner', $settings->learn_more_link) .'",
                            "allow":"'. Craft::t('cookie-consent-banner', $settings->allow) .'",
                            "deny":"'. Craft::t('cookie-consent-banner', $settings->decline) .'",
                            "target":"'. $settings->target .'"
                        },
                        "revokable":'. ($settings->revokable ? $settings->revokable : 0) .' === 1 ? true : false,
                        "dismissOnScroll":'. $settings->dismiss_on_scroll .' > 0 ? '. $settings->dismiss_on_scroll .' : false,
                        "dismissOnTimeout":'. $settings->dismiss_on_timeout .' > 0 ? ('. $settings->dismiss_on_timeout .' * 1000) : false,
                        "cookie": {
                            "expiryDays":'. $settings->expiry_days .' !== 0 ? '. $settings->expiry_days .' : 365,
                            "secure":'. ($settings->secure_only ? $settings->secure_only : 0) . ' === 1 ? true : false
                        },
                        onInitialise: function (status) {
                            var type = this.options.type;
                            var didConsent = this.hasConsented();
                            if (type == "opt-in" && didConsent) {
                                // enable cookies
                                if (typeof optInCookiesConsented === "function") {
                                    optInCookiesConsented();
                                    console.log("Opt in cookies consented");
                                } else {
                                    console.log("Opt in function not defined!");
                                }
                            }
                            if (type == "opt-out" && !didConsent) {
                                // disable cookies
                                if (typeof optOutCookiesNotConsented === "function") {
                                    optOutCookiesNotConsented();
                                    console.log("Opt out cookies not consented");
                                } else {
                                    console.log("Opt out function not defined!");
                                }
                            }
                        },
                        onStatusChange: function(status, chosenBefore) {
                            var type = this.options.type;
                            var didConsent = this.hasConsented();
                            if (type == "opt-in" && didConsent) {
                                // enable cookies
                                if (typeof optInCookiesConsented === "function") {
                                    optInCookiesConsented();
                                    console.log("Opt in cookies consented");
                                } else {
                                    console.log("Opt in function not defined!");
                                }
                            }
                            if (type == "opt-out" && !didConsent) {
                                // disable cookies
                                if (typeof optOutCookiesNotConsented === "function") {
                                    optOutCookiesNotConsented();
                                    console.log("Opt out cookies not consented");
                                } else {
                                    console.log("Opt out function not defined!");
                                }
                            }
                        },
                        onRevokeChoice: function() {
                            var type = this.options.type;
                            if (type == "opt-in") {
                                // disable cookies
                                if (typeof optInCookiesRevoked === "function") {
                                    optInCookiesRevoked();
                                    console.log("Opt in cookies revoked");
                                } else {
                                    console.log("Opt in revoked function not defined!");
                                }
                            }
                            if (type == "opt-out") {
                                // enable cookies
                                if (typeof optOutCookiesRevoked === "function") {
                                    optOutCookiesRevoked();
                                    console.log("Opt out cookies revoked");
                                } else {
                                    console.log("Opt out revoked function not defined!");
                                }
                            }
                        }
                    });
                });
            } else if ('. ($settings->honour_do_not_track_header ? $settings->honour_do_not_track_header : 0) .') {
                // disable cookies
                if (typeof optOutCookiesNotConsented === "function") {
                    optOutCookiesNotConsented();
                    console.log("Opt out cookies not consented");
                } else {
                    console.log("Opt out function not defined!");
                }
            }
        ';
        Craft::$app->getView()->registerScript($script, 1, array(), "cookie-consent-banner");

        return true;
    }

    public function validateRequestType(): bool
    {
        if (Craft::$app->request->getIsCpRequest()) {
            return false;
        }
        if (Craft::$app->request->getIsConsoleRequest()) {
            return false;
        }
        if (Craft::$app->request->hasMethod("getIsAjax") && Craft::$app->request->getIsAjax()) {
            return false;
        }
        if (Craft::$app->request->hasMethod("getIsLivePreview") && Craft::$app->request->getIsLivePreview() && CookieConsentBanner::$plugin->getSettings()->disable_in_live_preview) {
            return false;
        }

        return true;
    }

    public function validateCookieConsentSet(): bool
    {
        return isset($_COOKIE['cookieconsent_status']);
    }

    public function validateResponseType(): bool
    {
        if (str_contains(Craft::$app->response->format, 'template')) {
            return true;
        }

        return false;
    }
}
