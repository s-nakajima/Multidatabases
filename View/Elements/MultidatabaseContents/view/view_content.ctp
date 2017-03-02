<?php foreach ($multidatabaseContents as $contents): ?>
	<table class="table-bordered" style="width:100%; margin-bottom:20px;">
		<?php echo $this->MultidatabaseContentView->renderGroup($multidatabaseMetadataGroups,$contents,0,1); ?>
		<?php echo $this->MultidatabaseContentView->renderGroup($multidatabaseMetadataGroups,$contents,[1,2],2); ?>
		<?php echo $this->MultidatabaseContentView->renderGroup($multidatabaseMetadataGroups,$contents,3,1); ?>
	</table>
<?php endforeach; ?>
