<?php
/**
 * MultidatabasesBlocks edit view element
 * 汎用データベース ブロック設定 編集用フォーム view element
 *
 * @author Noriko Arai <arai@nii.ac.jp>
 * @author Tomoyuki OHNO (Ricksoft Co., Ltd.) <ohno.tomoyuki@ricksoft.jp>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */
?>

<article class="block-setting-body">
	<?php echo $this->BlockTabs->main(BlockTabsHelper::MAIN_TAB_BLOCK_INDEX); ?>

	<div class="tab-content">
		<?php echo $this->BlockTabs->block(BlockTabsHelper::BLOCK_TAB_SETTING); ?>

		<?php echo $this->BlockForm->displayEditForm(array(
			'model' => 'Multidatabase',
			'callback' => 'Multidatabases.MultidatabaseBlocks/edit_form',
			'cancelUrl' => NetCommonsUrl::backToIndexUrl('default_setting_action'),
			'displayModified' => true,
		)); ?>

		<?php echo $this->BlockForm->displayDeleteForm(array(
			'callback' => 'Multidatabases.MultidatabaseBlocks/delete_form',
		)); ?>
	</div>
</article>
