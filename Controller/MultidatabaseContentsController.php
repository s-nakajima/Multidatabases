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


/**
 * beforeFilter
 *
 * @return void
 */
	public function beforeFilter() {
		parent::beforeFilter();
		if (! Current::read('Block.id')) {
			$this->setAction('emptyRender');
			return false;
		}

		$multidatabaseFrameSetting = $this->MultidatabaseFrameSetting->getMultidatabaseFrameSetting(true);
		$this->set('multidatabaseFrameSetting', $multidatabaseFrameSetting['MultidatabaseFrameSetting']);

		// ゲストアクセスOKのアクションを設定
		$this->Auth->allow('index', 'view', 'tag', 'year_month');
		//$this->Categories->initCategories();
	}


    	public function index() {

			if (! $multidatabase = $this->Multidatabase->getMultidatabase()) {
				$this->setAction('throwBadRequest');
				return false;
			}
			$this->set('multidatabase', $multidatabase['Multidatabase']);

			$multidatabaseContents = false;
			$this->set('multidatabaseContents', $multidatabaseContents);


	}

	public function detail() {

	}

	public function add() {
		$this->set('isEdit', false);
		$this->_prepare();


		if ($this->request->is('post')) {
			$this->MultidatabaseContent->create();

			$this->request->data['MultidatabaseSetting']['key'] = $this->_multidatabaseSetting['MultidatabaseSetting']['key'];
			$status = $this->Workflow->parseStatus();
			$this->request->data['MultidatabaseContent']['status'] = $status;
			$this->request->data['MultidatabaseContent']['block_id'] = Current::read('Block.id');
			$this->request->data['MultidatabaseContent']['Language_id'] = Current::read('Language.id');

			if ($result = $this->MultidatabaseContent->saveContent($this->request->data)) {
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
		} else {
			//$this->request->data = $multidatabaseContent;
		}

		$this->render('form');

	}

	public function edit() {
		$this->render('form');

	}

	public function delete() {

	}


}

