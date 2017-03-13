<?php
/**
 * PluginRecords Migration
 * プラグインの追加のためのMigration
 *
 * @author Tomoyuki OHNO (Ricksoft Co., Ltd.) <ohno.tomoyuki@ricksoft.jp>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */

App::uses('NetCommonsMigration', 'NetCommons.Config/Migration');
App::uses('Space', 'Rooms.Model');

/**
 * PluginRecords Migration
 *
 * @author Tomoyuki OHNO (Ricksoft, Co., LTD.) <ohno.tomoyuki@ricksoft.jp>
 * @package NetCommons\Multidatabases\Config\Migration
 *
 */
class PluginRecords extends NetCommonsMigration {

/**
 * Migration description
 *
 * @var string
 */
	public $description = 'plugin_records';

/**
 * Actions to be performed
 *
 * @var array $migration
 */
	public $migration = [
		'up' => [],
		'down' => [],
	];

/**
 * plugin data
 *
 * @var array $migration
 */
	public $records = [
		'Plugin' => [
			//日本語
			[
				'language_id' => '2',
				'key' => 'multidatabases',
				'namespace' => 'netcommons/multidatabases',
				'name' => '汎用データベース',
				'type' => 1,
				'default_action' => 'multidatabase_contents/index',
				'default_setting_action' => 'multidatabase_blocks/index',
				'display_topics' => 1,
				'display_search' => 1,
			],
			//英語
			[
				'language_id' => '1',
				'key' => 'multidatabases',
				'namespace' => 'netcommons/multidatabases',
				'name' => 'MultiDatabases',
				'type' => 1,
				'default_action' => 'multidatabase_contents/index',
				'default_setting_action' => 'multidatabase_blocks/index',
				'display_topics' => 1,
				'display_search' => 1,
			],
		],
		'PluginsRole' => [
			[
				'role_key' => 'room_administrator',
				'plugin_key' => 'multidatabases',
			],
		],
		'PluginsRoom' => [
			//パブリックスペース
			['room_id' => '2', 'plugin_key' => 'multidatabases', ],
			//プライベートスペース
			['room_id' => '3', 'plugin_key' => 'multidatabases', ],
			//グループスペース
			['room_id' => '4', 'plugin_key' => 'multidatabases', ],
		],
	];

/**
 * Before migration callback
 *
 * @param string $direction Direction of migration process (up or down)
 * @return bool Should process continue
 */
	public function before($direction) {
		$pluginName = $this->records['Plugin'][0]['key'];
		$this->records['PluginsRoom'] = [
			//サイト全体
			[
				'room_id' => Space::getRoomIdRoot(Space::WHOLE_SITE_ID, 'Room'),
				'plugin_key' => $pluginName,
			],
			//パブリックスペース
			[
				'room_id' => Space::getRoomIdRoot(Space::PUBLIC_SPACE_ID, 'Room'),
				'plugin_key' => $pluginName,
			],
			//プライベートスペース
			[
				'room_id' => Space::getRoomIdRoot(Space::PRIVATE_SPACE_ID, 'Room'),
				'plugin_key' => $pluginName,
			],
			//グループスペース
			[
				'room_id' => Space::getRoomIdRoot(Space::COMMUNITY_SPACE_ID, 'Room'),
				'plugin_key' => $pluginName,
			],
		];

		return true;
	}

/**
 * After migration callback
 *
 * @param string $direction Direction of migration process (up or down)
 * @return bool Should process continue
 */
	public function after($direction) {
		$this->loadModels([
			'Plugin' => 'PluginManager.Plugin',
		]);

		if ($direction === 'down') {
			$this->Plugin->uninstallPlugin($this->records['Plugin'][0]['key']);

			return true;
		}

		foreach ($this->records as $model => $records) {
			if (!$this->updateRecords($model, $records)) {
				return false;
			}
		}

		return true;
	}
}
