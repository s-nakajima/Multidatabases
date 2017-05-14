<?php
/**
 * MultidatabaseContentEdit Model
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
 * MultidatabaseContentEdit Model
 *
 * @author Tomoyuki OHNO (Ricksoft, Co., Ltd.) <ohno.tomoyuki@ricksoft.jp>
 * @package NetCommons\Multidatabases\Model
 *
 */
class MultidatabaseContentEdit extends MultidatabasesAppModel {

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
 * 保存用に単一選択の値を変換する
 *
 * @param array $data データ配列
 * @param array $selections 選択肢配列
 * @return string
 */
	public function prSaveContentSelect($data, $selections) {
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
	public function prSaveConentCheck($data, $selections) {
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
 * 保存データを生成する
 *
 * @param array $data データ配列
 * @param array $metadatas メタデータ配列
 * @param boolean $isUpdate 更新処理であるか(true:更新,false:新規)
 * @return array
 */
	public function makeSaveData($data, $metadatas, $isUpdate = false) {
		$this->loadModels([
			'MultidatabaseContent' => 'Multidatabases.MultidatabaseContent',
		]);


		$multidatabaseContent = $data['MultidatabaseContent'];
		$skipAttaches = [];
		$attachFields = [];

		$dataOrg = $this->MultidatabaseContent->getEditData(
				[
					'MultidatabaseContent.key'=> $data['MultidatabaseContent']['key']
				]
			);

		foreach ($multidatabaseContent as $key => $val) {
			if ($colNo = $this->prGetColNo($metadatas, $key)) {

				$selections = $this->prSaveContentGetSel($metadatas, $colNo);
				switch ($metadatas[$colNo]['type']) {
					case 'select':
						$data['MultidatabaseContent'][$key] =
							$this->prSaveContentSelect(
								$data['MultidatabaseContent'][$key],
								$selections
							);
						break;
					case 'checkbox':
						$data['MultidatabaseContent'][$key] =
							$this->prSaveConentCheck(
								$data['MultidatabaseContent'][$key],
								$selections
							);
						break;
					case 'file':
					case 'image':
						if (empty($data['MultidatabaseContent'][$key]['name'])) {
							// 未アップロードの場合は既存ファイルを保持する
							$skipAttaches[] = $key . '_attach';
						} else {
							$data['MultidatabaseContent'][$key . '_attach'] =
								$data['MultidatabaseContent'][$key];
							$data['MultidatabaseContent'][$key] = $val['name'];
							$attachFields[] = $key . '_attach';
						}
						$data['MultidatabaseContent'][$key] = '';
						break;
				}
			}
		}

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
				default:
					break;
			}
		}
		$result = [
			'data' => $data,
			'attachFields' => $attachFields,
			'skipAttaches' => $skipAttaches
		];

		return $result;
	}

/**
 * 編集用データを生成する
 *
 * @param array $content コンテンツ配列
 * @param array $metadatas メタデータ配列
 * @return array
 */
	public function makeEditData($content, $metadatas) {
		$result = $content;

		foreach ($metadatas as $metadata) {
			if (
				isset($content['MultidatabaseContent']['value' . $metadata['col_no']]) &&
				$content['MultidatabaseContent']['value' . $metadata['col_no']] <> ''
			) {
				$tmpValue = $content['MultidatabaseContent']['value' . $metadata['col_no']];
				switch ($metadata['type']) {
					case 'radio':
					case 'select':
						$result['MultidatabaseContent']['value' . $metadata['col_no']]
							= md5($tmpValue);
						break;
					case 'checkbox' :
						$tmpValArr = explode('||', $tmpValue);
						$tmpValRes = [];
						foreach ($tmpValArr as $val) {
							$tmpValRes[] = md5($val);
						}
						$result['MultidatabaseContent']['value' . $metadata['col_no']]
							= $tmpValRes;
						break;
					default:
						break;
				}
			}
		}
		return $result;
	}
}
