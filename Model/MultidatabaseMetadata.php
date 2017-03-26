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
App::uses('CakeSession', 'Model/Datasourse');

/**
 * MultidatabaseMetadata Model
 *
 * @author Tomoyuki OHNO (Ricksoft, Co., Ltd.) <ohno.tomoyuki@ricksoft.jp>
 * @package NetCommons\Multidatabases\Model
 */
class MultidatabaseMetadata extends MultidatabasesAppModel {

	public $metadatas = [];

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
			'recursive' => -1,
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

		if (!$metadatas) {
			return false;
		}

		foreach ($metadatas as $key => $metadata) {
			if (!isset($metadata['MultidatabaseMetadata'])) {
				return false;
			}
			$tmp = $metadata['MultidatabaseMetadata'];
			$result[$key] = $this->MultidatabaseMetadataEdit
				->normalizeEditMetadatasType($tmp);
		}

		return $result;
	}

/**
 * Count metadatas
 * 件数カウント
 *
 * @param array $metadatas メタデータ配列
 * @return array|bool 全体のメタデータ合計と各ポジションのメタデータ合計
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

		$result = [
			'total' => $totalAllMetadatas,
			'position' => $totalPosMetadatas,
		];

		return $result;
	}

/**
 * 検索対象のメタデータフィールド一覧を取得する
 *
 * @param int $multidatabaseId 汎用データベースID
 * @return array|bool
 * @throws InternalErrorException
 */
	public function getSearchMetadatas($multidatabaseId = 0) {
		if (! $metadatas = $this->getMetadatas(
				$multidatabaseId,
				[
					'is_searchable' => 1
				]
			)) {
			throw new InternalErrorException(__d('net_commons', 'Internal Server Error'));
		}

		$result = [];
		foreach ($metadatas as $metadata) {
			$result[] = 'value' . $metadata['MultidatabaseMetadata']['col_no'];
		}

		return $result;
	}

/**
 * Get initial metadatas
 * 初期データ
 *
 * @return array
 */
	public function getInitMetadatas() {
		$this->loadModels([
			'MultidatabaseMetadataInit' => 'Multidatabases.MultidatabaseMetadataInit',
		]);

		$initMetadatas = $this->MultidatabaseMetadataInit->initMetadatas;
		$result = [];
		foreach ($initMetadatas as $key => $initMetadata) {
			$result[$key] = $initMetadata;
			$result[$key]['language_id'] = Current::read('Language.id');
		}
		return $result;
	}
}

