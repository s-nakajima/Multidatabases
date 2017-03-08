<?php if ($this->Workflow->canEdit('Multidatabases.MultidatabaseContent', $multidatabaseContent)) : ?>
	<div class="text-right">
		<?php echo $this->Button->editLink('',
			array(
				'controller' => 'multidatabase_content',
				'action' => 'edit',
				'key' => $multidatabaseContent['MultidatabaseContent']['key']
			),
			array(
				'tooltip' => true,
			)
		); ?>
	</div>
<?php endif;
