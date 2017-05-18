<?php
/**
 * MultidatabasesFrameSettings edit=form view element
 * 汎用データベース フレーム設定 view element
 *
 * @author Noriko Arai <arai@nii.ac.jp>
 * @author Tomoyuki OHNO (Ricksoft Co., Ltd.) <ohno.tomoyuki@ricksoft.jp>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */ ?>

<?php echo $this->NetCommonsForm->hidden('Frame.id'); ?>
<?php echo $this->NetCommonsForm->hidden('MultidatabaseFrameSetting.id'); ?>
<?php echo $this->NetCommonsForm->hidden('MultidatabaseFrameSetting.frame_key'); ?>

<?php echo $this->DisplayNumber->select('MultidatabaseFrameSetting.content_per_page', [
	'label' => __d('multidatabases', 'Show contents per page'),
	'unit' => [
		'single' => __d('multidatabases', '%s article'),
		'multiple' => __d('multidatabases', '%s articles'),
	],
]);

echo $this->MultidatabaseContentView->dropDownToggleSort($multidatabaseMetadata, 'frame_setting');
