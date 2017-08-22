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
App::uses('MultidatabasesMetadataEditCnvModel', 'MultidatabasesMetadataEditCnv.Model');

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
 * Begin Col No
 * @var int
 */
	private $__beginColNo = 1;

/**
 * End Col No
 * @var int
 */
	private $__endColNo = 79;

/**
 * Begin Col for TextArea No
 * @var int
 */
	private $__beginColNoT = 80;

/**
 * End Col for TextArea No
 * @var int
 */
	private $__endColNoT = 100;

/**
 * Make save data
 * 保存データを作成
 *
 * @param array $data データ配列
 * @return array
 */
	public function makeSaveData($data) {
		$this->loadModels([
			'MultidatabaseMetadata' => 'Multidatabases.MultidatabaseMetadata',
			'MultidatabaseMetadataEditCnv' => 'Multidatabases.MultidatabaseMetadataEditCnv',
		]);

		if (empty($data['MultidatabaseMetadata'])) {
			return [];
		}

		$tmp = $this->MultidatabaseMetadata->mergeGroupToMetadatas(
			$data['MultidatabaseMetadata']);

		$metadatas = $tmp['MultidatabaseMetadata'];

		// カラムNo初期値
		$colNos['col_no'] = $this->__beginColNo;
		$colNos['col_no_t'] = $this->__beginColNoT;

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

			$metadata = $this->MultidatabaseMetadataEditCnv->cnvMetaBoolToInt($metadata);

			$metadata['selections'] = $this->MultidatabaseMetadataEditCnv->cnvMetaSelToJson($metadata);

			// カラムNoが未設定の場合は、カラムNoを付与する
			$currentColNo = $this->addColNo($metadata, $colNos, $metadatas);

			$result[] = array_merge(
				$metadata,
				['language_id' => Current::read('Language.id')],
				['multidatabase_id' => $data['Multidatabase']['id']],
				['key' => $data['Multidatabase']['key']],
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
				if ($result['col_no'] >= $this->__beginColNo && $result['col_no'] <= $this->__endColNo) {
					$result['col_no']++;
				} else {
					return false;
				}
			} elseif (in_array($result['col_no_t'], $skipColNos)) {
				if (
					$result['col_no_t'] >= $this->__beginColNoT && $result['col_no_t'] <= $this->__endColNoT
				) {
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
			'selections' => [],
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
}

