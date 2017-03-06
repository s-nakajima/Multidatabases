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
			'order' => '',
			'counterCache' => array(
				'content_count' => array('MultidatabaseContent.is_latest' => 1),
			),
		)
	);

	public $actsAs = [
		'NetCommons.Trackable',
		'NetCommons.OriginalKey',
//		'Workflow.Workflow',
		'Likes.Like',
//		'Workflow.WorkflowComment',
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


	public function beforeValidate($options = []) {
		$this->validate = $this->makeValidation();
		return parent::beforeValidate($options);
	}


	public function getMultidatabaseContents() {
		if (! $multidatabase = $this->Multidatabase->getMultidatabase()) {
			return false;
		}

		$multidatabaseContents = $this->find('all', array(
			'recursive' => 0,
			'conditions' => [
				'multidatabase_key' => $multidatabase['Multidatabase']['key'],
			]
		));


		return $multidatabaseContents;
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


	public function saveContent($data) {

		if (! $multidatabase = $this->Multidatabase->getMultidatabase()) {
			return false;
		}

		if(! $multidatabaseMetadatas = $this->Multidatabase->MultidatabaseMetadata->getEditMetadatas($multidatabase['Multidatabase']['id'])) {
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



}
