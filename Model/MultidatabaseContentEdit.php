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
App::uses('MultidatabaseContentEditPrModel', 'MultidatabaseContentEditPr.Model');

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
 * 添付ファイル削除フラグを立てる
 *
 * @param array $content コンテンツ配列
 * @param array $metadatas メタデータ配列
 * @return array
 */
	public function getAttachDelFlg($content, $metadatas) {
		$this->loadModels([
			'MultidatabaseContentEditPr' => 'Multidatabases.MultidatabaseContentEditPr',
		]);

		// 添付ファイル削除フラグを立てる
		$result = [];
		$tmp = $content;
		foreach ($tmp as $key => $val) {
			if ($colNo = $this->MultidatabaseContentEditPr->prGetColNo($metadatas, $key)) {
				if (
					(
						$metadatas[$colNo]['type'] == 'file' ||
						$metadatas[$colNo]['type'] == 'image'
					) &&
					isset($tmp[$key . '_attach_del'])
				) {
					if (
						isset($tmp[$key . '_attach_del'][0]) &&
						$tmp[$key . '_attach_del'][0] == '1'
					) {
						$result[$key] = true;
					} else {
						$result[$key] = false;
					}
					unset($content[$key . '_attach_del']);
				}
			}
		}

		return [
			'attachDelFlg' => $result,
			'content' => $content
		];
	}

/**
 * 添付ファイル削除フラグを立てる
 *
 * @param array $content コンテンツ配列
 * @param array $metadatas メタデータ配列
 * @return array
 */
	public function getAttachPasswords($content, $metadatas) {
		$this->loadModels([
			'MultidatabaseContentEditPr' => 'Multidatabases.MultidatabaseContentEditPr',
		]);

		// 添付ファイル削除フラグを立てる
		$result = [];
		$tmp = $content;
		foreach ($tmp as $key => $val) {
			if ($colNo = $this->MultidatabaseContentEditPr->prGetColNo($metadatas, $key)) {
				if ($metadatas[$colNo]['type'] == 'file' &&
					isset($tmp[$key . '_attach_pw'])
				) {
					$tmpPw = trim($tmp[$key . '_attach_pw']);

					if (! empty($tmpPw)) {
						$result['value' . $colNo] = $tmpPw;
					}
					unset($content[$key . '_attach_pw']);
				}
			}
		}

		return [
			'attachPasswords' => $result,
			'content' => $content
		];
	}

/**
 * 保存データを生成する
 *
 * @param array $data データ配列
 * @param array $metadatas メタデータ配列
 * @param bool $isUpdate 更新処理であるか(true:更新,false:新規)
 * @return array
 */
	public function makeSaveData($data, $metadatas, $isUpdate) {
		$this->loadModels([
			'MultidatabaseContent' => 'Multidatabases.MultidatabaseContent',
			'MultidatabaseContentEditPr' => 'Multidatabases.MultidatabaseContentEditPr',
		]);

		$multidatabaseContent = $data['MultidatabaseContent'];
		$skipAttaches = [];
		$attachFields = [];
		$removeAttachFlds = [];
		$attachPasswords = [];

		$dataOrg = $this->MultidatabaseContent->getEditData(
				[
					'MultidatabaseContent.key' => $data['MultidatabaseContent']['key']
				]
			);

		// 添付ファイル削除フラグを立てる
		$tmp = $this->getAttachDelFlg($multidatabaseContent, $metadatas);
		$multidatabaseContent = $tmp['content'];
		$attachDelFlg = $tmp['attachDelFlg'];

		// パスワードを取得する
		$tmp = $this->getAttachPasswords($multidatabaseContent, $metadatas);
		$multidatabaseContent = $tmp['content'];
		$attachPasswords = $tmp['attachPasswords'];

		foreach ($multidatabaseContent as $key => $val) {
			if ($colNo = $this->MultidatabaseContentEditPr->prGetColNo($metadatas, $key)) {
				$selections = $this->MultidatabaseContentEditPr->prSaveContentGetSel($metadatas, $colNo);
				switch ($metadatas[$colNo]['type']) {
					case 'select':
						$data['MultidatabaseContent'][$key] =
							$this->MultidatabaseContentEditPr->prSaveContentSelect(
								$data['MultidatabaseContent'][$key],
								$selections
							);
						break;
					case 'checkbox':
						$data['MultidatabaseContent'][$key] =
							$this->MultidatabaseContentEditPr->prSaveContentCheck(
								$data['MultidatabaseContent'][$key],
								$selections
							);
						break;
					case 'file':
					case 'image':
						if (empty($data['MultidatabaseContent'][$key]['name'])) {
							if (isset($attachDelFlg[$key]) && $attachDelFlg[$key]) {
								$attachFields[] = $key . '_attach';
								$removeAttachFlds[] = $key;
							} else {
								$skipAttaches[] = $key . '_attach';
							}
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

		// 自動採番を行う
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
			'skipAttaches' => $skipAttaches,
			'removeAttachFields' => $removeAttachFlds,
			'attachPasswords' => $attachPasswords
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
		$this->loadModels([
			'MultidatabaseContent' => 'Multidatabases.MultidatabaseContent',
			'MultidatabaseContentEditPr' => 'Multidatabases.MultidatabaseContentEditPr',
		]);

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

			$result += $this->__makeEditDataFile($result, $metadata);
		}
		return $result;
	}

/**
 * ファイルに関する編集用データを生成する
 *
 * @param array $content コンテンツ配列
 * @param array $metadata メタデータ配列
 * @return array
 */
	private function __makeEditDataFile($content, $metadata) {
		if ($metadata['type'] == 'file') {
			$authPw = $this->MultidatabaseContent->getAuthKey(
				$content['MultidatabaseContent']['id'], 'value' . $metadata['col_no']);

			if (! $authPw) {
				$content['MultidatabaseContent']['value' . $metadata['col_no'] . '_attach_pw_flg']
					= 0;
			} else {
				$content['MultidatabaseContent']['value' . $metadata['col_no'] . '_attach_pw']
					= $authPw['authorization_key'];
				$content['MultidatabaseContent']['value' . $metadata['col_no'] . '_attach_pw_flg']
					= 1;
			}
		}
		return $content;
	}
}
