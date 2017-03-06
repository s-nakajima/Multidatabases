<div class="panel panel-default">
	<div class="panel-heading">
		Selections
	</div>
	<div class="panel-body">
		<div class="col-xs-12">
			<div class="form-group clearfix">
				<div class="pull-right">
					<button type="button" class="btn btn-success btn-ms" ng-click="addSelection(<?php echo $gPos; ?>, $index)">
						<span class="glyphicon glyphicon-plus" aria-hidden="true"></span> <span class="hidden-xs"><?php echo __d('multidatabases', 'Add'); ?></span>
					</button>
				</div>
			</div>
		</div>
		<div ng-hide="metadataGroup<?php echo $gPos; ?>[$index]['selections'].length">
			<div class="col-xs-12">
				<p><?php echo __d('multidatbases', 'No selections.'); ?></p>
			</div>
		</div>
		<div class="col-xs-12 pre-scrollable" ng-show="metadataGroup<?php echo $gPos; ?>[$index]['selections'].length">
			<article class="form-group" ng-repeat="s in metadataGroup<?php echo $gPos; ?>[$index]['selections'] track by $index">
				<div class="input-group input-group-sm">
					<div class="input-group-btn">
						<button type="button" class="btn btn-default"
								ng-click="moveSelection('up', <?php echo $gPos; ?>, $parent.$index, $index)" ng-disabled="$first">
							<span class="glyphicon glyphicon-arrow-up"></span>
						</button>

						<button type="button" class="btn btn-default"
								ng-click="moveSelection('down', <?php echo $gPos; ?>, $parent.$index, $index)" ng-disabled="$last">
							<span class="glyphicon glyphicon-arrow-down"></span>
						</button>
					</div>

					<input type="text" name="data[MultidatabaseMetadata][<?php echo $gPos; ?>][{{$parent.$index}}][selections][{{$index}}]" ng-model="s" class="form-control" required autofocus>

					<div class="input-group-btn">
						<button type="button" class="btn btn-default" tooltip="<?php echo __d('net_commons', 'Delete'); ?>"
								ng-click="delSelection(<?php echo $gPos; ?>, $parent.$index, $index)">
							<span class="glyphicon glyphicon-remove"> </span>
						</button>
					</div>
				</div>
			</article>


		</div>

	</div>
</div>
