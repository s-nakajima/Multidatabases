<div class="clearfix Multidatabases_content_reaction">
	<div class="pull-left">
		<div class="pull-left">
			<?php if (isset($viewMode) && ($viewMode === 'detail')) : ?>
				<span class="multidatabases__content-comment-count">
			<?php echo $this->ContentComment->count($content); ?>
		</span>
			<?php endif ?>
		</div>

		<div class="pull-left">
			<?php echo $this->Like->buttons('MultidatabaseContent', $multidatabaseSetting, $content); ?>
		</div>
		<div class="pull-right">
			<?php if (! isset($viewMode) || $viewMode <> 'detail') : ?>
				<?php echo $this->NetCommonsHtml->link(
					__d('multidatabases', 'View detail'),
					array(
						'controller' => 'multidatabase_contents',
						'action' => 'detail',
						//'frame_id' => Current::read('Frame.id'),
						'key' => $content['MultidatabaseContent']['key']
					)
				);
				?>

			<?php endif; ?>
		</div>
	</div>
</div>
