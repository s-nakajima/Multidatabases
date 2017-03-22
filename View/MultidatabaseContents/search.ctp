<?php
/**
 * MultidatabasesContents search form view
 * 汎用データベース コンテンツ検索フォーム view
 *
 * @author Noriko Arai <arai@nii.ac.jp>
 * @author Tomoyuki OHNO (Ricksoft Co., Ltd.) <ohno.tomoyuki@ricksoft.jp>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */

echo $this->NetCommonsHtml->css([
	'/multidatabases/css/style.css',
]);
?>

<div class="multidatabase-contents-search form">
	<article>
		<?php echo $this->NetCommonsHtml->blockTitle($multidatabase['Multidatabase']['name']); ?>
		<?php echo $this->NetCommonsForm->create('MultidatabaseContentSearch', ['type' => 'get']);?>
		<div class="panel panel-default">
			<div class="panel-body">
					<div>
						<?php
							$options = [
								'label' => __d('multidatabases','Keywords')
							];
							echo $this->NetCommonsForm->input('keywords', $options);
						?>
					</div>
					<div>
						<?php
							$options = [
								'type' => 'select',
								'options' => [
									'and' => __d('multidatabases','And search'),
									'or' => __d('multidatabases', 'Or search'),
									'phrase' => __d('multidatabases', 'Phrase search')
								],
								'label' => __d('multidatabases','Search type')
							];
							echo $this->NetCommonsForm->input('search_type', $options);
						?>
					</div>
					<div>
						<?php
							$options = [
								'label' => __d('multidatabases','Create user')
							];
							echo $this->NetCommonsForm->input('create_user', $options);
						?>
					</div>
					<div>
						<label for="create_date_range" class="control-label">
							<?php echo __d('multidatabases','Create date', $options); ?>
						</label>
						<?php
							$options = [
								'type' => 'datetime',
							];
							echo $this->NetCommonsForm->input('create_date', $options);
						?>
					</div>
					<?php echo $this->MultidatabaseContentView->dropDownToggleSelect($multidatabaseMetadata, 'search'); ?>

					<div>
						<?php
						$options = [
							'type' => 'select',
							'options' => [
								'0' => __d('multidatabases','All'),
								'pub' => __d('multidatabases','Published'),
								'unpub' => __d('multidatabases', 'Unpublished')
							],
							'label' => __d('multidatabases','Status')
						];
						echo $this->NetCommonsForm->input('status', $options);
						?>
					</div>
					<?php echo $this->MultidatabaseContentView->dropDownToggleSort($multidatabaseMetadata, 'search'); ?>
			</div>
			<div class="panel-footer text-center">
				<?php echo $this->Button->cancel(__d('net_commons', 'Cancel'), '', []); ?>
				<a href="" class="btn btn-default"><?php echo __d('multidatabases', 'Cancel'); ?></a>
				<button class="btn btn-primary" type="submit"><?php echo __d('multidatabases', 'Search'); ?></button>
			</div>
		</div>
		<?php echo $this->NetCommonsForm->end() ?>
	</article>
</div>
