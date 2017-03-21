<?php
/**
 * MultidatabasesContents view view_content_list view element
 * 汎用データベース コンテンツ一覧表示 view element
 *
 * @author Noriko Arai <arai@nii.ac.jp>
 * @author Tomoyuki OHNO (Ricksoft Co., Ltd.) <ohno.tomoyuki@ricksoft.jp>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */
?>
<div class="multidatabase-content-list">
	<?php
	foreach ($multidatabaseContents as $content) {
		echo $this->MultidatabaseContentView->renderContentLayout($content);
	}
	?>
</div>
