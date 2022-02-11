<?php

namespace adigital\cookieconsentbanner\migrations;
use adigital\cookieconsentbanner\CookieConsentBanner;

use Craft;
use craft\db\Migration;
use craft\db\Query;
use craft\services\Plugins;
use craft\commerce\db\Table as CommerceTable;

/**
 * m220211_113840_add_commerce_producttypes migration.
 */
class m220211_113840_add_commerce_producttypes extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
      $plugin = CookieConsentBanner::$plugin;
		$commercePlugin = Craft::$app->getPlugins()->getPlugin('commerce');
		if ($commercePlugin) {
			$settings = CookieConsentBanner::$plugin->getSettings();
			$productTypes = (new Query())
					->select(['id', 'uid'])
					->from([CommerceTable::PRODUCTTYPES])
					->pairs();

			if (is_array($settings->excluded_product_types)) {
				foreach ($settings->excluded_product_types as $productTypeId) {
					if (in_array($productTypeId, $settings->excluded_product_types)) {
						$settings->excluded_product_types[array_search($productTypeId, $settings->excluded_product_types)] = $productTypes[str_replace("id_", "", $productTypeId)];
					}
				}
			}
			// Update the plugin's settings in the project config
			Craft::$app->getProjectConfig()->set(Plugins::CONFIG_PLUGINS_KEY . '.' . $plugin->handle . '.settings', $settings->toArray());
		} else {
			return false;
		}
   }
    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        echo "m220211_113840_add_commerce_producttypes cannot be reverted.\n";
        return false;
    }
}
