<?php
echo $this->NetCommonsHtml->script([
	'/multidatabases/js/edit_multi_database_metadatas.js'
]);
?>

<?php echo $this->NetCommonsForm->unlockField('MultidatabaseMetadata'); ?>

<div id="multidatabase-metadata-fields" ng-controller="MultidatabaseMetadata" ng-init="initialize(<?php echo h(json_encode(['multidatabaseMetadata' => $multidatabaseMetadata])); ?>)">
	<div class="panel panel-default">
		<div class="panel-heading clearfix">
			<div class="pull-left">
				<strong><?php echo __d('multidatabases', 'Section 1'); ?></strong>
			</div>
		</div>
		<div class="panel-body">
			<?php echo $this->MultidatabaseMetadataSetting->renderGroup(0,1); ?>
		</div>
	</div>
	<div class="panel panel-default">
		<div class="panel-heading clearfix">
			<div class="pull-left">
				<strong><?php echo __d('multidatabases', 'Section 2'); ?></strong>
			</div>
		</div>
		<div class="panel-body">
			<div class="row">
				<div class="col-xs-12 col-sm-6 multidatabase-metadata-group" id="multidatabase-metadata-group-1">
					<?php echo $this->MultidatabaseMetadataSetting->renderGroup(1,2); ?>
				</div>
				<div class="col-xs-12 col-sm-6 multidatabase-metadata-group" id="multidatabase-metadata-group-2">
					<?php echo $this->MultidatabaseMetadataSetting->renderGroup(2,2); ?>
				</div>
			</div>
		</div>
	</div>
	<div class="panel panel-default">
		<div class="panel-heading clearfix">
			<div class="pull-left">
				<strong><?php echo __d('multidatabases', 'Section 3'); ?></strong>
			</div>
		</div>
		<div class="panel-body">
			<?php echo $this->MultidatabaseMetadataSetting->renderGroup(3,1); ?>
		</div>
	</div>
</div>
