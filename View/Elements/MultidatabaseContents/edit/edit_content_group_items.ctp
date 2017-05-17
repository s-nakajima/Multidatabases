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
					<?php
						$options = [
							'type' => 'select',
							'multiple' => 'checkbox',
							'options' => ['1' => __d('multidatabases', 'Delete file')],
							'class' => 'checkbox-inline nc-checkbox',
						];
						echo $tmpVal;
						echo $this->NetCommonsForm->input('value' . $metadata['col_no'] . '_attach_del', $options);
					?>
				<?php else: ?>
					<?php // パスワード設定 ?>
					<?php if ($metadata['type'] === 'file'): ?>
						<div class="checkbox-inline nc-checkbox">
							<label>
								<input type="checkbox"
								       name="value<?php echo $metadata['col_no']; ?>_attach_pw_flg"
								       ng-true-value="1"
								       ng-false-value=""
								       ng-model="value<?php echo $metadata['col_no']; ?>_attach_pw_flg">
								<?php echo __d('multidatabases', 'Set download password'); ?>
							</label>
						</div>
						<div ng-if="value<?php echo $metadata['col_no']; ?>_attach_pw_flg">
							<?php
							$options = [
							];
							echo $this->NetCommonsForm->input(
								'value' . $metadata['col_no'] . '_attach_pw', $options);
							?>
						</div>
					<?php endif; ?>
				<?php endif; ?>
			<?php endif; ?>
		</div>
	</div>
<?php endforeach;
