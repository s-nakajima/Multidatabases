<?php
/**
 * MultidatabaseContent Model
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
App::uses('MultidatabaseContentEditModel', 'MultidatabaseContentEdit.Model');
App::uses('TemporaryFolder', 'Files.Utility');

/**
 * MultidatabaseContent Model
 *
 * @author Tomoyuki OHNO (Ricksoft, Co., Ltd.) <ohno.tomoyuki@ricksoft.jp>
 * @package NetCommons\Multidatabases\Model
 *
 */
class MultidatabaseContent extends MultidatabasesAppModel {

/**
 * Validation rules
 *
 * @var array
 */
	public $validate = [];

/**
 * belongsTo associations
 *
 * @var array
 */
	public $belongsTo = [
		/*
		'Multidatabase' => array(
			'className' => 'Multidatabases.Multidatabase',
			'foreignKey' => 'multidatabase_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		),
		'Language' => array(
			'className' => 'M17.Language',
			'foreignKey' => 'language_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		),
		*/
		'Block' => [
			'className' => 'Blocks.Block',
			'foreignKey' => 'block_id',
			'conditions' => '',
			'fields' => '',
			'order' => '',
			'counterCache' => [
				'content_count' => [
					//'MultidatabaseContent.is_origin' => true,
					'MultidatabaseContent.is_latest' => true,
				],
			],
		],
	];

/**
 * Behavior
 *
 * @var array
 */
	public $actsAs = [
		'NetCommons.Trackable',
		'NetCommons.OriginalKey',
		'Workflow.Workflow',
		'Likes.Like',
		'Workflow.WorkflowComment',
		'ContentComments.ContentComment',
		'Files.Attachment',
	];

/**
 * @var array 絞り込みフィルタ保持値
 */
	protected $_filter = [
		'status' => 0,
	];

/**
 * Before validate
 *
 * @param array $options オプション
 * @return bool
 */
	public function beforeValidate($options = []) {
		$this->validate = $this->makeValidation();

		return parent::beforeValidate($options);
	}

/**
 * 編集用のデータを取得する
 *
 * @param array $conditions データ取得条件
 * @return array|bool
 */
	public function getEditData($conditions = []) {
		$this->loadModels([
			'Multidatabase' => 'Multidatabases.Multidatabase',
			'MultidatabaseMetadata' => 'Multidatabases.MultidatabaseMetadata',
			'MultidatabaseContentEdit' => 'Multidatabases.MultidatabaseContentEdit',
		]);

		if (empty($conditions)) {
			return false;
		}

		$options = [
			'conditions' => $conditions,
			'recursive' => 0,
		];

		$content = $this->find('first', $options);
		$metadatas = $this->MultidatabaseMetadata->getEditMetadatas();

		if (!$content || !$metadatas) {
			return false;
		}

		return $this->MultidatabaseContentEdit->makeEditData($content, $metadatas);
	}

/**
 * Get contents
 * 複数のコンテンツを取得
 *
 * @param array $conditions 条件
 * @param int $recursive recursive
 * @return array|bool
 */
	public function getMultidatabaseContents($conditions = [], $recursive = 0) {
		$this->loadModels([
			'Multidatabase' => 'Multidatabases.Multidatabase',
		]);

		if (!$multidatabase = $this->Multidatabase->getMultidatabase()) {
			return false;
		}

		if (empty($conditions)) {
			$conditions = [];
		}

		$conditions += [
			'multidatabase_key' => $multidatabase['Multidatabase']['key']
		];

		$result = $this->find('all', [
			'recursive' => $recursive,
			'conditions' => $conditions
		]);

		return $result;
	}

/**
 * Make validation rules
 * バリデーションルールの作成
 *
 * @return array|bool
 */
	public function makeValidation() {
		$this->loadModels([
			'MultidatabaseMetadata' => 'Multidatabases.MultidatabaseMetadata',
			'Multidatabase' => 'Multidatabases.Multidatabase',
		]);

		if (!$multidatabase = $this->Multidatabase->getMultidatabase()) {
			return false;
		}

		if (!$metadatas =
			$this->MultidatabaseMetadata->getEditMetadatas(
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
					default:
						$tmp['rule'][] = 'notBlank';
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

		return Hash::merge($this->validate, $result);
	}

/**
 * Clear values
 * 削除対象カラムに存在する値をクリアする
 *
 * @param string $multidatabaseKey 汎用データベースKey（プラグインキー）
 * @param array $colNos 列番号
 * @return bool
 * @throws InternalErrorException
 */
	public function clearValues($multidatabaseKey = null, $colNos = []) {
		if (
			is_null($multidatabaseKey)
		) {
			return false;
		}

		$conditions['multidatabase_key'] = $multidatabaseKey;

		$data = [];
		foreach ($colNos as $colNo) {
			$data['value' . $colNo] = '';
		}

		if (!$this->updateAll($data, $conditions)) {
			throw new InternalErrorException(__d('net_commons', 'Internal Server Error'));
		}

		return true;
	}

/**
 * パスワードを設定する
 *
 * @param int $content_id  コンテンツID
 * @param array $passwords パスワード配列
 * @return bool
 */
	public function saveAuthKey($content_id, $passwords) {
		$this->loadModels([
			'AuthorizationKeys' => 'AuthorizationKeys.AuthorizationKey',
		]);

		$baseDat = [
			'model' => 'MultidatabaseContent',
			'content_id' => $content_id
		];

		foreach ($passwords as $key => $val) {
			if(! $this->AuthorizationKeys->saveAuthorizationKey(
				$baseDat['model'],
				$baseDat['content_id'],
				$val,
				$key
			)) {
				throw new InternalErrorException(__d('net_commons', 'Internal Server Error'));
			}
		}

		return true;
	}

/**
 * 認証情報を取得する
 *
 * @param int $content_id  コンテンツID
 * @param string $field パスワードフィールド
 * @return bool
 */
	public function getAuthKey($content_id, $field) {
		$this->loadModels([
			'AuthorizationKeys' => 'AuthorizationKeys.AuthorizationKey',
		]);

		$options = [
			'conditions' => [
				'model' => 'MultidatabaseContent',
				'content_id' => $content_id,
				'additional_id' => $field
			],
			'recursive' => 0,
		];

		$authKey = $this->AuthorizationKeys->find('first', $options);

		if (! $authKey) {
			return false;
		}

		if (! isset($authKey['AuthorizationKey'])) {
			return false;
		}

		return $authKey['AuthorizationKey'];
	}


/**
 * Save content
 * コンテンツを保存する
 *
 * @param array $data 保存するコンテンツデータ
 * @param bool $isUpdate 更新処理であるか(true:更新,false:新規)
 * @param bool $skipValidate バリデーションをスキップするか(true:スキップする,false:スキップしない)
 * @return bool|array
 * @throws InternalErrorException
 */
	public function saveContent($data, $isUpdate = false) {
		$this->loadModels([
			'MultidatabaseContentEdit' => 'Multidatabases.MultidatabaseContentEdit',
		]);

		if (! $metadatas = $this->MultidatabaseContentEdit->prGetMetadatas()) {
			throw new InternalErrorException(__d('net_commons', 'Internal Server Error'));
		}

		$this->set($data);

		if (! $this->validates()) {
			return false;
		}

		$result = $this->MultidatabaseContentEdit->makeSaveData($data, $metadatas, $isUpdate);

		return $this->__saveContent(
				$result['data'],
				$result['attachFields'],
				$result['skipAttaches'],
				$result['removeAttachFields'],
				$result['attachPasswords']
		);
	}

/**
 * Save content for Import
 * コンテンツを保存する(インポート用)
 *
 * @param array $data 保存するコンテンツデータ
 * @return bool|array
*/
	public function saveContentForImport($data) {
		$this->begin();
		try {
			if (($savedData = $this->save($data, false)) === false) {
				throw new InternalErrorException(__d('net_commons', 'Internal Server Error'));
			}
			$this->commit();
		} catch (Exception $e) {
			$this->rollback($e);
		}

		return true;
	}

/**
 * Get File download URL
 * ファイルダウンロードURLを出力
 *
 * @return void
 */
	public function getFileURL() {
		$contentKey = $this->request->params['pass'][0];
		$options['field'] = $this->request->params['pass'][1];
		$options['size'] = Hash::get($this->request->params['pass'], 2, 'medium');
		return $this->Download->doDownload($contentKey, $options);
	}

/**
 * Get File Info
 * ファイル情報を出力
 *
 * @param array $content コンテンツ配列
 * @param string $fieldName フィールド名
 * @return array
 */
	public function getFileInfo($content,$fieldName = '') {
		$UploadFile = ClassRegistry::init('Files.UploadFile');
		$pluginKey = 'multidatabases';
		if (empty($fieldName)) {
			$files = [];
			for ($i = 1; $i <= 100; $i++) {
				$tmp = $UploadFile->getFile(
					$pluginKey,
					$content['MultidatabaseContent']['id'],
					'value' . $i . '_attach'
				);
				if (!empty($tmp)) {
					$files[] = $tmp;
				}
			}
			return $files;
		} else {
			$file = $UploadFile->getFile(
				$pluginKey,
				$content['MultidatabaseContent']['id'],
				$fieldName . '_attach'
			);
			return $file;
		}
	}

/**
 * Get File Info By ID
 * ファイル情報を出力(IDより)
 *
 * @param int $id コンテンツID
 * @param string $fieldName フィールド名
 * @return array|bool
 */
	public function getFileInfoById($id,$fieldName = '') {
		$content = $this->getEditData([
			'MultidatabaseContent.id' => $id,
			'MultidatabaseContent.is_active' => true,
			'MultidatabaseContent.is_latest' => true,
		]);

		if (! $content) {
			return false;
		}
		return $this->getFileInfo($content,$fieldName);
	}

/**
 * Get File Info By ContentKey
 * ファイル情報を出力(コンテンツKeyより)
 *
 * @param string $key コンテンツKey
 * @param string $fieldName フィールド名
 * @return array|bool
 */
	public function getFileInfoByContentKey($key,$fieldName = '') {
		$content = $this->getEditData([
			'MultidatabaseContent.key' => $key,
			'MultidatabaseContent.is_active' => 1,
			'MultidatabaseContent.is_latest' => 1,
		]);

		if (! $content) {
			return false;
		}
		return $this->getFileInfo($content,$fieldName);
	}

/**
 * Remove File(s)
 * ファイルを削除する
 *
 * @param array $content コンテンツ配列
 * @param string $fieldName フィールド名
 * @return bool
 */
	public function removeFile($content, $fieldName = '') {
		$fileInfo = $this->getFileInfo($content, $fieldName);
		return $this->__removeFile($fileInfo,$fieldName);
	}

/**
 * Remove File(s)
 * ファイルを削除する（コンテンツIDより）
 *
 * @param int $id コンテンツID
 * @param string $fieldName フィールド名
 * @return bool
 */
	public function removeFileById($id, $fieldName = '') {
		$fileInfo = $this->getFileInfoById($id, $fieldName);
		return $this->__removeFile($fileInfo,$fieldName);
	}

/**
 * Remove File(s)
 * ファイルを削除する（コンテンツKeyより）
 *
 * @param string $key コンテンツKey
 * @param string $fieldName フィールド名
 * @return bool
 */
	public function removeFileByContentKey($key, $fieldName = '') {
		$fileInfo = $this->getFileInfoByContentKey($key, $fieldName);
		return $this->__removeFile($fileInfo,$fieldName);
	}

/**
 * RemoveFile(s) Base
 * ファイルを削除する
 *
 * @param $fileInfo array アップロードファイル情報
 * @param string $fieldName フィールド名
 * @return bool
 */
	private function __removeFile($fileInfo,$fieldName) {
		$UploadFile = ClassRegistry::init('Files.UploadFile');

		if (empty($fileInfo)) {
			return false;
		}

		if (empty($fieldName)) {
			foreach ($fileInfo as $val) {
				$UploadFile->removeFile($val['UploadFilesContent']['content_id'], $val['UploadFile']['id']);
			}
		} else {
			$UploadFile->removeFile($fileInfo['UploadFilesContent']['content_id'], $fileInfo['UploadFile']['id']);
		}

		return true;
	}

/**
 * Get conditions
 * UserIdと権限から参照可能なEntryを取得するCondition配列を返す
 *
 * @param int $blockId ブロックID
 * @param array $permissions 権限
 * @return array condition
 */
	public function getConditions($blockId, $permissions) {
		// contentReadable falseなら何も見えない
		if ($permissions['content_readable'] === false) {
			$conditions = ['MultidatabaseContent.id' => 0];

			return $conditions;
		}

		// デフォルト絞り込み条件
		$conditions = [
			'MultidatabaseContent.block_Id' => $blockId,
		];

		$conditions = $this->getWorkflowConditions($conditions);

		return $conditions;
	}

/**
 * Delete content
 * コンテンツの削除
 *
 * @param string $key コンテンツキー
 * @return bool
 * @throws InternalErrorException
 */
	public function deleteContentByKey($key) {
		$this->begin();

		$result = false;

		try {
			$this->contentKey = $key;

			// コメントの削除
			$this->deleteCommentsByContentKey($key);

			// 添付ファイルの削除
			if(! $this->removeFileByContentKey($key)) {
				throw new InternalErrorException(__d('net_commons', 'Internal Server Error'));
			}

			$conditions = [
				'MultidatabaseContent.Key' => $key,
			];

			if ($result = $this->deleteAll($conditions, true, true)) {
				$this->commit();
			} else {
				throw new InternalErrorException(__d('net_commons', 'Internal Server Error'));
			}
		} catch (Exception $e) {
			$this->rollback($e);
		}

		return $result;
	}

/**
 * データを保存する
 *
 * @param array $data データ配列
 * @param array $attachFields 添付ファイルフィールド配列
 * @param array $skipAttaches 添付ファイル除外フィールド配列
 * @param array $removeAttachFields 添付ファイル削除フィールド配列
 * @param array $attachPasswords 添付ファイルパスワード配列
 * @return array|bool
 * @throws InternalErrorException
 */
	private function __saveContent(
		$data, $attachFields = [], $skipAttaches = [], $removeAttachFields = [], $attachPasswords = []) {
		if (! empty($attachFields)) {
			$this->Behaviors->load('Files.Attachment', $attachFields);
		}

		// 未アップロードの場合は既存ファイルを保持する
		if (! empty($skipAttaches)) {
			foreach ($skipAttaches as $val) {
				$this->uploadSettings($val);
			}
		}

		$this->begin();
		try {

			if (! $searchContents = $this->MultidatabaseMetadata->getSearchMetadatas()) {
				throw new InternalErrorException(__d('net_commons', 'Internal Server Error'));
			}

			// 新着情報を登録
			$this->Behaviors->load('Topics.Topics', [
				'fields' => [
					'title' => 'MultidatabaseContent.value1',
					'summary' => 'MultidatabaseContent.value1',
					'path' => '/:plugin_key/multidatabase_contents/detail/:block_id/:content_key',
				],
				'search_contents' => $searchContents
			]);

			// メールキューを登録
			$this->Behaviors->load('Mails.MailQueue', [
				'embedTags' => [
					'X-SUBJECT' => 'MultidatabaseContent.value1',
					'X-URL' => [
						'controller' => 'multidatabase_contents'
					]
				],
			]);

			if (($savedData = $this->save($data, false)) === false) {
				throw new InternalErrorException(__d('net_commons', 'Internal Server Error'));
			}

			// パスワードを登録
			$this->saveAuthKey(
				$savedData['MultidatabaseContent']['id'],
				$attachPasswords
			);

			$this->commit();

			// ファイルを削除する
			if (!empty($removeAttachFields)) {
				foreach ($removeAttachFields as $val) {
					if (! $this->removeFileByContentKey($data['MultidatabaseContent']['key'], $val)) {
						throw new InternalErrorException(__d('net_commons', 'Internal Server Error'));
					}
				}
			}

		} catch (Exception $e) {
			$this->rollback($e);
		}
		return $savedData;
	}
}
