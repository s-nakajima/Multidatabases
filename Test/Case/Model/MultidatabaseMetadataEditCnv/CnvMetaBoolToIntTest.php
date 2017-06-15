<?php
/**
 * MultidatabaseMetadataEditCnv::cnvMetaBoolToInt()のテスト
 *
 * @author Noriko Arai <arai@nii.ac.jp>
 * @author Tomoyuki OHNO (Ricksoft, Co., Ltd.) <ohno.tomoyuki@ricksoft.jp>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */

/**
 * MultidatabaseMetadataEditCnv::cnvMetaBoolToInt()のテスト
 *
 * @author Tomoyuki OHNO (Ricksoft, Co., Ltd.) <ohno.tomoyuki@ricksoft.jp>
 * @package NetCommons\Multidatabases\Test\Case\Model\MultidatabaseMetadataInit
 */
class CnvMetaBoolToIntTest extends CakeTestCase {

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
 * testStrOn
 *
 * @return void
 */
	public function testStrOn() {
		$key = 'is_require';
		$metadata[$key] = 'on';
		$result = $this->MultidatabaseMetadataEditCnv->cnvMetaBoolToInt($metadata);

		$this->assertEquals(1, $result[$key]);
	}

/**
 * testIntOne
 *
 * @return void
 */
	public function testIntOne() {
		$key = 'is_require';
		$metadata[$key] = 1;
		$result = $this->MultidatabaseMetadataEditCnv->cnvMetaBoolToInt($metadata);

		$this->assertEquals(1, $result[$key]);
	}

/**
 * testBoolTrue
 *
 * @return void
 */
	public function testBoolTrue() {
		$key = 'is_require';
		$metadata[$key] = true;
		$result = $this->MultidatabaseMetadataEditCnv->cnvMetaBoolToInt($metadata);

		$this->assertEquals(1, $result[$key]);
	}

/**
 * testStrOn
 *
 * @return void
 */
	public function testStrOff() {
		$key = 'is_require';
		$metadata[$key] = 'off';
		$result = $this->MultidatabaseMetadataEditCnv->cnvMetaBoolToInt($metadata);

		$this->assertEquals(0, $result[$key]);
	}

/**
 * testIntOne
 *
 * @return void
 */
	public function testIntZero() {
		$key = 'is_require';
		$metadata[$key] = 0;
		$result = $this->MultidatabaseMetadataEditCnv->cnvMetaBoolToInt($metadata);

		$this->assertEquals(0, $result[$key]);
	}

/**
 * testBoolTrue
 *
 * @return void
 */
	public function testBoolFalse() {
		$key = 'is_require';
		$metadata[$key] = false;
		$result = $this->MultidatabaseMetadataEditCnv->cnvMetaBoolToInt($metadata);

		$this->assertEquals(0, $result[$key]);
	}

/**
 * testStrOther
 *
 * @return void
 */
	public function testStrOther() {
		$key = 'hogehoge';
		$metadata[$key] = 'fugafuga';
		$result = $this->MultidatabaseMetadataEditCnv->cnvMetaBoolToInt($metadata);

		$this->assertEquals('fugafuga', $result[$key]);
	}

/**
 * testCnvKeys
 *
 * @return void
 */
	public function testCnvKeys() {
		$key = 'is_require';
		$metadata[$key] = 'on';
		$result = $this->MultidatabaseMetadataEditCnv->cnvMetaBoolToInt($metadata);
		$this->assertEquals(1, $result[$key]);

		$key = 'is_title';
		$metadata[$key] = 'on';
		$result = $this->MultidatabaseMetadataEditCnv->cnvMetaBoolToInt($metadata);
		$this->assertEquals(1, $result[$key]);

		$key = 'is_searchable';
		$metadata[$key] = 'on';
		$result = $this->MultidatabaseMetadataEditCnv->cnvMetaBoolToInt($metadata);
		$this->assertEquals(1, $result[$key]);

		$key = 'is_sortable';
		$metadata[$key] = 'on';
		$result = $this->MultidatabaseMetadataEditCnv->cnvMetaBoolToInt($metadata);
		$this->assertEquals(1, $result[$key]);

		$key = 'is_file_dl_require_auth';
		$metadata[$key] = 'on';
		$result = $this->MultidatabaseMetadataEditCnv->cnvMetaBoolToInt($metadata);
		$this->assertEquals(1, $result[$key]);

		$key = 'is_visible_file_dl_counter';
		$metadata[$key] = 'on';
		$result = $this->MultidatabaseMetadataEditCnv->cnvMetaBoolToInt($metadata);
		$this->assertEquals(1, $result[$key]);

		$key = 'is_visible_field_name';
		$metadata[$key] = 'on';
		$result = $this->MultidatabaseMetadataEditCnv->cnvMetaBoolToInt($metadata);
		$this->assertEquals(1, $result[$key]);

		$key = 'is_visible_list';
		$metadata[$key] = 'on';
		$result = $this->MultidatabaseMetadataEditCnv->cnvMetaBoolToInt($metadata);
		$this->assertEquals(1, $result[$key]);

		$key = 'is_visible_detail';
		$metadata[$key] = 'on';
		$result = $this->MultidatabaseMetadataEditCnv->cnvMetaBoolToInt($metadata);
		$this->assertEquals(1, $result[$key]);
	}

}
