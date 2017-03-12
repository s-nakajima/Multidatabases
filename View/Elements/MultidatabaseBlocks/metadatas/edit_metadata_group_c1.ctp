<?php
/**
 * MultidatabasesBlocks metadatas edit_metadata_group_c1 view element
 * 汎用データベース ブロック設定 メタデータ編集フォーム 1段レイアウト view element
 *
 * @author Noriko Arai <arai@nii.ac.jp>
 * @author Tomoyuki OHNO (Ricksoft Co., Ltd.) <ohno.tomoyuki@ricksoft.jp>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */
?>

<p class="text-right">
	<a href="" class="btn btn-success nc-btn-style" ng-click="
		add(<?php echo $gPos; ?>,
		metadataGroup<?php echo $gPos; ?>.length)">
	<span class="glyphicon glyphicon-plus" aria-hidden="true"></span> <span class="hidden-xs"><?php echo __d('multidatabases', 'Add'); ?></span>
	</a>
</p>
<div class="row">
	<div class="col-xs-12 col-sm-12">
		<?php echo $this->MultidatabaseMetadataSetting->renderGroupItems($gPos); ?>
	</div>
</div>
