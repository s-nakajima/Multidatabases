<uib-accordion close-others="true">
	<div ng-repeat="g<?php echo $gPos; ?> in metadataGroup<?php echo $gPos; ?> track by $index"
		 class="panel-default form-horizontal"
		 is-open="metadata.isOpen"
		 uib-accordion-group>
		<div uib-accordion-heading>
			<div class="pull-right">
				<button class="btn btn-xs btn-danger" type="button"
						ng-disabled="metadataGroup<?php echo $gPos; ?>.length < 2"
						ng-click="delete($event, <?php echo $gPos; ?>, $index, '<?php echo __d('multidatabases', 'Do you want to delete this question ?'); ?>')">
					<span class="glyphicon glyphicon-remove"> </span>
				</button>
			</div>
			<div class="pull-left">
				<div class="btn-group" uib-dropdown dropdown-append-to-body>
					<button type="button" class="btn btn-xs btn-default dropdown-toggle" uib-dropdown-toggle ng-disabled="sending" ng-click="eventStop($event)">
						<?php echo __d('multidatabases', 'Move'); ?><span class="caret"></span>
					</button>
					<ul class="dropdown-menu" uib-dropdown-menu role="menu">
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
				<span class="multidatabase-metadata-item-caption">{{g<?php echo $gPos; ?>.name}}</span>
			<div class="clearfix"></div>
		</div>
		<div>
			<?php echo $this->MultidatabaseMetadataSetting->renderGroupItemProperty($gPos); ?>
		</div>
	</div>
</uib-accordion>

