<?php
/**
 * Multidatabase Helper
 *
 * @author Noriko Arai <arai@nii.ac.jp>
 * @author Tomoyuki OHNO (Ricksoft Inc.) <ohno.tomoyuki@ricksoft.jp>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */

App::uses('AppHelper', 'View/Helper');

/**
 * 汎用データベースメタデータのレイアウトで使用するHelper
 *
 * このHelperを使う場合、
 * [Multidatabases.multidatabaseMetadataComponent](./multidatabaseMetadataComponent.html)
 * が読み込まれている必要がある。
 *
 * @author Tomoyuki OHNO (Ricksoft Inc.) <ohno.tomoyuki@ricksoft.jp>
 * @package NetCommons\Multidatabase\View\Helper
 */
class MultidatabaseMetadataSettingHelper extends AppHelper {

/**
 * 使用するHelpsers
 *
 * - [NetCommons.ButtonHelper](../../NetCommons/classes/ButtonHelper.html)
 * - [NetCommons.NetCommonsHtml](../../NetCommons/classes/NetCommonsHtml.html)
 * - [NetCommons.NetCommonsForm](../../NetCommons/classes/NetCommonsForm.html)
 *
 * @var array
 */
	public $helpers = array(
		'NetCommons.Button',
		'NetCommons.NetCommonsHtml',
		'NetCommons.NetCommonsForm'
	);

/**
 * CSS Style Sheetを読み込む
 *
 * @param string $viewFile viewファイル
 * @return void
 * @link http://book.cakephp.org/2.0/ja/views/helpers.html#Helper::beforeRender Helper::beforeRender
 */
	public function beforeRender($viewFile) {
		parent::beforeRender($viewFile);
	}

/**
 * 汎用データベースメタデータレイアウトのHTMLを出力する(列)
 *
 * @param string $elementFile elementファイル名
 * @param array $metadata multidatabaseMetadataデータ配列
 * @return string HTML
 */
	public function renderGroup($position,$colSize = 1) {

		switch ($colSize) {
			case 2:
				$element = 'MultidatabaseBlocks/metadatas/edit_metadata_group_c2';
				break;
			default:
				$element = 'MultidatabaseBlocks/metadatas/edit_metadata_group_c1';
		}


		switch ($position) {
			case 0:
			case 1:
			case 2:
			case 3:
				return $this->_View->Element(
					$element,
					['gPos' => $position]
				);
			default:
				return false;
		}
	}

	public function renderGroupItems($position) {
		switch ($position) {
			case 0:
			case 1:
			case 2:
			case 3:
				return $this->_View->Element(
					'MultidatabaseBlocks/metadatas/edit_metadata_group_items',
					['gPos' => $position]
				);
			default:
				return false;
		}
	}

	public function fieldList() {
		return [
			'key',
			'name',
			'position',
			'rank',
			'type',
			'selections',
			'is_require',
			'is_searchable',
			'is_sortable',
			'is_file_dl_require_auth',
			'is_visible_list',
			'is_visible_detail'
		];

	}


	/**
	 * 項目の移動HTMLを出力する
	 *
	 * @param array $layout userAttributeLayoutデータ配列
	 * @param array $userAttribute userAttributeデータ配列
	 * @return string HTML
	 */
	public function moveSetting($currentMetadata) {

		$output = '';

		$output .= '<button type="button" ' .
			'class="btn btn-xs btn-default dropdown-toggle" ' .
			'data-toggle="dropdown" ' .
			'aria-haspopup="true" ' .
			'aria-expanded="false" ' .
			'ng-disabled="sending">' .
			__d('multidatabases', 'Move') .
			' <span class="caret"></span>' .
			'</button>';

		$output .= '<ul class="dropdown-menu">';
		$output .= $this->moveSettingTopMenu($currentMetadata);
		$output .= $this->moveSettingBottomMenu($currentMetadata);

		//区切り線
		$output .= '<li class="divider"></li>';

		$output .= $this->moveSettingPosMenu($currentMetadata);

		$output .= '</ul>';

		return $output;
	}


	/**
	 * 項目の移動メニューHTMLを出力する(上へ)
	 *
	 * @param array $layout userAttributeLayoutデータ配列
	 * @param array $userAttribute userAttributeデータ配列
	 * @return string HTML
	 */
	public function moveSettingTopMenu() {
		$output = '<li class="move-field-up"><a href=""><span class="glyphicon glyphicon-arrow-up">' . __d('multidatabases', 'Go to Up') . '</a></li>';
		return $output;
	}

	/**
	 * 項目の移動メニューHTMLを出力する(下へ)
	 *
	 * @param array $layout userAttributeLayoutデータ配列
	 * @param array $userAttribute userAttributeデータ配列
	 * @return string HTML
	 */
	public function moveSettingBottomMenu() {
		$output = '<li class="move-field-down"><a href=""><span class="glyphicon glyphicon-arrow-down">' . __d('multidatabases', 'Go to Down') . '</a></li>';
		return $output;
	}

	/**
	 * 項目の移動メニューHTMLを出力する(○段へ)
	 *
	 * @param array $layout userAttributeLayoutデータ配列
	 * @param array $userAttribute userAttributeデータ配列
	 * @return string HTML
	 */
	public function moveSettingPosMenu($currentMetadata) {
		$output = '';

		//データを変数にセット
		$id = $currentMetadata['id'];

		for ($i = 0; $i <= 3; $i++) {
			$posName = '';
			switch ($i) {
				case 0:
					$posName = 'Section 1';
					break;
				case 1:
					$posName = 'Section 2 (Left)';
					break;
				case 2:
					$posName = 'Section 2 (Right)';
					break;
				case 3:
					$posName = 'Section 3';
					break;
			}
			$output .= '<li class="move-field-group-' . $i .'"><a href=""><span>' . sprintf(__d('multidatabases', 'Go to %s'), __d('multidatabases', $posName)) . '</a></li>';

		}

		return $output;

	}


}
