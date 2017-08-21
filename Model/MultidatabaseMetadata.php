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
App::uses('MultidatabasesMetadataInitModel', 'MultidatabaseMetadataInit.Model');
App::uses('MultidatabasesMetadataEditModel', 'MultidatabaseMetadataEdit.Model');
App::uses('MultidatabasesMetadataEditCnvModel', 'MultidatabasesMetadataEditCnv.Model');
App::uses('MultidatabasesMetadataSettingModel', 'MultidatabaseMetadataSetting.Model');
App::uses('CakeSession', 'Model/Datasourse');

/**
 * MultidatabaseMetadata Model
 *
 * @author Tomoyuki OHNO (Ricksoft, Co., Ltd.) <ohno.tomoyuki@ricksoft.jp>
 * @package NetCommons\Multidatabases\Model
 */
class MultidatabaseMetadata extends MultidatabasesAppModel {

/**
 * Use table
 *
 * @var mixed False or table name
 */
	public $useTable = 'multidatabase_metadatas';

/**
 * Validation rules
 *
 * @var array
 */
	public $validate = [];

/**
 * hasOne associations
 *
 * @var array
 */
	public $hasOne = [
		'MultidatabaseMetadataSetting' => [
			'className' => 'Multidatabases.MultidatabaseMetadataSetting',
			'foreignKey' => 'id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		]
	];

/**
 * belongsTo associations
 *
 * @var array
 */
	public $belongsTo = [
		'Multidatabase' => [
			'className' => 'Multidatabases.Multidatabase',
			'foreignKey' => 'multidatabase_id',
			'conditions' => '',
			'fields' => '',
			'order' => '',
		],
		'Language' => [
			'className' => 'M17n.Language',
			'foreignKey' => 'language_id',
			'conditions' => '',
			'fields' => '',
			'order' => '',
		],
	];

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
 * Before validate
 *
 * @param array $options オプション
 * @return bool
 */
	public function beforeValidate($options = []) {
		$this->validate = $this->__makeValidation();
		return parent::beforeValidate($options);
	}

/**
 * Get one metadata
 * メタデータを1件取得する
 *
 * @param int|string|null $key キー
 * @param string $type 出力方法
 * @return array|bool
 */
	public function getMetadata($key = null, $type = 'id') {
		if (empty($key)) {
			return false;
		}

		$multidatabase = $this->multidatabase->getMultidatabase();

		if (!$multidatabase) {
			return $multidatabase;
		}

		$conditions['multidatabase_id'] = $multidatabase['Multidatabase']['id'];

		if ($type == 'key') {
			$conditions['key'] = $key;
		} else {
			$conditions['id'] = (int)$key;
			if ($conditions['id'] < 1) {
				return false;
			}
		}

		$metadata = $this->find('first',
			[
				'conditions' => $conditions,
				'recursive' => -1,
			]
		);

		return $metadata;
	}

/**
 * カラムNoをKeyとしてメタデータリストを出力する
 *
 * @param int $multidatabaseId 汎用データベースID
 * @return array|bool
 */
	public function getMetadatasColNo($multidatabaseId = 0) {
		if (empty($multidatabaseId)) {
			return false;
		}

		$metadatas = $this->getMetadatas($multidatabaseId);
		$result = [];

		foreach ($metadatas as $metadata) {
			if (!isset( $metadata['MultidatabaseMetadata'])) {
				return false;
			}
			$tmp = $metadata['MultidatabaseMetadata'];

			$result[$tmp['col_no']] = $tmp;

		}
		return $result;
	}

/**
 * Get metadatas
 * DBよりメタデータを取得する
 *
 * @param int $multidatabaseId 汎用データベースID
 * @param array $extraConditions 追加条件
 * @return array|bool
 */
	public function getMetadatas($multidatabaseId = 0, $extraConditions = []) {
		if (empty($multidatabaseId)) {
			if (! $multidatabase = $this->Multidatabase->getMultidatabase()) {
				return false;
			}
			$multidatabaseId = $multidatabase['Multidatabase']['id'];
		}

		$conditions['multidatabase_id'] = $multidatabaseId;

		if (!empty($conditions)) {
			$conditions += $extraConditions;
		}

		$orders = [
			'MultidatabaseMetadata.position ASC',
			'MultidatabaseMetadata.rank ASC',
		];

		$metadatas = $this->find('all', [
			'conditions' => $conditions,
			'order' => $orders,
		]);

		return $metadatas;
	}

/**
 * Get metadata group
 * メタデータグループを取得する
 * $result[グループ][並び順] = $metadataを返す
 *
 * @param int $multidatabaseId 汎用データベースID
 * @return bool|array
 */
	public function getMetadataGroups($multidatabaseId) {
		if (empty($multidatabaseId)) {
			return false;
		}

		$metadatas = $this->getEditMetadatas($multidatabaseId);

		if (!$metadatas) {
			return false;
		}

		foreach ($metadatas as $metadata) {
			$result[$metadata['position']][$metadata['rank']] = $metadata;
		}

		return $result;
	}

/**
 * Merge Group Metadatas
 * グループごとに分かれているメタデータを１つの配列に統合する
 *
 * @param array $data メタデータグループ配列
 * @return array
 */
	public function mergeGroupToMetadatas($data) {
		for ($i = 1; $i <= 3; $i++) {
			if (!isset($data[$i])) {
				$data[$i] = [];
			}
		}

		$result['MultidatabaseMetadata'] = array_merge($data[0], $data[1], $data[2], $data[3]);
		return $result;
	}

/**
 * Delete metadata
 * メタデータを削除する
 *
 * @param array $metadataIds メタデータID配列
 * @return void
 * @throws InternalErrorException
 */
	public function deleteMetadatas($metadataIds = []) {
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
 * Save metadata (New/Update)
 * メタデータを保存する（新規・更新）
 *
 * @param array $metadatas メタデータ配列
 * @return void
 * @throws InternalErrorException
 */
	public function saveMetadatas($metadatas) {
		if (!$this->saveAll($metadatas)) {
			throw new InternalErrorException(__d('net_commons', 'Internal Server Error'));
		}
	}

/**
 * Get Metadata columns for delete
 * 削除対象のカラムNoを取得する
 *
 * @param int|null $multidatabaseId 汎用データベースID
 * @param array $currentMetadatas メタデータ配列
 * @param string $type 出力方法
 * @return array|bool
 */
	public function getDeleteMetadatas(
		$multidatabaseId = null, $currentMetadatas = [], $type = 'all'
	) {
		$this->loadModels([
			'MultidatabaseMetadataEdit' => 'Multidatabases.MultidatabaseMetadataEdit',
		]);

		if (is_null($multidatabaseId) || empty($currentMetadatas)) {
			return false;
		}

		if (!$beforeSaveMetadatas = $this->getEditMetadatas($multidatabaseId)) {
			return false;
		}

		$result = [];

		$currentColNos = $this->MultidatabaseMetadataEdit->getColNos($currentMetadatas);
		if (empty($currentColNos)) {
			return false;
		}

		foreach ($beforeSaveMetadatas as $beforeSaveMetadata) {
			$tmp = $this->MultidatabaseMetadataEdit->getDeleteMetadata(
				$beforeSaveMetadata, $currentMetadatas, $type
			);

			if (!$tmp) {
				$result[] = $tmp;
			}
		}

		if (empty($result)) {
			return false;
		}

		return $result;
	}

/**
 * Get metadatas for edit
 * 編集用のメタデータを取得する
 *
 * @param int|null $multidatabaseId 汎用データベースID
 * @return array|bool
 */
	public function getEditMetadatas($multidatabaseId = 0) {
		$this->loadModels([
			'MultidatabaseMetadataEdit' => 'Multidatabases.MultidatabaseMetadataEdit',
		]);

		if (empty($multidatabaseId)) {
			if (! $multidatabase = $this->Multidatabase->getMultidatabase()) {
				return false;
			}
			$multidatabaseId = $multidatabase['Multidatabase']['id'];
		}

		$metadatas = $this->getMetadatas($multidatabaseId);
		$result = $this->__normalizeEditMetadatas($metadatas);

		return $result;
	}

/**
 * メタデータのバリデーションを行う
 *
 * @param array $metadataGroups メタデータグループ配列
 * @return bool|array
 */
	public function doValidateMetadatas($metadataGroups) {
		$this->loadModels([
			'MultidatabaseMetadataEditCnv' => 'Multidatabases.MultidatabaseMetadataEditCnv'
		]);

		$metadatas = $this->mergeGroupToMetadatas($metadataGroups);

		$result['has_err'] = false;
		$result['errors'] = [];
		$result['data'] = $metadatas['MultidatabaseMetadata'];

		foreach ($metadatas['MultidatabaseMetadata'] as $key => $metadata) {
			$this->set($metadata);

			$result['data'][$key] = $this->MultidatabaseMetadataEditCnv
				->normalizeEditMetadatasType($metadata);

			$result['data'][$key]['has_err'] = 0;
			$result['data'][$key]['err_msg'] = '';
			$result['errors'][$key] = '';

			if (! $this->validates()) {
				$result['has_err'] = true;
				$result['errors'][$key] = $this->ValidationErrors;
				$result['data'][$key]['has_err'] = 1;
				if (! empty($this->validationErrors['name'][0])) {
					$result['data'][$key]['err_msg'] = $this->validationErrors['name'][0];
				}
			}
		}

		return $result;
	}

/**
 * 編集用メタデータの値を調整する
 *
 * @param array $metadatas メタデータ配列
 * @return array|bool
 */
	private function __normalizeEditMetadatas($metadatas) {
		$this->loadModels([
			'MultidatabaseMetadataEditCnv' => 'Multidatabases.MultidatabaseMetadataEditCnv'
		]);

		if (!$metadatas) {
			return false;
		}

		$result = [];
		foreach ($metadatas as $key => $metadata) {
			if (!isset($metadata['MultidatabaseMetadata'])) {
				return false;
			}
			$tmp = $metadata['MultidatabaseMetadata'];
			$result[$key] = $this->MultidatabaseMetadataEditCnv
				->normalizeEditMetadatasType($tmp);
		}

		return $result;
	}

/**
 * Make validation rules
 * バリデーションルールの作成
 *
 * @return array|bool
 */
	private function __makeValidation() {
		$result = [
			'name' => [
				'notBlank' => [
					'rule' => ['notBlank'],
					'message' => sprintf(
						__d('net_commons', 'Please input %s.'),
						__d('multidatabases', 'Field name')
					),
					'required' => true
				],
			],
		];
		return Hash::merge($this->validate, $result);
	}
}

