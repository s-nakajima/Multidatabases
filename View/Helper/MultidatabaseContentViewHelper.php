<?php
/**
 * MultidatabaseContentEdit Helper
 *
 * @author Noriko Arai <arai@nii.ac.jp>
 * @author Tomoyuki OHNO (Ricksoft Inc.) <ohno.tomoyuki@ricksoft.jp>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */

App::uses('AppHelper', 'View/Helper');

/**
 * 汎用データベースコンテンツのレイアウトで使用するHelper
 *
 * このHelperを使う場合、
 * [Multidatabases.multidatabaseMetadataComponent](./multidatabaseMetadataComponent.html)
 * が読み込まれている必要がある。
 *
 * @author Tomoyuki OHNO (Ricksoft Inc.) <ohno.tomoyuki@ricksoft.jp>
 * @package NetCommons\Multidatabase\View\Helper
 */
class MultidatabaseContentViewHelper extends AppHelper
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
		'NetCommons.NetCommonsForm',
		'Form'
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
 * 汎用データベースコンテンツ グループのHTMLを出力する(列)
 *
 * @param integer $position グループ
 * @param integer $colSize 段の列数
 * @return string HTML
 */
	public function renderGroup($metadataGroups, $contents, $position, $colSize = 1)
	{

		switch ($colSize) {
			case 2:
				// 2列レイアウト
				$element = 'MultidatabaseContents/view/view_content_group_c2';
				break;
			default:
				// 1列レイアウト
				$element = 'MultidatabaseContents/view/view_content_group_c1';
		}

		$metadatas = $metadataGroups[$position];

		return $this->_View->Element(
			$element,
			[
				'gMetadatas' => $metadatas,
				'gContents' => $contents,
				'colSize' => $colSize
			]
		);

	}


/**
 * 汎用データベースコンテンツ コンテンツフッターのHTMLを出力する
 *
 * @param $content
 * @param $index
 * @return string HTML
 */
	public function renderContentFooter($content, $index) {
		return $this->_View->Element(
			'MultidatabaseContents/view/view_content_footer',
			[
				'content' => $content,
				'index' => $index
			]
		);
	}

/**
 * 汎用データベースコンテンツ レイアウトHTMLを出力する
 *
 *
 * @param $metadataGroup
 * @param $content HTML
 */
	public function renderContentLayout($content) {
		return $this->_View->Element(
			'MultidatabaseContents/view/view_content_layout',
			[
				'content' => $content
			]
		);
	}

/**
 * 汎用データベースコンテンツ 一覧HTMLを出力する
 *
 *
 * @param $metadataGroup
 * @param $content HTML
 */
	public function renderContentsList() {
		return $this->_View->Element(
			'MultidatabaseContents/view/view_contents_list',
			[
				'viewMode' => 'list'
			]
		);
	}

/**
 * 汎用データベースコンテンツ 詳細HTMLを出力する
 *
 *
 * @param $metadataGroup
 * @param $content HTML
 */
	public function renderContentsDetail($content, $metadatas) {
		return $this->_View->Element(
			'MultidatabaseContents/view/view_content_detail',
			[
				'viewMode' => 'detail',
			]
		);
	}

/**
 * 汎用データベースコンテンツ アイテムのHTMLを出力する
 *
 * @param integer $position グループ
 * @return string HTML
 */
	public function renderGroupItems($metadatas)
	{
		return $this->_View->Element(
			'MultidatabaseContents/view/view_content_group_items',
			[
				'metadatas' => $metadatas
			]
		);
	}

	public function convertSelectionsToArray($selections) {
		foreach (explode('||',$selections) as $selection) {
			$result[md5($selection)] = $selection;
		}

		return $result;
	}






}
