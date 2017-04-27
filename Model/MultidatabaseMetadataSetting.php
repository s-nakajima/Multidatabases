<?php
/**
 * MultidatabaseMetadataSetting Model
 * 汎用データベースメタデータ定義の設定に関するモデル処理
 *
 * @author Noriko Arai <arai@nii.ac.jp>
 * @author Tomoyuki OHNO (Ricksoft Co., Ltd.) <ohno.tomoyuki@ricksoft.jp>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */

App::uses('MultidatabasesAppModel', 'Multidatabases.Model');
App::uses('MultidatabasesMetadataModel', 'MultidatabaseMetadata.Model');

/**
 * MultidatabaseMetadataSetting Model
 *
 * @author Tomoyuki OHNO (Ricksoft, Co., Ltd.) <ohno.tomoyuki@ricksoft.jp>
 * @package NetCommons\Multidatabases\Model
 */
class MultidatabaseMetadataSetting extends MultidatabasesAppModel {

	public $metadatas = [];

	/**
	 * Use table
	 *
	 * @var mixed False or table name
	 */
	public $useTable = 'multidatabase_metadata_settings';

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
		'MultidatabaseMetadata' => [
			'className' => 'MultidatabaseMetadata',
			'foreignKey' => 'id',
			'conditions' => '',
			'fields' => '',
			'order' => '',
			'dependent' => true
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
	}

	public function updateAutoNum($id) {
		$metadataSetting = $this->findById($id);

		if (!$metadataSetting) {
			return 0;
		}

		$tmpNum = $metadataSetting['MultidatabaseMetadataSetting']['auto_number_sequence'];
		$tmpNum++;

		$this->set(array(
			'id' => $id,
			'auto_number_sequence' => $tmpNum
		));

		if($this->save()) {
			return $tmpNum;
		}

		return 0;
	}

}

