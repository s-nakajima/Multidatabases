<p class="text-right">
	<a href="" id="btn-multidatabase-metadata-group-add-item-<?php echo $gPos; ?>" class="btn btn-success nc-btn-style btn-sm btn-multidatabase-metadata-group-add-item"  ng-click="add(<?php echo $gPos; ?>,metadataGroup0.length)">
		<span class="glyphicon glyphicon-plus" aria-hidden="true"></span> <span class="hidden-xs">追加</span>
	</a>
</p>
<div class="row">
	<div class="col-xs-12 col-sm-12 multidatabase-metadata-group" id="multidatabase-metadata-group-<?php echo $gPos; ?>">
		<?php echo $this->MultidatabaseMetadataSetting->renderGroupItems($gPos); ?>
	</div>
</div>
