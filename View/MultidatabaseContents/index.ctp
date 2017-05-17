<?php
/**
 * MultidatabasesContents index view
 * 汎用データベース コンテンツ一覧表示 view
 *
 * @author Noriko Arai <arai@nii.ac.jp>
 * @author Tomoyuki OHNO (Ricksoft Co., Ltd.) <ohno.tomoyuki@ricksoft.jp>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */

echo $this->Html->script(
	'/AuthorizationKeys/js/authorization_keys.js',
	array(
		'plugin' => false,
		'once' => true,
		'inline' => false
	)
);

echo $this->NetCommonsHtml->css([
	'/multidatabases/css/style.css',
	'/likes/css/style.css',
]);
?>

<article class="multidatabase-contents">
	<?php echo $this->NetCommonsHtml->blockTitle($multidatabase['Multidatabase']['name']); ?>
	<header class="clearfix">
		<div class="pull-left">
			<?php echo $this->DisplayNumber->dropDownToggle(); ?>
			<?php echo $this->MultidatabaseContentView->dropDownToggleSort($multidatabaseMetadata); ?>
			<?php echo $this->MultidatabaseContentView->dropDownToggleSelect($multidatabaseMetadata); ?>
		</div>

		<div class="pull-right">
			<?php echo $this->Button->searchLink(
				'',
				null,
				['tooltip' => __d('multidatabases', 'Search content')]
			); ?>
			<?php if (Current::permission('content_creatable')) : ?>
				<?php echo $this->Button->addLink(
					'',
					null,
					['tooltip' => __d('multidatabases', 'Create content')]
				); ?>
			<?php endif; ?>
		</div>
	</header>

	<?php if ($multidatabaseContents) : ?>
		<?php echo $this->MultidatabaseContentView->renderContentsList($multidatabaseContents); ?>
		<?php echo $this->element('NetCommons.paginator'); ?>
	<?php else : ?>
		<article class="nc-not-found">
			<?php echo __d('multidatabases', 'No contents found.') ?>
		</article>
	<?php endif; ?>
</article>
