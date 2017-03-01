<?php
/**
 * MultidatabaseContent Model
 *
 * @property Multidatabase $Multidatabase
 * @property Language $Language
 * @property Block $Block
 *
* @author Noriko Arai <arai@nii.ac.jp>
* @author Tomoyuki OHNO (Ricksoft Inc.) <ohno.tomoyuki@ricksoft.jp>
* @link http://www.netcommons.org NetCommons Project
* @license http://www.netcommons.org/license.txt NetCommons License
* @copyright Copyright 2014, NetCommons Project
 */

App::uses('MultidatabasesAppModel', 'Multidatabases.Model');
App::uses('MultidatabaseModel', 'Multidatabase.Model');
App::uses('MultidatabaseMetadataModel', 'MultidatabaseMetadata.Model');

/**
 * Summary for MultidatabaseContent Model
 */
class MultidatabaseContent extends MultidatabasesAppModel {

/**
 * Use database config
 *
 * @var string
 */
	public $useDbConfig = 'master';

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
		'Multidatabase' => array(
			'className' => 'Multidatabase',
			'foreignKey' => 'multidatabase_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		),
		'Language' => array(
			'className' => 'Language',
			'foreignKey' => 'language_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		),
		'Block' => array(
			'className' => 'Block',
			'foreignKey' => 'block_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		)
	);


	public function getNew() {
		$new = $this->_getNew();
		$netCommonsTime = new NetCommonsTime();
		$new['MultidatabaseContent']['publish_start'] = $netCommonsTime->getNowDatetime();
		return $new;
	}

/**
 * バリデーションルールの作成
 *
 * @return bool
 */
	public function makeValidation() {
		if (! $multidatabase = $this->Multidatabase->getMultidatabase()) {
			return false;
		}

		if(! $multidatabaseMetadatas = $this->Multidatabase->MultidatabaseMetadata->getEditMetadatas($multidatabase['Multidatabase']['id'])) {
			return false;
		}

		foreach ($multidatabaseMetadatas as $metadata) {

			$tmp = [];
			if ($metadata['is_require']) {
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
				$result['metadata' . $metadata['id']] =  $tmp;
			}


		}
		$this->validate = $result;


	}



}
