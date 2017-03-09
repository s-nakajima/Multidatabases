<?php
/**
 * MultidatabaseContentsController Controller
 *
 * @author Noriko Arai <arai@nii.ac.jp>
 * @author Tomoyuki OHNO (Ricksoft, Inc) <ohno.tomoyuki@ricksoft.jp>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */

App::uses('MultidatabasesAppController', 'Multidatabases.Controller');

/**
 * MultidatabaseContentsController Controller
 *
 * @author Tomoyuki OHNO (Ricksoft, Inc) <ohno.tomoyuki@ricksoft.jp>
 * @package NetCommons\Multidatabases\Controller
 */
class MultidatabaseContentsController extends MultidatabasesAppController {

    /**
 * @var array use models
 */
	public $uses = array(
		'Multidatabases.MultidatabaseContent',
		'Workflow.WorkflowComment',
		'Categories.Category',
		'NetCommons.NetCommonsTime',
	);

/**
 * @var array helpers
 */
	public $helpers = array(
		'NetCommons.BackTo',
		'Workflow.Workflow',
		'Likes.Like',
		'ContentComments.ContentComment' => array(
			'viewVarsKey' => array(
				'contentKey' => 'multidatabaseContent.MultidatabaseContent.key',
				'useComment' => 'multidatabaseSetting.use_comment',
				'useCommentApproval' => 'multidatabaseSetting.use_comment_approval'
			)
		),
		'NetCommons.NetCommonsForm',
		'NetCommons.NetCommonsTime',
		'NetCommons.SnsButton',
		'NetCommons.TitleIcon',
		'NetCommons.DisplayNumber',
	);

/**
 * Components
 *
 * @var array
 */
	public $components = array(
		'Paginator',
		'NetCommons.Permission' => array(
			//アクセスの権限
			'allow' => array(
					'add,edit,delete' => 'content_creatable',
					'approve' => 'content_comment_publishable',
			),
		),
		'ContentComments.ContentComments' => array(
			'viewVarsKey' => array(
				'contentKey' => 'multidatabaseContent.MultidatabaseContent.key',
				'useComment' => 'multidatabaseSetting.use_comment',
			),
			'allow' => array('detail')
		)	);

/**
 * @var array 絞り込みフィルタ保持値
 */
	protected $_filter = array(
		'categoryId' => 0,
		'status' => 0,
		'yearMonth' => 0,
	);

/**
 * 権限の取得
 *
 * @return array
 */
	protected function _getPermission() {
		$permissionNames = array(
			'content_readable',
			'content_creatable',
			'content_editable',
			'content_publishable',
		);
		$permission = array();
		foreach ($permissionNames as $key) {
			$permission[$key] = Current::permission($key);
		}
		return $permission;
	}


/**
 * beforeFilter
 *
 * @return void
 */
	public function beforeFilter() {
		parent::beforeFilter();
		$this->Security->validatePost = false;
		$this->Security->csrfCheck=false;

		if (! Current::read('Block.id')) {
			$this->setAction('emptyRender');
			return false;
		}

		$multidatabaseFrameSetting = $this->MultidatabaseFrameSetting->getMultidatabaseFrameSetting(true);
		$this->set('multidatabaseFrameSetting', $multidatabaseFrameSetting['MultidatabaseFrameSetting']);

		// ゲストアクセスOKのアクションを設定
		$this->Auth->allow('index', 'detail');

		$this->_prepare();
	}


/**
 * 汎用データベース コンテンツ一覧表示
 *
 * @return void
 */
 	public function index() {
 		$conditions = [];
		$this->_list($conditions);

	}

/**
 * 汎用データべース 一覧表示
 *
 * @param array $extraCondition
 * @return void
 */
	private function _list($extraConditions = []) {

		$permission = $this->_getPermission();

		$conditions = $this->MultidatabaseContent->getConditions(
			Current::read('Block.id'),
			$permission
		);

		if ($extraConditions) {
			$conditions = Hash::merge($conditions, $extraConditions);
		}

		$this->Paginator->settings = array_merge(
			$this->Paginator->settings,
			array(
				'conditions' => $conditions,
				'limit' => $this->_frameSetting['MultidatabaseFrameSetting']['content_per_page'],
				'order' => 'MultidatabaseContent.created DESC',
			)
		);

		$this->MultidatabaseContent->recursive = 0;
		$this->MultidatabaseContent->Behaviors->load('ContentComments.ContentComment');
		$this->set('multidatabaseContents', $this->Paginator->paginate());
		$this->MultidatabaseContent->Behaviors->unload('ContentComments.ContentComment');
		$this->MultidatabaseContent->recursive = -1;

		$this->set('viewMode', 'list');
	}


/**
 * 汎用データベース コンテンツ詳細表示
 *
 * @return void
 */
	public function detail() {

		$key = $this->params['key'];

		$permission = $this->_getPermission();

		$conditions = $this->MultidatabaseContent->getConditions(
			Current::read('Block.id'),
			$permission
		);

		$conditions['MultidatabaseContent.key'] = $key;

		$options = array(
			'conditions' => $conditions,
			'recursive' => 0,
		);

		$this->MultidatabaseContent->recursive = 0;
		$this->MultidatabaseContent->Behaviors->load('ContentComments.ContentComment');
		$multidatabaseContent = $this->MultidatabaseContent->find('first', $options);
		$this->MultidatabaseContent->Behaviors->unload('ContentComments.ContentComment');
		$this->MultidatabaseContent->recursive = -1;


		if ($multidatabaseContent) {
			$this->set('multidatabaseContent',$multidatabaseContent);
			$this->set('viewMode', 'detail');
			if ($this->_multidatabaseSetting['MultidatabaseSetting']['use_comment']) {
				if ($this->request->is('post')) {
					$multidatabaseContentKey = $multidatabaseContent['MultidatabaseContent']['key'];
					$useCommentApproval = $this->_multidatabaseSetting['MultidatabaseSetting']['use_comment_approval'];
					if (!$this->ContentComments->comment('multidatabases', $multidatabaseContentKey,
						$useCommentApproval)
					) {
						return $this->throwBadRequest();
					}
				}
			}
		} else {
			return $this->throwBadRequest();
		}

	}

/**
 * 汎用データベース コンテンツ追加
 *
 * @return void
 */
	public function add() {
		$this->set('isEdit', false);

		if ($this->request->is('post')) {
			$url = $this->_save();
			if (! $url) {
				$this->NetCommons->handleValidationError($this->MultidatabaseContent->validationErrors);
			}
			return $this->redirect($url);
		}

		$this->render('form');
	}

/**
 * 汎用データベース コンテンツ編集
 *
 * @return void
 */
	public function edit() {
		$this->set('isEdit', true);
		$key = $this->params['key'];

		$this->MultidatabaseContent->recursive = 0;
		$options = [
			'conditions' => [
				'MultidatabaseContent.key' => $key
			],
			'recursive' => 0,
		];

		$multidatabaseContent = $this->MultidatabaseContent->find('first', $options);

		if (empty($multidatabaseContent)) {
			return $this->throwBadRequest();
		}

		if ($this->MultidatabaseContent->canEditWorkflowContent($multidatabaseContent) === false) {
			return $this->throwBadRequest();
		}

		if ($this->request->is(['post','put'])) {
			$url = $this->_save();
			if (! $url) {
				$this->NetCommons->handleValidationError($this->MultidatabaseContent->validationErrors);
			}
			return $this->redirect($url);
		} else {
			$this->request->data = $multidatabaseContent;
		}

		$this->set('multidatabaseContent',$multidatabaseContent);
		$this->set('isDeletable', $this->MultidatabaseContent->canDeleteWorkflowContent($multidatabaseContent));
		$comments = $this->MultidatabaseContent->getCommentsByContentKey($multidatabaseContent['MultidatabaseContent']['key']);
		$this->set('comments',$comments);

		$this->render('form');

	}

/**
 * データを保存する
 *
 * @return boolean|string
 */

