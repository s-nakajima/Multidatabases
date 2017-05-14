<?php

/**
 * MultidatabaseFrameSettings Controller
 * 汎用データベース フレーム設定コントローラー
 * （表示件数の設定）
 *
 * @author Noriko Arai <arai@nii.ac.jp>
 * @author Tomoyuki OHNO (Ricksoft Co., Ltd.) <ohno.tomoyuki@ricksoft.jp>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */
App::uses('MultidatabasesAppController', 'Multidatabases.Controller');

/**
 * MultidatabaseFrameSettings Controller
 *
 * @author Tomoyuki OHNO (Ricksoft Co., Ltd.) <ohno.tomoyuki@ricksoft.jp>
 * @package NetCommons\Multidatabases\Controller
 */
class MultidatabaseFrameSettingsController extends MultidatabasesAppController {

/**
 * layout
 *
 * @var array
 */
	public $layout = 'NetCommons.setting';

/**
 * use models
 *
 * @var array
 */
	public $uses = [
		'Multidatabases.MultidatabaseFrameSetting',
		'Multidatabases.MultidatabaseContentSearch',
	];

/**
 * use components
 *
 * @var array
 */
	public $components = [
		'NetCommons.Permission' => [
			//アクセスの権限
			'allow' => [
				'edit' => 'page_editable',
			],
		],
	];

/**
 * use helpers
 *
 * @var array
 */
	public $helpers = [
		'Blocks.BlockForm',
		'Blocks.BlockTabs' => [
			'mainTabs' => [
				'block_index' => ['url' => ['controller' => 'multidatabase_blocks']],
				'frame_settings' => ['url' => ['controller' => 'multidatabase_frame_settings']],
			],
			'blockTabs' => [
				'block_settings' => ['url' => ['controller' => 'multidatabase_blocks']],
				'mail_settings' => ['url' => ['controller' => 'multidatabase_mail_settings']],
				'role_permissions' => ['url' => ['controller' => 'multidatabase_block_role_permissions']],
				'content_imports' => [
					'url' => ['controller' => 'multidatabase_content_imports'],
					'label' => ['multidatabases', 'Import contents']
				],
				'content_exports' => [
					'url' => ['controller' => 'multidatabase_content_exports'],
					'label' => ['multidatabases', 'Export contents']
				],
			],
		],
		'NetCommons.DisplayNumber',
	];

/**
 * edit
 *
 * @return void
 */
	public function edit() {
		$this->_prepare();
		if ($this->request->is('put') || $this->request->is('post')) {
			if ($this->MultidatabaseFrameSetting->saveMultidatabaseFrameSetting($this->data)) {
				$this->redirect(NetCommonsUrl::backToPageUrl(true));
				return;
			}
			$this->NetCommons->handleValidationError($this->MultidatabaseFrameSetting->validationErrors);
		} else {
			$this->request->data = $this->MultidatabaseFrameSetting->getMultidatabaseFrameSetting(true);
			$this->request->data['Frame'] = Current::read('Frame');
		}
	}
}
