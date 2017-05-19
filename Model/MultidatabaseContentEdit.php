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
App::uses('MultidatabaseContentFileModel', 'MultidatabaseContentFile.Model');
App::uses('MultidatabaseContentEditPrModel', 'MultidatabaseContentEditPr.Model');
App::uses('MultidatabaseContentEditAtModel', 'MultidatabaseContentEditAt.Model');

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
			'MultidatabaseContentEditAt' => 'Multidatabases.MultidatabaseContentEditAt',
		]);

		$dataOrg = $this->MultidatabaseContent->getEditData(
				[
					'MultidatabaseContent.key' => $data['MultidatabaseContent']['key']
				]
			);

		$multidatabaseContent = $data['MultidatabaseContent'];

		// 添付ファイル削除フラグを立てる
		$tmp = $this->MultidatabaseContentEditAt->getAttachDelFlg($data, $metadatas);
		$data = $tmp['data'];
		$attachDelFlgs = $tmp['attachDelFlg'];

		// パスワードを取得する
		$tmp = $this->MultidatabaseContentEditAt->getAttachPasswords($multidatabaseContent, $metadatas);
		$multidatabaseContent = $tmp['content'];
		$attachPasswords = $tmp['attachPasswords'];

		foreach (array_keys($multidatabaseContent) as $key) {
			if (! $colNo = $this->MultidatabaseContentEditPr->prGetColNo($metadatas, $key)) {
				continue;
			}

			$data = $this->MultidatabaseContentEditPr->prMakeSaveData(
				$data, $key, $metadatas, $colNo, $attachDelFlgs);
		}

		$data = $this->MultidatabaseContentEditPr->prSaveAutoNum($metadatas, $data, $dataOrg, $isUpdate);

		$result = $data;
		$result['attachDelFlg'] = $attachDelFlgs;
		$result['attachPasswords'] = $attachPasswords;

		foreach (['skipAttaches', 'attachFields', 'removeAttachFields'] as $key) {
			if (!isset($result[$key])) {
				$result[$key] = [];
			}
		}

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
			'MultidatabaseContentEditAt' => 'Multidatabases.MultidatabaseContentEditAt',
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

			$result = $this->__makeEditDataFile($result, $metadata);
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
		$this->loadModels([
			'MultidatabaseContentFile' => 'Multidatabases.MultidatabaseContentFile',
		]);

		if ($metadata['type'] == 'file') {
			$authPw = $this->MultidatabaseContentFile->getAuthKey(
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
