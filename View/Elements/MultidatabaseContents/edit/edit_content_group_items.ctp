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
		<?php echo $this->MultidatabaseContentEdit->renderFormElement($metadata);
		?>
	</div>
	<?php
endforeach;
