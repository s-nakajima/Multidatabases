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
	public $belongsTo = array(
//		'Multidatabase' => array(
//			'className' => 'Multidatabases.Multidatabase',
//			'foreignKey' => 'multidatabase_id',
//			'conditions' => '',
//			'fields' => '',
//			'order' => ''
//		),
//		'Language' => array(
//			'className' => 'M17.Language',
//			'foreignKey' => 'language_id',
//			'conditions' => '',
//			'fields' => '',
//			'order' => ''
//		),
		'Block' => array(
			'className' => 'Blocks.Block',
			'foreignKey' => 'block_id',
			'conditions' => '',
			'fields' => '',
			'order' => '',
			'counterCache' => array(
				'content_count' => array(
					//'MultidatabaseContent.is_origin' => true,
					'MultidatabaseContent.is_latest' => true
				),
			),
		)
	);

	public $actsAs = [
		'NetCommons.Trackable',
		'NetCommons.OriginalKey',
		'Workflow.Workflow',
		'Likes.Like',
		'Workflow.WorkflowComment',
		'ContentComments.ContentComment',
/*
		'Topics.Topics' => array(
			'fields' => array(
				'title' => 'title',
				'summary' => 'body1',
				'path' => '/:plugin_key/multidatabase_contents/view/:block_id/:content_key',
			),
			'search_contents' => array('body2')
		),
		'Mails.MailQueue' => array(
			'embedTags' => array(
				'X-SUBJECT' => 'BlogEntry.title',
				'X-BODY' => 'BlogEntry.body1',
				'X-URL' => [
					'controller' => 'blog_entries'
				]
			),
		),
		'Wysiwyg.Wysiwyg' => array(
			'fields' => array('body1', 'body2'),
		),
*/

	];

	/**
	 * @var array 絞り込みフィルタ保持値
	 */
	protected $_filter = array(
		'status' => 0,
	);


	public function beforeValidate($options = []) {
		$this->validate = $this->makeValidation();
		return parent::beforeValidate($options);
	}


/**
 * コンテンツを取得
 *
 * @return array|bool|null
 */
	public function getMultidatabaseContents() {
		$this->loadModels([
			'Multidatabase' => 'Multidatabases.Multidatabase',
		]);
		if (! $multidatabase = $this->Multidatabase->getMultidatabase()) {
			return false;
		}

		$result = $this->find('all', array(
			'recursive' => 0,
			'conditions' => [
				'multidatabase_key' => $multidatabase['Multidatabase']['key'],
			]
		));

		return $result;
	}




/**
 * バリデーションルールの作成
 *
 * @return bool
 */
	public function makeValidation() {
		$this->loadModels([
			'MultidatabaseMetadata' => 'Multidatabases.MultidatabaseMetadata',
			'Multidatabase' => 'Multidatabases.Multidatabase',
		]);

		if (! $multidatabase = $this->Multidatabase->getMultidatabase()) {
			return false;
		}

		if(! $multidatabaseMetadatas = $this->MultidatabaseMetadata->getEditMetadatas($multidatabase['Multidatabase']['id'])) {
			return false;
		}

		$result = [];
		foreach ($multidatabaseMetadatas as $metadata) {
			if ($metadata['is_require']) {
				$tmp = [];
				switch ($metadata['type']) {
					case 'checkbox':
						$tmp['rule'] = [
							'multiple',
							[
								'min' => 1
							]
						];
						break;
					default:
						$tmp['rule'][] = 'notBlank';
						$tmp['allowEmpty'] = false;
						break;
				}
				$tmp['required'] = true;
				$result['value' . $metadata['col_no']] =  $tmp;
			}
		}

		return Hash::merge($this->validate,$result);
	}



