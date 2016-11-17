<?php
/**
 * Multidatabase Test Case
 *
* @author Noriko Arai <arai@nii.ac.jp>
* @author Tomoyuki OHNO (Ricksoft Inc.) <ohno.tomoyuki@ricksoft.jp>
* @link http://www.netcommons.org NetCommons Project
* @license http://www.netcommons.org/license.txt NetCommons License
* @copyright Copyright 2014, NetCommons Project
 */

App::uses('Multidatabase', 'Multidatabases.Model');

/**
 * Summary for Multidatabase Test Case
 */
class MultidatabaseTest extends CakeTestCase {

/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array(
		'plugin.multidatabases.multidatabase',
		'plugin.multidatabases.block',
		'plugin.multidatabases.user',
		'plugin.multidatabases.role',
		'plugin.multidatabases.user_role_setting',
		'plugin.multidatabases.users_language',
		'plugin.multidatabases.language',
		'plugin.multidatabases.multidatabase_metadata_title',
		'plugin.multidatabases.multidatabase_content',
		'plugin.multidatabases.multidatabase_metadata'
	);

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();
		$this->Multidatabase = ClassRegistry::init('Multidatabases.Multidatabase');
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->Multidatabase);

		parent::tearDown();
	}

}
