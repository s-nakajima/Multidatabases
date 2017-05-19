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
 * @param array $data データ配列
 * @param array $metadatas メタデータ配列
 * @return array
 */
	public function getAttachDelFlg($data, $metadatas) {
		$this->loadModels([
			'MultidatabaseContentEditPr' => 'Multidatabases.MultidatabaseContentEditPr',
		]);

		// 添付ファイル削除フラグを立てる
		$result = [];
		$tmp = $data;

		foreach (array_keys($tmp['MultidatabaseContent']) as $key) {
			if ($colNo = $this->MultidatabaseContentEditPr->prGetColNo($metadatas, $key)) {
				if (
					(
						$metadatas[$colNo]['type'] == 'file' ||
						$metadatas[$colNo]['type'] == 'image'
					) &&
					isset($tmp[$key . '_attach_del'])
				) {
					if (
						isset($tmp[$key . '_attach_del']) &&
						$tmp[$key . '_attach_del'] == 'on'
					) {
						$result[$key] = true;
					} else {
						$result[$key] = false;
					}
					//unset($data[$key . '_attach_del']);
				}
			}
		}
		return [
			'attachDelFlg' => $result,
			'data' => $data
		];
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
}

