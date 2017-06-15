<?php
/**
 * MultidatabaseMetadataEditCnv::cnvMetaSelToJson()のテスト
 *
 * @author Noriko Arai <arai@nii.ac.jp>
 * @author Tomoyuki OHNO (Ricksoft, Co., Ltd.) <ohno.tomoyuki@ricksoft.jp>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */

/**
 * MultidatabaseMetadataEditCnv::cnvMetaSelToJson()のテスト
 *
 * @author Tomoyuki OHNO (Ricksoft, Co., Ltd.) <ohno.tomoyuki@ricksoft.jp>
 * @package NetCommons\Multidatabases\Test\Case\Model\MultidatabaseMetadataInit
 */
class CnvMetaSelToJsonTest extends CakeTestCase {

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
		$this->MultidatabaseMetadataEditCnv = ClassRegistry::init('Multidatabases.MultidatabaseMetadataEditCnv');
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->MultidatabaseMetadataEditCnv);

		parent::tearDown();
	}

/**
 * testMetaSelToJson
 *
 * @return void
 */
	public function testMetaSelToJson() {
		// JSON変換前
		$beforeValue = ['算数', '理科', '社会', '総合', '音楽', '図工', '体育'];

		// JSON変換後（期待される値）
		$afterValue = json_encode($beforeValue);

		$metadata['selections'] = $beforeValue;
		$result = $this->MultidatabaseMetadataEditCnv->cnvMetaSelToJson($metadata);

		$this->assertEquals($afterValue, $result);
	}

}
