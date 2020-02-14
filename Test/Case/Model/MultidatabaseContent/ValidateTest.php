<?php
/**
 * ValidateTest.php
 *
 * @author   Ryuji AMANO <ryuji@ryus.co.jp>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 */

App::uses('NetCommonsModelTestCase', 'NetCommons.TestSuite');

/**
 * Class MultidatabaseContentValidateTest
 */
final class MultidatabaseContentValidateTest extends CakeTestCase {

/**
 * @var array fixtures
 */
	public $fixtures = [
		'plugin.site_manager.site_setting',
		'plugin.multidatabases.multidatabase_content',
	];

/**
 * testValidate
 *
 * @return void
 */
	public function testValidate() {
		$this->__setupMock();

		$data = [
			'MultidatabaseContent' => [
				'key' => '',
				'multidatabase_id' => '1',
				'value1' => 'Title',
				'status_' => '',
				'value2' =>
					[
						'name' => 'viewpmsg.php',
						'type' => 'text/php',
						'tmp_name' => '/tmp/phprbaqM5',
						'error' => 0,
						'size' => 696,
					],
				'multidatabase_key' => '9a409bc0949828c9b4d87fe24d23db8e',
				'block_id' => '113',
				'language_id' => '2',
				'status' => '1',
			]
		];
		/** @var MultidatabaseContent $contentModel */
		$contentModel = ClassRegistry::init('Multidatabases.MultidatabaseContent');
		// Workflowを無効化
		$contentModel->Behaviors->unload('Workflow');
		$contentModel->set($data);
		$result = $contentModel->validates();

		$this->assertFalse($result);

		$this->assertArrayHasKey('value2', $contentModel->validationErrors);
	}

/**
 * testValidate
 *
 * @return void
 */
	public function testValidateWhenUnloadAttachment() {
		$this->__setupMock();

		$data = [
			'MultidatabaseContent' => [
				'key' => '',
				'multidatabase_id' => '1',
				'value1' => 'Title',
				'status_' => '',
				'value2' => '',
				'multidatabase_key' => '9a409bc0949828c9b4d87fe24d23db8e',
				'block_id' => '113',
				'language_id' => '2',
				'status' => '1',
			]
		];
		/** @var MultidatabaseContent $contentModel */
		$contentModel = ClassRegistry::init('Multidatabases.MultidatabaseContent');
		// Workflowを無効化
		$contentModel->Behaviors->unload('Workflow');

		// AttachmentBehaviorをunload(インポート時の状況)
		$contentModel->Behaviors->unload('Files.Attachment');

		$contentModel->set($data);
		$result = $contentModel->validates();
		// ファイル添付が任意入呂kうなのでエラーなし.
		$this->assertTrue($result);
	}

/**
 * __setupMock
 *
 * @return void
 */
	private function __setupMock() {
		$mdbData = [
			'Multidatabase' => array (
				'id' => '1',
				'block_id' => '1',
				'key' => 'db1',
				'name' => 'DBタイトル',
			)
		];
		$mdbMock = $this->getMockForModel('Multidatabases.Multidatabase');
		$mdbMock->expects($this->once())
			->method('getMultidatabase')
			->will($this->returnValue($mdbData));

		$metadata = [
				[
					'id' => 1,
					'key' => 'db1',
					'multidatabase_id' => 1,
					'language_id' => 2,
					'name' => 'タイトル',
					'col_no' => 1,
					'type' => 'text',
					'rank' => 0,
					'position' => 0,
					'selections' =>
						[
						],
					'is_require' => 1,
					'is_title' => 1,
					'is_searchable' => 1,
					'is_sortable' => 1,
					'is_file_dl_require_auth' => 0,
					'is_visible_file_dl_counter' => 0,
					'is_visible_field_name' => 1,
					'is_visible_list' => 1,
					'is_visible_detail' => 1,
					'created_user' => 1,
					'created' => '2020-01-19 06:05:59',
					'modified_user' => 5,
					'modified' => '2020-02-03 02:17:36',
				],
				[
					'id' => 2,
					'key' => 'db1',
					'multidatabase_id' => 1,
					'language_id' => 2,
					'name' => 'ファイル',
					'col_no' => 2,
					'type' => 'file',
					'rank' => 0,
					'position' => 1,
					'selections' =>
						[
						],
					'is_require' => 0,
					'is_title' => 0,
					'is_searchable' => 0,
					'is_sortable' => 0,
					'is_file_dl_require_auth' => 1,
					'is_visible_file_dl_counter' => 1,
					'is_visible_field_name' => 1,
					'is_visible_list' => 1,
					'is_visible_detail' => 1,
					'created_user' => 1,
					'created' => '2020-01-19 06:05:59',
					'modified_user' => 5,
					'modified' => '2020-02-03 02:17:36',
				],
		];
		$mdbMock = $this->getMockForModel('Multidatabases.MultidatabaseMetadata');
		$mdbMock->expects($this->once())
			->method('getEditMetadatas')
			->with($this->equalTo(1))
			->will($this->returnValue($metadata));
	}
}
