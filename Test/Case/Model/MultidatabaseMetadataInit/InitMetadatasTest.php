<?php
/**
 * MultidatabaseMetadataInit::initMetadatas()のテスト
 *
 * @author Noriko Arai <arai@nii.ac.jp>
 * @author Tomoyuki OHNO (Ricksoft, Co., Ltd.) <ohno.tomoyuki@ricksoft.jp>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */

/**
 * MultidatabaseMetadataInit::initMetadatas()のテスト
 *
 * @author Tomoyuki OHNO (Ricksoft, Co., Ltd.) <ohno.tomoyuki@ricksoft.jp>
 * @package NetCommons\Multidatabases\Test\Case\Model\MultidatabaseMetadataInit
 */
class InitMetadatasTest extends CakeTestCase {

/**
 * Plugin name
 *
 * @var string
 */
	public $plugin = 'multidatabases';

/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = [];

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();
		$this->MultidatabaseMetadataInit = ClassRegistry::init('Multidatabases.MultidatabaseMetadataInit');
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->MultidatabaseMetadata);

		parent::tearDown();
	}

/**
 * testInitMetadatas
 * 初期メタデータの配列が全て揃っているかチェック
 *
 * @return void
 */
	public function testInitMetadatas() {
		$initMetadatas = $this->MultidatabaseMetadataInit->initMetadatas();

		$metadataKeys = [
			'id',
			'key',
			'position',
			'rank',
			'col_no',
			'type',
			'selections',
			'is_require',
			'is_title',
			'is_searchable',
			'is_sortable',
			'is_file_dl_require_auth',
			'is_visible_file_dl_counter',
			'is_visible_field_name',
			'is_visible_list',
			'is_visible_detail',
			'name'
		];

		$result = true;
		foreach ($initMetadatas as $initMetadata) {
			foreach ($metadataKeys as $metadataKey) {
				if (! isset($initMetadata[$metadataKey])) {
					$result = false;
					break;
				}
			}
		}

		$this->assertEquals(true, $result);
	}
}
