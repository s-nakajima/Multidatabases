NetCommonsApp.controller('MultidatabaseMetadatas', ['$scope', function($scope) {

	$scope.metadataGroup0 = [];
	$scope.metadataGroup1 = [];
	$scope.metadataGroup2 = [];
	$scope.metadataGroup3 = [];
	$scope.metadataEdit = {};

	var editPositionNo = null;
	var editRank = null;


	// グループのオブジェクトを返す
	function getGroup(positionNo) {
		switch (positionNo) {
			case 0:
				return $scope.metadataGroup0;
			case 1:
				return $scope.metadataGroup1;
			case 2:
				return $scope.metadataGroup2;
			case 3:
				return $scope.metadataGroup3;
			default:
				return false;
		}
	}

	// 編集画面フィールドの初期化
	function editInit() {
		$scope.metadataEdit.name = null;
		$scope.metadataEdit.type = null;
		$scope.metadataEdit.selection = [];
		$scope.metadataEdit.is_file_dl_require_auth = null;
		$scope.metadataEdit.is_require = null;
		$scope.metadataEdit.is_searchable = null;
		$scope.metadataEdit.is_sortable = null;
		$scope.metadataEdit.is_visible_detail = null;
		$scope.metadataEdit.is_visible_list = null;
	}

	$scope.initialize = function(data) {
		editInit();
		console.log(data.multidatabaseMetadatas);
		angular.forEach(data.multidatabaseMetadatas, function(value) {
			switch (value.position) {
				case 0:
					value.rank = $scope.metadataGroup0.length;
					$scope.metadataGroup0.push(value);
					break;
				case 1:
					value.rank = $scope.metadataGroup1.length;
					$scope.metadataGroup1.push(value);
					break;
				case 2:
					value.rank = $scope.metadataGroup2.length;
					$scope.metadataGroup2.push(value);
					break;
				case 3:
					value.rank = $scope.metadataGroup3.length;
					$scope.metadataGroup3.push(value);
					break;
			}

			console.log($scope.metadataGroup0);
		});
	}

	// 編集画面表示
	$scope.edit = function(positionNo,rank) {
		$('#multidatabaseMetadataSettingEdit').modal('show');
		editInit();

		currentMetadatas = getGroup(positionNo);

		editPositionNo = positionNo;
		editRank = rank;

		if (angular.isUndefined(currentMetadatas[rank])) {
			return false;
		}


		$scope.metadataEdit.name = currentMetadatas[rank].name;
		$scope.metadataEdit.type = currentMetadatas[rank].type;
		$scope.metadataEdit.selection = currentMetadatas[rank].selection;
		$scope.metadataEdit.is_file_dl_require_auth = currentMetadatas[rank].is_file_dl_require_auth;
		$scope.metadataEdit.is_require = currentMetadatas[rank].is_require;
		$scope.metadataEdit.is_searchable = currentMetadatas[rank].is_searchable;
		$scope.metadataEdit.is_sortable = currentMetadatas[rank].is_sortable;
		$scope.metadataEdit.is_visible_detail = currentMetadatas[rank].is_visible_detail;
		$scope.metadataEdit.is_visible_list = currentMetadatas[rank].is_visible_list;
	}

	// 編集画面キャンセル
	$scope.editCancel = function() {
		editPositionNo = null;
		editRank = null;
		editInit();
		$('#multidatabaseMetadataSettingEdit').modal('hide');
	}


	// 編集内容反映
	$scope.editCommit = function() {

		currentMetadatas = getGroup(editPositionNo);

		currentMetadatas[editRank].name = $scope.metadataEdit.name;
		currentMetadatas[editRank].is_require = $scope.metadataEdit.is_require;
		currentMetadatas[editRank].is_searchable = $scope.metadataEdit.is_searchable;
		currentMetadatas[editRank].is_sortable = $scope.metadataEdit.is_sortable;
		currentMetadatas[editRank].is_visible_list = $scope.metadataEdit.is_visible_list;
		currentMetadatas[editRank].is_visible_detail = $scope.metadataEdit.is_visible_detail;

		$('#multidatabaseMetadataSettingEdit').modal('hide');
	}

	// 行追加
	$scope.add = function(positionNo,last) {
		var nextRank = last + 1;
		var value = {
			col_no: '',
			id: '',
			is_file_dl_require_auth: '',
			is_require: '',
			is_searchable: '',
			is_sortable: '',
			is_visible_detail: '',
			is_visible_list: '',
			key: '',
			language_id: '',
			name: '無題',
			position: positionNo,
			rank: nextRank,
			selections: '',
			type: 'text'
		}
		currentMetadatas = getGroup(positionNo);
		currentMetadatas.push(value);
	}


	// 行削除
	$scope.delete = function(positionNo, index) {
		$scope.multidatabaseMetadatas.splice(index,1);
	}

	// 行移動
	$scope.moveRank = function(type, positionNo, rank) {
		var dest = (type === 'up') ? rank - 1 : rank + 1;

		currentMetadatas = getGroup(positionNo);
		if (angular.isUndefined(currentMetadatas[dest])) {
			return false;
		}
		var destMetadata = angular.copy(currentMetadatas[dest]);
		var targetMetadata = angular.copy(currentMetadatas[rank]);
		currentMetadatas[rank] = destMetadata;
		currentMetadatas[dest] = targetMetadata;

		currentMetadatas[rank].position = destMetadata.position;
		currentMetadatas[rank].rank = rank;
		currentMetadatas[dest].position = targetMetadata.position;
		currentMetadatas[dest].rank = dest;

	}

	// 段移動
	$scope.movePosition = function(destPositionNo, currentPositionNo, rank) {

		currentMetadatas = getGroup(currentPositionNo);
		destMetadatas = getGroup(destPositionNo);

		if (angular.isUndefined(currentMetadatas[currentPositionNo])) {
			return false;
		}

		var targetMetadata = angular.copy(currentMetadatas[rank]);

		destMetadatas.push(targetMetadata);
		currentMetadatas.splice(rank, 1);

		currentMetadatas[rank].position = destPositionNo;
		currentMetadatas[rank].rank = currentMetadatas[rank].length;

	}

}]);
