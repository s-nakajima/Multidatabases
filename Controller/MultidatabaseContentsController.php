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
		//'ContentComments.ContentComment',	// コンテンツコメント
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
		//'NetCommons.NetCommonsWorkflow',
		//'NetCommons.NetCommonsRoomRole' => array(
		//	//コンテンツの権限設定
		//	'allowedActions' => array(
		//		'contentEditable' => array('edit', 'add'),
		//		'contentCreatable' => array('edit', 'add'),
		//	),
		//),
		'NetCommons.Permission' => array(
			//アクセスの権限
			'allow' => array(
					'add,edit,delete' => 'content_creatable',
					'approve' => 'content_comment_publishable',
			),
		),
		'Categories.Categories',
		'ContentComments.ContentComments' => array(
			'viewVarsKey' => array(
				'contentKey' => 'multidatabaseContent.MultidatabaseContent.key',
				'useComment' => 'multidatabaseSetting.use_comment'
			),
			'allow' => array('view')
		)	);

/**
 * @var array 絞り込みフィルタ保持値
 */
	protected $_filter = array(
		'categoryId' => 0,
		'status' => 0,
		'yearMonth' => 0,
	);


	public function beforeValidate() {
		parent::beforeValidate();
		exit;

	}

/**
 * beforeFilter
 *
 * @return void
 */
	public function beforeFilter() {
		parent::beforeFilter();
		$this->Security->validatePost = false;

		//$this->Security->csrfCheck=false;
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

			$this->set('multidatabase', $this->_multidatabase['Multidatabase']);
			$this->set('multidatabaseMetadatas', $this->_multidatabaseMetadatas);
			$this->set('multidatabaseContents', $multidatabaseContents);


	}

	public function detail($id = null) {

		if (! $multidatabaseContents = $this->MultidatabaseContent->getMultidatabaseContents()) {
			$this->setAction('throwBadRequest');
			return false;
		}

		$this->set('multidatabase', $this->_Multidatabase);
		$this->set('multidatabaseMetadatas', $this->_MultidatabaseMetadatas);
		$this->set('multidatabaseContents', $multidatabaseContents);

	}


	public function add() {

		$this->MultidatabaseContent->makeValidation();
		if ($this->request->is('post')) {
			$this->MultidatabaseContent->set($this->request->data);

			if (! $this->MultidatabaseContent->validates()) {
				$this->NetCommons->handleValidationError($this->MultidatabaseContent->validationErrors);
			} else {
				$this->MultidatabaseContent->create();

				$this->request->data['MultidatabaseSetting']['multidatabase_key'] = $this->_multidatabaseSetting['MultidatabaseSetting']['multidatabase_key'];

				$status = $this->Workflow->parseStatus();
				$this->request->data['BlogEntry']['status'] = $status;

				$this->request->data['MultidatabaseContent']['status'] = $status;
				$this->request->data['MultidatabaseContent']['multidatabase_id'] = $this->_multidatabase['Multidatabase']['id'];
				$this->request->data['MultidatabaseContent']['multidatabase_key'] = $this->_multidatabase['Multidatabase']['key'];
				$this->request->data['MultidatabaseContent']['block_id'] = Current::read('Block.id');
				$this->request->data['MultidatabaseContent']['language_id'] = Current::read('Language.id');

				if ($result = $this->MultidatabaseContent->saveContent($this->request->data)) {
					$url = NetCommonsUrl::actionUrl(
						[
							'controller' => 'multidatabases',
							'action' => 'index',
							'block_id' => Current::read('Block.id'),
							'frame_id' => Current::read('Frame.id'),
						]
					);
					return $this->redirect($url);
				}
			}

		}

		$this->render('form');

	}

	public function edit() {
		$this->render('form');

	}

	public function delete() {

	}


}

