# Cookie Consent Banner Changelog

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](http://keepachangelog.com/) and this project adheres to [Semantic Versioning](http://semver.org/).

## 2.0.3 - 2025-06-09
### Added
- Support for Craft Commerce

## 2.0.2 - 2025-06-09
### Changed
- Code cleanup to make some of the larger conditionals more understandable
- General cleanup of comment blocks

## 2.0.1 - 2024-02-27
### Changed
- Added Craft Cloud compatibility (via Pixel and Tonic) - [#48](https://github.com/a-digital/cookie-consent-banner/pull/48)
- Merged pull request to correct minimum version number - [#40](https://github.com/a-digital/cookie-consent-banner/pull/40)

## 2.0.0 - 2022-08-11
### Changed
- Update plugin for Craft 4

## 1.2.9 - 2021-11-24
### Fixed
- Change how CSS and JS options are passed to resolve conflict with Craft 3.7.22 [#37](https://github.com/a-digital/cookie-consent-banner/issues/37)

## 1.2.8 - 2021-05-28
### Fixed
- Add '#' to colour setting hex code outputs where these have been dropped in newer versions of Craft


## 1.2.7 - 2020-09-10
### Changed
- All namespaces PSR-4-compliant (thanks, [@qrazi](https://github.com/qrazi))
- Allow translation of button text (thanks, [@drifteaur](https://github.com/drifteaur))

## 1.2.6 - 2020-03-25
### Fixed
- Ensure `entry` variable is instance of craft elements entry (thanks, [@chrislkeefer](https://github.com/chrislkeefer))

## 1.2.5 - 2020-01-20
### Added
- Support for detecting/honouring `doNotTrack` headers

### Changed
- Refactor `validateCookiesAccepted()` into `validateCookieConsentSet()`

## 1.2.4 - 2020-01-20
### Fixed
- Correct function calls when banner type is opt out and consent not given

## 1.2.3 - 2019-12-09
### Fixed
- Resolved issue when using variable rather than auto-injecting banner

## 1.2.2 - 2019-11-29
### Changed
- Increased required Craft version to 3.1.0

## 1.2.1 - 2019-11-29
### Fixed
- Resolved issue with migration where table prefix incorrectly assumed

## 1.2.0 - 2019-11-29
> {warning} This is a major update which changes how config data is stored and introduces a lot of additional config options, so as the plugin is initialised on all page loads, we would **strongly** recommend taking a backup before updating and testing in dev environments first.

### Released

- Full release of 1.2.0-beta.1

## 1.2.0-beta.1 - 2019-11-12
### Added
- Add Project Config compatibility
- Update Cookie Consent library, add clutch of new settings
- Add plugin services and variable so doesn't have to be auto-injected

## 1.1.6 - 2019-07-25
### Released
- Add key param to registered `<script>` block to prevent being overwritten

## 1.1.5 - 2019-07-05
### Released
- Update decline `href` attribute to be `#` instead of empty

## 1.1.4 - 2019-07-05
### Released
- Add empty `href` attribute to 'Decline' button so works for keyboard users

## 1.1.3 - 2019-04-17
### Released
- Full release of 1.1.3-beta.1

## 1.1.3-beta.1 - 2019-02-27
### Added
- Added test for HTTP status code less than 400 so banner not shown on error pages
- Added test of content type so banner not shown on pages where the content type is not text/html

## 1.1.2 - 2018-09-06
### Fixed
- Changed order of nl2br/translate method calls on message field so messages with line breaks can be added to static translation files

## 1.1.1 - 2018-08-31
### Added
- The message / learn more text now hyphenates words across line breaks
- The message, learn more text and dismiss button text fields are now translated on output as long as a translation has been supplied

## 1.1.0 - 2018-08-29
### Fixed
- Resolved issue where JS would break if new line used in message text

## 1.0.9 - 2018-08-15
### Fixed
- Tightened up checking around excluded entry types/categories

## 1.0.8 - 2018-08-15
### Fixed
- Fixed excluded entry type/category checking when none specified

### Housekeeping
- Removed some commented code

## 1.0.7 - 2018-08-15
### Housekeeping
- Removed some redundant checking

## 1.0.6 - 2018-08-15
### Added
- Added new setting to disable in live preview
- Added new setting to exclude from certain entry types
- Added new setting to exclude from certain categories

### Fixed
- Added JS to settings template so preloaded stylesheets are properly included in admin

## 1.0.5 - 2018-07-23
### Fixed
- Added checking for if console request or getIsAjax() method does not exist

## 1.0.4 - 2018-07-13
### Fixed
- Updated SVG icon classes to be more specific


## 1.0.3 - 2018-07-11
### Added
- Added new plugin icon

## 1.0.2 - 2018-07-06
### Fixed
- Added 'as style' value to preloaded CSS

## 1.0.1 - 2018-07-06
### Fixed
- Updated documentation and support urls

## 1.0.0 - 2018-07-05
### Added
- Initial release

Brought to you by [A Digital](https://adigital.agency)
