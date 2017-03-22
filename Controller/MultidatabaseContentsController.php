<?php
/**
 * MultidatabaseContentsController Controller
 * 汎用データベース コンテンツ処理関連コントローラー
 *
 * @author Noriko Arai <arai@nii.ac.jp>
 * @author Tomoyuki OHNO (Ricksoft Co., Ltd.) <ohno.tomoyuki@ricksoft.jp>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */

App::uses('MultidatabasesAppController', 'Multidatabases.Controller');

/**
 * MultidatabaseContentsController Controller
 *
 * @author Tomoyuki OHNO (Ricksoft Co., Ltd.) <ohno.tomoyuki@ricksoft.jp>
 * @package NetCommons\Multidatabases\Controller
 */
class MultidatabaseContentsController extends MultidatabasesAppController {

/**
 * @var array use models
 */
	public $uses = [
		'Multidatabases.MultidatabaseContent',
		'Workflow.WorkflowComment',
		'Categories.Category',
		'NetCommons.NetCommonsTime',
	];

/**
 * @var array helpers
 */
	public $helpers = [
		'NetCommons.BackTo',
		'Workflow.Workflow',
		'Likes.Like',
		'ContentComments.ContentComment' => [
			'viewVarsKey' => [
				'contentKey' => 'multidatabaseContent.MultidatabaseContent.key',
				'contentTitleForMail' => 'multidatabaseContent.MultidatabaseContent.key',
				'useComment' => 'multidatabaseSetting.use_comment',
				'useCommentApproval' => 'multidatabaseSetting.use_comment_approval',
			],
		],
		'NetCommons.NetCommonsForm',
		'NetCommons.NetCommonsTime',
		'NetCommons.SnsButton',
		'NetCommons.TitleIcon',
		'NetCommons.DisplayNumber',
	];

/**
 * Components
 *
 * @var array
 */
	public $components = [
		'Paginator',
		'NetCommons.Permission' => [
			//アクセスの権限
			'allow' => [
				'add,edit,delete' => 'content_creatable',
				'approve' => 'content_comment_publishable',
			],
		],
		'ContentComments.ContentComments' => [
			'viewVarsKey' => [
				'contentKey' => 'multidatabaseContent.MultidatabaseContent.key',
				'useComment' => 'multidatabaseSetting.use_comment',
			],
			'allow' => ['detail'],
		],
		'Files.Download',
	];

/**
 * @var array 絞り込みフィルタ保持値
 */
	protected $_filter = [
		'status' => 0,
	];

/**
 * Get Permission
 * 権限の取得
 *
 * @return array
 */
	protected function _getPermission() {
		$permissionNames = [
			'content_readable',
			'content_creatable',
			'content_editable',
			'content_publishable',
		];
		$permission = [];
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
		$this->Security->csrfCheck = false;

		if (!Current::read('Block.id')) {
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
 * Show Index
 * 汎用データベース コンテンツインデックス
 *
 * @return void
 */
	public function index() {
		$conditions = [];
		// 一覧を表示する
		$this->__list($conditions);
	}

/**
 * Make sort condition
 * 汎用データベース コンテンツ一覧ソート処理（条件設定）
 *
 * @return string Order句の内容
 */
	private function __sortList() {
		$pagerNamed = $this->Paginator->Controller->params->named;

		if (
			isset($pagerNamed['sort_col']) &&
			$pagerNamed['sort_col'] !== '0' &&
			(
				strstr($pagerNamed['sort_col'], 'value') <> false ||
				in_array($pagerNamed['sort_col'], ['created', 'modified'])
			)
		) {
			if (strstr($pagerNamed['sort_col'], '_desc')) {
				$sortCol = str_replace('_desc', '', $pagerNamed['sort_col']);
				$sortColDir = 'desc';
			} else {
				$sortCol = $pagerNamed['sort_col'];
				$sortColDir = 'asc';
			}
		} else {
			$sortCol = 'created';
			$sortColDir = 'desc';
		}

		$sortOrder = 'MultidatabaseContent.' . $sortCol . ' ' . $sortColDir;

		return $sortOrder;
	}

	private function __limitSelect() {
		$pagerNamed = $this->Paginator->Controller->params->named;
		$result = [];

		foreach ($this->_multidatabaseMetadata as $metadata) {
			if (
				$metadata['type'] === 'select' ||
				$metadata['type'] === 'checkbox'
			) {
				$valueKey = 'value' . $metadata['col_no'];
				if (
					isset($pagerNamed[$valueKey]) &&
					$pagerNamed[$valueKey] !== '0'
				) {
					foreach ($metadata['selections'] as $selection) {
						if (md5($selection) === $pagerNamed[$valueKey]) {
							$result['MultidatabaseContent.' . $valueKey . ' like'] = "%{$selection}%";
							break;
						}
					}
				}
			}
		}
		return $result;
	}

/**
 * Show Contents List
 * 汎用データべース コンテンツ一覧表示
 *
 * @param array $extraConditions 追加条件
 * @return void
 */
	private function __list($extraConditions = []) {
		$permission = $this->_getPermission();

		$conditions = $this->MultidatabaseContent->getConditions(
			Current::read('Block.id'),
			$permission
		);

		if ($extraConditions) {
			$conditions = Hash::merge($conditions, $extraConditions);
		}

		$limitSelect = $this->__limitSelect();

		if (! empty($limitSelect)) {
			$conditions = Hash::merge($conditions,
				['and' => $limitSelect]
			);
		}

		$this->Paginator->settings = array_merge(
			$this->Paginator->settings,
			[
				'conditions' => $conditions,
				'limit' => $this->_frameSetting['MultidatabaseFrameSetting']['content_per_page'],
				'order' => $this->__sortList()
			]
		);

		$this->MultidatabaseContent->recursive = 0;
		$this->MultidatabaseContent->Behaviors->load('ContentComments.ContentComment');
		$this->set('multidatabaseContents', $this->Paginator->paginate());
		$this->MultidatabaseContent->Behaviors->unload('ContentComments.ContentComment');
		$this->MultidatabaseContent->recursive = -1;

		$this->set('viewMode', 'list');
	}

/**
 * Show Content Detail
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

		$options = [
			'conditions' => $conditions,
			'recursive' => 0,
		];

		$this->MultidatabaseContent->recursive = 0;
		$this->MultidatabaseContent->Behaviors->load('ContentComments.ContentComment');
		$multidatabaseContent = $this->MultidatabaseContent->find('first', $options);
		$this->MultidatabaseContent->Behaviors->unload('ContentComments.ContentComment');
		$this->MultidatabaseContent->recursive = -1;

		if ($multidatabaseContent) {
			$this->set('multidatabaseContent', $multidatabaseContent);
			$this->set('viewMode', 'detail');
			if ($this->_multidatabaseSetting['MultidatabaseSetting']['use_comment']) {
				if ($this->request->is('post')) {
					$multidatabaseContentKey
						= $multidatabaseContent['MultidatabaseContent']['key'];
					$useCommentApproval
						= $this->_multidatabaseSetting['MultidatabaseSetting']['use_comment_approval'];
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
 * Add Content
 * 汎用データベース コンテンツ追加
 *
 * @return void
 */
	public function add() {
		$this->set('isEdit', false);

		if ($this->request->is('post')) {
			$url = $this->__save();
			if (!$url) {
				$this->NetCommons->handleValidationError($this->MultidatabaseContent->validationErrors);
			} else {
				return $this->redirect($url);
			}
			$multidatabaseContent = $this->request->data['MultidatabaseContent'];
		} else {
			$multidatabaseContent = [];
		}

		$this->set('multidatabaseContent', $multidatabaseContent);

		$this->render('form');
	}

/**
 * Edit Content
 * 汎用データベース コンテンツ編集
 *
 * @return void
 */
	public function edit() {
		$this->set('isEdit', true);
		$key = $this->params['key'];

		$permission = $this->_getPermission();

		$conditions = $this->MultidatabaseContent->getConditions(
			Current::read('Block.id'),
			$permission
		);

		$conditions['MultidatabaseContent.key'] = $key;


		//$multidatabaseContent = $this->MultidatabaseContent->find('first', $options);
		$multidatabaseContent = $this->MultidatabaseContent->getEditData($conditions);

		if (
			! $multidatabaseContent ||
			$this->MultidatabaseContent->canEditWorkflowContent($multidatabaseContent) === false
		) {
			return $this->throwBadRequest();
		}

		if ($this->request->is(['post', 'put'])) {
			$url = $this->__save();
			if (!$url) {
				$this->NetCommons->handleValidationError($this->MultidatabaseContent->validationErrors);
			} else {
				return $this->redirect($url);
			}

		} else {
			$this->request->data = $multidatabaseContent;
		}

		$this->set('multidatabaseContent', $multidatabaseContent['MultidatabaseContent']);
		$this->set('isDeletable',
			$this->MultidatabaseContent->canDeleteWorkflowContent($multidatabaseContent)
		);
		$comments = $this->MultidatabaseContent->getCommentsByContentKey(
			$multidatabaseContent['MultidatabaseContent']['key']
		);
		$this->set('comments', $comments);

		$this->render('form');
	}

/**
 * Save Content
 * データを保存する
 *
 * @return bool|string
 */
	private function __save() {
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
					'key' => $result['MultidatabaseContent']['key'],

				]
			);
			return $url;
		}
		return false;
	}

