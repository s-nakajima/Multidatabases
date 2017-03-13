<?php
/**
 * MultidatabasesContents view view_content_footer view element
 * 汎用データベース コンテンツ一覧・詳細表示 フッター view element
 *
 * @author Noriko Arai <arai@nii.ac.jp>
 * @author Tomoyuki OHNO (Ricksoft Co., Ltd.) <ohno.tomoyuki@ricksoft.jp>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */
?>
<footer>
	<div class="clearfix">
		<div class="pull-left">
			<?php if (!isset($viewMode) || ($viewMode <> 'detail')) : ?>
				<span class="multidatabases__content-comment-count">
					<?php echo $this->ContentComment->count($content); ?>
				</span>
			<?php endif ?>
			<?php echo $this->Like->buttons('MultidatabaseContent', $multidatabaseSetting, $content); ?>
		</div>
		<div class="pull-right">
			<?php echo $this->Workflow->label($content['MultidatabaseContent']['status']); ?>
			<?php echo $this->NetCommonsHtml->handleLink($content, ['avatar' => true]); ?>&nbsp;
			<?php if (!isset($viewMode) || $viewMode <> 'detail') : ?>
				<?php echo $this->NetCommonsHtml->link(
					__d('multidatabases', 'View detail'),
					[
						'controller' => 'multidatabase_contents',
						'action' => 'detail',
						//'frame_id' => Current::read('Frame.id'),
						'key' => $content['MultidatabaseContent']['key'],
					],
					[
						'class' => 'btn btn-default',
					]
				);
				?>
			<?php endif; ?>
		</div>
	</div>
	<div>
		<?php if (isset($viewMode) && ($viewMode === 'detail')) : ?>
			<?php echo $this->ContentComment->index($content); ?>
		<?php endif; ?>
	</div>
</footer>
