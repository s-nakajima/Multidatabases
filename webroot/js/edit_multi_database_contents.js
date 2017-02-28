NetCommonsApp.controller('MultidatabaseContentEdit',
	['$scope', 'NetCommonsWysiwyg', function($scope, NetCommonsWysiwyg) {

		/**
		 * tinymce
		 *
		 * @type {object}
		 */

		$scope.tinymce = NetCommonsWysiwyg.new();

		/**
		 * initialize
		 *
		 * @type {Array}
		 */
		$scope.initialize = function(data) {
			$scope.multidatabaseMetadatas = data.multidatabaseMetadatas;
		}

}]);
