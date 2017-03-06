<input name="data[MultidatabaseMetadata][<?php echo $gPos; ?>][{{$index}}][id]" type="text" class="hidden" value="{{g<?php echo $gPos; ?>.id}}">
<input name="data[MultidatabaseMetadata][<?php echo $gPos; ?>][{{$index}}][key]" type="text" class="hidden" value="{{g<?php echo $gPos; ?>.key}}">
<input name="data[MultidatabaseMetadata][<?php echo $gPos; ?>][{{$index}}][rank]" type="text" class="hidden" value="{{g<?php echo $gPos; ?>.rank}}">
<input name="data[MultidatabaseMetadata][<?php echo $gPos; ?>][{{$index}}][position]" type="text" class="hidden" value="{{g<?php echo $gPos; ?>.position}}">
<input name="data[MultidatabaseMetadata][<?php echo $gPos; ?>][{{$index}}][col_no]" type="text" class="hidden" value="{{g<?php echo $gPos; ?>.col_no}}">
<div class="row form-group">
	<div class="col-xs-12">
		<label for="multidatabaseMetadataSettingEditName<?php echo$gPos; ?>-{{$index}}" class="control-label">
			<?php echo __d('multidatabases', 'Field name'); ?>
			<strong class="text-danger h4">*</strong>
		</label>
		<input type="text"
			   class="form-control"
			   id="multidatabaseMetadataSettingEditName<?php echo$gPos; ?>-{{$index}}"
			   name="data[MultidatabaseMetadata][<?php echo $gPos; ?>][{{$index}}][name]"
			   ng-model="metadataGroup<?php echo $gPos; ?>[$index]['name']">
	</div>
</div>
<div class="row form-group">
	<div class="col-xs-12">
		<label>
			<?php echo __d('multidatabases', 'Field type'); ?>
			<strong class="text-danger h4">*</strong>
		</label>
		<select name="data[MultidatabaseMetadata][<?php echo $gPos; ?>][{{$index}}][type]"
				id="multidatabaseMetadataSettingEditType<?php echo$gPos; ?>-{{$index}}"
				class="form-control"
				ng-model="metadataGroup<?php echo $gPos; ?>[$index]['type']">
				<?php foreach ($this->MultidatabaseMetadataSetting->fieldTypeList() as $key => $fieldName): ?>
					<option value="<?php echo $key; ?>"><?php echo $fieldName; ?></option>
				<?php endforeach; ?>
		</select>
	</div>
</div>
<div ng-if="g<?php echo $gPos; ?>.type == 'select' || g<?php echo $gPos; ?>.type == 'checkbox'">
	<?php echo $this->MultidatabaseMetadataSetting->renderGroupItemPropertySelections($gPos); ?>
</div>
<div class="row form-group">
	<div class="col-xs-12">
		<div class="checkbox">
			<label class="control-label" for="MultidatabaseMetadataSettingEditIsRequire<?php echo$gPos; ?>-{{$index}}">
				<input type="checkbox"
					   name="data[MultidatabaseMetadata][<?php echo $gPos; ?>][{{$index}}][is_require]"
					   id="MultidatabaseMetadataSettingEditIsRequire<?php echo$gPos; ?>-{{$index}}"
					   ng-true-value="1"
					   ng-false-value=""
					   ng-model="metadataGroup<?php echo $gPos; ?>[$index]['is_require']">Require
			</label>
		</div>
		<div class="checkbox" ng-if="
				g<?php echo $gPos; ?>.type != 'file' &&
				g<?php echo $gPos; ?>.type != 'image' &&
				g<?php echo $gPos; ?>.type != 'autonumber' &&
				g<?php echo $gPos; ?>.type != 'date' &&
				g<?php echo $gPos; ?>.type != 'created' &&
				g<?php echo $gPos; ?>.type != 'updated'
		">
			<label class="control-label" for="MultidatabaseMetadataSettingEditIsSearchable<?php echo$gPos; ?>-{{$index}}">
				<input type="checkbox"
					   name="data[MultidatabaseMetadata][<?php echo $gPos; ?>][{{$index}}][is_searchable]"
					   id="MultidatabaseMetadataSettingEditIsSearchable<?php echo$gPos; ?>-{{$index}}"
					   ng-true-value="1"
					   ng-false-value=""
					   ng-model="metadataGroup<?php echo $gPos; ?>[$index]['is_searchable']">Searchable
			</label>
		</div>
		<div class="checkbox" ng-if="
				g<?php echo $gPos; ?>.type != 'file' &&
				g<?php echo $gPos; ?>.type != 'image' &&
				g<?php echo $gPos; ?>.type != 'textarea' &&
				g<?php echo $gPos; ?>.type != 'wysiwyg' &&
				g<?php echo $gPos; ?>.type != 'checkbox'
		">
			<label class="control-label" for="MultidatabaseMetadataSettingEditIsSortable<?php echo$gPos; ?>-{{$index}}">
				<input type="checkbox"
					   name="data[MultidatabaseMetadata][<?php echo $gPos; ?>][{{$index}}][is_sortable]"
					   id="MultidatabaseMetadataSettingEditIsSortable<?php echo$gPos; ?>-{{$index}}"
					   ng-true-value="1"
					   ng-false-value=""
					   ng-model="metadataGroup<?php echo $gPos; ?>[$index]['is_sortable']">Sortable
			</label>
		</div>
		<div class="checkbox" ng-if="g<?php echo $gPos; ?>.type == 'file'">
			<label class="control-label" for="MultidatabaseMetadataSettingEditIsFileDlRequireAuth<?php echo$gPos; ?>-{{$index}}">
				<input type="checkbox"
					   id="MultidatabaseMetadataSettingEditIsFileDlRequireAuth<?php echo$gPos; ?>-{{$index}}"
					   name="data[MultidatabaseMetadata][<?php echo $gPos; ?>][{{$index}}][is_file_dl_require_auth]"
					   ng-true-value="1"
					   ng-false-value=""
					   ng-model="metadataGroup<?php echo $gPos; ?>[$index]['is_visible_list']">Require auth if one file download
			</label>
		</div>
		<div class="checkbox">
			<label class="control-label" for="MultidatabaseMetadataSettingEditIsVisibleList<?php echo$gPos; ?>-{{$index}}">
				<input type="checkbox"
					   id="MultidatabaseMetadataSettingEditIsVisibleList<?php echo$gPos; ?>-{{$index}}"
					   name="data[MultidatabaseMetadata][<?php echo $gPos; ?>][{{$index}}][is_visible_list]"
					   ng-true-value="1"
					   ng-false-value=""
					   ng-model="metadataGroup<?php echo $gPos; ?>[$index]['is_visible_list']">Visible List
			</label>
		</div>
		<div class="checkbox">
			<label class="control-label" for="MultidatabaseMetadataSettingEditIsVisibleDetail<?php echo$gPos; ?>-{{$index}}">
				<input type="checkbox"
					   name="data[MultidatabaseMetadata][<?php echo $gPos; ?>][{{$index}}][is_visible_detail]"
					   id="MultidatabaseMetadataSettingEditIsVisibleDetail<?php echo$gPos; ?>-{{$index}}"
					   ng-true-value="1"
					   ng-false-value=""
					   ng-model="metadataGroup<?php echo $gPos; ?>[$index]['is_visible_detail']">Visible Detail
			</label>
		</div>
	</div>
</div>

