<?php
/**
 * All MultidatabaseMetadata Model Test suite
 *
 * @author Noriko Arai <arai@nii.ac.jp>
 * @author Tomoyuki OHNO <ohno.tomoyuki@ricksoft.jp>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */

App::uses('NetCommonsTestSuite', 'NetCommons.TestSuite');

/**
 * All MultidatabaseMetadata Model Test suite
 *
 * @author Tomoyuki OHNO <ohno.tomoyuki@ricksoft.jp>
 * @package NetCommons\Multidatabases\Test\Case\Model\MultidatabaseMetadata
 * @codeCoverageIgnore
 */
class AllMultidatabaseModelMultidatabaseMetadataTest extends NetCommonsTestSuite {

/**
 * Test suite
 *
 * @return NetCommonsTestSuite
 * @codeCoverageIgnore
 */
	public static function suite() {
		$name = preg_replace('/^All([\w]+)Test$/', '$1', __CLASS__);
		$suite = new NetCommonsTestSuite(sprintf('All %s tests', $name));
		$suite->addTestDirectoryRecursive(__DIR__ . DS . 'CabinetFile');
		return $suite;
	}

}
