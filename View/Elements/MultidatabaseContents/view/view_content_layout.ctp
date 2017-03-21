<?php
/**
 * MultidatabasesContents view view_content_layout view element
 * 汎用データベース コンテンツ一覧・詳細表示 レイアウト view element
 *
 * @author Noriko Arai <arai@nii.ac.jp>
 * @author Tomoyuki OHNO (Ricksoft Co., Ltd.) <ohno.tomoyuki@ricksoft.jp>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */
?>

<article>
	<div class="row">
		<?php
		$viewContentGrp[0] = $this->MultidatabaseContentView->renderGroup($multidatabaseMetadataGroups, $content, 0, 1, $viewMode);
		$viewContentGrp[1] = $this->MultidatabaseContentView->renderGroup($multidatabaseMetadataGroups, $content, 1, 2, $viewMode);
		$viewContentGrp[2] = $this->MultidatabaseContentView->renderGroup($multidatabaseMetadataGroups, $content, 2, 2, $viewMode);
		$viewContentGrp[3] = $this->MultidatabaseContentView->renderGroup($multidatabaseMetadataGroups, $content, 3, 1, $viewMode);
		?>
		<?php if (!empty($viewContentGrp[0])): ?>
			<?php echo $viewContentGrp[0]; ?>
		<?php endif; ?>

		<?php if (empty($viewContentGrp[1]) && empty($viewContentGrp[2])): ?>
		<?php else: ?>
			<?php echo $viewContentGrp[1]; ?>
			<?php echo $viewContentGrp[2]; ?>
		<?php endif; ?>

		<?php if (!empty($viewContentGrp[3])): ?>
			<?php echo $viewContentGrp[3]; ?>
		<?php endif; ?>

		<?php echo $this->MultidatabaseContentView->renderContentFooter($content, true); ?>
	</div>
</article>
