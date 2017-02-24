<div ng-repeat="g<?php echo $gPos; ?> in metadataGroup<?php echo $gPos; ?> track by $index">
	<ul class="multidatabase-metadata-edit multidatabase-metadata-item">
		<li class="list-group-item clearfix list-group-item-success">
			<div class="pull-left multidatabase-metadata-move">
				<div class="btn-group">
					<button type="button" class="btn btn-xs btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" ng-disabled="sending">
						<?php echo __d('multidatabases', 'Move'); ?><span class="caret"></span>
					</button>
					<ul class="dropdown-menu">
						<li class="move-item-up"><a href="" ng-click="moveRank('up',<?php echo $gPos; ?>,$index)" ng-disabled="$first"><span class="glyphicon glyphicon-arrow-up"><?php echo __d('multidatabases', 'Go to Up'); ?></a></li>
						<li class="move-item-down"><a href="" ng-click="moveRank('down',<?php echo $gPos; ?>,$index)" ng-disabled="$last"><span class="glyphicon glyphicon-arrow-down"><?php echo __d('multidatabases', 'Go to Down'); ?></a></li>
						<li class="divider"></li>
						<li class="move-item-group-0"><a href="" ng-click="movePosition(0,<?php echo $gPos; ?>,$index)"><span><?php echo __d('multidatabases', 'Go to '); ?><?php echo __d('multidatabases', 'Section 1'); ?></span></a></li>
						<li class="move-item-group-1"><a href="" ng-click="movePosition(1,<?php echo $gPos; ?>,$index)"><span><?php echo __d('multidatabases', 'Go to '); ?><?php echo __d('multidatabases', 'Section 2 (Left)'); ?></span></a></li>
						<li class="move-item-group-2"><a href="" ng-click="movePosition(2,<?php echo $gPos; ?>,$index)"><span><?php echo __d('multidatabases', 'Go to '); ?><?php echo __d('multidatabases', 'Section 2 (Right)'); ?></span></a></li>
						<li class="move-item-group-3"><a href="" ng-click="movePosition(3,<?php echo $gPos; ?>,$index)"><span><?php echo __d('multidatabases', 'Go to '); ?><?php echo __d('multidatabases', 'Section 3'); ?></span></a></li>
					</ul>
				</div>
			</div>
			<div class="pull-left">
				<span class="multidatabase-metadata-item-caption">{{g<?php echo $gPos; ?>.name}}</span>
			</div>
			<div class="pull-right">
				<button type="button" class="btn btn-primary nc-btn-style btn-xs btn-multidatabase-metadata-item-edit" ng-click="edit(g<?php echo $gPos; ?>.position,g<?php echo $gPos; ?>.rank)">
					<span class="glyphicon glyphicon-edit" aria-hidden="true"></span> <span class="hidden-xs">編集</span>
				</button>
			</div>
			 <?php foreach ($this->MultidatabaseMetadataSetting->fieldList() as $field): ?>
				<input name="data[MultidatabaseMetadatas][{{$index}}][<?php echo $field; ?>]" type="text" class="hidden" value="{{g<?php echo $gPos; ?>.<?php echo $field; ?>}}">
			<?php endforeach; ?>
		</li>
	</ul>
</div>