	private function _save() {
		$this->MultidatabaseContent->create();

		$this->request->data['MultidatabaseContent']['multidatabase_key'] =
			$this->_multidatabaseSetting['MultidatabaseSetting']['multidatabase_key'];

		$status = $this->Workflow->parseStatus();
		$this->request->data['MultidatabaseContent']['block_id'] = Current::read('Block.id');
		$this->request->data['MultidatabaseContent']['language_id'] = Current::read('Language.id');
		$this->request->data['MultidatabaseContent']['status'] = $status;

		$data = $this->request->data;

		unset($data['MultidatabaseContent']['id']);


		if ($result = $this->MultidatabaseContent->saveContent($data)) {
			$url = NetCommonsUrl::actionUrl(
				[
					'controller' => 'multidatabase_contents',
					'action' => 'detail',
					'block_id' => Current::read('Block.id'),
					'frame_id' => Current::read('Frame.id'),
					'key' => $result['MultidatabaseContent']['key']

				]
			);

			return $url;

		}

		return false;

	}

/**
 * 汎用データベース コンテンツ削除
 *
 * @return void
 */
	public function delete() {
		$this->request->allowMethod('post', 'delete');

		$key = $this->request->data['MultidatabaseContent']['key'];
		$multidatabaseContent = $this->MultidatabaseContent->getWorkflowContents('first', array(
			'recursive' => 0,
			'conditions' => array(
				'MultidatabaseContent.key' => $key
			)
		));

		// 権限チェック
		if ($this->MultidatabaseContent->canDeleteWorkflowContent($multidatabaseContent) === false) {
			return $this->throwBadRequest();
		}

		if ($this->MultidatabaseContent->deleteContentByKey($key) === false) {
			throw new InternalErrorException(__d('net_commons', 'Internal Server Error'));
		}
		return $this->redirect(
			NetCommonsUrl::actionUrl(
				array(
					'controller' => 'multidatabase_contents',
					'action' => 'index',
					'frame_id' => Current::read('Frame.id'),
					'block_id' => Current::read('Block.id')
				)
			)
		);
	}


}

