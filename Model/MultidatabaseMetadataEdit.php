<?php
/**
 * MultidatabaseMetadataEdit Model
 * 汎用データベースメタデータ編集に関するモデル処理
 *
 * @author Noriko Arai <arai@nii.ac.jp>
 * @author Tomoyuki OHNO (Ricksoft Co., Ltd.) <ohno.tomoyuki@ricksoft.jp>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */

App::uses('MultidatabasesAppModel', 'Multidatabases.Model');

/**
 * MultidatabaseMetadataEdit Model
 *
 * @author Tomoyuki OHNO (Ricksoft, Co., Ltd.) <ohno.tomoyuki@ricksoft.jp>
 * @package NetCommons\Multidatabases\Model
 */
class MultidatabaseMetadataEdit extends MultidatabasesAppModel {

/**
 * Use table
 *
 * @var mixed False or table name
 */
	public $useTable = false;

/**
 * Data type
 *
 * @var array
 */
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
		'is_title' => 'boolean',
		'is_searchable' => 'boolean',
		'is_sortable' => 'boolean',
		'is_file_dl_require_auth' => 'boolean',
		'is_visible_file_dl_counter' => 'boolean',
		'is_visible_field_name' => 'boolean',
		'is_visible_list' => 'boolean',
		'is_visible_detail' => 'boolean',
		'created' => 'datetime',
		'created_user' => 'numeric',
		'modified' => 'datetime',
		'modified_user' => 'numeric',
	];

