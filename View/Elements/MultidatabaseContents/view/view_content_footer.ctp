<footer>
	<div class="clearfix">
		<div class="pull-left">
			<?php if (! isset($viewMode) || ($viewMode <> 'detail')) : ?>
				<span class="multidatabases__content-comment-count">
					<?php echo $this->ContentComment->count($content); ?>
				</span>
			<?php endif ?>
			<?php echo $this->Like->buttons('MultidatabaseContent', $multidatabaseSetting, $content); ?>
		</div>
		<div class="pull-right">
			<?php echo $this->NetCommonsHtml->handleLink($content, array('avatar' => true)); ?>&nbsp;
			<?php if (! isset($viewMode) || $viewMode <> 'detail') : ?>
				<?php echo $this->NetCommonsHtml->link(
					__d('multidatabases', 'View detail'),
					array(
						'controller' => 'multidatabase_contents',
						'action' => 'detail',
						//'frame_id' => Current::read('Frame.id'),
						'key' => $content['MultidatabaseContent']['key']
					),
					array(
						'class' => 'btn btn-default',
					)
				);
				?>
			<?php endif; ?>
		</div>
	</div>
	<div>
		<?php if (isset($viewMode) && ($viewMode === 'detail')) : ?>
			<?php echo $this->ContentComment->index($content); ?>
		<?php endif; ?>
	</div>
</footer>
