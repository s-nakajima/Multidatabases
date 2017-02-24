<?php
/**
 * MultidatabaseSettings Model
 *
 * @property Block $Block
 *
  * @author Noriko Arai <arai@nii.ac.jp>
 * @author Tomoyuki OHNO (Ricksoft, Inc) <ohno.tomoyuki@ricksoft.jp>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */

App::uses('BlockBaseModel', 'Blocks.Model');
App::uses('BlockSettingBehavior', 'Blocks.Model/Behavior');

/**
 * MultidatabaseSettings Model
 *
 * @author Tomoyuki OHNO (Ricksoft, Inc) <ohno.tomoyuki@ricksoft.jp>
 * @package NetCommons\Multidatabases\Model
 */
class MultidatabaseSetting extends BlockBaseModel {

/**
 * Custom database table name
 *
 * @var string
 */
	public $useTable = false;

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
		'Blocks.BlockRolePermission',
		'Blocks.BlockSetting' => array(
			BlockSettingBehavior::FIELD_USE_LIKE,
			BlockSettingBehavior::FIELD_USE_UNLIKE,
			BlockSettingBehavior::FIELD_USE_COMMENT,
			BlockSettingBehavior::FIELD_USE_WORKFLOW,
			BlockSettingBehavior::FIELD_USE_COMMENT_APPROVAL,
		),
	);

/**
 * Get multidatabase setting data
 *
 * @return array
 * @see BlockSettingBehavior::getBlockSetting() 取得
 */
	public function getMultidatabaseSetting() {
		return $this->getBlockSetting();
	}

/**
 * Save multidatabase_setting
 *
 * @param array $data received post data
 * @return mixed On success Model::$data if its not empty or true, false on failure
 * @throws InternalErrorException
 */
	public function saveMultidatabaseSetting($data) {
		$this->loadModels([
			'MultidatabaseSetting' => 'Multidatabases.MultidatabaseSetting',
		]);

		//トランザクションBegin
		$this->begin();

		//バリデーション
		$this->set($data);
		if (! $this->validates()) {
			return false;
		}

		try {
			$this->save(null, false);

			//トランザクションCommit
			$this->commit();

		} catch (Exception $ex) {
			//トランザクションRollback
			$this->rollback($ex);
		}

		return true;
	}

}
