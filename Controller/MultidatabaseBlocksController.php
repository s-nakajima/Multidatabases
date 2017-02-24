<?php

/**
 * MultidatabaseBlocks Controller
 *
 * @author Noriko Arai <arai@nii.ac.jp>
 * @author Tomoyuki OHNO (Ricksoft, Inc) <ohno.tomoyuki@ricksoft.jp>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */
App::uses('MultidatabasesAppController', 'Multidatabases.Controller');

/**
 * MultidatabaseBlocks Controller
 *
 * @author Tomoyuki OHNO (Ricksoft, Inc) <ohno.tomoyuki@ricksoft.jp>
 * @package NetCommons\Multidatabase\Controller
 *
 */
class MultidatabaseBlocksController extends MultidatabasesAppController {

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
		'Multidatabases.MultidatabaseFrameSetting',
		'Multidatabases.MultidatabaseMetadata',
		'DataTypes.DataTypeChoice',
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
        'Blocks.BlockIndex',
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
        'Likes.Like',
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
 * add
 *
 * @return void
 */
    public function add() {
        $this->view = 'edit';

		if ($this->request->is('put')  || $this->request->is('post')) {
			var_dump($this->data);
			exit;
			if ($this->Multidatabase->saveMultidatabase($this->data)) {
				return $this->redirect(NetCommonsUrl::backToIndexUrl('default_setting_action'));
			}
			return;
		}

		$multidatabaseMetadatas['data'] = $this->MultidatabaseMetadata->getInitMetadatas();
		$multidatabaseMetadatas['count'] = $this->MultidatabaseMetadata->countMetadatas($multidatabaseMetadatas['data']);
		$multidatabases['data'] = $this->Multidatabase->createMultidatabase();

		$this->request->data = $multidatabases['data'];

		$this->request->data = Hash::merge(
			$this->request->data, $this->MultidatabaseFrameSetting->getMultidatabaseFrameSetting(true)
		);

		// 表示データをSessionへ格納
		$this->set('multidatabases', $multidatabases);
		$this->set('multidatabaseMetadatas', $multidatabaseMetadatas);
		$this->request->data['Frame'] = Current::read('Frame');


    }

    public function add_field($position) {

	}



/**
 * edit
 *
 * @return void
 */
    public function edit() {
        if ($this->request->is('put')) {
            //登録処理
            if ($this->Multidatabase->saveMultidatabase($this->data)) {
                return $this->redirect(NetCommonsUrl::backToIndexUrl('default_setting_action'));
            }
            $this->NetCommons->handleValidationError($this->Multidatabase->validationErrors);
        } else {
            //表示処理(初期データセット)
            if (!$multidatabase = $this->Multidatabase->getMultidatabase()) {
                return $this->throwBadRequest();
            }
            $this->request->data = Hash::merge($this->request->data, $multidatabase);
            $this->request->data = Hash::merge(
                    $this->request->data, $this->MultidatabaseFrameSetting->getMultidatabaseFrameSetting(true)
            );
            $this->request->data['Frame'] = Current::read('Frame');
        }
    }

/**
 * delete
 *
 * @return void
 */
    public function delete() {
        if ($this->request->is('delete')) {
            if ($this->Multidatabase->deleteMultidatabase($this->data)) {
                return $this->redirect(NetCommonsUrl::backToIndexUrl('default_setting_action'));
            }
        }

        return $this->throwBadRequest();
    }




}
