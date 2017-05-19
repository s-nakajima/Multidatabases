<?php
/**
 * MultidatabaseContentEditPr Model
 * 汎用データベースコンテンツデータに関するモデル処理
 *
 * @author Noriko Arai <arai@nii.ac.jp>
 * @author Tomoyuki OHNO (Ricksoft Co., Ltd.) <ohno.tomoyuki@ricksoft.jp>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */

App::uses('MultidatabasesAppModel', 'Multidatabases.Model');
App::uses('MultidatabaseModel', 'Multidatabase.Model');
App::uses('MultidatabaseMetadataModel', 'MultidatabaseMetadata.Model');
App::uses('MultidatabaseContentModel', 'MultidatabaseContent.Model');

/**
 * MultidatabaseContentEditPr Model
 *
 * @author Tomoyuki OHNO (Ricksoft, Co., Ltd.) <ohno.tomoyuki@ricksoft.jp>
 * @package NetCommons\Multidatabases\Model
 *
 */
class MultidatabaseContentEditPr extends MultidatabasesAppModel {

/**
 * Custom database table name
 *
 * @var string
 */
	public $useTable = false;

/**
 * 保存用に処理対象のカラムNoを取得する
 *
 * @param array $metadatas メタデータ配列
 * @param string $valKey フィールド名(value??)
 * @return bool|array
 */
	public function prGetColNo($metadatas, $valKey) {
		if (! strstr($valKey, 'value')) {
			return false;
		}

		$colNo = (int)str_replace('value', '', $valKey);
		if (! isset($metadatas[$colNo])) {
			return false;
		}

		return $colNo;
	}

/**
 * 保存用にメタデータを取得する
 *
 * @return bool|array
 */
	public function prGetMetadatas() {
		$this->loadModels([
			'Multidatabase' => 'Multidatabases.Multidatabase',
			'MultidatabaseMetadata' => 'Multidatabases.MultidatabaseMetadata',
		]);

		if (! $multidatabase = $this->Multidatabase->getMultidatabase()) {
			return false;

		}
		if (! $metadatas = $this->MultidatabaseMetadata->getMetadatasColNo(
			$multidatabase['Multidatabase']['id'])
		) {
			return false;
		}

		return $metadatas;
	}

/**
 * 保存用の単一・複数用の選択肢を取得する
 *
 * @param array $metadatas メタデータ配列
 * @param int $colNo カラムNo
 * @return array
 */
	public function prSaveContentGetSel($metadatas, $colNo) {
		if (isset($metadatas[$colNo]['selections'])) {
			return json_decode($metadatas[$colNo]['selections'], true);
		}
		return [];
	}

/**
 * 保存用に単一選択(+複数選択)の値を変換する
 *
 * @param array $data データ配列
 * @param array $selections 選択肢配列
 * @param array $elementType エレメントタイプ
 * @return string
 */
	public function prSaveContentSelect($data, $selections, $elementType) {
		if ($elementType == 'checkbox') {
			$this->prSaveContentCheck($data, $selections);
		}
		foreach ($selections as $metaSel) {
			if (md5($metaSel) === $data) {
				return $metaSel;
			}
		}
		return '';
	}

/**
 * 保存用に複数選択の値を変換する
 *
 * @param array $data データ配列
 * @param array $selections 選択肢配列
 * @return string
 */
	public function prSaveContentCheck($data, $selections) {
		$tmpArr = $data;
		$tmpRes = [];

		if (empty($tmpArr)) {
			return '';
		}

		foreach ($selections as $metaSel) {
			if (in_array(md5($metaSel), $tmpArr)) {
				$tmpRes[] = $metaSel;
			}
		}
		if (empty($tmpRes)) {
			return '';
		} else {
			return implode('||', $tmpRes);
		}
	}

/**
 * 保存用に自動採番処理を行う
 *
 * @param array $metadatas メタデータ配列
 * @param array $data データ配列
 * @param array $dataOrg オリジナルデータ配列
 * @param bool $isUpdate 更新処理であるか(true:更新,false:新規)
 * @return string
 */
	public function prSaveAutoNum($metadatas, $data, $dataOrg, $isUpdate) {
		foreach ($metadatas as $metadata) {
			$key = 'value' . $metadata['col_no'];
			switch ($metadata['type']) {
				case 'autonumber':
					if (! $isUpdate) {
						$data['MultidatabaseContent'][$key] =
							$this->MultidatabaseMetadata->MultidatabaseMetadataSetting
								->updateAutoNum($metadata['id']);
					} else {
						$data['MultidatabaseContent'][$key] =
							$dataOrg['MultidatabaseContent'][$key];
					}
					break;
			}
		}
		return $data;
	}

/**
 * 保存データ処理（特定の要素への対応）
 *
 * @param array $data データ配列
 * @param string $fieldName フィールド名
 * @param array $metadatas メタデータ配列
 * @param int $colNo カラムNo
 * @param array $attachDelFlgs 添付ファイル削除フラグ
 * @return array
 */
	public function prMakeSaveData($data, $fieldName, $metadatas, $colNo, $attachDelFlgs) {
		if ($metadatas[$colNo]['type'] == 'select' || $metadatas[$colNo]['type'] == 'checkbox') {
			$selections = $this->prSaveContentGetSel($metadatas, $colNo);
			$data['MultidatabaseContent'][$fieldName] =
				$this->prSaveContentSelect(
					$data['MultidatabaseContent'][$fieldName],
					$selections,
					$metadatas[$colNo]['type']
				);
		}

		if ($metadatas[$colNo]['type'] == 'file' || $metadatas[$colNo]['type'] == 'image') {
			$attachFileName = '';
			if (isset($data['MultidatabaseContent'][$fieldName]['name'])) {
				$attachFileName = $data['MultidatabaseContent'][$fieldName]['name'];
			}

			if (! empty($attachFileName)) {
				$data['MultidatabaseContent'][$fieldName . '_attach'] =
					$data['MultidatabaseContent'][$fieldName];
			}

			$prAttachFile = $this->__prAttachFile(
				$attachFileName,
				$fieldName,
				$attachDelFlgs
			);

			$data = $this->__prMergeAttachFile($data, $prAttachFile);

			$data['MultidatabaseContent'][$fieldName] = '';
		}
		return $data;
	}

/**
 * 添付ファイルに関する保存前処理(フラグ設定のマージ)
 *
 * @param array $data データ配列
 * @param array $prAttachFile フラグ設定
 * @return array
 */
	private function __prMergeAttachFile($data, $prAttachFile) {
		foreach ($prAttachFile as $atFileKey => $atFileVal) {
			if (!empty($atFileVal)) {
				switch ($atFileKey) {
					case 'attachField':
						$data['attachFields'][] = $atFileVal;
						break;
					case 'removeAttachField':
						$data['removeAttachFields'][] = $atFileVal;
						break;
					case 'skipAttach':
						$data['skipAttaches'][] = $atFileVal;
						break;
				}
			}
		}
		return $data;
	}

/**
 * 添付ファイルに関する保存前処理(フラグ設定)
 *
 * @param string $attachFileName 添付ファイル名
 * @param string $fieldName フィールド名（添付ファイルのフィールド）
 * @param array $attachDelFlgs 添付ファイル削除フラグ（削除対象のフィールドにfalseが設定される）
 * @return array
 */
	private function __prAttachFile($attachFileName, $fieldName, $attachDelFlgs) {
		$result['attachField'] = '';
		$result['removeAttachField'] = '';
		$result['skipAttach'] = '';

		if (empty($attachFileName)) {
			if (isset($attachDelFlgs[$fieldName]) && $attachDelFlgs[$fieldName]) {
				$result['attachField'] = $fieldName . '_attach';
				$result['removeAttachField'] = $fieldName;
			} else {
				$result['skipAttach'] = $fieldName . '_attach';
			}
		} else {
			$result['attachField'] = $fieldName . '_attach';
		}

		return $result;
	}
}
