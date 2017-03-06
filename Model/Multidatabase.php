<?php
/**
 * Multidatabase Model
 *
 * @property Block $Block
 * @property MultidatabaseMetadataTitle $MultidatabaseMetadataTitle
 * @property MultidatabaseContent $MultidatabaseContent
 * @property MultidatabaseMetadata $MultidatabaseMetadata
 *
* @author Noriko Arai <arai@nii.ac.jp>
* @author Tomoyuki OHNO (Ricksoft Inc.) <ohno.tomoyuki@ricksoft.jp>
* @link http://www.netcommons.org NetCommons Project
* @license http://www.netcommons.org/license.txt NetCommons License
* @copyright Copyright 2014, NetCommons Project
 */

App::uses('MultidatabasesAppModel', 'Multidatabases.Model');

/**
 * Multidatabase Model
 *
* @author Tomoyuki OHNO (Ricksoft Inc.) <ohno.tomoyuki@ricksoft.jp>
 * @package NetCommons\Multidatabases\Model
 */
class Multidatabase extends MultidatabasesAppModel {

/**
 * use tables
 *
 * @var string
 */
	public $useTable = 'multidatabases';

/**
 * Validation rules
 *
 * @var array
 */
	public $validate = array();

/**
 * use behaviors
 *
 * @var array
 */
	public $actsAs = array(
		'Blocks.Block' => array(
			'name' => 'Multidatabase.name',
			'loadModels' => array(
				'Like' => 'Likes.Like',
				'BlockSetting' => 'Blocks.BlockSetting',
			)
		),
		'NetCommons.OriginalKey',
	);

/**
 * belongsTo associations
 *
 * @var array
 */
	public $belongsTo = array(
		'Block' => array(
			'className' => 'Blocks.Block',
			'foreignKey' => 'block_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		),
	);

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
	public function beforeValidate($options = array()) {

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

		if (isset($this->data['MultidatabaseFrameSetting']) && ! $this->data['MultidatabaseFrameSetting']['id']) {
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
			$metadatas = $this->data['MultidatabaseMetadata'];
			$metadatas = $this->MultidatabaseMetadata->mergeGroupToMetadatas($metadatas);

			$this->MultidatabaseMetadata->set($metadatas);

			if (! $this->MultidatabaseMetadata->validates()) {
				$this->validationErrors = Hash::merge(
					$this->validationErrors, $this->MultidatabaseMetadata->validationErrors
				);
				return false;
			}
		}



		return parent::beforeValidate($options);


	}



/**
 * hasMany associations
 *
 * @var array
 */

	public $hasMany = array(
		'MultidatabaseContent' => array(
			'className' => 'MultidatabaseContent',
			'foreignKey' => 'multidatabase_id',
			'dependent' => false,
			'conditions' => '',
			'fields' => '',
			'order' => '',
			'limit' => '',
			'offset' => '',
			'exclusive' => '',
			'finderQuery' => '',
			'counterQuery' => ''
		),
		'MultidatabaseMetadata' => array(
			'className' => 'MultidatabaseMetadata',
			'foreignKey' => 'multidatabase_id',
			'dependent' => false,
			'conditions' => '',
			'fields' => '',
			'order' => '',
			'limit' => '',
			'offset' => '',
			'exclusive' => '',
			'finderQuery' => '',
			'counterQuery' => ''
		)
	);


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
	public function afterSave($created, $options = array()) {
		//MultidatabaseSetting登録
		if (isset($this->MultidatabaseSetting->data['MultidatabaseSetting'])) {
			$this->MultidatabaseSetting->set($this->MultidatabaseSetting->data['MultidatabaseSetting']);
			$this->MultidatabaseSetting->save(null, false);
		}

		//MultidatabaseFrameSetting登録
		if (isset($this->MultidatabaseFrameSetting->data['MultidatabaseFrameSetting']) &&
				! $this->MultidatabaseFrameSetting->data['MultidatabaseFrameSetting']['id']) {

			if (! $this->MultidatabaseFrameSetting->save(null, false)) {
				throw new InternalErrorException(__d('net_commons', 'Internal Server Error'));
			}
		}

		if (! isset($this->MultidatabaseMetadata->data['MultidatabaseMetadata'])) {
			throw new InternalErrorException(__d('net_commons', 'Internal Server Error'));
		}

		$metadatas = $this->MultidatabaseMetadata->data['MultidatabaseMetadata'];

		// 削除ID,カラムの確認
		$delMetadataIds = $this->MultidatabaseMetadata->getDeleteMetadatas($this->data['Multidatabase']['id'],$metadatas,'id');
		$delMetadataColNos = $this->MultidatabaseMetadata->getDeleteMetadatas($this->data['Multidatabase']['id'],$metadatas,'col_no');

		// MultidatabaseMetadata登録
		$metadatas = $this->MultidatabaseMetadata->makeSaveData(
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
 * バリデーションルールの作成
 *
 * @return
 */
	public function makeValidation($multidatabaseMetadatas = []) {
		$result = [];
/*
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
*/
		$this->validate = Hash::merge($this->validate,$result);
	}

/**
 * Create Multidatabase data
 *
 * @return array
 */
	public function createMultidatabase() {
		$multidatabase = $this->createAll(array(
			'Multidatabase' => array(
				'name' => __d('multidatabases', 'New multidatabase %s', date('YmdHis')),
			),
			'Block' => array(
				'room_id' => Current::read('Room.id'),
				'language_id' => Current::read('Language.id'),
			),

		));
		$multidatabase = Hash::merge($multidatabase, $this->MultidatabaseSetting->createBlockSetting());
		return $multidatabase;
	}


/**
 * Get Multidatabase data
 *
 * @return array
 */


	public function getMultidatabase() {

		$multidatabase = $this->find('first', array(
			'recursive' => 0,
			'conditions' => $this->getBlockConditionById(),
		));

		if (! $multidatabase) {
			return $multidatabase;
		}

		return Hash::merge($multidatabase, $this->MultidatabaseSetting->getMultidatabaseSetting());
	}





/**
 * Save Multidatabases
 *
 * @param array $data received post data
 * @return bool True on success, false on validation errors
 * @throws InternalErrorException
 */
        public function saveMultidatabase($data) {
			$this->loadModels([
				'MultidatabaseSetting' => 'Multidatabases.MultidatabaseSetting',
				'MultidatabaseFrameSetting' => 'Multidatabases.MultidatabaseFrameSetting',
				'MultidatabaseMetadata' => 'Multidatabases.MultidatabaseMetadata',
			]);


		//トランザクションBegin
		$this->begin();

		try {

			// メタデータ登録

			//バリデーション
			$this->set($data);

			if (! $this->validates()) {
				$this->rollback();
				return false;
			}


			//登録処理
			$result = $this->save($data, false);

			if (! $result) {
				throw new InternalErrorException(__d('net_commons', 'Internal Server Error'));
			}

			$this->commit();
		} catch (Exception $ex) {
			//トランザクションRollback
			$this->rollback($ex);
		}

		return true;
        }

/**
 * Delete Multidatabases
 *
 * @param array $data received post data
 * @return mixed On success Model::$data if its not empty or true, false on failure
 * @throws InternalErrorException
 */
	public function deleteMultidatabase($data) {
		$this->loadModels([
			'Multidatabase' => 'Multidatabases.Multidatabase',
			'MultidatabaseContent' => 'Multidatabases.MultidatabaseContent',
		]);

		//トランザクションBegin
		$this->begin();

		$conditions = array(
			$this->alias . '.key' => $data['Multidatabase']['key']
		);
		$multidatabases = $this->find('list', array(
			'recursive' => -1,
			'conditions' => $conditions,
		));
		$multidatabaseIds = array_keys($multidatabases);

		try {
			if (! $this->deleteAll(array($this->alias . '.key' => $data['Multidatabase']['key']), false, false)) {
				throw new InternalErrorException(__d('net_commons', 'Internal Server Error'));
			}

			$this->MultidatabaseContent->blockKey = $data['Block']['key'];
			$conditions = array($this->MultidatabaseContent->alias . '.multidatabase_id' => $multidatabaseIds);
			if (! $this->MultidatabaseContent->deleteAll($conditions, false, true)) {
				throw new InternalErrorException(__d('net_commons', 'Internal Server Error'));
			}

			$conditions = array($this->MultidatabaseMetadata->alias . '.multidatabase_id' => $data['Multidatabase']['id']);
			if (! $this->MultidatabaseMetadata->deleteAll($conditions, false, false)) {
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
