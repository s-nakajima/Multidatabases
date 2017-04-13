<?php
/**
 * MultidatabaseContentEditElementHelper Helper
 * 汎用データベースコンテンツ編集 フォームエレメントヘルパー
 *
 * @author Noriko Arai <arai@nii.ac.jp>
 * @author Tomoyuki OHNO (Ricksoft Co., Ltd.) <ohno.tomoyuki@ricksoft.jp>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */

App::uses('AppHelper', 'View/Helper');

/**
 * MultidatabaseContentEditElementHelper Helper
 *
 * @author Tomoyuki OHNO (Ricksoft, Co., LTD.) <ohno.tomoyuki@ricksoft.jp>
 * @package NetCommons\Multidatabase\View\Helper
 *
 */
class MultidatabaseContentEditElementHelper extends AppHelper {

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
 * フォーム部品を出力する
 *
 * @param array $metadata メタデータ
 * @param array $options オプション
 * @return string HTML
 */
	public function renderFormElement($metadata, $options = []) {
		$name = 'MultidatabaseContent.value' . $metadata['col_no'];
		$options['id'] = $name;
		$options['label'] = $metadata['name'];
		if ((int)$metadata['is_require'] === 1) {
			$options['required'] = true;
		}
		$elementType = $metadata['type'];

		switch ($elementType) {
			case 'textarea':
				return $this->__renderFormElementTextArea($name, $options);
			case 'select':
				$options['options'] = $this->__cnvMetaSelToArr($metadata['selections']);
				return $this->__renderFormElementSelect($name, $options);
			case 'checkbox':
				$options['options'] = $this->__cnvMetaSelToArr($metadata['selections']);
				return $this->__renderFormElementCheckBox($name, $options);
			case 'wysiwyg':
				$options['rows'] = 12;
				return $this->__renderFormElementWysiwyg($name, $options);
			case 'file':
				return $this->__renderFormElementFile($name, $options, $elementType);
			case 'date':
				return $this->__renderFormElementDate($name, $options);
			case 'hidden':
				return $this->__renderFormElementHidden($name, $options);
		}

		if (in_array($elementType, ['text', 'link', 'mail'])) {
			return $this->__renderFormElementText($name, $options);
		}

		return $this->__renderFormElementReadOnly($name, $options);
	}

/**
 * 値を出力する
 *
 * @param string $name フィールド名(key)
 * @param array $options オプション
 * @return string HTML
 */
	private function __renderFormElementReadOnly($name, $options = []) {
		if (isset($options['value'])) {
			return $options['value'];
		}

		return '';
	}

/**
 * テキストボックスを出力する
 *
 * @param string $name フィールド名(key)
 * @param array $options オプション
 * @return string HTML
 */
	private function __renderFormElementText($name, $options = []) {
		$options['type'] = 'text';
		return $this->NetCommonsForm->input($name, $options);
	}

/**
 * WYSIWYGを出力する
 *
 * @param string $name フィールド名(key)
 * @param array $options オプション
 * @return string HTML
 */
	private function __renderFormElementWysiwyg($name, $options = []) {
		return $this->NetCommonsForm->wysiwyg($name, $options);
	}

/**
 * テキストエリアを出力する
 *
 * @param string $name フィールド名(key)
 * @param array $options オプション
 * @return string HTML
 */
	private function __renderFormElementTextArea($name, $options = []) {
		$options['type'] = 'textarea';
		return $this->NetCommonsForm->input($name, $options);
	}

/**
 * チェックボックスを出力する
 *
 * @param string $name フィールド名(key)
 * @param array $options オプション
 * @return string HTML
 */
	private function __renderFormElementCheckBox($name, $options = []) {
		$options += [
			'type' => 'select',
			'multiple' => 'checkbox',
			'options' => $options,
			'class' => 'checkbox-inline nc-checkbox',
		];

		return $this->NetCommonsForm->input($name, $options);
	}

/**
 * セレクトボックスを出力する
 *
 * @param string $name フィールド名(key)
 * @param array $options オプション
 * @return string HTML
 */
	private function __renderFormElementSelect($name, $options = []) {
		$options['type'] = 'select';
		return $this->NetCommonsForm->input($name, $options);
	}

/**
 * デートピッカー対応のテキストボックスを出力する
 *
 * @param string $name フィールド名(key)
 * @param array $options オプション
 * @return string HTML
 */
	private function __renderFormElementDate($name, $options = []) {
		$options['type'] = 'datetime';
		return $this->NetCommonsForm->input($name, $options);
	}

/**
 * ファイルアップロードエレメントを出力する
 *
 * @param string $name フィールド名(key)
 * @param array $options オプション
 * @param string $type 種別(file, image)
 * @return string HTML
 */
	private function __renderFormElementFile($name, $options = [], $type = 'file') {
		$options['remove'] = false;
		$options['filename'] = false;
		return $this->NetCommonsForm->uploadFile($name, $options);
	}

/**
 * 隠し属性のエレメントを出力する
 *
 * @param string $name フィールド名(key)
 * @param array $options オプション
 * @return string HTML
 */
	private function __renderFormElementHidden($name, $options = []) {
		$options['type'] = 'hidden';
		return $this->NetCommonsForm->input($name, $options);
	}

/**
 * メタデータ選択肢の値を配列に変換する
 *
 * @param array $metaSelections メタデータの選択肢の値
 * @return array
 */
	private function __cnvMetaSelToArr($metaSelections) {
		$result = [];
		foreach ($metaSelections as $metaSelection) {
			$result[md5($metaSelection)] = $metaSelection;
		}
		return $result;
	}

}
