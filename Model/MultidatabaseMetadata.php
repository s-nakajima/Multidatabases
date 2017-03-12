<?php
/**
 * MultidatabaseMetadata Model
 * 汎用データベースメタデータ定義に関するモデル処理
 *
 * @author Noriko Arai <arai@nii.ac.jp>
 * @author Tomoyuki OHNO (Ricksoft Co., Ltd.) <ohno.tomoyuki@ricksoft.jp>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */

App::uses('MultidatabasesAppModel', 'Multidatabases.Model');
App::uses('CakeSession', 'Model/Datasourse');

/**
 * MultidatabaseMetadata Model
 *
 * @author Tomoyuki OHNO (Ricksoft, Co., Ltd.) <ohno.tomoyuki@ricksoft.jp>
 * @package NetCommons\Multidatabases\Model
 */
class MultidatabaseMetadata extends MultidatabasesAppModel
{

	public $metadatas = [];

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
		'selections' => 'json',
		'is_require' => 'boolean',
		'is_searchable' => 'boolean',
		'is_sortable' => 'boolean',
		'is_file_dl_require_auth' => 'boolean',
		'is_visible_list' => 'boolean',
		'is_visible_detail' => 'boolean',
		'created' => 'datetime',
		'created_user' => 'numeric',
		'modified' => 'datetime',
		'modified_user' => 'numeric',
	];

/**
 * Validation rules
 *
 * @var array
 */
	public $validate = [];
	/*
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
		//'type' => array(
		//	'numeric' => array(
		//		'rule' => array('numeric'),
		//		//'message' => 'Your custom message here',
		//		//'allowEmpty' => false,
		//		//'required' => false,
		//		//'last' => false, // Stop validation after this rule
		//		//'on' => 'create', // Limit validation to 'create' or 'update' operations
		//	),
		//),
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
*/


