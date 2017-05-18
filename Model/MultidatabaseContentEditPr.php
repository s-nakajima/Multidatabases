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
}
