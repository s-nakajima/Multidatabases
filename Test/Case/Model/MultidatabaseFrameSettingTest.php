<?php
/**
 * MultidatabaseFrameSetting Test Case
 *
* @author Noriko Arai <arai@nii.ac.jp>
* @author Tomoyuki OHNO (Ricksoft Co., Ltd.) <ohno.tomoyuki@ricksoft.jp>
* @link http://www.netcommons.org NetCommons Project
* @license http://www.netcommons.org/license.txt NetCommons License
* @copyright Copyright 2014, NetCommons Project
 */

App::uses('MultidatabaseFrameSetting', 'Multidatabases.Model');

/**
 * Summary for MultidatabaseFrameSetting Test Case
 */
class MultidatabaseFrameSettingTest extends CakeTestCase {

/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array(
		'plugin.multidatabases.multidatabase_frame_setting',
		'plugin.multidatabases.multidatabase_metadata_sort',
		'plugin.multidatabases.user',
		'plugin.multidatabases.role',
		'plugin.multidatabases.user_role_setting',
		'plugin.multidatabases.users_language',
		'plugin.multidatabases.language'
	);

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();
		$this->MultidatabaseFrameSetting = ClassRegistry::init('Multidatabases.MultidatabaseFrameSetting');
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->MultidatabaseFrameSetting);

		parent::tearDown();
	}

}
