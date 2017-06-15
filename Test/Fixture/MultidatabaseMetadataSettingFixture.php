<?php
/**
 * MultidatabaseMetadataSettingFixture
 *
 * @author Noriko Arai <arai@nii.ac.jp>
 * @author Tomoyuki OHNO (Ricksoft Co., Ltd.) <ohno.tomoyuki@ricksoft.jp>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */

/**
 * Summary for MultidatabaseMetadataSettingFixture
 */
class MultidatabaseMetadataSettingFixture extends CakeTestFixture {

/**
 * Table name
 *
 * @var string
 */
	public $table = 'multidatabase_metadata_settings';

/**
 * Records
 *
 * @var array
 */
	public $records = [
		[
			'id' => '1',
			'block_id' => '1',
			'key' => 'multidatabase_1',
			'auto_number_sequence' => '0',
			'created_user' => '1',
			'created' => '2016/04/01 1:10:20',
			'modified_user' => '1',
			'modified' => '2016/04/01 1:10:20',
		],
		[
			'id' => '2',
			'block_id' => '2',
			'key' => 'multidatabase_2',
			'auto_number_sequence' => '0',
			'created_user' => '1',
			'created' => '2016/04/01 1:10:20',
			'modified_user' => '1',
			'modified' => '2016/04/01 1:10:20',
		],
		[
			'id' => '3',
			'block_id' => '3',
			'key' => 'multidatabase_3',
			'auto_number_sequence' => '0',
			'created_user' => '1',
			'created' => '2016/04/01 1:10:20',
			'modified_user' => '1',
			'modified' => '2016/04/01 1:10:20',
		],
		[
			'id' => '4',
			'block_id' => '4',
			'key' => 'multidatabase_4',
			'auto_number_sequence' => '0',
			'created_user' => '1',
			'created' => '2016/04/01 1:10:20',
			'modified_user' => '1',
			'modified' => '2016/04/01 1:10:20',
		],
		[
			'id' => '5',
			'block_id' => '5',
			'key' => 'multidatabase_5',
			'auto_number_sequence' => '0',
			'created_user' => '1',
			'created' => '2016/04/01 1:10:20',
			'modified_user' => '1',
			'modified' => '2016/04/01 1:10:20',
		],
		[
			'id' => '6',
			'block_id' => '6',
			'key' => 'multidatabase_6',
			'auto_number_sequence' => '0',
			'created_user' => '2',
			'created' => '2016/04/01 1:10:20',
			'modified_user' => '2',
			'modified' => '2016/04/01 1:10:20',
		],
		[
			'id' => '7',
			'block_id' => '7',
			'key' => 'multidatabase_7',
			'auto_number_sequence' => '0',
			'created_user' => '2',
			'created' => '2016/04/01 1:10:20',
			'modified_user' => '2',
			'modified' => '2016/04/01 1:10:20',
		],
		[
			'id' => '8',
			'block_id' => '8',
			'key' => 'multidatabase_8',
			'auto_number_sequence' => '0',
			'created_user' => '2',
			'created' => '2016/04/01 1:10:20',
			'modified_user' => '2',
			'modified' => '2016/04/01 1:10:20',
		],
		[
			'id' => '9',
			'block_id' => '9',
			'key' => 'multidatabase_9',
			'auto_number_sequence' => '0',
			'created_user' => '2',
			'created' => '2016/04/01 1:10:20',
			'modified_user' => '2',
			'modified' => '2016/04/01 1:10:20',
		],
		[
			'id' => '10',
			'block_id' => '10',
			'key' => 'multidatabase_10',
			'auto_number_sequence' => '0',
			'created_user' => '2',
			'created' => '2016/04/01 1:10:20',
			'modified_user' => '2',
			'modified' => '2016/04/01 1:10:20',
		],
	];

/**
 * Initialize the fixture.
 *
 * @return void
 */
	public function init() {
		require_once App::pluginPath('Links') . 'Config' . DS . 'Schema' . DS . 'schema.php';
		$this->fields = (new LinksSchema())->tables[Inflector::tableize($this->name)];
		parent::init();
	}
}
