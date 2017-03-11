<?php
/**
 * MultidatabasesContents view view_content_edit_link view element
 * 汎用データベース 編集ボタン view element
 *
 * @author Noriko Arai <arai@nii.ac.jp>
 * @author Tomoyuki OHNO (Ricksoft Co., Ltd.) <ohno.tomoyuki@ricksoft.jp>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */
?>

<?php if ($this->Workflow->canEdit('Multidatabases.MultidatabaseContent', $multidatabaseContent)) : ?>
	<div class="text-right">
		<?php echo $this->Button->editLink('',
			array(
				'controller' => 'multidatabase_contents',
				'action' => 'edit',
				'key' => $multidatabaseContent['MultidatabaseContent']['key']
			),
			array(
				'tooltip' => true,
			)
		); ?>
	</div>
<?php
endif;
