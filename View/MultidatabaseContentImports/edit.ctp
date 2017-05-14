<?php
/**
 *  [[changeme]] [Controller|Model|View]
 *
 *  @author Noriko Arai <arai@nii.ac.jp>
 *  @author Tomoyuki OHNO (Ricksoft, Co., Ltd.) <ohno.tomoyuki@ricksoft.jp>
 *  @link http://www.netcommons.org NetCommons Project
 *  @license http://www.netcommons.org/license.txt NetCommons License
 *  @copyright Copyright 2014, NetCommons Project
 */
?>

<div class="block-setting-body">
	<?php echo $this->BlockTabs->main(BlockTabsHelper::MAIN_TAB_BLOCK_INDEX); ?>

	<div class="tab-content">
		<?php echo $this->BlockTabs->block('content_imports'); ?>
	</div>
</div>

<div class="well well-sm">
	<?php echo __d('multidatabases', 'Import description'); ?>
</div>

<div class="text-right">
	<?php echo $this->NetCommonsHtml->link(__d('multidatabases', 'Format file download'), array(
		'action' => 'download_import_format'
	)); ?>
</div>
<div class="panel panel-default">
	<?php echo $this->NetCommonsForm->create(false, array('type' => 'file')); ?>

	<div class="panel-body">
		<?php
		echo $this->NetCommonsForm->input('import_csv', array(
			'type' => 'file',
			'class' => '',
			'label' => __d('multidatabases', 'Import file'),
		));
		?>
	</div>

	<div class="panel-footer text-center">
		<?php echo $this->Button->Save(
			__d('net_commons', 'OK'),
			array('ng-class' => null),
			array('ng-class' => null)
		); ?>
	</div>

	<?php echo $this->Form->end(); ?>
</div>

