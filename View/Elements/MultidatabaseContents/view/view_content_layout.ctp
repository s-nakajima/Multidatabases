<article style="margin-bottom:50px;">
	<div class="col-xs-12">
		<?php echo $this->MultidatabaseContentView->renderGroup($multidatabaseMetadataGroups,$content,0,1); ?>
	</div>
	<div class="col-xs-12 col-sm-6">
		<?php echo $this->MultidatabaseContentView->renderGroup($multidatabaseMetadataGroups,$content,1,2); ?>
	</div>
	<div class="col-xs-12 col-sm-6">
		<?php echo $this->MultidatabaseContentView->renderGroup($multidatabaseMetadataGroups,$content,2,2); ?>
	</div>
	<div class="col-xs-12">
		<?php echo $this->MultidatabaseContentView->renderGroup($multidatabaseMetadataGroups,$content,3,1); ?>
	</div>
	<?php echo $this->MultidatabaseContentView->renderContentFooter($content,true); ?>
</article>

