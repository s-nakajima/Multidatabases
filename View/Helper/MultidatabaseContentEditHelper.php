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
					[
						'gMetadatas' => $metadataGroups[$position],
						'gPos' => $position
					]
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
	public function renderGroupItems($metadatas)
	{
		return $this->_View->Element(
			'MultidatabaseContents/edit/edit_content_group_items',
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

/**
 * フォーム部品を出力する
 *
 * @param $elementType
 */
	public function renderFormElement($metadata, $options = []) {

		if (!empty($label)) {
			$options['label'] = $label;
		}


		$name = 'metadata' . $metadata['id'];
		$options['id'] = $name;
		$options['label'] = $metadata['name'];
		$elementType = $metadata['type'];

		$result = '';
		switch ($elementType) {
			case 'text':
				$result .= $this->renderFormElementText($name, $options);
				break;
			case 'textarea':
				$result .= $this->renderFormElementTextArea($name, $options);
				break;
			case 'link':
				$result .= $this->renderFormElementText($name, $options);
				break;
			case 'radio':
				$options['options'] = $this->convertSelectionsToArray($metadata['selections']);
				$result .= $this->renderFormElementRadio($name, $options);
				break;
			case 'select':
				$options['options'] = $this->convertSelectionsToArray($metadata['selections']);
				$result .= $this->renderFormElementSelect($name, $options);
				break;
			case 'checkbox':
				$options['options'] = $this->convertSelectionsToArray($metadata['selections']);
				$result .= $this->renderFormElementCheckBox($name, $options);

				break;
			case 'wysiwyg':
				$options['rows'] = 12;
				$result .= $this->renderFormElementWysiwyg($name, $options);
				break;
			case 'file':
				$result .= $this->renderFormElementFile($name, $options);
				break;
			case 'image':
				$result .= $this->renderFormElementImage($name, $options);
				break;
			case 'autonumber':
				$result .= $this->renderFormElementReadOnly($name, $options);
				break;
			case 'mail':
				$result .= $this->renderFormElementText($name, $options);
				break;
			case 'date':
				$result .= $this->renderFormElementDate($name, $options);
				break;
			case 'created':
				$result .= $this->renderFormElementReadOnly($name, $options);
				break;
			case 'updated':
				$result .= $this->renderFormElementReadOnly($name, $options);
				break;
			case 'hidden':
				$result .= $this->renderFormElementHidden($name, $options);
				break;
			default:
				$result .= $this->renderFormElementReadOnly($name, $options);
				break;
		}


		return $result;
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
		$options += [
			'type' => 'select',
			'multiple' => 'checkbox',
			'options' => $options,
			'class' => 'checkbox-inline nc-checkbox'
		];

		return $this->NetCommonsForm->input($name,$options);
	}

	public function renderFormElementRadio($name,  $options = []) {
		$options['type'] = 'radio';
		$options['inline'] = true;
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
