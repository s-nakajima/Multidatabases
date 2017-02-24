<?php

/**
 * MultidatabaseMetadataSettings Controller
 *
 * @author Noriko Arai <arai@nii.ac.jp>
 * @author Tomoyuki OHNO (Ricksoft, Inc) <ohno.tomoyuki@ricksoft.jp>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */
App::uses('MultidatabasesAppController', 'Multidatabases.Controller');

/**
 * MultidatabaseMetadataSettings Controller
 *
 * @author Tomoyuki OHNO (Ricksoft, Inc) <ohno.tomoyuki@ricksoft.jp>
 * @package NetCommons\Multidatabase\Controller
 *
 */
class MultidatabaseMetadataSettingsController extends MultidatabasesAppController {

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
		'Blocks.BlockForm',
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
		$this->Security->unlockedActions = array('add');
	}

	/**
	 * index
	 *
	 * @return void
	 */
	public function index() {
		$this->Paginator->settings = array(
			'Multidatabase' => $this->Multidatabase->getBlockIndexSettings()
		);
		$multidatabases = $this->Paginator->paginate('Multidatabase');
		if (!$multidatabases) {
			$this->view = 'Blocks.Blocks/not_found';
			return;
		}
		$this->set('multidatabases', $multidatabases);
		$this->request->data['Frame'] = Current::read('Frame');
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

		$this->MultidatabaseMetadata->updateMetadata($multidatabaseMetadatas['data']);

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

	public function add() {

	}

	/**
	 * メタデータ項目の編集
	 * @return void
	 */
	public function edit() {

		$multidatabaseMetadatas['data'] = $this->MultidatabaseMetadata->getMetadatas();
		$multidatabaseMetadatas['count'] = $this->MultidatabaseMetadata->countMetadatas($multidatabaseMetadatas['data']);
		$multidatabaseMetadatas['Frame'] = Current::read('Frame');

		$this->set('multidatabaseMetadatas', $multidatabaseMetadatas);

	}

	public function addField() {

	}


	/**
	 * delete
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

	/**
	 * メタデータのフィールドテンプレートを返す
	 * @return void
	 */
	public function getMetadataFieldTemplate() {

		$this->viewClass = 'Json';

		if (
			!isset($this->request->query['tmp_id']) ||
			empty((int)$this->request->query['tmp_id'])
		) {
			$result['result'] = 'fail';
		} else {
			$tmp_id = (int)$this->request->query['tmp_id'];
			$metadata = $this->MultidatabaseMetadata->getEmptyMetadata();

			$metadata['MultidatabaseMetadata']['tmp_id'] = $tmp_id;
			$view = new View($this, false);
			$content = $view->element('MultidatabaseBlocks/edit_form_field',array('currentMetadata' => $metadata['MultidatabaseMetadata']));

			$result['result'] = 'success';
			$result['metadata'] = $metadata;
			$result['content'] = $content;
		}


		$this->set(compact('result'));
		$this->set('_serialize', 'result');

	}


}
