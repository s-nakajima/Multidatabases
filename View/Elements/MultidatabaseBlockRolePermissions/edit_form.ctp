<?php
/**
 * MultidatabaseSettings edit template
 *
 * @author Noriko Arai <arai@nii.ac.jp>
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */
?>

<?php echo $this->Form->hidden('Block.id'); ?>
<?php echo $this->Form->hidden('Block.key'); ?>

<?php echo $this->element('Blocks.block_creatable_setting', array(
	'settingPermissions' => array(
		'content_creatable' => __d('blocks', 'Content creatable roles'),
		'content_comment_creatable' => array(
			'label' => __d('blocks', 'Content comment creatable roles'),
			'help' => __d('content_comments', 'Content comment creatable roles help'),
		),
	),
)); ?>

<?php echo $this->element('Blocks.block_approval_setting', array(
	'model' => 'MultidatabaseSetting',
	'useWorkflow' => 'use_workflow',
	'useCommentApproval' => 'use_comment_approval',
	'settingPermissions' => array(
		'content_comment_publishable' => __d('blocks', 'Content comment publishable roles'),
	),
	'options' => array(
		Block::NEED_APPROVAL => __d('blocks', 'Need approval in both %s and comments ', __d('multidatabases', 'MultidatabaseContent')),
		Block::NEED_COMMENT_APPROVAL => __d('blocks', 'Need only comments approval'),
		Block::NOT_NEED_APPROVAL => __d('blocks', 'Not need approval'),
	),
));
