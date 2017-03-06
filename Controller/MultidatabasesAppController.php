<?php
/**
 * MultidatabasesAppController Controller
 *
 * @author Noriko Arai <arai@nii.ac.jp>
 * @author Tomoyuki OHNO (Ricksoft, Inc) <ohno.tomoyuki@ricksoft.jp>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */

App::uses('AppController', 'Controller');

/**
 * MultidatabasesAppController Controller
 *
 * @author Tomoyuki OHNO (Ricksoft, Inc) <ohno.tomoyuki@ricksoft.jp>
 * @package NetCommons\Multidatabases\Controller
 */
class MultidatabasesAppController extends AppController {

/**
 * 使用するComponent
 *
 * @var array
 */
    public $components = array(
        'Pages.PageLayout',
        'Security',
    );

/**
 * @var array use model
 */
    public $uses = array(
		'Multidatabases.Multidatabase',
		'Multidatabases.MultidatabaseMetadata',
        'Multidatabases.MultidatabaseSetting',
        'Multidatabases.MultidatabaseFrameSetting'
    );

/**
 * @var array 汎用DBタイトル
 */
	protected $_multidatabaseTitle;

/**
 * @var array フレーム設定
 */
	protected $_frameSetting;

/**
 * @var array 汎用DB設定
 */
	protected $_multidatabaseSetting;
	protected $_multidatabase;
	protected $_multidatabaseMetadata;


	protected function _prepare() {
		$this->_setupMultidatabaseTitle();
		$this->_initMultidatabase(['multidatabaseSetting']);
		$this->_loadFrameSetting();
	}

	protected function _loadFrameSetting() {
		$this->_frameSetting = $this->MultidatabaseFrameSetting->getMultidatabaseFrameSetting();
	}

/**
 * ブロック名を汎用DBタイトルとしてセットする
 *
 * @return void
 */
	protected function _setupMultidatabaseTitle() {
		$this->loadModel('Blocks.Block');
		$block = $this->Block->find('first', array(
			'recursive' => 0,
			'conditions' => array('Block.id' => Current::read('Block.id'))
		));
		$this->_multidatabaseTitle = $block['BlocksLanguage']['name'];
	}

	protected function _initMultidatabase($contains = []) {
		// 汎用DBを取得
		if (! $multidatabase = $this->Multidatabase->getMultidatabase()) {
			return $this->throwBadRequest();
		}

		// メタデータを取得
		if(! $multidatabaseMetadata = $this->MultidatabaseMetadata->getEditMetadatas($multidatabase['Multidatabase']['id'])) {
			return $this->throwBadRequest();
		}

		if(! $multidatabaseMetadataGroups = $this->MultidatabaseMetadata->getMetadataGroups($multidatabase['Multidatabase']['id'])) {
			return $this->throwBadRequest();
		}


		$this->_multidatabaseTitle = $multidatabase['Multidatabase']['name'];
		$this->set('multidatabase', $multidatabase);
		$this->set('multidatabaseMetadata', $multidatabaseMetadata);
		$this->set('multidatabaseMetadataGroups', $multidatabaseMetadataGroups);

		if (! $multidatabaseSetting = $this->MultidatabaseSetting->getMultidatabaseSetting()) {
			$multidatabaseSetting = $this->MultidatabaseSetting->createBlockSetting();
			$multidatabaseSetting['MultidatabaseSetting']['multidatabase_key'] = null;
		} else {
			$multidatabaseSetting['MultidatabaseSetting']['multidatabase_key'] = $multidatabase['Multidatabase']['key'];
		}

		$this->_multidatabase =$multidatabase;
		$this->_multidatabaseMetadata =$multidatabaseMetadata;
		$this->_multidatabaseSetting = $multidatabaseSetting;
		$this->set('multidatabaseSetting',$multidatabaseSetting['MultidatabaseSetting']);
		$this->set('userId', (int)$this->Auth->user('id'));

		return true;

	}


}