/**
 * 削除対象カラムに存在する値をクリアする
 *
 * @param null $multidatabase_key
 * @param array $colNos
 * @return bool
 */
	public function clearValues($multidatabaseKey = null, $colNos = []) {

		if (
			is_null($multidatabaseKey)
			|| empty($currentMetadatas)
		) {
			return false;
		}

		$conditions['multidatabase_key'] = $multidatabaseKey;

		$data = [];
		foreach ($colNos as $colNo) {
			$data['value' . $colNo] = '';
		}

		if (! $this->updateAll($data,$conditions)) {
			throw new InternalErrorException(__d('net_commons', 'Internal Server Error'));
		}

		return true;

	}


/**
 * コンテンツを保存する
 *
 * @param $data
 * @return bool|mixed
 */
	public function saveContent($data) {
		$this->loadModels([
			'Multidatabase' => 'Multidatabases.Multidatabase',
			'MultidatabaseMetadata' => 'Multidatabases.MultidatabaseMetadata',
		]);

		if (! $multidatabase = $this->Multidatabase->getMultidatabase()) {
			return false;
		}

		if(! $multidatabaseMetadatas = $this->MultidatabaseMetadata->getEditMetadatas($multidatabase['Multidatabase']['id'])) {
			return false;
		}

		$this->begin();
		try {
			$this->create();
			$this->set($data);

			if (!$this->validates()) {
				$this->rollback();
				return false;
			}

			$multidatabaseContent = $data['MultidatabaseContent'];
			foreach ($multidatabaseContent as $key => $val) {
				if (
					isset($data['MultidatabaseContent'][$key]) &&
					is_array($data['MultidatabaseContent'][$key])
				) {
					$data['MultidatabaseContent'][$key] = implode('||',$val);
				}
			}


			if (($savedData = $this->save($data,false)) === false) {
				throw new InternalErrorException(__d('net_commons', 'Internal Server Error'));
			}
			$this->commit();
		} catch (Exception $e) {
			$this->rollback($e);
		}

		return $savedData;


	}

	/**
	 * UserIdと権限から参照可能なEntryを取得するCondition配列を返す
	 *
	 * @param int $blockKey ブロックKey
	 * @param array $permissions 権限
	 * @return array condition
	 */
	public function getConditions($blockId, $permissions) {
		// contentReadable falseなら何も見えない
		if ($permissions['content_readable'] === false) {
			$conditions = array('MultidatabaseContent.id' => 0);
			return $conditions;
		}

		// デフォルト絞り込み条件
		$conditions = array(
			'MultidatabaseContent.block_Id' => $blockId
		);

		$conditions = $this->getWorkflowConditions($conditions);

		return $conditions;
	}


/**
 * コンテンツの削除
 *
 * @param $key
 * @return bool
 */
	public function deleteContentByKey($key) {
		$this->begin();
		try {
			$this->contentKey = $key;

			$conditions = [
				'MultidatabaseContent.Key' => $key
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
	 * ファイルアップロード
	 *
	 * @return array
	 */
/*
	private function uploadFile($content){

		$uploadData = array(
			'name' => '',
			'type' => '',
			'tmp_name' => '',
			'error' => UPLOAD_ERR_NO_FILE,
			'size' => '',
		);

		$multidatabaseContentKey = $data['MultidatabaseContent']['key'];

		if (!$multidatabaseContentKey) {
			return $uploadData;
		}


		$UploadFile = ClassRegistry::init('Files.UploadFile');
		$fieldName = PhotoAlbumPhoto::ATTACHMENT_FIELD_NAME;
		$file = $UploadFile->getFile('multidatabases', $photoId, $fieldName);
		$path = $UploadFile->getRealFilePath($file);

		$Folder = new TemporaryFolder();
		$tmpName = $Folder->path . DS . $file['UploadFile']['real_file_name'];
		$jackeData = array(
			'name' => $file['UploadFile']['original_name'],
			'type' => $file['UploadFile']['mimetype'],
			'tmp_name' => $tmpName,
			'error' => UPLOAD_ERR_OK,
			'size' => $file['UploadFile']['size'],
		);
		copy($path, $tmpName);

		return $jackeData;
	}

	}
*/

}
