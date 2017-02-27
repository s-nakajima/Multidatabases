NetCommonsApp.controller('MultidatabaseContent', ['$scope', function($scope) {

	$scope.contentGroup0 = [];
	$scope.contentGroup1 = [];
	$scope.contentGroup2 = [];
	$scope.contentGroup3 = [];
	$scope.metadataEdit = {};

	$scope.initialize = function(data) {
		angular.forEach(data.multidatabaseMetadatas, function(value) {
			switch (value.position) {
				case 0:
					value.rank = $scope.contentGroup0.length;
					$scope.contentGroup0.push(value);
					break;
				case 1:
					value.rank = $scope.contentGroup1.length;
					$scope.contentGroup1.push(value);
					break;
				case 2:
					value.rank = $scope.contentGroup2.length;
					$scope.contentGroup2.push(value);
					break;
				case 3:
					value.rank = $scope.contentGroup3.length;
					$scope.contentGroup3.push(value);
					break;
			}
		});
	}

}]);
