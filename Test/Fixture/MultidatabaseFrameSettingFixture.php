<?php
/**
 * MultidatabaseFrameSettingFixture
 *
 * @author Noriko Arai <arai@nii.ac.jp>
 * @author Tomoyuki OHNO (Ricksoft Co., Ltd.) <ohno.tomoyuki@ricksoft.jp>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */

/**
 * Summary for MultidatabaseFrameSettingFixture
 */
class MultidatabaseFrameSettingFixture extends CakeTestFixture {

/**
 * Fields
 *
 * @var array
 */
	public $fields = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false, 'key' => 'primary', 'comment' => 'ID'),
		'frame_key' => array('type' => 'string', 'null' => false, 'default' => null, 'collate' => 'utf8_general_ci', 'comment' => 'フレームKey', 'charset' => 'utf8'),
		'content_per_page' => array('type' => 'integer', 'null' => false, 'default' => '10', 'unsigned' => false, 'comment' => '表示件数 1件,5件,10件,20件,50件,100件'),
		'default_sort_type' => array('type' => 'integer', 'null' => false, 'default' => '0', 'length' => 4, 'unsigned' => false, 'comment' => '表示順 0:登録日時,1:更新日時,2:メタデータ'),
		'default_sort_order' => array('type' => 'boolean', 'null' => false, 'default' => '1', 'comment' => '並び順 0:昇順,1:降順'),
		'multidatabase_metadata_sort_id' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false, 'comment' => 'ソートキーとするメタデータID'),
		'created_user' => array('type' => 'integer', 'null' => true, 'default' => '0', 'unsigned' => false, 'comment' => '作成者'),
		'created' => array('type' => 'datetime', 'null' => true, 'default' => null, 'comment' => '作成日時'),
		'modified_user' => array('type' => 'integer', 'null' => true, 'default' => '0', 'unsigned' => false, 'comment' => '更新者'),
		'modified' => array('type' => 'datetime', 'null' => true, 'default' => null, 'comment' => '更新日時'),
		'indexes' => array(
			'PRIMARY' => array('column' => 'id', 'unique' => 1)
		),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_general_ci', 'engine' => 'InnoDB')
	);

/**
 * Records
 *
 * @var array
 */
	public $records = array(
		array(
			'id' => 1,
			'frame_key' => 'Lorem ipsum dolor sit amet',
			'content_per_page' => 1,
			'default_sort_type' => 1,
			'default_sort_order' => 1,
			'multidatabase_metadata_sort_id' => 1,
			'created_user' => 1,
			'created' => '2016-11-17 09:20:12',
			'modified_user' => 1,
			'modified' => '2016-11-17 09:20:12'
		),
	);

}
