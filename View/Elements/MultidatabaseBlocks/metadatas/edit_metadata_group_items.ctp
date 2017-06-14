<?php
/**
 * MultidatabasesBlocks metadatas edit_metadata_group_items view element
 * 汎用データベース ブロック設定 メタデータ編集フォーム アイテム view element
 *
 * @author Noriko Arai <arai@nii.ac.jp>
 * @author Tomoyuki OHNO (Ricksoft Co., Ltd.) <ohno.tomoyuki@ricksoft.jp>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */
?>

<uib-accordion close-others="true">
	<div ng-repeat="<?php echo "g{$gPos} in metadataGroup${gPos}"; ?> track by $index"
	     class="panel-default form-horizontal"
	     is-open="metadata.isOpen"
	     uib-accordion-group>
		<div uib-accordion-heading>
			<div class="pull-right">
				<button class="btn btn-xs btn-danger" type="button"
				        ng-if="<?php echo "g${gPos}.is_title != 1"; ?>"
				        ng-click="delete($event, <?php echo $gPos; ?>,
				            $index,
				            '<?php echo __d('multidatabases', 'Do you want to delete this metadata field?'); ?>'
				            )">
					<span class="glyphicon glyphicon-remove"> </span>
				</button>
			</div>
			<div class="pull-left">
				<div class="btn-group" uib-dropdown dropdown-append-to-body>
					<button type="button" class="btn btn-xs btn-default dropdown-toggle" uib-dropdown-toggle
					        ng-disabled="sending" ng-click="eventStop($event)">
						<?php echo __d('multidatabases', 'Move'); ?><span class="caret"></span>
					</button>
					<ul class="dropdown-menu" uib-dropdown-menu role="menu">
						<?php // 上に移動（無効） ?>
						<li class="move-item-up disabled" ng-if="$first == true">
							<a href="">
								<span class="glyphicon glyphicon-arrow-up">
									<?php echo __d('multidatabases', 'Go to Up'); ?>
								</span>
							</a>
						</li>
						<?php // 上に移動（有効） ?>
						<li class="move-item-up" ng-if="$first == false">
							<a href="" ng-click="moveRank('up',<?php echo $gPos; ?>,$index)">
								<span class="glyphicon glyphicon-arrow-up">
									<?php echo __d('multidatabases', 'Go to Up'); ?>
								</span>
							</a>
						</li>
						<?php // 下に移動（無効） ?>
						<li class="move-item-down disabled" ng-if="$last == true">
							<a href="">
								<span class="glyphicon glyphicon-arrow-down">
									<?php echo __d('multidatabases', 'Go to Down'); ?>
								</span>
							</a>
						</li>
						<?php // 下に移動（有効） ?>
						<li class="move-item-down" ng-if="$last == false">
							<a href="" ng-click="moveRank('down',<?php echo $gPos; ?>,$index)">
								<span class="glyphicon glyphicon-arrow-down">
									<?php echo __d('multidatabases', 'Go to Down'); ?>
								</span>
							</a>
						</li>
						<li class="divider"></li>
						<?php // 1段目に移動（無効） ?>
						<li class="move-item-group-0 disabled" ng-if="<?php echo "g${gPos}.position == 0";?>">
							<a href="">
								<span>
									<?php echo __d('multidatabases', 'Go to Section 1'); ?>
								</span>
							</a>
						</li>
						<?php // 1段目に移動（有効） ?>
						<li class="move-item-group-0" ng-if="<?php echo "g${gPos}.position != 0";?>">
							<a href="" ng-click="movePosition(0,<?php echo $gPos; ?>,$index)">
								<span>
									<?php echo __d('multidatabases', 'Go to Section 1'); ?>
								</span>
							</a>
						</li>
						<?php // 2段目左に移動（無効） ?>
						<li class="move-item-group-1 disabled" ng-if="<?php echo "g${gPos}.position == 1";?>">
							<a href="">
								<span>
									<?php echo __d('multidatabases', 'Go to Section 2 (Left)'); ?>
								</span>
							</a>
						</li>
						<?php // 2段目左に移動（有効） ?>
						<li class="move-item-group-1" ng-if="<?php echo "g${gPos}.position != 1";?>">
							<a href="" ng-click="movePosition(1,<?php echo $gPos; ?>,$index)">
								<span>
									<?php echo __d('multidatabases', 'Go to Section 2 (Left)'); ?>
								</span>
							</a>
						</li>
						<?php // 2段目右に移動（無効） ?>
						<li class="move-item-group-2 disabled" ng-if="<?php echo "g${gPos}.position == 2";?>">
							<a href="">
								<span>
									<?php echo __d('multidatabases', 'Go to Section 2 (Right)'); ?>
								</span>
							</a>
						</li>
						<?php // 2段目右に移動（有効） ?>
						<li class="move-item-group-2" ng-if="<?php echo "g${gPos}.position != 2";?>">
							<a href="" ng-click="movePosition(2,<?php echo $gPos; ?>,$index)">
								<span>
									<?php echo __d('multidatabases', 'Go to Section 2 (Right)'); ?>
								</span>
							</a>
						</li>
						<?php // 3段目に移動（無効） ?>
						<li class="move-item-group-3 disabled" ng-if="<?php echo "g${gPos}.position == 3";?>">
							<a href="">
								<span>
									<?php echo __d('multidatabases', 'Go to Section 3'); ?>
								</span>
							</a>
						</li>
						<?php // 3段目に移動（有効） ?>
						<li class="move-item-group-3" ng-if="<?php echo "g${gPos}.position  != 3";?>">
							<a href="" ng-click="movePosition(3,<?php echo $gPos; ?>,$index)">
								<span>
									<?php echo __d('multidatabases', 'Go to Section 3'); ?>
								</span>
							</a>
						</li>
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

