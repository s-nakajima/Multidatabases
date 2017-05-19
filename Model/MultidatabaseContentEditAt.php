<?php
/**
 * MultidatabaseContentEditAt Model
 * 汎用データベースコンテンツデータに関するモデル処理（添付ファイル）
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
App::uses('MultidatabaseContentFileModel', 'MultidatabaseContentFile.Model');
App::uses('MultidatabaseContentEditPrModel', 'MultidatabaseContentEditPr.Model');

/**
 * MultidatabaseContentEditAt Model
 *
 * @author Tomoyuki OHNO (Ricksoft, Co., Ltd.) <ohno.tomoyuki@ricksoft.jp>
 * @package NetCommons\Multidatabases\Model
 *
 */
class MultidatabaseContentEditAt extends MultidatabasesAppModel {

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
		foreach (array_keys($tmp) as $key) {
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
 * 添付ファイルに関する保存前処理
 *
 * @param string $AttachFileName 添付ファイル名
 * @param string $fieldName フィールド名（添付ファイルのフィールド）
 * @param array $attachDelFlgs 添付ファイル削除フラグ（削除対象のフィールドにfalseが設定される）
 * @return array
 */
	public function prAttachFile($AttachFileName, $fieldName, $attachDelFlgs) {
		$result['attachField'] = '';
		$result['removeAttachFld'] = '';
		$result['skipAttach'] = '';

		if (empty($AttachFileName)) {
			if (isset($attachDelFlgs[$fieldName]) && $attachDelFlgs[$fieldName]) {
				$result['attachField'] = $fieldName . '_attach';
				$result['removeAttachFld'] = $fieldName;
			} else {
				$result['skipAttach'] = $fieldName . '_attach';
			}
		} else {
			$result['attachField'] = $fieldName . '_attach';
		}

		return $result;
	}

/**
 * 添付ファイルパスワードを取得する
 *
 * @param array $content コンテンツ配列
 * @param array $metadatas メタデータ配列
 * @return array
 */
	public function getAttachPasswords($content, $metadatas) {
		$this->loadModels([
			'MultidatabaseContentEditPr' => 'Multidatabases.MultidatabaseContentEditPr',
		]);

		// 添付ファイルパスワードを取得する
		$result = [];
		$tmp = $content;
		foreach (array_keys($tmp) as $key) {
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
 * 添付ファイル削除を行う
 *
 * @param array $removeAttachFields 削除対象フィールド配列
 * @param string $contentKey コンテンツキー
 * @return void
 * @throws InternalErrorException
 */
	public function removeAttachFile($removeAttachFields, $contentKey) {
		// ファイルを削除する
		if (!empty($removeAttachFields)) {
			foreach ($removeAttachFields as $val) {
				if (! $this->MultidatabaseContentFile
					->removeFileByContentKey($contentKey, $val)) {
					throw new InternalErrorException(__d('net_commons', 'Internal Server Error'));
				}
			}
		}
	}

}

