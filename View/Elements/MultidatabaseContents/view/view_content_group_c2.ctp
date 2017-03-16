<?php
/**
 * MultidatabasesContents view view_content_group_c2 view element
 * 汎用データベース コンテンツ一覧・詳細表示 2段レイアウト view element
 *
 * @author Noriko Arai <arai@nii.ac.jp>
 * @author Tomoyuki OHNO (Ricksoft Co., Ltd.) <ohno.tomoyuki@ricksoft.jp>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */
?>

<div class="col-xs-12 col-sm-6">
	<table class="table table-bordered">
		<?php foreach ($gMetadatas as $key => $metadata): ?>
			<tr>
				<th class="col-xs-3 col-sm-4"><?php echo $metadata['name']; ?></th>
				<td>
					<?php echo $this->MultidatabaseContentView->renderViewElement($gContents, $metadata); ?>
					<?php ?>
				</td>
			</tr>
		<?php endforeach; ?>
	</table>
</div>