/**
 * Make save data
 * 保存データを作成
 *
 * @param array $multidatabase 汎用データベース配列
 * @param array $metadatas メタデータ配列
 * @return array
 */
	public function makeSaveData($multidatabase = [], $metadatas = []) {
		if (empty($metadatas)) {
			return [];
		}

		// カラムNo初期値
		$colNos['col_no'] = 1;
		$colNos['col_no_t'] = 80;

		$result = [];

		foreach ($metadatas as $metadata) {
			// 1列目はタイトルとする
			if ((int)$metadata['col_no'] === 1) {
				$metadata['is_title'] = 1;
				$metadata['is_require'] = 1;
				$metadata['type'] = 'text';
			} else {
				$metadata['is_title'] = 0;
			}

			$metadata = $this->cnvMetaBoolToInt($metadata);

			$metadata['selections'] = $this->cnvMetaSelToJson($metadata);

			// カラムNoが未設定の場合は、カラムNoを付与する
			$currentColNo = $this->addColNo($metadata, $colNos, $metadatas);

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
 * メタデータのカラムNoを出力する
 *
 * @param array $metadatas メタデータ配列
 * @return array
 */
	public function getColNos($metadatas) {
		$colNos = [];
		if (!empty($metadatas)) {
			foreach ($metadatas as $metadata) {
				if (isset($metadata['col_no'])) {
					$colNos[] = $metadata['col_no'];
				}
			}
		}

		return $colNos;
	}

/**
 * 削除対象のカラムを1件出力する
 *
 * @param array $metadata メタデータ配列
 * @param array $colNos カラムNo配列
 * @param string $type 種別
 * @return bool|array
 */
	public function getDeleteMetadata($metadata, $colNos, $type) {
		if (
			isset($metadata['col_no']) &&
			!in_array($metadata['col_no'], $colNos)
		) {
			switch ($type) {
				case 'id':
					return $metadata['id'];
				case 'col_no':
					return $metadata['col_no'];
			}
			$result['id'] = $metadata['id'];
			$result['col_no'] = $metadata['col_no'];
			return $result;
		}
		return false;
	}

/**
 * Get Free column no
 * 空きカラムNoを取得
 *
 * @param array $metadatas メタデータ配列
 * @param array $colNos 列番号配列
 * @return array|bool
 */
	public function getFreeColNo($metadatas, $colNos) {
		$skipColNos = $this->getSkipColNos($metadatas);

		if (empty($skipColNos)) {
			return $colNos;
		}

		$result = $colNos;

		$chkSkipColNo = false;

		while (! $chkSkipColNo) {
			if (in_array($result['col_no'], $skipColNos)) {
				if ($result['col_no'] >= 1 && $result['col_no'] <= 79) {
					$result['col_no']++;
				} else {
					return false;
				}
			} elseif (in_array($result['col_no_t'], $skipColNos)) {
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
 * Get Skip column no
 * スキップ対象のカラムNoを取得
 *
 * @param array $metadatas メタデータ配列
 * @return array
 */
	public function getSkipColNos($metadatas = []) {
		$result = [];

		foreach ($metadatas as $metadata) {
			if (isset($metadata['col_no'])) {
				$result[] = $metadata['col_no'];
			}
		}
		return $result;
	}

/**
 * Get empty metadata
 * 空のメタデータを取得する
 *
 * @return array
 */
	public function getEmptyMetadata() {
		return [
			'id' => '',
			'key' => '',
			'name' => __d('multidatabases', 'No title'),
			'language_id' => Current::read('Language.id'),
			'position' => 0,
			'rank' => 0,
			'col_no' => 0,
			'type' => 'text',
			'selections' => '',
			'is_require' => 0,
			'is_title' => 0,
			'is_searchable' => 0,
			'is_sortable' => 0,
			'is_file_dl_require_auth' => 0,
			'is_visible_file_dl_counter' => 0,
			'is_visible_field_name' => 1,
			'is_visible_list' => 1,
			'is_visible_detail' => 1,
		];
	}

/**
 * カラムNoが未設定の場合は、カラムNoを付与する（保存データのため）
 *
 * @param array $metadata メタデータ配列（単一）
 * @param array $colNos カラムNo配列
 * @param array $metadatas メタデータ配列
 * @return int
 */
	public function addColNo($metadata, $colNos, $metadatas) {
		if (!isset($metadata['col_no']) || empty($metadata['col_no'])) {
			// 空きカラムNoの取得
			$colNos = $this->getFreeColNo($metadatas, $colNos);

			if (
				$metadata['type'] == 'textarea' ||
				$metadata['type'] == 'wysiwyg' ||
				$metadata['type'] == 'select' ||
				$metadata['type'] == 'checkbox'
			) {
				$currentColNo = $colNos['col_no_t'];
				$colNos['col_no_t']++;
			} else {
				$currentColNo = $colNos['col_no'];
				$colNos['col_no']++;
			}
		} else {
			$currentColNo = $metadata['col_no'];
		}

		return $currentColNo;
	}

/**
 * 選択肢をJSON化する（保存データのため）
 *
 * @param array $metadata メタデータ配列
 * @return string
 */
	public function cnvMetaSelToJson($metadata) {
		if (!empty($metadata['selections']) && is_array($metadata['selections'])) {
			$selectionsJson = json_encode($metadata['selections']);
			return $selectionsJson;
		}
		return '';
	}

/**
 * Bool型のデータをIntに変換する（保存データのため）
 *
 * @param array $metadata メタデータ配列
 * @return array
 */
	public function cnvMetaBoolToInt($metadata) {
		// メタデータの変換 (on => 1, keyが存在しない => 0)
		foreach ([
			'is_require',
			'is_title',
			'is_searchable',
			'is_sortable',
			'is_file_dl_require_auth',
			'is_visible_file_dl_counter',
			'is_visible_field_name',
			'is_visible_list',
			'is_visible_detail'
		] as $metaKey) {
			if (isset($metadata[$metaKey])) {
				if ($metadata[$metaKey] == 'on') {
					$metadata[$metaKey] = 1;
				}
			} else {
				$metadata[$metaKey] = 0;
			}
		}

		return $metadata;
	}

/**
 * Normalize edit metadatas type for JSON
 * メタデータの型を調整する（JSONのため）
 *
 * @param array $metadatas メタデータ配列
 * @return array|bool
 */
	public function normalizeEditMetadatasType($metadatas = []) {
		if (empty($metadatas)) {
			return false;
		}

		foreach ($metadatas as $key => $metadata) {
			$result[$key] = $metadata;

			if (in_array($this->dataType[$key], [
				'numeric', 'integer'
			])) {
				$result[$key] = (int)$metadata;
			}

			if (in_array($this->dataType[$key], [
				'string', 'text'
			])) {
				$result[$key] = (string)$metadata;
			}

			if ($this->dataType[$key] === 'boolean') {
				$result[$key] = 0;
				if ($metadata) {
					$result[$key] = 1;
				}
			}

			if ($this->dataType[$key] === 'json') {
				if (empty($metadata)) {
					$result[$key] = [];
				} else {
					$result[$key] = json_decode($metadata, true);
				}
			}
		}
		return $result;
	}
}

