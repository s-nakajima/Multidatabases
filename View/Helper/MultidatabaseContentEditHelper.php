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
class MultidatabaseContentEditHelper extends AppHelper
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
	public function renderGroup($metadataGroups, $position, $colSize = 1)
	{

		switch ($colSize) {
			case 2:
				// 2列レイアウト
				$element = 'MultidatabaseContents/edit/edit_content_group_c2';
				break;
			default:
				// 1列レイアウト
				$element = 'MultidatabaseContents/edit/edit_content_group_c1';
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
	public function renderGroupItems($metadataGroups, $position)
	{
		switch ($position) {
			case 0:
			case 1:
			case 2:
			case 3:
				if (!isset($metadataGroups[$position])) {
					return false;
				}

				return $this->_View->Element(
					'MultidatabaseContents/edit/edit_content_group_items',
					[
						'gPos' => $position,
						'metadatas' => $metadataGroups[$position]
					]
				);
			default:
				return false;
		}
	}

/**
 * フォーム部品を出力する
 *
 * @param $elementType
 */
	public function renderFormElement($metadata, $options = []) {

		if (!empty($label)) {
			$options['label'] = $label;
		}


		$name = 'metadata' . $metadata['col_no'];
		$options['label'] = $metadata['name'];
		$elementType = $metadata['type'];
		switch ($elementType) {
			case 'text':
				return $this->renderFormElementText($name, $options);
				break;
			case 'textarea':
				return $this->renderFormElementTextArea($name, $options);
				break;
			case 'link':
				return $this->renderFormElementText($name, $options);
				break;
			case 'radio':
				$options['options'] = explode('||',$metadata['selections']);
				return $this->renderFormElementRadio($name, $options);
				break;
			case 'select':
				$options['options'] = explode('||',$metadata['selections']);
				return $this->renderFormElementSelect($name, $options);
				break;
			case 'checkbox':
				$options['options'] = explode('||',$metadata['selections']);
				return $this->renderFormElementCheckBox($name, $options);
				break;
			case 'wysiwyg':
				$options['rows'] = 12;
				return $this->renderFormElementWysiwyg($name, $options);
				break;
			case 'file':
				return $this->renderFormElementFile($name, $options);
				break;
			case 'image':
				return $this->renderFormElementImage($name, $options);
				break;
			case 'autonumber':
				return $this->renderFormElementReadOnly($name, $options);
				break;
			case 'mail':
				return $this->renderFormElementText($name, $options);
				break;
			case 'date':
				return $this->renderFormElementDate($name, $options);
				break;
			case 'created':
				return $this->renderFormElementReadOnly($name, $options);
				break;
			case 'updated':
				return $this->renderFormElementReadOnly($name, $options);
				break;
			case 'hidden':
				return $this->renderFormElementHidden($name, $options);
				break;
			default:
				return $this->renderFormElementReadOnly($name, $options);
				break;
		}
	}

	public function renderFormElementReadOnly($name,  $options = []) {
		if (isset($options['value'])) {
			return $options['value'];
		}
		return '';
	}

	public function renderFormElementText($name,  $options = []) {
		$options['type'] = 'text';
		return $this->NetCommonsForm->input($name,$options);
	}

	public function renderFormElementWysiwyg($name,  $options = []) {
		return $this->NetCommonsForm->wysiwyg($name,$options);
	}

	public function renderFormElementTextArea($name,  $options = []) {
		$options['type'] = 'textarea';
		return $this->NetCommonsForm->input($name,$options);
	}

	public function renderFormElementCheckBox($name,  $options = []) {
		$options['type'] = 'checkbox';
		return $this->NetCommonsForm->input($name,$options);
	}

	public function renderFormElementRadio($name,  $options = []) {
		$options['type'] = 'radio';
		return $this->NetCommonsForm->input($name,$options);
	}

	public function renderFormElementSelect($name,  $options = []) {
		$options['type'] = 'select';
		return $this->NetCommonsForm->input($name,$options);
	}

	public function renderFormElementDate($name,  $options = []) {
		$options['type'] = 'datetime';
		return $this->NetCommonsForm->input($name,$options);
	}

	public function renderFormElementFile($name,  $options = []) {
		$options['type'] = 'file';
		return $this->NetCommonsForm->input($name,$options);
	}

	public function renderFormElementImage($name,  $options = []) {
		$options['type'] = 'file';
		return $this->NetCommonsForm->input($name,$options);
	}

	public function renderFormElementHidden($name,  $options = []) {
		$options['type'] = 'hidden';
		return $this->NetCommonsForm->input($name,$options);
	}






}
