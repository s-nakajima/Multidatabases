<?php
/**
 * メール設定Controller
 *
 * @author Noriko Arai <arai@nii.ac.jp>
 * @author Tomoyuki OHNO (Ricksoft, Inc) <ohno.tomoyuki@ricksoft.jp>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */

App::uses('MailSettingsController', 'Mails.Controller');

/**
 * メール設定Controller
 *
 * @author Tomoyuki OHNO (Ricksoft, Inc) <ohno.tomoyuki@ricksoft.jp>
 * @package NetCommons\Blogs\Controller
 */
class MultidatabaseMailSettingsController extends MailSettingsController {

	/**
	 * use helpers
	 *
	 * @var array
	 */
	public $helpers = array(
		'Blocks.BlockRolePermissionForm',
		'Blocks.BlockTabs' => array(
			'mainTabs' => array(
				'block_index',
				'frame_settings'
			),
			'blockTabs' => array(
				'block_settings' => array('url' => array('controller' => 'multidatabase_blocks')),
				'metadata_settings' => array('url' => array('controller' => 'multidatabase_metadata_settings'), 'label' => ['multidatabases', 'Metadata Settings']),
				'mail_settings' => array('url' => array('controller' => 'multidatabase_mail_settings')),
				'role_permissions' => array('url' => array('controller' => 'multidatabase_block_role_permissions')),
			)
		),
		'Mails.MailForm',
	);

}
