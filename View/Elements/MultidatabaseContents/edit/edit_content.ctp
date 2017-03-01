<div class="panel panel-default">
	<div class="panel-body">
		<?php echo $this->MultidatabaseContentEdit->renderGroup($multidatabaseMetadataGroups,0,1); ?>
	</div>
</div>
<div class="panel panel-default">
	<div class="panel-body">
		<div class="row">
			<div class="col-xs-12 col-sm-6 multidatabase-content-group" id="multidatabase-content-group-1">
				<?php echo $this->MultidatabaseContentEdit->renderGroup($multidatabaseMetadataGroups,1,2); ?>
			</div>
			<div class="col-xs-12 col-sm-6 multidatabase-content-group" id="multidatabase-content-group-2">
				<?php echo $this->MultidatabaseContentEdit->renderGroup($multidatabaseMetadataGroups,2,2); ?>
			</div>
		</div>
	</div>
</div>
<div class="panel panel-default">
	<div class="panel-body">
		<?php echo $this->MultidatabaseContentEdit->renderGroup($multidatabaseMetadataGroups,3,1); ?>
	</div>
</div>

