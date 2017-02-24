<ul class="multidatabase-metadata-edit">
	<li class="list-group-item clearfix list-group-item-success">
		<div class="pull-left multidatabase-metadata-move">
			<div class="btn-group">
				<?php echo $this->MultidatabaseMetadataSetting->moveSetting($currentMetadata,$multidatabaseMetadatas); ?>
			</div>
		</div>
		<div class="pull-left">
			<?php  echo h($currentMetadata['name']); ?>
			<?php if (isset($currentMetadata['is_required']) && $currentMetadata['is_required']) : ?>
				<?php echo $this->element('NetCommons.required'); ?>
			<?php endif; ?>
		</div>
		<div class="pull-right">
			<?php
			echo $this->Button->editLink('',
				array(
					'controller' => 'multidatabase_metadata_property_settings',
					'action' => 'edit',
					'frame_id' => Current::read('Frame.id'),
					'block_id' => Current::read('Frame.block_id'),
					h($currentMetadata['id']),
				),
				array('iconSize' => 'btn-xs'));
			?>
		</div>
	</li>
</ul>