/**
 * belongsTo associations
 *
 * @var array
 */
	public $belongsTo = array(
		'Multidatabase' => array(
			'className' => 'Multidatabases.Multidatabase',
			'foreignKey' => 'multidatabase_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		),
		'Language' => array(
			'className' => 'M17n.Language',
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
	public function __construct($id = false, $table = null, $ds = null)
	{
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
	public function getMetadata($keyValue = null, $type = 'id')
	{

		if (empty($keyValue)) {
			return false;
		}

		$multidatabase = $this->multidatabase->getMultidatabase();

		if (!$multidatabase) {
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
 * DBよりメタデータを取得する
 * @return array
 */
	public function getMetadatas($multidatabase_id = null)
	{

		if (!$multidatabase_id) {
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

	public function getMetadataGroups($multidatabase_id)
	{
		if (empty($multidatabase_id)) {
			return false;
		}

		$metadatas = $this->getEditMetadatas($multidatabase_id);

		if (!$metadatas) {
			return false;
		}

		foreach ($metadatas as $metadata) {
			$result[$metadata['position']][$metadata['rank']] = $metadata;
		}

		return $result;

	}


/**
 * メタデータの型を調整する（JSのため）
 * @param array $metadatas
 */
	public function normalizeEditMetadatasType($metadatas = array())
	{
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
				case 'json':
					if (empty($metadata)) {
						$result[$key] = [];
					} else {
						$result[$key] = json_decode($metadata, true);
					}
					break;

				default:
					$result[$key] = $metadata;
					break;
			}

		}

		return $result;


	}


	public function mergeGroupToMetadatas($data)
	{

		$result['MultidatabaseMetadata'] = array_merge($data[0], $data[1], $data[2], $data[3]);

		return $result;


	}

/**
 * スキップ対象のカラムNoを取得
 *
 * @param array $metadatas
 * @return array
 */
	public function getSkipColNos($metadatas = [])
	{

		if (empty($metadatas)) {
			return [];
		}

		$result = [];

		foreach ($metadatas as $metadata) {
			if (isset($metadata['col_no'])) {
				$result[] = $metadata['col_no'];
			}
		}

		return $result;

	}

/**
 * 空きカラムNoを取得
 * @param $metadatas
 * @param $col_no
 * @param $col_no_t
 * @return array|bool
 */
	public function getFreeColNo($metadatas, $colNos)
	{

		if (empty($metadatas) || empty($colNos)) {
			return false;
		}
		$skipColNos = $this->getSkipColNos($metadatas);


		if (empty($skipColNos)) {
			return $colNos;
		}

		$result = $colNos;

		$chkSkipColNo = false;

		while (!$chkSkipColNo) {
			if (in_array($result['col_no'], $skipColNos)) {
				if ($result['col_no'] >= 1 && $result['col_no'] <= 79) {
					$result['col_no']++;
				} else {
					echo "aaa";
					return false;
				}
			} else {
				$chkSkipColNo = true;
			}
		}

		$chkSkipColNo = false;

		while (!$chkSkipColNo) {
			if (in_array($result['col_no_t'], $skipColNos)) {
				if ($result['col_no_t'] >= 80 && $result['col_no_t'] <= 100) {
					$result['col_no_t']++;
				} else {
					return false;
				}
			} else {
				$chkSkipColNo = true;
			}
		}

		return $result;

	}


/**
 * 保存データを作成
 * @param array $metadatas
 * @return array
 */
	public function makeSaveData($multidatabase, $metadatas = [])
	{

		if (empty($metadatas)) {
			return [];
		}

		// カラムNo初期値
		$colNos['col_no'] = 1;
		$colNos['col_no_t'] = 80;

		$result = [];

		foreach ($metadatas as $metadata) {
			$selections_json = [];

			// チェックボックス、セレクトの場合の処理（JSON化）
			if (!empty($metadata['selections']) && is_array($metadata['selections'])) {
				$selections_json = json_encode($metadata['selections']);
				$metadata['selections'] = $selections_json;
			}


			// カラムNoが未設定の場合は、カラムNoを付与する
			if (!isset($metadata['col_no']) || empty($metadata['col_no'])) {
				// 空きカラムNoの取得
				$colNos = $this->getFreeColNo($metadatas, $colNos);

				switch ($metadata['type']) {
					case 'textarea':
					case 'wysiwyg':
					case 'select':
					case 'checkbox':
						$currentColNo = $colNos['col_no_t'];
						$colNos['col_no_t']++;
						break;
					default:
						$currentColNo = $colNos['col_no'];
						$colNos['col_no']++;
						break;
				}

			} else {
				$currentColNo = $metadata['col_no'];
			}

			$result[] = array_merge(
				$metadata,
				['language_id' => Current::read('Language.id')],
				['multidatabase_id' => $multidatabase['Multidatabase']['id']],
				['key' => $multidatabase['Multidatabase']['key']],
				['col_no' => $currentColNo]
			);
		}

		return $result;

	}

/**
 * メタデータを削除する
 *
 * @param null $metadata_id
 * @return void
 */
	public function deleteMetadatas($metadataIds = [])
	{
		if (empty($metadataIds)) {
			return false;
		}

		foreach ($metadataIds as $metadataId) {
			if (!$this->delete($metadataId)) {
				throw new InternalErrorException(__d('net_commons', 'Internal Server Error'));
			}
		}
	}

/**
 * メタデータを保存する（新規・更新）
 *
 * @param $metadatas
 * @return void
 */
	public function saveMetadatas($metadatas)
	{
		if (!$this->saveAll($metadatas)) {
			throw new InternalErrorException(__d('net_commons', 'Internal Server Error'));
		}
	}

/**
 * 削除対象のカラムNoを取得する
 *
 * @param null $multidatabase_id
 * @param array $currentMetadatas
 * @return array|bool
 */
	public function getDeleteMetadatas($multidatabaseId = null, $currentMetadatas = [], $type = 'all')
	{

		if (
			is_null($multidatabaseId)
			|| empty($currentMetadatas)
		) {
			return false;
		}

		if (!$beforeSaveMetadatas = $this->getEditMetadatas($multidatabaseId)) {
			return false;
		}

		$result = [];

		foreach ($currentMetadatas as $currentMetadata) {
			if (isset($currentMetadata['col_no'])) {
				$currentColNos[] = $currentMetadata['col_no'];
			}
		}

		if (empty($currentColNos)) {
			return false;
		}

		foreach ($beforeSaveMetadatas as $beforeSaveMetadata) {
			if (isset($beforeSaveMetadata['col_no'])) {
				if (!in_array($beforeSaveMetadata['col_no'], $currentColNos)) {
					switch ($type) {
						case 'id':
							$result[] = $beforeSaveMetadata['id'];
							break;
						case 'col_no':
							$result[] = $beforeSaveMetadata['col_no'];
							break;
						default:
							$result[]['id'] = $beforeSaveMetadata['id'];
							$result[]['col_no'] = $beforeSaveMetadata['col_no'];
							break;
					}
				}
			}
		}


		if (empty($result)) {
			return false;
		}

		return $result;

	}

/**
 * 編集用のメタデータを取得する
 * @param null $multidatabaseId
 * @return bool
 */
	public function getEditMetadatas($multidatabaseId = null)
	{

		if (is_null($multidatabaseId)) {
			return false;
		}

		$multidatabaseMetadatas = $this->getMetadatas($multidatabaseId);

		if (!$multidatabaseMetadatas) {
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
	public function countMetadatas($metadatas)
	{

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


	public function getEmptyMetadata()
	{
		return array(
			'MultidatabaseMetadata' => array(
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
	public function getInitMetadatas()
	{
		return array(
			array(
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
			array(
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
			array(
				'id' => '',
				'key' => '',
				'name' => 'カテゴリ',
				'language_id' => Current::read('Language.id'),
				'position' => 0,
				'rank' => 2,
				'col_no' => 3,
				'type' => 'select',
				'selections' => ['国語', '算数', '理科', '社会', '総合', '音楽', '図工', '体育'],
				'is_require' => 1,
				'is_searchable' => 1,
				'is_sortable' => 0,
				'is_file_dl_require_auth' => 0,
				'is_visible_list' => 1,
				'is_visible_detail' => 1,
			),
			array(
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
			array(
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
				'selections' => ['小学校', '中学校', '高校'],
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
			array(
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
			array(
				'id' => '',
				'key' => '',
				'name' => '画像',
				'language_id' => Current::read('Language.id'),
				'position' => 3,
				'rank' => 0,
				'col_no' => 9,
				'type' => 'image',
				'selections' => '',
				'is_require' => 0,
				'is_searchable' => 0,
				'is_sortable' => 0,
				'is_file_dl_require_auth' => 0,
				'is_visible_list' => 0,
				'is_visible_detail' => 1,
			)
		);
	}
}
