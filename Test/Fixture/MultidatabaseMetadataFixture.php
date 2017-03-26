<?php
/**
 * MultidatabaseMetadataFixture
 *
 * @author Noriko Arai <arai@nii.ac.jp>
 * @author Tomoyuki OHNO (Ricksoft Co., Ltd.) <ohno.tomoyuki@ricksoft.jp>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */

/**
 * Summary for MultidatabaseMetadataFixture
 */
class MultidatabaseMetadataFixture extends CakeTestFixture {

/**
 * Table name
 *
 * @var string
 */
	public $table = 'multidatabase_metadatas';

/**
 * Fields
 *
 * @var array
 */
	public $fields = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false, 'key' => 'primary', 'comment' => 'ID'),
		'key' => array('type' => 'string', 'null' => false, 'default' => null, 'collate' => 'utf8_general_ci', 'comment' => '汎用DBキー(plugin key)', 'charset' => 'utf8'),
		'multidatabase_id' => array('type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'unsigned' => false, 'comment' => '汎用DBID'),
		'language_id' => array('type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'unsigned' => false, 'comment' => '言語ID'),
		'name' => array('type' => 'string', 'null' => false, 'default' => null, 'collate' => 'utf8_general_ci', 'comment' => '項目名', 'charset' => 'utf8'),
		'col_no' => array('type' => 'integer', 'null' => true, 'default' => '0', 'length' => 3, 'unsigned' => false, 'comment' => 'カラムNo'),
		'type' => array('type' => 'integer', 'null' => false, 'default' => '1', 'length' => 4, 'unsigned' => false, 'comment' => '属性 0:画像,1:テキスト,2:テキストエリア,3:リンク,4:選択式（択一）,5:ファイル,6:WYSIWYGテキスト,7:自動採番,8:メール,9:日付,10:登録日時,11:更新日時,12:選択式（複数）'),
		'rank' => array('type' => 'integer', 'null' => false, 'default' => '0', 'unsigned' => false, 'comment' => '表示順'),
		'position' => array('type' => 'integer', 'null' => false, 'default' => '0', 'length' => 4, 'unsigned' => false, 'comment' => '表示位置 0:上,1:中左,2:中右,3:下'),
		'selections' => array('type' => 'text', 'null' => true, 'default' => null, 'collate' => 'utf8_general_ci', 'comment' => '選択肢', 'charset' => 'utf8'),
		'is_require' => array('type' => 'boolean', 'null' => false, 'default' => '0', 'comment' => '必須か 0:必須でない,1:必須'),
		'is_searchable' => array('type' => 'boolean', 'null' => false, 'default' => '0', 'comment' => '検索対象とするか 0:対象外,1:対象'),
		'is_sortable' => array('type' => 'boolean', 'null' => false, 'default' => '0', 'comment' => 'ソート対象とするか 0:対象外,1:対象'),
		'is_file_dl_require_auth' => array('type' => 'boolean', 'null' => false, 'default' => '0', 'comment' => 'ファイルDL時に認証させるか 0:認証させる,1:認証させない'),
		'is_visible_list' => array('type' => 'boolean', 'null' => false, 'default' => '0', 'comment' => '一覧に表示するか 0:表示しない,1:表示する'),
		'is_visible_detail' => array('type' => 'boolean', 'null' => false, 'default' => '0', 'comment' => '詳細に表示するか 0:表示しない,1:表示する'),
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
			'key' => 'Lorem ipsum dolor sit amet',
			'multidatabase_id' => 1,
			'language_id' => 1,
			'name' => 'Lorem ipsum dolor sit amet',
			'col_no' => 1,
			'type' => 1,
			'rank' => 1,
			'position' => 1,
			'selections' => 'Lorem ipsum dolor sit amet, aliquet feugiat. Convallis morbi fringilla gravida, phasellus feugiat dapibus velit nunc, pulvinar eget sollicitudin venenatis cum nullam, vivamus ut a sed, mollitia lectus. Nulla vestibulum massa neque ut et, id hendrerit sit, feugiat in taciti enim proin nibh, tempor dignissim, rhoncus duis vestibulum nunc mattis convallis.',
			'is_require' => 1,
			'is_searchable' => 1,
			'is_sortable' => 1,
			'is_file_dl_require_auth' => 1,
			'is_visible_list' => 1,
			'is_visible_detail' => 1,
			'created_user' => 1,
			'created' => '2016-11-17 09:20:25',
			'modified_user' => 1,
			'modified' => '2016-11-17 09:20:25'
		),
	);

}
