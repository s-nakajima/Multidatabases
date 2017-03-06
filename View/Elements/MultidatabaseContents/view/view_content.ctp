<?php foreach ($multidatabaseContents as $content): ?>
	<div style="margin-bottom:50px;">
		<table class="table-bordered" style="width:100%;">
			<?php echo $this->MultidatabaseContentView->renderGroup($multidatabaseMetadataGroups,$content,0,1); ?>
			<?php echo $this->MultidatabaseContentView->renderGroup($multidatabaseMetadataGroups,$content,[1,2],2); ?>
			<?php echo $this->MultidatabaseContentView->renderGroup($multidatabaseMetadataGroups,$content,3,1); ?>
		</table>
		<?php echo $this->element('MultidatabaseContents/view/view_content_footer', array('content' => $content, 'index' => true)); ?>
	</div>
<?php endforeach; ?>
