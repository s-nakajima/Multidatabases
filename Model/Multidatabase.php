<?php
/**
 * Multidatabase Model
 * 汎用データベース基本データに関するモデル処理
 *
 * @author Noriko Arai <arai@nii.ac.jp>
 * @author Tomoyuki OHNO (Ricksoft Co., Ltd.) <ohno.tomoyuki@ricksoft.jp>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */

App::uses('MultidatabasesAppModel', 'Multidatabases.Model');
App::uses('MultidatabasesMetadataEditModel', 'MultidatabaseMetadataEdit.Model');

/**
 * Multidatabase Model
 *
 * @author Tomoyuki OHNO (Ricksoft, Co., LTD.) <ohno.tomoyuki@ricksoft.jp>
 * @package NetCommons\Multidatabases\Model
 */
class Multidatabase extends MultidatabasesAppModel {

/**
 * Validation rules
 *
 * @var array
 */
	public $validate = [];

/**
 * use behaviors
 *
 * @var array
 */
	public $actsAs = [
		'Blocks.Block' => [
			'name' => 'Multidatabase.name',
			'loadModels' => [
				'Like' => 'Likes.Like',
				'BlockSetting' => 'Blocks.BlockSetting',
			],
		],
		'NetCommons.OriginalKey',
	];

/**
 * belongsTo associations
 *
 * @var array
 */
	public $belongsTo = [
		'Block' => [
			'className' => 'Blocks.Block',
			'foreignKey' => 'block_id',
			'conditions' => '',
			'fields' => '',
			'order' => '',
		],
	];

/**
 * hasMany associations
 *
 * @var array
 */
	public $hasMany = [
		'MultidatabaseContent' => [
			'className' => 'Multidatabases.MultidatabaseContent',
			'foreignKey' => 'multidatabase_id',
			'dependent' => false,
			'conditions' => '',
			'fields' => '',
			'order' => '',
			'limit' => '',
			'offset' => '',
			'exclusive' => '',
			'finderQuery' => '',
			'counterQuery' => '',
		],
		'MultidatabaseMetadata' => [
			'className' => 'Multidatabases.MultidatabaseMetadata',
			'foreignKey' => 'multidatabase_id',
			'dependent' => false,
			'conditions' => '',
			'fields' => '',
			'order' => '',
			'limit' => '',
			'offset' => '',
			'exclusive' => '',
			'finderQuery' => '',
			'counterQuery' => '',
		],
	];

/**
 * Constructor. Binds the model's database table to the object.
 *
 * @param bool|int|string|array $id Set this ID for this model on startup,
 * can also be an array of options, see above.
 * @param string $table Name of database table to use.
 * @param string $ds DataSource connection name.
 * @see Model::__construct()
 * @SuppressWarnings(PHPMD.BooleanArgumentFlag)
 */
	public function __construct($id = false, $table = null, $ds = null) {
		parent::__construct($id, $table, $ds);

		$this->loadModels([
			'MultidatabaseFrameSetting' => 'Multidatabases.MultidatabaseFrameSetting',
			'MultidatabaseSetting' => 'Multidatabases.MultidatabaseSetting',
			'MultidatabaseMetadata' => 'Multidatabases.MultidatabaseMetadata',
		]);
	}

/**
 * Called during validation operations, before validation. Please note that custom
 * validation rules can be defined in $validate.
 *
 * @param array $options Options passed from Model::save().
 * @return bool True if validate operation should continue, false to abort
 * @link http://book.cakephp.org/2.0/en/models/callback-methods.html#beforevalidate
 * @see Model::save()
 */
	public function beforeValidate($options = []) {
		$this->MultidatabaseSetting->set($this->data['MultidatabaseSetting']);
		$this->MultidatabaseFrameSetting->set($this->data['MultidatabaseFrameSetting']);
		/*
				if (isset($this->data['MultidatabaseSetting'])) {
					$this->MultidatabaseSetting->set($this->data['MultidatabaseSetting']);
					if (! $this->MultidatabaseSetting->validates()) {
						$this->validationErrors = Hash::merge(
							$this->validationErrors, $this->MultidatabaseSetting->validationErrors
						);
						return false;
					}
				}

				if (
					isset($this->data['MultidatabaseFrameSetting']) &&
					! $this->data['MultidatabaseFrameSetting']['id']
				) {
					$this->MultidatabaseFrameSetting->set($this->data['MultidatabaseFrameSetting']);
					if (! $this->MultidatabaseFrameSetting->validates()) {
						$this->validationErrors = Hash::merge(
							$this->validationErrors, $this->MultidatabaseFrameSetting->validationErrors
						);
						return false;
					}
				}
		*/
		if (isset($this->data['MultidatabaseMetadata'])) {
			// SecurityComponentを除外したため、ここにMetadataのチェックを記述する
			$metadatas = $this->data['MultidatabaseMetadata'];
			$metadatas = $this->MultidatabaseMetadata->mergeGroupToMetadatas($metadatas);

			$this->MultidatabaseMetadata->set($metadatas);

			if (!$this->MultidatabaseMetadata->validates()) {
				$this->validationErrors = Hash::merge(
					$this->validationErrors, $this->MultidatabaseMetadata->validationErrors
				);
				return false;
			}
		}

		return parent::beforeValidate($options);
	}

/**
 * Called after each successful save operation.
 *
 * @param bool $created True if this save created a new record
 * @param array $options Options passed from Model::save().
 * @return void
 * @throws InternalErrorException
 * @link http://book.cakephp.org/2.0/en/models/callback-methods.html#aftersave
 * @see Model::save()
 */
	public function afterSave($created, $options = []) {
		$this->loadModels([
			'MultidatabaseMetadataEdit' => 'Multidatabases.MultidatabaseMetadataEdit',
		]);

		//MultidatabaseSetting登録
		if (isset($this->MultidatabaseSetting->data['MultidatabaseSetting'])) {
			$this->MultidatabaseSetting->set($this->MultidatabaseSetting->data['MultidatabaseSetting']);
			$this->MultidatabaseSetting->save(null, false);
		}

		//MultidatabaseFrameSetting登録
		if (isset($this->MultidatabaseFrameSetting->data['MultidatabaseFrameSetting']) &&
			!$this->MultidatabaseFrameSetting->data['MultidatabaseFrameSetting']['id']
		) {
			if (!$this->MultidatabaseFrameSetting->save(null, false)) {
				throw new InternalErrorException(__d('net_commons', 'Internal Server Error'));
			}
		}

		if (!isset($this->MultidatabaseMetadata->data['MultidatabaseMetadata'])) {
			throw new InternalErrorException(__d('net_commons', 'Internal Server Error'));
		}

		$metadatas = $this->MultidatabaseMetadata->data['MultidatabaseMetadata'];

		// 削除ID,カラムの確認
		$delMetadataIds =
			$this->MultidatabaseMetadata->getDeleteMetadatas(
				$this->data['Multidatabase']['id'], $metadatas, 'id'
			);
		/*
		$delMetadataColNos =
			$this->MultidatabaseMetadata->getDeleteMetadatas(
				$this->data['Multidatabase']['id'], $metadatas, 'col_no'
			);
		*/
		// MultidatabaseMetadata登録
		$metadatas = $this->MultidatabaseMetadataEdit->makeSaveData(
			$this->data,
			$metadatas
		);

		$this->MultidatabaseMetadata->saveMetadatas($metadatas);

		// MultidatabaseMetadata削除
		if (!empty($delMetadataIds)) {
			$this->MultidatabaseMetadata->deleteMetadatas($delMetadataIds);
		}

		// MultidatabaseContentの削除
		parent::afterSave($created, $options);
	}

/**
 * Create Multidatabase data
 * 汎用データベースを作成する
 *
 * @return array
 */
	public function createMultidatabase() {
		$multidatabase = $this->createAll([
			'Multidatabase' => [
				'name' => __d('multidatabases', 'New multidatabase %s', date('YmdHis')),
			],
			'Block' => [
				'room_id' => Current::read('Room.id'),
				'language_id' => Current::read('Language.id'),
			],

		]);
		$multidatabase = Hash::merge($multidatabase, $this->MultidatabaseSetting->createBlockSetting());

		return $multidatabase;
	}

/**
 * Get Multidatabase data
 * 汎用データベースを取得する
 *
 * @return array
 */
	public function getMultidatabase() {
		$multidatabase = $this->find('first', [
			'recursive' => 0,
			'conditions' => $this->getBlockConditionById(),
		]);

		if (!$multidatabase) {
			return $multidatabase;
		}

		return Hash::merge($multidatabase, $this->MultidatabaseSetting->getMultidatabaseSetting());
	}

/**
 * Save Multidatabases
 * 汎用データベースを保存する
 *
 * @param array $data received post data
 * @return bool True on success, false on validation errors
 * @throws InternalErrorException
 */
	public function saveMultidatabase($data) {
		//トランザクションBegin
		$this->begin();

		//バリデーション
		$this->set($data);

		if (!$this->validates()) {
			$this->rollback();

			return false;
		}

		try {
			//登録処理
			$result = $this->save($data, false);

			if (!$result) {
				throw new InternalErrorException(__d('net_commons', 'Internal Server Error'));
			}

			//トランザクションCommit
			$this->commit();
		} catch (Exception $ex) {
			//トランザクションRollback
			$this->rollback($ex);
		}

		return true;
	}

/**
 * Delete Multidatabases
 * 汎用データベースを削除する
 *
 * @param array $data received post data
 * @return mixed On success Model::$data if its not empty or true, false on failure
 * @throws InternalErrorException
 */
	public function deleteMultidatabase($data) {
		//トランザクションBegin
		$this->begin();

		$conditions = [
			$this->alias . '.key' => $data['Multidatabase']['key'],
		];
		$multidatabases = $this->find('list', [
			'recursive' => -1,
			'conditions' => $conditions,
		]);
		$multidatabaseIds = array_keys($multidatabases);

		try {
			if (!$this->deleteAll(
				[$this->alias . '.key' => $data['Multidatabase']['key']], false, false
			)) {
				throw new InternalErrorException(__d('net_commons', 'Internal Server Error'));
			}

			$this->MultidatabaseContent->blockKey = $data['Block']['key'];
			$conditions = [$this->MultidatabaseContent->alias . '.multidatabase_id' => $multidatabaseIds];
			if (!$this->MultidatabaseContent->deleteAll($conditions, false, true)) {
				throw new InternalErrorException(__d('net_commons', 'Internal Server Error'));
			}

			$conditions = [
				$this->MultidatabaseMetadata->alias . '.multidatabase_id' => $data['Multidatabase']['id']
			];
			if (!$this->MultidatabaseMetadata->deleteAll($conditions, false, false)) {
				throw new InternalErrorException(__d('net_commons', 'Internal Server Error'));
			}

			//Blockデータ削除
			$this->deleteBlock($data['Block']['key']);

			//トランザクションCommit
			$this->commit();
		} catch (Exception $ex) {
			//トランザクションRollback
			$this->rollback($ex);
		}
		return true;
	}
}
