<?php
/**
 * MultidatabaseMetadata Model
 *
 * @property Multidatabase $Multidatabase
 * @property Language $Language
 *
* @author Noriko Arai <arai@nii.ac.jp>
* @author Tomoyuki OHNO (Ricksoft Inc.) <ohno.tomoyuki@ricksoft.jp>
* @link http://www.netcommons.org NetCommons Project
* @license http://www.netcommons.org/license.txt NetCommons License
* @copyright Copyright 2014, NetCommons Project
 */

App::uses('MultidatabasesAppModel', 'Multidatabases.Model');
App::uses('CakeSession', 'Model/Datasourse');
/**
 * Summary for MultidatabaseMetadata Model
 */
class MultidatabaseMetadata extends MultidatabasesAppModel {

/**
 * Use database config
 *
 * @var string
 */
	public $useDbConfig = 'master';

/**
 * Use table
 *
 * @var mixed False or table name
 */
	public $useTable = 'multidatabase_metadatas';

	public $dataType = [
		'id' => 'numeric',
		'key' => 'string',
		'name' => 'string',
		'multidatabase_id' => 'numeric',
		'language_id' => 'numeric',
		'position' => 'numeric',
		'rank' => 'numeric',
		'col_no' => 'numeric',
		'type' => 'string',
		'selections' => 'string',
		'is_require' => 'boolean',
		'is_searchable' => 'boolean',
		'is_sortable' => 'boolean',
		'is_file_dl_require_auth' => 'boolean',
		'is_visible_list' => 'boolean',
		'is_visible_detail' => 'boolean',
		'created' => 'datetime',
		'created_user' => 'numeric',
		'modified' => 'numeric',
		'modified_user' => 'numeric',
	];

/**
 * Validation rules
 *
 * @var array
 */
	public $validate = array(
		'key' => array(
			'notBlank' => array(
				'rule' => array('notBlank'),
				//'message' => 'Your custom message here',
				//'allowEmpty' => false,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),
		'multidatabase_id' => array(
			'numeric' => array(
				'rule' => array('numeric'),
				//'message' => 'Your custom message here',
				//'allowEmpty' => false,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),
		'language_id' => array(
			'numeric' => array(
				'rule' => array('numeric'),
				//'message' => 'Your custom message here',
				//'allowEmpty' => false,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),
		'name' => array(
			'notBlank' => array(
				'rule' => array('notBlank'),
				//'message' => 'Your custom message here',
				//'allowEmpty' => false,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),
		'type' => array(
			'numeric' => array(
				'rule' => array('numeric'),
				//'message' => 'Your custom message here',
				//'allowEmpty' => false,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),
		'rank' => array(
			'numeric' => array(
				'rule' => array('numeric'),
				//'message' => 'Your custom message here',
				//'allowEmpty' => false,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),
		'position' => array(
			'numeric' => array(
				'rule' => array('numeric'),
				//'message' => 'Your custom message here',
				//'allowEmpty' => false,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),
		'is_require' => array(
			'boolean' => array(
				'rule' => array('boolean'),
				//'message' => 'Your custom message here',
				//'allowEmpty' => false,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),
		'is_searchable' => array(
			'boolean' => array(
				'rule' => array('boolean'),
				//'message' => 'Your custom message here',
				//'allowEmpty' => false,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),
		'is_sortable' => array(
			'boolean' => array(
				'rule' => array('boolean'),
				//'message' => 'Your custom message here',
				//'allowEmpty' => false,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),
		'is_file_dl_require_auth' => array(
			'boolean' => array(
				'rule' => array('boolean'),
				//'message' => 'Your custom message here',
				//'allowEmpty' => false,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),
		'is_visible_list' => array(
			'boolean' => array(
				'rule' => array('boolean'),
				//'message' => 'Your custom message here',
				//'allowEmpty' => false,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),
		'is_visible_detail' => array(
			'boolean' => array(
				'rule' => array('boolean'),
				//'message' => 'Your custom message here',
				//'allowEmpty' => false,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),
	);

	//The Associations below have been created with all possible keys, those that are not needed can be removed



/**
 * belongsTo associations
 *
 * @var array
 */
	public $belongsTo = array(
		'Multidatabase' => array(
			'className' => 'Multidatabase',
			'foreignKey' => 'multidatabase_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		),
		'Language' => array(
			'className' => 'Language',
			'foreignKey' => 'language_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		)
	);


	/**
	 * Constructor. Binds the model's database table to the object.
	 *
	 * @param bool|int|string|array $id Set this ID for this model on startup,
	 * can also be an array of options, see above.
	 * @param string $table Name of database table to use.
	 * @param string $ds DataSource connection name.
	 * @see Model::__construct()
	 * @SuppressWarnings(PHPMD.BooleanArgumentFlag)
	 */
	public function __construct($id = false, $table = null, $ds = null) {
		parent::__construct($id, $table, $ds);

		$this->loadModels([
			'multidatabase' => 'Multidatabases.Multidatabase',
			'MultidatabaseFrameSetting' => 'Multidatabases.MultidatabaseFrameSetting',
			'MultidatabaseSetting' => 'Multidatabases.MultidatabaseSetting',
		]);
	}



	/**
	 * メタデータを1件取得する
	 * @param array $metadatas
	 * @param null $myMetadataId
	 * @return array|bool
	 */
	public function getMetadata($keyValue = null, $type = 'id') {

		if(empty($keyValue)) {
			return false;
		}

		$multidatabase = $this->multidatabase->getMultidatabase();

		if (! $multidatabase) {
			return $multidatabase;
		}

		$conditions['multidatabase_id'] = $multidatabase['Multidatabase']['id'];

		if ($type == 'key') {
			$conditions['key'] = $keyValue;
		} else {
			$conditions['id'] = (int)$keyValue;
			if ($conditions['id'] < 1) {
				return false;
			}
		}


		$multidatabaseMetadata = $this->find('first',
			array(
				'conditions' => $conditions,
				'recursive' => -1
			)
		);

		return $multidatabaseMetadata;

	}

	/**
	 * 変更内容の保存
	 * @param $metadatas
	 * @return bool
	 */
	public function updateMetadata($metadatas) {

		//トランザクションBegin
		$this->begin();
		try {
			//登録処理
			if (! $this->saveAll($metadatas)) {
				throw new InternalErrorException(__d('net_commons', 'Internal Server Error'));
			}
			//トランザクションCommit
			$this->commit();

		} catch (Exception $ex) {
			//トランザクションRollback
			$this->rollback($ex);
		}
		return true;
	}


/**
 * DBよりメタデータを取得する
 * @return array
 */
	public function getMetadatas($multidatabase_id = null) {

		if (! $multidatabase_id) {
			return false;
		}

		$conditions['multidatabase_id'] = $multidatabase_id;

		$orders = array(
			'MultidatabaseMetadata.position ASC',
			'MultidatabaseMetadata.rank ASC',
		);

		$multidatabaseMetadatas = $this->find('all', array(
			'conditions' => $conditions,
			'recursive' => -1,
			'order' => $orders
		));


		return $multidatabaseMetadatas;
	}

	public function getMetadataGroups($multidatabase_id) {
		if (empty($multidatabase_id)) {
			return false;
		}

		$metadatas = $this->getEditMetadatas($multidatabase_id);

		if (! $metadatas) {
			return false;
		}

		foreach ($metadatas as  $metadata) {
			$result[$metadata['position']][$metadata['rank']] = $metadata;
		}

		return $result;

	}


/**
 * メタデータの型を調整する
 * @param array $metadatas
 */
	public function normalizeEditMetadatasType($metadatas = array()) {
		if (empty($metadatas)) {
			return false;
		}

		foreach ($metadatas as $key => $metadata) {
			switch ($this->dataType[$key]) {
				case 'numeric':
				case 'integer':
					$result[$key] = (int)$metadata;
					break;
				case 'boolean':
					$result[$key] = 0;
					if ($metadata) {
						$result[$key] = 1;
					}
					break;
				case 'string':
				case 'text':
					$result[$key] = (string)$metadata;
					break;
				default:
					$result[$key] = $metadata;
					break;
			}

		}

		return $result;


	}

/**
 * 編集用のメタデータを取得する
 * @param null $multidatabase_id
 * @return bool
 */
	public function getEditMetadatas($multidatabase_id = null) {

		$multidatabaseMetadatas = $this->getMetadatas($multidatabase_id);

		if (! $multidatabaseMetadatas) {
			return false;
		}

		foreach ($multidatabaseMetadatas as $key => $metadata) {
			if (!isset($metadata['MultidatabaseMetadata'])) {
				return false;
			}
			$tmp = $metadata['MultidatabaseMetadata'];
			$result[$key] = $this->normalizeEditMetadatasType($tmp);
		}



		return $result;

	}


	/**
	 * 件数カウント
	 * @param $metadatas
	 */
	public function countMetadatas($metadatas) {

		$totalAllMetadatas = count($metadatas);

		foreach ($metadatas as $metadata) {

			if (!isset($metadata['MultidatabaseMetadata']['position'])) {
				return false;
			}

			$position = $metadata['MultidatabaseMetadata']['position'];

			if (isset($totalPosMetadatas[$position])) {
				$totalPosMetadatas[$position]++;
			} else {
				$totalPosMetadatas[$position] = 1;
			}
		}

		$result = array(
			'total' => $totalAllMetadatas,
			'position' => $totalPosMetadatas
		);


		return $result;

	}


	public function getEmptyMetadata() {
		return array(
			'MultidatabaseMetadata' => array (
				'id' => '',
				'key' => '',
				'name' => '無題',
				'language_id' => Current::read('Language.id'),
				'position' => 0,
				'rank' => 0,
				'col_no' => 0,
				'type' => 'text',
				'selections' => '',
				'is_require' => 0,
				'is_searchable' => 0,
				'is_sortable' => 0,
				'is_file_dl_require_auth' => 0,
				'is_visible_list' => 1,
				'is_visible_detail' => 1,
			)
		);
	}


	/**
	 * 初期データ
	 * @return array
	 */
	public function getInitMetadatas() {
		return array(
			array (
				'id' => '',
				'key' => '',
				'name' => 'タイトル',
				'language_id' => Current::read('Language.id'),
				'position' => 0,
				'rank' => 0,
				'col_no' => 1,
				'type' => 'text',
				'selections' => '',
				'is_require' => 1,
				'is_searchable' => 1,
				'is_sortable' => 1,
				'is_file_dl_require_auth' => 0,
				'is_visible_list' => 1,
				'is_visible_detail' => 1,
			),
			array (
				'id' => '',
				'key' => '',
				'name' => 'ふりがな',
				'language_id' => Current::read('Language.id'),
				'position' => 0,
				'rank' => 1,
				'col_no' => 2,
				'type' => 'text',
				'selections' => '',
				'is_require' => 0,
				'is_searchable' => 1,
				'is_sortable' => 0,
				'is_file_dl_require_auth' => 0,
				'is_visible_list' => 0,
				'is_visible_detail' => 1,
			),
			array (
				'id' => '',
				'key' => '',
				'name' => 'カテゴリ',
				'language_id' => Current::read('Language.id'),
				'position' => 0,
				'rank' => 2,
				'col_no' => 3,
				'type' => 'select',
				'selections' => implode('||',array('国語','算数','理科','社会','総合','音楽','図工','体育')),
				'is_require' => 1,
				'is_searchable' => 1,
				'is_sortable' => 0,
				'is_file_dl_require_auth' => 0,
				'is_visible_list' => 1,
				'is_visible_detail' => 1,
			),
			array (
				'id' => '',
				'key' => '',
				'name' => '概要',
				'language_id' => Current::read('Language.id'),
				'position' => 0,
				'rank' => 3,
				'col_no' => 81,
				'type' => 'wysiwyg',
				'selections' => '',
				'is_require' => 1,
				'is_searchable' => 1,
				'is_sortable' => 0,
				'is_file_dl_require_auth' => 0,
				'is_visible_list' => 1,
				'is_visible_detail' => 1,
			),
			array (
				'id' => '',
				'key' => '',
				'name' => '連絡先',
				'language_id' => Current::read('Language.id'),
				'position' => 1,
				'rank' => 0,
				'col_no' => 82,
				'type' => 'textarea',
				'selections' => '',
				'is_require' => 0,
				'is_searchable' => 1,
				'is_sortable' => 1,
				'is_file_dl_require_auth' => 0,
				'is_visible_list' => 0,
				'is_visible_detail' => 1,
			),
			array(
				'id' => '',
				'key' => '',
				'name' => '担当者',
				'language_id' => Current::read('Language.id'),
				'position' => 1,
				'rank' => 1,
				'col_no' => 4,
				'type' => 'text',
				'selections' => '',
				'is_require' => 0,
				'is_searchable' => 1,
				'is_sortable' => 0,
				'is_file_dl_require_auth' => 0,
				'is_visible_list' => 0,
				'is_visible_detail' => 1,
			),
			array(
				'id' => '',
				'key' => '',
				'name' => 'ホームページ',
				'language_id' => Current::read('Language.id'),
				'position' => 1,
				'rank' => 2,
				'col_no' => 5,
				'type' => 'text',
				'selections' => '',
				'is_require' => 0,
				'is_searchable' => 1,
				'is_sortable' => 0,
				'is_file_dl_require_auth' => 0,
				'is_visible_list' => 0,
				'is_visible_detail' => 1,
			),
			array(
				'id' => '',
				'key' => '',
				'name' => '対象',
				'language_id' => Current::read('Language.id'),
				'position' => 1,
				'rank' => 3,
				'col_no' => 6,
				'type' => 'select',
				'selections' => implode('||',array('小学校','中学校','高校')),
				'is_require' => 0,
				'is_searchable' => 1,
				'is_sortable' => 0,
				'is_file_dl_require_auth' => 0,
				'is_visible_list' => 0,
				'is_visible_detail' => 1,
			),
			array(
				'id' => '',
				'key' => '',
				'name' => '資料',
				'language_id' => Current::read('Language.id'),
				'position' => 1,
				'rank' => 4,
				'col_no' => 7,
				'type' => 'file',
				'selections' => 0,
				'is_require' => 0,
				'is_searchable' => 0,
				'is_sortable' => 0,
				'is_file_dl_require_auth' => 0,
				'is_visible_list' => 0,
				'is_visible_detail' => 1,
			),
			array (
				'id' => '',
				'key' => '',
				'name' => 'コメント',
				'language_id' => Current::read('Language.id'),
				'position' => 2,
				'rank' => 0,
				'col_no' => 82,
				'type' => 'textarea',
				'selections' => '',
				'is_require' => 0,
				'is_searchable' => 1,
				'is_sortable' => 0,
				'is_file_dl_require_auth' => 0,
				'is_visible_list' => 0,
				'is_visible_detail' => 1,
			),
			array(
				'id' => '',
				'key' => '',
				'name' => '検索キーワード',
				'language_id' => Current::read('Language.id'),
				'position' => 2,
				'rank' => 1,
				'col_no' => 8,
				'type' => 'text',
				'selections' => '',
				'is_require' => 0,
				'is_searchable' => 1,
				'is_sortable' => 0,
				'is_file_dl_require_auth' => 0,
				'is_visible_list' => 0,
				'is_visible_detail' => 0,
			),
			array (
				'id' => '',
				'key' => '',
				'name' => '画像',
				'language_id' => Current::read('Language.id'),
				'position' => 3,
				'rank' => 0,
				'col_no' => 9,
				'type' => 'image',
				'selections' => '',
				'is_require' => 1,
				'is_searchable' => 0,
				'is_sortable' => 0,
				'is_file_dl_require_auth' => 0,
				'is_visible_list' => 0,
				'is_visible_detail' => 1,
			)
		);
	}
}
