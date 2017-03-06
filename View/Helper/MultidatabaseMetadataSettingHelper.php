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
class MultidatabaseMetadataSettingHelper extends AppHelper
{

	/**
	 * 使用するHelpers
	 *
	 * - [NetCommons.ButtonHelper](../../NetCommons/classes/ButtonHelper.html)
	 * - [NetCommons.NetCommonsHtml](../../NetCommons/classes/NetCommonsHtml.html)
	 * - [NetCommons.NetCommonsForm](../../NetCommons/classes/NetCommonsForm.html)
	 *
	 * @var array
	 */
	public $helpers = [
		'NetCommons.Button',
		'NetCommons.NetCommonsHtml',
		'NetCommons.NetCommonsForm'
	];

	/**
	 * CSS Style Sheetを読み込む
	 *
	 * @param string $viewFile viewファイル
	 * @return void
	 * @link http://book.cakephp.org/2.0/ja/views/helpers.html#Helper::beforeRender Helper::beforeRender
	 */
	public function beforeRender($viewFile)
	{
		parent::beforeRender($viewFile);
	}

/**
 * 汎用データベースメタデータレイアウト グループのHTMLを出力する(列)
 *
 * @param integer $position グループ
 * @param integer $colSize 段の列数
 * @return string HTML
 */
	public function renderGroup($position, $colSize = 1)
	{

		switch ($colSize) {
			case 2:
				// 2列レイアウト
				$element = 'MultidatabaseBlocks/metadatas/edit_metadata_group_c2';
				break;
			default:
				// 1列レイアウト
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

/**
 * 汎用データベースメタデータレイアウト アイテムのHTMLを出力する
 *
 * @param integer $position グループ
 * @return string HTML
 */
	public function renderGroupItems($position)
	{
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

/**
 * 汎用データベースメタデータレイアウト アイテムプロパティのHTMLを出力する
 *
 * @param integer $position グループ
 * @return string HTML
 */
	public function renderGroupItemProperty($position)
	{
		switch ($position) {
			case 0:
			case 1:
			case 2:
			case 3:
				return $this->_View->Element(
					'MultidatabaseBlocks/metadatas/edit_metadata_item_property',
					['gPos' => $position]
				);
			default:
				return false;
		}
	}

/**
 * 汎用データベースメタデータレイアウト アイテムプロパティ 選択肢入力のHTMLを出力する
 *
 * @param integer $position グループ
 * @return string HTML
 */
	public function renderGroupItemPropertySelections($position) {
		switch ($position) {
			case 0:
			case 1:
			case 2:
			case 3:
				return $this->_View->Element(
					'MultidatabaseBlocks/metadatas/edit_metadata_item_property_selections',
					['gPos' => $position]
				);
			default:
				return false;
		}
	}


/**
 * フィールド種別
 *
 * @return array
 */
	public function fieldTypeList() {
		return [
			'text' => __d('multidatabases','Text'),
			'textarea' => __d('multidatabases','Text area'),
			'link' => __d('multidatabases','Link'),
			'select' => __d('multidatabases','Select'),
			'checkbox' => __d('multidatabases','Check box'),
			'wysiwyg' => __d('multidatabases','Wysiwyg'),
			'file' => __d('multidatabases','File'),
			'image' => __d('multidatabases','Image'),
			'autonumber' => __d('multidatabases','Auto number'),
			'mail' => __d('multidatabases','E-mail Address'),
			'date' => __d('multidatabases','Date'),
			'created' => __d('multidatabases','Create date'),
			'updated' => __d('multidatabases','Update date'),
		];
	}

/**
 * メタデータ設定項目一覧
 *
 * @return array
 */
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
}
