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

echo $this->NetCommonsHtml->css([
	'/multidatabases/css/style.css',
	'/likes/css/style.css',
]);
?>

<article class="multidatabaseContents">
	<style>
		.multidatabaseContents td,th {
			padding:5px;
		}

		.multidatabaseContents th {
			text-align:center;
			background-color:#F5F5F5;
		}
	</style>
	<?php echo $this->NetCommonsHtml->blockTitle($multidatabase['Multidatabase']['name']); ?>
	<header class="clearfix">
		<div class="pull-left">
			<?php /*
			<?php
			$paginatorUrl = NetCommonsUrl::actionUrlAsArray(Hash::merge(array(
				'plugin' => 'multidatabases',
				'controller' => 'multidatabase_contents',
				'action' => 'index',
				'block_id' => Current::read('Block.id'),
				'frame_id' => Current::read('Frame.id'),
			), $this->Paginator->params['named']));

			$currentLabel = $options[$curretSort . '.' . $curretDirection]['label'];
			?>
			<span class="btn-group">
				<button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
					<?php echo $currentLabel; ?>
					<span class="caret"></span>
				</button>
				<ul class="dropdown-menu" role="menu">
					<?php foreach ($options as $key => $sort) : ?>
						<li>
							<?php echo $this->Paginator->link(
								$sort['label'],
								array('sort' => $sort['sort'], 'direction' => $sort['direction']),
								array('url' => $paginatorUrl)
							); ?>
						</li>
					<?php endforeach; ?>
				</ul>
			</span>

			<?php echo $this->DisplayNumber->dropDownToggle(); ?>
 				*/ ?>
		</div>

		<div class="pull-right">
			<?php if (Current::permission('content_creatable')) : ?>
				<?php echo $this->Button->addLink('', null, array('tooltip' => __d('multidatabases', 'Create content'))); ?>
			<?php endif; ?>
		</div>
	</header>

	<?php if ($multidatabaseContents) : ?>
		<div>
			<?php echo $this->MultidatabaseContentView->renderContentsList($multidatabaseContents); ?>
			<?php echo $this->element('NetCommons.paginator'); ?>
		</div>
	<?php else : ?>
		<article class="nc-not-found">
			<?php echo __d('multidatabases', 'No contents found.') ?>
		</article>
	<?php endif; ?>
</article>
