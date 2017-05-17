<?php
/**
 * MultidatabasesContents edit edit_content_group_item view element
 * 汎用データベース コンテンツ編集 編集フォーム アイテム view element
 *
 * @author Noriko Arai <arai@nii.ac.jp>
 * @author Tomoyuki OHNO (Ricksoft Co., Ltd.) <ohno.tomoyuki@ricksoft.jp>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */
?>

<?php foreach ($metadatas as $metadata): ?>
	<div>
		<?php echo $this->MultidatabaseContentEditElement->renderFormElement($metadata); ?>
		<?php
		if ($isEdit && ($metadata['type'] === 'file' || $metadata['type'] === 'image')) {
			// ファイル or 画像の場合
			$tmpVal = $this->MultidatabaseContentViewElement->renderViewElement(
				['MultidatabaseContent' => $multidatabaseContent],
				$metadata,
				'file'
			);
			if (! empty($tmpVal)) {
				$options = [
					'type' => 'select',
					'multiple' => 'checkbox',
					'options' => ['1' => __d('multidatabases', 'Delete file')],
					'class' => 'checkbox-inline nc-checkbox',
				];
				echo $tmpVal;
				echo $this->NetCommonsForm->input('value' . $metadata['col_no'] . '_attach_del', $options);
			}
		}
		?>
	</div>
<?php endforeach;
