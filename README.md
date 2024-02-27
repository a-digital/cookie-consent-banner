# Cookie Consent Banner plugin for Craft CMS 4.x

Add a configurable cookie consent banner to the website.

## Requirements

This plugin requires Craft CMS 4.0.0 or later.

## Installation

To install the plugin, follow these instructions.

1. Open your terminal and go to your Craft project:

        cd /path/to/project

2. Then tell Composer to load the plugin:

        composer require adigital/cookie-consent-banner

3. In the Control Panel, go to Settings → Plugins and click the “Install” button for Cookie Consent Banner.

## Cookie Consent Banner Overview

Use this plugin to display a configurable 'cookie consent banner' on a website.

## Configuring Cookie Consent Banner

The appearance of the banner can be customised by choosing its position, layout style and colour palette. The banner text and a 'learn more' link can also be defined. Additionally, there are options to control whether the banner is auto-injected, how the required CSS and JS assets are loaded and whether the banner should display on certain entry types/categories or in live preview.

If auto-injection is not used, the banner can be rendered by using the `{{ craft.cookieConsentBanner.addBanner() }}` variable/method.

Brought to you by [Mark @ A Digital](https://adigital.agency)
