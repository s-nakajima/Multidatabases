<?php
/**
 * MultidatabaseContent Validation Behavior
 *
 * @author Mitsuru Mutaguchi <mutaguchi@opensource-workshop.jp>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */

App::uses('MultidatabaseContent', 'MultidatabaseContents.Model');

/**
 * Summary for MultidatabaseContent Validation Behavior
 */
class MultidatabaseContentValidationBehavior extends ModelBehavior {

/**
 * Make validation rules
 * バリデーションルールの作成
 *
 * @param Model $model モデル
 * @param array $deleteFiles 削除ファイルリスト
 * @return array|bool
 */
	public function makeValidation(Model $model, $deleteFiles) {
		if (!$multidatabase = $model->Multidatabase->getMultidatabase()) {
			return false;
		}

		if (!$metadatas =
			$model->MultidatabaseMetadata->getEditMetadatas(
				$multidatabase['Multidatabase']['id']
			)
		) {
			return false;
		}

		$result = [];
		foreach ($metadatas as $metadata) {
			if ($metadata['is_require']) {
				$tmp = [];
				switch ($metadata['type']) {
					case 'checkbox':
						$tmp['rule'] = [
							'multiple',
							[
								'min' => 1,
							],
						];
						break;
					case 'image':
					case 'file':
						$tmp['rule'] = ['notBlankFile', in_array($metadata['col_no'], $deleteFiles, true)];
						$tmp['message'] = sprintf(
							__d('net_commons', 'Please input %s.'),
							$metadata['name']
						);
						break;
					default:
						$tmp['rule'] = ['notBlank'];
						$tmp['message'] = sprintf(
							__d('net_commons', 'Please input %s.'),
							$metadata['name']
						);
						break;
				}
				$tmp['required'] = true;
				$result['value' . $metadata['col_no']] = $tmp;
			}
		}

		return ValidateMerge::merge($model->validate, $result);
	}

/**
 * ファイルタイプのnotBlank
 *
 * @param Model $model モデル
 * @param array $check チェック値
 * @param bool $isDelete ファイル削除か否か
 * @return bool
 */
	public function notBlankFile(Model $model, $check, $isDelete) {
		$key = key($check);
		$value = array_shift($check);
		return !empty($value['name']) ||
				!$isDelete && !empty($model->data['UploadFile'][$key . '_attach']);
	}
}