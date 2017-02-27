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


	$scope.initialize = function(data) {
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
		});
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
	$scope.delete = function(positionNo, index, message) {
		if (! confirm(message)) {
			return false;
		}

		currentMetadatas = getGroup(positionNo);
		currentMetadatas.splice(index,1);

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
