<?php
/**
 * MultidatabaseContentViewHelper Helper
 * 汎用データベースコンテンツ表示ヘルパー
 *
 * @author Noriko Arai <arai@nii.ac.jp>
 * @author Tomoyuki OHNO (Ricksoft Co., Ltd.) <ohno.tomoyuki@ricksoft.jp>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */

App::uses('AppHelper', 'View/Helper');

/**
 * MultidatabaseContentViewHelper Helper
 *
 * @author Tomoyuki OHNO (Ricksoft, Co., LTD.) <ohno.tomoyuki@ricksoft.jp>
 * @package NetCommons\Multidatabase\View\Helper
 *
 */
class MultidatabaseContentViewHelper extends AppHelper {

/**
 * 使用するHelpers
 *
 * @var array
 */
	public $helpers = [
		'NetCommons.Button',
		'NetCommons.NetCommonsHtml',
		'NetCommons.NetCommonsForm',
		'Form',
	];

/**
 * before render
 *
 * @param string $viewFile viewファイル
 * @return void
 * @link http://book.cakephp.org/2.0/ja/views/helpers.html#Helper::beforeRender Helper::beforeRender
 */
	public function beforeRender($viewFile) {
		parent::beforeRender($viewFile);
	}

/**
 * 汎用データベースコンテンツ グループのHTMLを出力する(列)
 *
 * @param array $metadataGroups メタデータグループ配列
 * @param array $contents コンテンツ配列
 * @param int $position 位置（グループ）
 * @param int $colSize 列サイズ（1:1列,2:2列）
 * @param null $viewMode 表示方法
 * @return string HTML
 */
	public function renderGroup(
		$metadataGroups, $contents, $position, $colSize = 1, $viewMode = null
	) {
		switch ($colSize) {
			case 2:
				// 2列レイアウト
				$element = 'MultidatabaseContents/view/view_content_group_c2';
				break;
			default:
				// 1列レイアウト
				$element = 'MultidatabaseContents/view/view_content_group_c1';
		}

		$tmp = $metadataGroups[$position];

		$metadatas = [];

		if ($viewMode === 'detail') {
			foreach ($tmp as $metadata) {
				if ($metadata['is_visible_detail'] === 1) {
					$metadatas[] = $metadata;
				}
			}
		} else {
			foreach ($tmp as $metadata) {
				if ($metadata['is_visible_list'] === 1) {
					$metadatas[] = $metadata;
				}
			}
		}

		if (empty($metadatas)) {
			return '';
		}

		return $this->_View->Element(
			$element,
			[
				'gMetadatas' => $metadatas,
				'gContents' => $contents,
				'colSize' => $colSize,
			]
		);
	}

/**
 * 汎用データベースコンテンツ コンテンツフッターのHTMLを出力する
 *
 * @param array $content コンテンツ配列
 * @param int $index 順番
 * @return string HTML
 */
	public function renderContentFooter($content, $index) {
		return $this->_View->Element(
			'MultidatabaseContents/view/view_content_footer',
			[
				'content' => $content,
				'index' => $index,
			]
		);
	}

/**
 * 汎用データベースコンテンツ レイアウトHTMLを出力する
 *
 * @param array $content コンテンツ配列
 * @return string $content HTML
 */
	public function renderContentLayout($content) {
		return $this->_View->Element(
			'MultidatabaseContents/view/view_content_layout',
			[
				'content' => $content,
			]
		);
	}

/**
 * 汎用データベースコンテンツ 一覧HTMLを出力する
 *
 * @return string $content HTML
 */
	public function renderContentsList() {
		return $this->_View->Element(
			'MultidatabaseContents/view/view_contents_list',
			[
				'viewMode' => 'list',
			]
		);
	}

/**
 * 汎用データベースコンテンツ 詳細HTMLを出力する
 *
 * @return string $content HTML
 */
	public function renderContentsDetail() {
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
 * @param array $metadatas メタデータ配列
 * @return string HTML
 */
	public function renderGroupItems($metadatas) {
		return $this->_View->Element(
			'MultidatabaseContents/view/view_content_group_items',
			[
				'metadatas' => $metadatas,
			]
		);
	}

/**
 * 選択肢元データ（JSON）をArrayに変換する
 *
 * @param string $selections 選択肢（JSON）
 * @return array
 */
	public function convertSelectionsToArray($selections) {
		foreach (explode('||', $selections) as $selection) {
			$result[md5($selection)] = $selection;
		}
		return $result;
	}
}
