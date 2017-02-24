<div class="modal" id="multidatabaseMetadataSettingEdit" data-backdrop="static">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				{{metadataEdit.name}}
			</div>
			<div class="modal-body">
				<div class="form-group">
					<label for="multidatabaseMetadataSettingEditName" class="control-label">
						<?php echo __d('multidatabases', 'Field name'); ?>
					</label>
					<input type="text" class="form-control" id="multidatabaseMetadataSettingEditName"
						   ng-model="metadataEdit.name">
				</div>
				<div class="form-select-outer">
					<label><?php echo __d('multidatabases', 'Field type'); ?></label>
					<strong class="text-danger h4">*</strong><br>
					<?php echo $this->NetCommonsForm->select('MultidatabaseMetadataSettingEdit.type', array(
						'text' => 'テキスト',
						'textarea' => 'テキストエリア',
						'link' => 'リンク',
						'select' => '選択式（択一）',
						'checkbox' => '選択式（複数）',
						'wysiwyg' => 'WYSIWYGテキスト',
						'file' => 'ファイル',
						'image' => '画像',
						'autonumber' => '自動採番',
						'mail' => 'メール',
						'date' => '日付',
						'created' => '登録日時',
						'updated' => '更新日時',
					)); ?>
				</div>
				<div class="form-group">
					<div class="checkbox">
						<label class="control-label" for="MultidatabaseMetadataSettingEditIsRequire">
							<input type="checkbox"
								   id="MultidatabaseMetadataSettingEditIsRequire"
								   ng-true-value="1"
								   ng-false-value=""
								   ng-model="metadataEdit.is_require">Require
						</label>
					</div>
				</div>
				<div class="form-group">
					<div class="checkbox">
						<label class="control-label" for="MultidatabaseMetadataSettingEditIsSearchable">
							<input type="checkbox"
								   id="MultidatabaseMetadataSettingEditIsSearchable"
								   ng-true-value="1"
								   ng-false-value=""
								   ng-model="metadataEdit.is_searchable">Searchable
						</label>
					</div>
				</div>
				<div class="form-group">
					<div class="checkbox">
						<label class="control-label" for="MultidatabaseMetadataSettingEditIsSortable">
							<input type="checkbox"
								   id="MultidatabaseMetadataSettingEditIsSortable"
								   ng-true-value="1"
								   ng-false-value=""
								   ng-model="metadataEdit.is_sortable">Sortable
						</label>
					</div>
				</div>
				<div class="form-group">
					<div class="checkbox">
						<label class="control-label" for="MultidatabaseMetadataSettingEditIsVisibleList">
							<input type="checkbox"
								   id="MultidatabaseMetadataSettingEditIsVisibleList"
								   ng-true-value="1"
								   ng-false-value=""
								   ng-model="metadataEdit.is_visible_list">Visible List
						</label>
					</div>
				</div>
				<div class="form-group">
					<div class="checkbox">
						<label class="control-label" for="MultidatabaseMetadataSettingEditIsVisibleDetail">
							<input type="checkbox"
								   id="MultidatabaseMetadataSettingEditIsVisibleDetail"
								   ng-true-value="1"
								   ng-false-value=""
								   ng-model="metadataEdit.is_visible_detail">Visible Detail
						</label>
					</div>
				</div>

			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default btn-workflow" ng-click="editCancel()">
					<span class="glyphicon glyphicon-remove" aria-hidden="true"></span>キャンセル
				</button>
				<button type="button" class="btn btn-primary btn-workflow" ng-click="editCommit()">
					決定
				</button>

				<div aria-hidden="false" role="tabpanel" class="panel-collapse in collapse" uib-collapse="!isOpen"
					 aria-expanded="true">
					<div class="nc-danger-zone" ng-init="dangerZone=false;" style="margin-top:20px;">
						<uib-accordion close-others="false">
							<div uib-accordion-group is-open="dangerZone" class="panel-danger">
								<uib-accordion-heading class="clearfix">
									<span style="cursor: pointer">削除処理</span>
									<span class="pull-right glyphicon"
										  ng-class="{'glyphicon-chevron-down': dangerZone, 'glyphicon-chevron-right': ! dangerZone}"></span>
								</uib-accordion-heading>
								<div class="inline-block">
									このメタデータ項目を削除します。
								</div>
								<button type="button" class="btn btn-danger pull-right"
										id="btnMultidatabaseMetadataSettingDelete"
										onclick="return confirm('メタデータ項目を削除します。本当によろしいですか。')">
									<span class="glyphicon glyphicon-trash"> </span> 削除
								</button>
							</div>
						</uib-accordion>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
