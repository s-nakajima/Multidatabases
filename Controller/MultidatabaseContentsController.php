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
		$this->Auth->allow('index', 'view', 'tag', 'year_month');
		//$this->Categories->initCategories();

		$this->_prepare();
	}


    	public function index() {


			if (! $multidatabaseContents = $this->MultidatabaseContent->getMultidatabaseContents()) {
				//$this->setAction('throwBadRequest');
				//return false;
			}

			$this->set('viewMode', 'view');
			$this->set('multidatabaseContents', $multidatabaseContents);


	}

	public function detail() {

		$key = $this->params['key'];

		/*
		$conditions = $this->MultidatabaseContent->getConditions(
			Current::read('Block.id'),
			//$this->_getPermission()
		);
		exit;
*/
		$conditions['MultidatabaseContent.key'] = $key;

		$options = array(
			'conditions' => $conditions,
			'recursive' => 0,
		);


		$this->MultidatabaseContent->Behaviors->load('ContentComments.ContentComment');
		$multidatabaseContent = $this->MultidatabaseContent->find('first', $options);
		$this->MultidatabaseContent->Behaviors->unload('ContentComments.ContentComment');


		if ($multidatabaseContent) {
			$this->set('multidatabaseContent',$multidatabaseContent);
			$this->set('viewMode', 'detail');
			if ($this->_multidatabaseSetting['BlogSetting']['use_comment']) {
				$multidatabaseContentKey = $multidatabaseContent['MultidatabaseContent']['key'];
				$useCommentApproval = $this->_multidatabaseSetting['MultidatabaseSetting']['use_comment_approval'];
				if (!$this->ContentComments->comment('multidatabases', $multidatabaseContentKey,
					$useCommentApproval)) {
					return $this->throwBadRequest();
				}
			}
		} else {
			return $this->throwBadRequest();
		}

	}


	public function add() {
		if ($this->request->is('post')) {
			$data = $this->request->data;

			$status = $this->Workflow->parseStatus();

			$data['MultidatabaseContent']['status'] = $status;
			$data['MultidatabaseContent']['multidatabase_id'] = $this->_multidatabase['Multidatabase']['id'];
			$data['MultidatabaseContent']['multidatabase_key'] = $this->_multidatabase['Multidatabase']['key'];
			$data['MultidatabaseContent']['block_id'] = Current::read('Block.id');
			$data['MultidatabaseContent']['language_id'] = Current::read('Language.id');

			$data['MultidatabaseContent'] = Hash::remove($data['MultidatabaseContent'], 'id');

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

				return $this->redirect($url);
			}
			$this->NetCommons->handleValidationError($this->MultidatabaseContent->validationErrors);
		}

		$this->render('form');
	}

	public function edit() {
		$this->render('form');

	}

	public function delete() {

	}


}

