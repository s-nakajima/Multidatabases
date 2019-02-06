<?php
/**
 * MultidatabasesContentExports edit view
 *
 * @author Noriko Arai <arai@nii.ac.jp>
 * @author Tomoyuki OHNO (Ricksoft Co., Ltd.) <ohno.tomoyuki@ricksoft.jp>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */
?>

<div class="block-setting-body">
	<?php echo $this->BlockTabs->main(BlockTabsHelper::MAIN_TAB_BLOCK_INDEX); ?>

	<div class="tab-content">
		<?php echo $this->BlockTabs->block('content_exports'); ?>
	</div>
</div>

<div class="panel panel-default">
	<?php echo $this->NetCommonsForm->create(false, array('type' => 'get', 'ng-submit' => false)); ?>

	<div class="panel-body">
		<?php echo $this->NetCommonsForm->input('pass', array(
			'type' => 'text',
			'label' => __d('multidatabases', 'Password'),
			'value' => substr(str_shuffle(ImportExportBehavior::RANDAMSTR), 0, 10),
			'help' => __d('multidatabases', 'If you do not want to assign a password, please leave it blank.'),
		)); ?>
	</div>

	<div class="panel-footer text-center">
		<?php echo $this->Button->Save(
			__d('net_commons', 'OK'),
			array('ng-class' => null),
			array('ng-class' => null)
		); ?>
	</div>

	<?php echo $this->NetCommonsForm->end(); ?>
</div>
