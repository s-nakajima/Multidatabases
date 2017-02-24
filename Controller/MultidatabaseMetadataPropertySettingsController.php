<?php

/**
 * MultidatabaseMetadataPropertySettings Controller
 *
 * @author Noriko Arai <arai@nii.ac.jp>
 * @author Tomoyuki OHNO (Ricksoft, Inc) <ohno.tomoyuki@ricksoft.jp>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */
App::uses('MultidatabasesAppController', 'Multidatabases.Controller');

/**
 * MultidatabaseMetadataPropertySettings Controller
 *
 * @author Tomoyuki OHNO (Ricksoft, Inc) <ohno.tomoyuki@ricksoft.jp>
 * @package NetCommons\Multidatabase\Controller
 *
 */
class MultidatabaseMetadataPropertySettingsController extends MultidatabasesAppController {

	/**
	 * layout
	 *
	 * @var array
	 */
	public $layout = "NetCommons.setting";

	/**
	 * use models
	 *
	 * @var array
	 */
	public $uses = array(
		'Multidatabases.Multidatabase',
		'Multidatabases.MultidatabaseFrameSetting',
		'Multidatabases.MultidatabaseMetadata',
		'Blocks.Block',
	);

	/**
	 * use components
	 *
	 * @var array
	 */
	public $components = array(
		'NetCommons.Permission' => array(
			'allow' => array(
				'index,add,edit,delete' => 'block_editable',
			),
		),
		'Paginator',
	);

	/**
	 * use helpers
	 *
	 * @var array
	 */
	public $helpers = array(
		'Blocks.BlockTabs' => array(
			'mainTabs' => array(
				'block_index' => array('url' => array('controller' => 'multidatabase_blocks')),
				'frame_settings' => array('url' => array('controller' => 'multidatabase_frame_settings')),
			),
			'blockTabs' => array(
				'block_settings' => array('url' => array('controller' => 'multidatabase_blocks')),
				'metadata_settings' => array('url' => array('controller' => 'multidatabase_metadata_settings'), 'label' => ['multidatabases', 'Metadata Settings']),
				'mail_settings' => array('url' => array('controller' => 'multidatabase_mail_settings')),
				'role_permissions' => array('url' => array('controller' => 'multidatabase_block_role_permissions')),
			),
		),
		'NetCommons.DisplayNumber',
	);

	public function beforeFilter() {
		parent::beforeFilter();
	}

	/**
	 * index
	 *
	 * @return void
	 */
	public function index() {
	}

	/**
	 * メタデータを更新
	 * @return mixed
	 */
	public function update() {
		if (! $this->request->is('put') && ! $this->request->is('post')) {
			return $this->throwBadRequest();
		}

		$requestData = $this->request->data;

		$multidatabaseMetadatas['Frame'] = Current::read('Frame');


		$multidatabaseMetadatas['data'] = $this->MultidatabaseMetadata->swapMetadatas(
			$requestData['MultidatabaseMetadata']['id'],
			$requestData
		);

		$this->MultidatabaseMetadata->saveMetadata($multidatabaseMetadatas['data']);

		$this->NetCommons->setFlashNotification(
			__d('net_commons', 'Successfully saved.'), array('class' => 'success')
		);

		$this->redirect(
			array(
				'controller' => 'multidatabase_metadata_settings',
				'action' => 'edit',
				'?' => array (
					'frame_id' => $multidatabaseMetadatas['Frame']['id']
				),
				$multidatabaseMetadatas['Frame']['block_id']
			)
		);

		return;

	}



	/**
	 * edit
	 *
	 * @param string $key user_attributes.key
	 * @return void
	 */
	public function edit($metadata_id) {

		var_dump($this->params);
		var_dump($metadata_id);
		exit;


		if ($this->request->is('put')) {
			//不要パラメータ除去
			//unset($this->request->data['save'], $this->request->data['active_lang_id']);

			/*
			$result = $this->MutidatabaseMetadata->validateRequestData($this->request->data);
			if ($result === false) {
				$this->throwBadRequest();
				return;
			}*/
			$multidatabaseMetadatas['data'] = $this->request->data;
			$multidatabaseMetadatas['Frame'] = Current::read('Frame');

			$result = $this->request->data;
			$this->request->data['MutidatabaseMetadata'] = $result;

			//他言語が入力されていない場合、表示されている言語データをセット
			//$this->SwitchLanguage->setM17nRequestValue();

			//登録処理
			if ($this->UserAttribute->saveMetadata($multidatabaseMetadatas['data'])) {
				$this->NetCommons->setFlashNotification(
					__d('net_commons', 'Successfully saved.'), array('class' => 'success')
				);

				$this->redirect(
					array(
						'controller' => 'multidatabase_metadata_settings',
						'action' => 'edit',
						'?' => array (
							'frame_id' => $multidatabaseMetadatas['Frame']['id']
						),
						$multidatabaseMetadatas['Frame']['block_id']
					)
				);

				return;
			}
			$this->NetCommons->handleValidationError($this->UserAttribute->validationErrors);

		} else {
			//既存データ取得
			$this->request->data = $this->MultidatabaseMetadata->getMetadata($id);
			if (! $this->request->data) {
				$this->throwBadRequest();
				return;
			}
		}
		/*
		if ($this->request->data['UserAttributeSetting']['is_system']) {
			$this->DataTypeForm->dataTypes = $this->UserAttributeSetting->editDataTypes;
		} else {
			$this->DataTypeForm->dataTypes = $this->UserAttributeSetting->addDataTypes;
		}*/
	}


	/**
	 * delete
	 *
	 * @return void
	 */
	public function delete() {
		if ($this->request->is('delete')) {
			if ($this->Multidatabase->deleteMultidatabase($this->data)) {
				$this->redirect(NetCommonsUrl::backToIndexUrl('default_setting_action'));
				return;
			}
		}

		return $this->throwBadRequest();
	}


}
