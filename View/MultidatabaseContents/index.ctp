<?php
echo $this->NetCommonsHtml->css([
	'/multidatabases/css/multidatabases.css',
	'/likes/css/style.css',
]);
echo $this->NetCommonsHtml->script([
	'/multidatabases/js/multidatabases.js',
	'/likes/js/likes.js',
]);
?>

<article class="multidatbaseContents">
	<?php echo $this->NetCommonsHtml->blockTitle($multidatabase['name']); ?>
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
		<div class="nc-content-list">
			<?php foreach ($multidatabaseContents as $multidatabaseContent) : ?>
				<?php echo $this->element('MultidatabaseContents/index_multidatabase_content', array(
					'multidatabaseContent' => $multidatabaseContent
				)); ?>
			<?php endforeach; ?>

			<?php echo $this->element('NetCommons.paginator'); ?>
		</div>

	<?php else : ?>
		<article class="nc-not-found">
			<?php echo __d('multidatabases', 'No contents found.') ?>
		</article>
	<?php endif; ?>
</article>
