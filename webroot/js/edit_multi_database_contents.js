/**
 *  [[changeme]] [Controller|Model|View]
 *
 *  @author Noriko Arai <arai@nii.ac.jp>
 *  @author Tomoyuki OHNO (Ricksoft, Co., Ltd.) <ohno.tomoyuki@ricksoft.jp>
 *  @link http://www.netcommons.org NetCommons Project
 *  @license http://www.netcommons.org/license.txt NetCommons License
 *  @copyright Copyright 2014, NetCommons Project
 */

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
			if (data.multidatabaseContent) {
				$scope.multidatabaseContent = data.multidatabaseContent;
			}


		}

}]);
