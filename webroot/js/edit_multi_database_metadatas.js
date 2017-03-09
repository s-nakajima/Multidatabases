NetCommonsApp.controller('MultidatabaseMetadata', ['$scope', function($scope) {

	$scope.metadataGroup0 = [];
	$scope.metadataGroup1 = [];
	$scope.metadataGroup2 = [];
	$scope.metadataGroup3 = [];
	$scope.metadataEdit = {};

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
		angular.forEach(data.multidatabaseMetadata, function(value) {
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
			name: 'No title',
			position: positionNo,
			rank: nextRank,
			selections: '',
			type: 'text'
		}
		var currentMetadatas = getGroup(positionNo);
		currentMetadatas.push(value);
	}


	// 行削除
	$scope.delete = function($event, positionNo, index, message) {
        $scope.eventStop($event);

        if (! confirm(message)) {
			return false;
		}
		var currentMetadatas = getGroup(positionNo);
		currentMetadatas.splice(index,1);
	}

	// 行移動
	$scope.moveRank = function(type, positionNo, rank) {
		var dest = (type === 'up') ? rank - 1 : rank + 1;

		var currentMetadatas = getGroup(positionNo);

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

		var currentMetadatas = getGroup(currentPositionNo);
		var destMetadatas = getGroup(destPositionNo);

		if (currentMetadatas == false) {
			return false;
		}

		if (destMetadatas == false) {
			return false;
		}

		var targetMetadata = angular.copy(currentMetadatas[rank]);

		destMetadatas.push(targetMetadata);
		currentMetadatas.splice(rank, 1);

		targetMetadata.position = destPositionNo;
		targetMetadata.rank = destMetadatas.length - 1;
	}

	// 選択肢追加
	$scope.addSelection = function(positionNo, parentIndex) {
		var currentMetadatas = getGroup(positionNo);
		var currentMetadata = currentMetadatas[parentIndex];

		var selection = {
			id: null,
			value: ''
		}

		currentMetadata.selections.push(selection);
	}

	// 選択肢削除
	$scope.delSelection = function(positionNo, parentIndex, index) {
		var currentMetadatas = getGroup(positionNo);
		var currentMetadata = currentMetadatas[parentIndex];
		currentMetadata.selections.splice(index,1);
	}

	// 選択肢移動
	$scope.moveSelection = function(type, positionNo, parentIndex, index) {
		var currentMetadatas = getGroup(positionNo);
		var currentMetadata = currentMetadatas[parentIndex];

		var dest = (type === 'up') ? index - 1 : index + 1;

		if (angular.isUndefined(currentMetadata.selections[dest])) {
			return false;
		}

		var destSelection = angular.copy(currentMetadata.selections[dest]);
		var targetSelection = angular.copy(currentMetadata.selections[index]);
		currentMetadata.selections[index] = destSelection;
		currentMetadata.selections[dest] = targetSelection;
	}

  /**
   * イベントストップ
   *
   * @return {void}
   */
	$scope.eventStop = function($event) {
      $event.preventDefault();
      $event.stopPropagation();
	}

}]);
