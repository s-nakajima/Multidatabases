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

	public function saveContent($data) {

		$this->makeValidation();
		$this->set($data);

		if (!$this->validates()) {
			return false;
		}

		if (! $multidatabase = $this->Multidatabase->getMultidatabase()) {
			return false;
		}

		if(! $multidatabaseMetadatas = $this->Multidatabase->MultidatabaseMetadata->getEditMetadatas($multidatabase['Multidatabase']['id'])) {
			return false;
		}

		foreach ($multidatabaseMetadatas as $metadata) {
			if (isset($data['MultidatabaseContent']['metadata' . $metadata['id']])) {
				$tmpDat = $data['MultidatabaseContent']['metadata' . $metadata['id']];

				switch ($metadata['type']) {
					case 'checkbox':
						$tmpSelections = [];
						foreach (explode('||',$metadata['selections']) as $val) {
							if(in_array(md5($val),$tmpDat)) {
								$tmpSelections[] = $val;
							}
						}
						if (!empty($tmpSelections)) {
							$contentDat['value' . $metadata['col_no']] = implode('||',$tmpSelections);
						}
						break;
					default:
						$contentDat['value' . $metadata['col_no']] = $tmpDat;
						break;
				}
			} else {
				$contentDat['value' . $metadata['col_no']] = '';
			}
		}

		$contentDat['status'] = $data['MultidatabaseContent']['status'];
		$contentDat['block_id'] = $data['MultidatabaseContent']['block_id'];
		$contentDat['language_id'] = $data['MultidatabaseContent']['language_id'];

		unset($data['MultidatabaseContent']);
		$data['MultidatabaseContent'] = $contentDat;


		var_dump($data);


		
		$this->begin();
		try {




			if (($savedData = $this->save($data,false)) === false) {
				throw new InternalErrorException(__d('net_commons', 'Internal Server Error'));
			}
		} catch (Exception $e) {
			$this->rollback($e);
		}

		return $savedData;


	}



}
