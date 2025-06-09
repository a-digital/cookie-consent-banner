<?php

namespace adigital\cookieconsentbanner\migrations;
use adigital\cookieconsentbanner\CookieConsentBanner;

use Craft;
use craft\db\Migration;
use craft\db\Query;
use craft\db\Table;
use craft\services\Plugins;

class m190902_000000_migrate_settings_to_uid extends Migration
{
    // Public Methods
    // =========================================================================
    public function safeUp() : void
    {
        $plugin = CookieConsentBanner::$plugin;
        $settings = CookieConsentBanner::$plugin->getSettings();
        $categories = (new Query())
            ->select(['{{%categories}}.id as id', '{{%elements}}.uid as uid'])
            ->from([Table::CATEGORIES])
            ->innerJoin([Table::ELEMENTS], '{{%categories}}.id = {{%elements}}.id')
            ->pairs();
        $entryTypes = (new Query())
            ->select(['id', 'uid'])
            ->from([Table::ENTRYTYPES])
            ->pairs();
        if (is_array($settings->excluded_categories)) {
            foreach ($settings->excluded_categories as $categoryId) {
                if (in_array($categoryId, $settings->excluded_categories)) {
                    $settings->excluded_categories[array_search($categoryId, $settings->excluded_categories)] = $categories[str_replace("id_", "", $categoryId)];
                }
            }
        }
        if (is_array($settings->excluded_entry_types)) {
            foreach ($settings->excluded_entry_types as $entryTypeId) {
                if (in_array($entryTypeId, $settings->excluded_entry_types)) {
                    $settings->excluded_entry_types[array_search($entryTypeId, $settings->excluded_entry_types)] = $entryTypes[str_replace("id_", "", $entryTypeId)];
                }
            }
        }
        // Update the plugin's settings in the project config
        Craft::$app->getProjectConfig()->set(Plugins::CONFIG_PLUGINS_KEY . '.' . $plugin->handle . '.settings', $settings->toArray());
    }

    public function safeDown() : bool
    {
        echo "m190902_000000_migrate_settings_to_uid cannot be reverted.\n";
        return false;
    }
}
