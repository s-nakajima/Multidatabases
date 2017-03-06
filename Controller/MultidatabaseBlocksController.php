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
                'mail_settings' => array('url' => array('controller' => 'multidatabase_mail_settings')),
                'role_permissions' => array('url' => array('controller' => 'multidatabase_block_role_permissions')),
            ),
        ),
        'Likes.Like',
    );

    public function beforeFilter() {
        parent::beforeFilter();

		if (
			$this->action === 'add' ||
			$this->action === 'edit'
		) {
			// メタデータフォーム動的生成のため自動Validateしない
			$this->Security->validatePost = false;
		}

    }

/**
 * index
 *
 * @return void
 */
    public function index() {
        $this->Paginator->settings = [
            'Multidatabase' => $this->Multidatabase->getBlockIndexSettings()
        ];

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

		if ($this->request->is('put') || $this->request->is('post')) {
			if ($this->Multidatabase->saveMultidatabase($this->data)) {
				return $this->redirect(NetCommonsUrl::backToIndexUrl('default_setting_action'));
			}
			return;
		}

		$multidatabases = $this->Multidatabase->createMultidatabase();
		$multidatabases['MultidatabaseMetadata'] = $this->MultidatabaseMetadata->getInitMetadatas();

		$this->request->data = $multidatabases;
		$this->request->data = Hash::merge(
			$this->request->data,
			$this->MultidatabaseFrameSetting->getMultidatabaseFrameSetting(true)
		);

		$this->set('multidatabaseMetadata', $multidatabases['MultidatabaseMetadata']);
		$this->request->data['Frame'] = Current::read('Frame');

    }



/**
 * edit
 *
 * @return void
 */
    public function edit() {



        if ($this->request->is('put')) {

            if ($this->Multidatabase->saveMultidatabase($this->data)) {
                return $this->redirect(NetCommonsUrl::backToIndexUrl('default_setting_action'));
            }
            return;
        }


		$multidatabases = $this->Multidatabase->getMultidatabase();

        if (! $multidatabases) {
			return $this->throwBadRequest();
		}

        $multidatabases['MultidatabaseMetadata'] = $this->MultidatabaseMetadata->getEditMetadatas($multidatabases['Multidatabase']['id']);

		if (! $multidatabases['MultidatabaseMetadata']) {
			return $this->throwBadRequest($multidatabases['MultidatabaseMetadata']);
		}

		$this->request->data = $multidatabases;
		$this->request->data = Hash::merge(
			$this->request->data,
			$this->MultidatabaseFrameSetting->getMultidatabaseFrameSetting(true)
		);

		$this->set('multidatabaseMetadata', $multidatabases['MultidatabaseMetadata']);
		$this->request->data['Frame'] = Current::read('Frame');
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
