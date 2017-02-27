<?php echo $this->NetCommonsHtml->script([
	'/multidatabases/js/edit_multi_database_contents.js',
]); ?>
<div class="multidatabaseContents form" ng-controller="MultidatabaseContent" ng-init="initialize(<?php echo h(json_encode(['multidatabaseMetadatas' => $multidatabaseMetadatas])); ?>)">
	<article>
		<h1><?php echo h($multidatabase['Multidatabase']['name']) ?></h1>
		<div class="panel panel-default">
			<?php echo $this->NetCommonsForm->create(
				'MultidatabaseContent',
				array(
					'inputDefaults' => array(
						'div' => 'form-group',
						'class' => 'form-control',
						'error' => false,
					),
					'div' => 'form-control',
					'novalidate' => true
				)
			);
			?>
			<?php echo $this->NetCommonsForm->hidden('key'); ?>
			<?php echo $this->NetCommonsForm->hidden('Frame.id', [
						'value' => Current::read('Frame.id'),
					]); ?>
			<?php echo $this->NetCommonsForm->hidden('Block.id', [
				'value' => Current::read('Block.id'),
			]); ?>

			<div class="panel-body">
				<fieldset>
				<?php echo $this->element('MultidatabaseContents/edit/edit_content'); ?>
				</fieldset>
				<hr/>
				<?php echo $this->Workflow->inputComment('MultidatabaseContent.status'); ?>

			</div>

			<?php echo $this->Workflow->buttons('MultidatabaseContent.status'); ?>

			<?php echo $this->NetCommonsForm->end() ?>

			<?php if ($isEdit && $isDeletable) : ?>
				<div  class="panel-footer" style="text-align: right;">
					<?php echo $this->NetCommonsForm->create('MultidatabaseContent',
						array(
							'type' => 'delete',
							'url' => NetCommonsUrl::blockUrl(
								array('controller' => 'multidatabase_contents', 'action' => 'delete', 'frame_id' => Current::read('Frame.id')))
						)
					) ?>
					<?php echo $this->NetCommonsForm->input('key', array('type' => 'hidden')); ?>

					<?php echo $this->Button->delete('', __d('net_commons', 'Deleting the %s. Are you sure to proceed?', __d('multidatabases', 'MultidatabaseContent')));?>

					</span>
					<?php echo $this->NetCommonsForm->end() ?>
				</div>
			<?php endif ?>

		</div>

		<?php echo $this->Workflow->comments(); ?>

	</article>

</div>