/**
 * Delete Content
 * 汎用データベース コンテンツ削除
 *
 * @return string
 * @throws InternalErrorException
 */
	public function delete() {
		$this->request->allowMethod('post', 'delete');

		$key = $this->request->data['MultidatabaseContent']['key'];
		$multidatabaseContent = $this->MultidatabaseContent->getWorkflowContents('first', [
			'recursive' => 0,
			'conditions' => [
				'MultidatabaseContent.key' => $key,
			],
		]);

		// 権限チェック
		if ($this->MultidatabaseContent->canDeleteWorkflowContent($multidatabaseContent) === false) {
			return $this->throwBadRequest();
		}

		if ($this->MultidatabaseContent->deleteContentByKey($key) === false) {
			throw new InternalErrorException(__d('net_commons', 'Internal Server Error'));
		}

		return $this->redirect(
			NetCommonsUrl::actionUrl(
				[
					'controller' => 'multidatabase_contents',
					'action' => 'index',
					'frame_id' => Current::read('Frame.id'),
					'block_id' => Current::read('Block.id'),
				]
			)
		);
	}

/**
 * File Download
 * ファイルダウンロード
 *
 * @throws NotFoundException
 * @return void
 */
	public function download() {
		$contentId = (int)$this->request->params['pass'][0];
		$colNo = (int)$this->request->query['col_no'];
		$field = 'value' . $colNo . '_attach';

		$options = array(
			'field' => $field,
			'download' => true,
			//'name' => '',
		);

		return $this->Download->doDownload($contentId, $options);
	}

	public function search() {
		$this->render('search');
	}
}


