# Cookie Consent Banner Changelog

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](http://keepachangelog.com/) and this project adheres to [Semantic Versioning](http://semver.org/).

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
