<?php
/**
 * MultidatabasesBlocks index view
 * 汎用データベース ブロック設定 一覧表示 view
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

	<?php echo $this->BlockIndex->description(); ?>

	<div class="tab-content">
		<?php echo $this->BlockIndex->create(); ?>
		<?php echo $this->BlockIndex->addLink(); ?>

		<?php echo $this->BlockIndex->startTable(); ?>
		<thead>
		<tr>
			<?php echo $this->BlockIndex->tableHeader(
				'Frame.block_id'
			); ?>
			<?php echo $this->BlockIndex->tableHeader(
				'BlocksLanguage.name', __d('multidatabases', 'Multidatabase name'),
				['sort' => true, 'editUrl' => true]
			); ?>
			<?php echo $this->BlockIndex->tableHeader(
				'Block.content_count', __d('net_commons', 'Number'),
				['sort' => true, 'type' => 'numeric']
			); ?>
			<?php echo $this->BlockIndex->tableHeader(
				'Block.public_type', __d('blocks', 'Publishing setting'),
				['sort' => true]
			); ?>
			<?php echo $this->BlockIndex->tableHeader(
				'TrackableUpdater.handlename', __d('net_commons', 'Modified user'),
				['sort' => true, 'type' => 'handle']
			); ?>
			<?php echo $this->BlockIndex->tableHeader(
				'Block.modified', __d('net_commons', 'Modified datetime'),
				['sort' => true, 'type' => 'datetime']
			); ?>
		</tr>
		</thead>
		<tbody>
		<?php foreach ($multidatabases as $multidatabase) : ?>
			<?php echo $this->BlockIndex->startTableRow($multidatabase['Block']['id']); ?>
			<?php echo $this->BlockIndex->tableData(
				'Frame.block_id', $multidatabase['Block']['id']
			); ?>
			<?php echo $this->BlockIndex->tableData(
				'BlocksLanguage.name', $multidatabase['BlocksLanguage']['name'],
				['editUrl' => ['block_id' => $multidatabase['Block']['id']]]
			); ?>
			<?php echo $this->BlockIndex->tableData(
				'Block.content_count', $multidatabase['Block']['content_count'],
				['type' => 'numeric']
			); ?>
			<?php echo $this->BlockIndex->tableData(
				'Block.public_type', $multidatabase
			); ?>
			<?php echo $this->BlockIndex->tableData(
				'TrackableUpdater', $multidatabase,
				['type' => 'handle']
			); ?>
			<?php echo $this->BlockIndex->tableData(
				'Block.modified', $multidatabase['Block']['modified'],
				['type' => 'datetime']
			); ?>
			<?php echo $this->BlockIndex->endTableRow(); ?>
		<?php endforeach; ?>
		</tbody>
		<?php echo $this->BlockIndex->endTable(); ?>

		<?php echo $this->BlockIndex->end(); ?>

		<?php echo $this->element('NetCommons.paginator'); ?>
	</div>


</article>




