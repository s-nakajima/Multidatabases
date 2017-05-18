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
		<div>
			<?php // パスワード設定 ?>
			<?php if ($metadata['type'] === 'file'): ?>
				<div class="nc-checkbox">
					<label>
						<?php
							$pw_flg_field = 'value' . $metadata['col_no'] . '_attach_pw_flg';
							if (isset($multidatabaseContent[$pw_flg_field]) && $multidatabaseContent[$pw_flg_field] == 1) {
								$pw_flg_value = 1;
							} else{
								$pw_flg_value = 0;
							}
						?>
						<input type="checkbox"
						       name="<?php echo $pw_flg_field; ?>"
						       ng-init="<?php echo $pw_flg_field; ?> = <?php echo $pw_flg_value; ?>"
						       ng-true-value="1"
						       ng-false-value=""
						       ng-model="<?php echo $pw_flg_field; ?>"
						>
						<?php echo __d('multidatabases', 'Set download password'); ?>
					</label>
				</div>
				<div ng-if="<?php echo $pw_flg_field; ?> == 1">
					<?php
					$options = [
					];
					echo $this->NetCommonsForm->input(
						'value' . $metadata['col_no'] . '_attach_pw', $options);
					?>
				</div>
			<?php endif; ?>
			<?php
			// ファイル削除＆登録済ファイルのリンク
			if ($isEdit && ($metadata['type'] === 'file' || $metadata['type'] === 'image')):
			?>
				<?php
					// ファイル or 画像の場合
					$tmpVal = $this->MultidatabaseContentViewElement->renderViewElement(
						['MultidatabaseContent' => $multidatabaseContent],
						$metadata,
						'file'
					);
				?>
				<?php if (! empty($tmpVal)): ?>
				<div class="nc-checkbox">
					<label>
						<input type="checkbox"
						       name="value<?php echo $metadata['col_no']; ?>_attach_del">
						<?php echo __d('multidatabases', 'Delete file'); ?>
					</label>
				</div>
				<div style="margin-bottom:10px;">
					<?php
					echo $tmpVal;
					?>
				</div>
				<?php endif; ?>
			<?php endif; ?>
		</div>
	</div>
<?php endforeach;
