<?php
/**
 * MultidatabaseContents search_results View
 *
 * @author Noriko Arai <arai@nii.ac.jp>
 * @author Tomoyuki OHNO (Ricksoft, Co., Ltd.) <ohno.tomoyuki@ricksoft.jp>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */

echo $this->NetCommonsHtml->css([
	'/multidatabases/css/style.css',
	'/likes/css/style.css',
]);
?>

<article class="multidatabase-contents">
	<?php echo $this->NetCommonsHtml->blockTitle($multidatabase['Multidatabase']['name']); ?>
	<header class="clearfix">
		<div class="pull-left">
			<?php echo $this->LinkButton->toList(); ?>
		</div>
		<div class="pull-right">
			<?php
			$named = $this->Paginator->params['named'];
			$named['page'] = '1';
			$url = NetCommonsUrl::blockUrl($named);
			$url['?'] = $this->request->query;
			echo $this->DisplayNumber->dropDownToggle(['url' => $url]);
			?>
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
