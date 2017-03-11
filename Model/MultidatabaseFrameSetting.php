<?php

/**
 * MultidatabaseFrameSetting Model
 * 汎用データベースフレーム設定に関するモデル処理
 *
 * @author Noriko Arai <arai@nii.ac.jp>
 * @author Tomoyuki OHNO (Ricksoft Co., Ltd.) <ohno.tomoyuki@ricksoft.jp>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */

App::uses('MultidatabasesAppModel', 'Multidatabases.Model');

/**
 * MultidatabaseFrameSetting Model
 *
 * @author Tomoyuki OHNO (Ricksoft, Co., Ltd.) <ohno.tomoyuki@ricksoft.jp>
 * @package NetCommons\Multidatabases\Model
 *
 */
class MultidatabaseFrameSetting extends MultidatabasesAppModel {

    /**
     * Validation rules
     *
     * @var array
     */
    public $validate = array();

    public function beforeValidate($options = array()) {
        $this->validate = array(
            'frame_key' => array(
                'notBlank' => array(
                        'rule' => array('notBlank'),
                        'message' => __d('net_commons', 'Invalid request.'),
                        'required' => true,
                )
            ),
            'content_per_page' => array(
                'number' => array(
                        'rule' => array('notBlank'),
                        'message' => __d('net_commons', 'Invalid request.'),
                        'required' => true,
                )

            ),
            'default_sort_type' => array(
                'numeric' => array(
                    'rule' => array('numeric'),
                //'message' => 'Your custom message here',
                //'allowEmpty' => false,
                //'required' => false,
                //'last' => false, // Stop validation after this rule
                //'on' => 'create', // Limit validation to 'create' or 'update' operations
                ),
            ),
            'default_sort_order' => array(
                'boolean' => array(
                    'rule' => array('boolean'),
                //'message' => 'Your custom message here',
                //'allowEmpty' => false,
                //'required' => false,
                //'last' => false, // Stop validation after this rule
                //'on' => 'create', // Limit validation to 'create' or 'update' operations
                ),
            ),
            'multidatabase_metadata_sort_id' => array(
                'numeric' => array(
                    'rule' => array('numeric'),
                //'message' => 'Your custom message here',
                //'allowEmpty' => false,
                //'required' => false,
                //'last' => false, // Stop validation after this rule
                //'on' => 'create', // Limit validation to 'create' or 'update' operations
                ),
            ),
        );
        return parent::beforeValidate($options);

        //The Associations below have been created with all possible keys, those that are not needed can be removed
    }

    public function getMultidatabaseFrameSetting() {

        $conditions = array(
            'frame_key' => Current::read('Frame.key')
        );

        $multidatabaseFrameSetting = $this->find('first', array(
            'recursive' => -1,
            'conditions' => $conditions,
                )
        );

        if (!$multidatabaseFrameSetting) {
            $multidatabaseFrameSetting = $this->create(array(
                'frame_key' => Current::read('Frame.key'),
            ));
        }

        return $multidatabaseFrameSetting;
    }

    public function saveMultidatabaseFrameSetting($data) {
        $this->loadModels([
            'MultidatabaseFrameSetting' => 'Multidatabases.MultidatabaseFrameSetting',
        ]);

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
            if (!$this->save(null, false)) {
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

}
