<?php
/**
 * MultidatabaseContentEditHelper Helper
 * 汎用データベースコンテンツ編集ヘルパー
 *
 * @author Noriko Arai <arai@nii.ac.jp>
 * @author Tomoyuki OHNO (Ricksoft Co., Ltd.) <ohno.tomoyuki@ricksoft.jp>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */

App::uses('AppHelper', 'View/Helper');

/**
 * MultidatabaseContentEditHelper Helper
 *
 * @author Tomoyuki OHNO (Ricksoft, Co., LTD.) <ohno.tomoyuki@ricksoft.jp>
 * @package NetCommons\Multidatabase\View\Helper
 *
 */
class MultidatabaseContentEditHelper extends AppHelper {

/**
 * 使用するHelpers
 *
 * @var array
 */
	public $helpers = [
		'NetCommons.Button',
		'NetCommons.NetCommonsHtml',
		'NetCommons.NetCommonsForm',
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
 * Wysiwygアイテムの有無確認
 *
 * @param array $metadatas メタデータ配列
 * @return bool
 */
	public function chkHaveWysiwygItems($metadatas) {
		if (empty($metadatas)) {
			return false;
		}

		foreach ($metadatas as $metadata) {
			if (isset($metadata['type']) && $metadata['type'] === 'wysiwyg') {
				return true;
			}
		}
	}

/**
 * 汎用データベースメタデータレイアウト グループのHTMLを出力する(列)
 *
 * @param array $metadataGroups メタデータグループ
 * @param int $position グループNo
 * @return string HTML
 */
	public function renderGroup($metadataGroups, $position) {
		$element = 'MultidatabaseContents/edit/edit_content_group';

		switch ($position) {
			case 0:
			case 1:
			case 2:
			case 3:
				return $this->_View->Element(
					$element,
					[
						'gMetadatas' => $metadataGroups[$position],
						'gPos' => $position,
					]
				);
			default:
				return '';
		}
	}

/**
 * 汎用データベースメタデータレイアウト アイテムのHTMLを出力する
 *
 * @param array $metadatas 特定グループのメタデータ
 * @return string HTML
 */
	public function renderGroupItems($metadatas) {
		return $this->_View->Element(
			'MultidatabaseContents/edit/edit_content_group_items',
			[
				'metadatas' => $metadatas,
			]
		);
	}
}
