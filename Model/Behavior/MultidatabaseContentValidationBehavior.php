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
 * @var array metadata list
 */
	private $__metadatas;

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

		$this->__metadatas = $metadatas;
		$result = [];
		foreach ($metadatas as $metadata) {
			if ($metadata['is_require']) {
				$tmp = [];
				$tmp['required'] = true;

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
						$tmp = [
							'notBlankFile' => $tmp
						];
						break;
					default:
						$tmp['rule'] = ['notBlank'];
						$tmp['message'] = sprintf(
							__d('net_commons', 'Please input %s.'),
							$metadata['name']
						);
						break;
				}
				$result['value' . $metadata['col_no']] = $tmp;
			}
		}

		$this->__setupUploadFileValidations($model, $metadatas);

		return ValidateMerge::merge($model->validate, $result);
	}

/**
 * AttachmentBehaviorによるバリデーションが働くようにuploadSettingsで設定する
 *
 * @param Model $model 元モデル
 * @param array $metadatas metadata list
 * @return void
 */
	private function __setupUploadFileValidations(Model $model, array $metadatas) {
		if (!$model->Behaviors->loaded('Files.Attachment')) {
			return;
		}
		$fileTypes = ['image', 'file'];
		foreach ($metadatas as $metadata) {
			if (in_array($metadata['type'], $fileTypes, true)) {
				$uploadFieldName = 'value' . $metadata['col_no'];
				// ファイルのバリデーション用に uploadSettings(valueNフィールド)する
				$this->__setupUploadFileValidation($model, $uploadFieldName);
			}
		}
	}

/**
 * setupUploadFileValidation
 *
 * @param Model $model 元モデル
 * @param string $field フィールド名
 * @return void
 */
	private function __setupUploadFileValidation(Model $model, $field) {
		$model->uploadSettings($field);
		//$model->validate[$field]['size'] =
		//	[
		//		'rule' => ['validateRoomFileSizeLimit']
		//	];
		//
		//// 元モデルに拡張子バリデータをセットする
		//$uploadFile = ClassRegistry::init('Files.UploadFile');
		//
		//$uploadAllowExtension = $uploadFile->getAllowExtension();
		//$model->validate[$field]['extension'] = [
		//	// システム設定の値をとってくる。trimすること
		//	'rule' => ['isValidExtension', $uploadAllowExtension, false],
		//	'message' => __d('files', 'It is upload disabled file format')
		//];
	}

/**
 * afterValidate
 *
 * バリデーション用にAttachmentBehaviorに追加した設定を除外する
 *
 * @param Model $model 元モデル
 * @return mixed
 */
	public function afterValidate(Model $model) {
		$this->__removeUploadFileValidations($model);
		return parent::afterValidate($model);
	}

/**
 * バリデーション用にAttachmentBehaviorに追加した設定を除外する
 *
 * @param Model $model 元モデル
 * @return void
 */
	private function __removeUploadFileValidations(Model $model) {
		if (!$model->Behaviors->loaded('Files.Attachment')) {
			return;
		}

		$fileTypes = ['image', 'file'];
		foreach ($this->__metadatas as $metadata) {
			if (in_array($metadata['type'], $fileTypes, true)) {
				$uploadFieldName = 'value' . $metadata['col_no'];
				// ファイルのバリデーション用に valueXXフィールドに対してuploadSettings()したのを無効化する
				// 保存時には valueXX でなく valueXX_attachにデータを移して保存をするつくりになっているため、
				// そちらに影響が出ないようにするため。
				$this->__removeUploadFileValidation($model, $uploadFieldName);
			}
		}
	}

/**
 * removeUploadFileValidation
 *
 * @param Model $model 元モデル
 * @param string $field フィールド名
 * @return void
 */
	private function __removeUploadFileValidation(Model $model, $field) {
		$model->removeUploadSettings($field);
		//unset($model->validate[$field]['size']);
		//unset($model->validate[$field]['extension']);
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
