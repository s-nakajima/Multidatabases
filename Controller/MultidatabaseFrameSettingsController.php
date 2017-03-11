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
    public $uses = array(
        'Multidatabases.MultidatabaseFrameSetting',
    );

    /**
     * use components
     *
     * @var array
     */
	public $components = array(
		'NetCommons.Permission' => array(
			//アクセスの権限
			'allow' => array(
				'edit' => 'page_editable',
			),
		),
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


    /**
     * edit
     *
     * @return void
     */
    public function edit() {
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
