<?php
/**
 * MultidatabasesContents edit edit_content view element
 * 汎用データベース コンテンツ編集 編集フォーム view element
 *
 * @author Noriko Arai <arai@nii.ac.jp>
 * @author Tomoyuki OHNO (Ricksoft Co., Ltd.) <ohno.tomoyuki@ricksoft.jp>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */
?>

<div class="row">
	<div class="col-xs-12 col-sm-12">
		<?php echo $this->MultidatabaseContentEdit->renderGroup($multidatabaseMetadataGroups,0,1); ?>
	</div>
</div>


<div class="row">
	<div class="col-xs-12 col-sm-6">
		<?php echo $this->MultidatabaseContentEdit->renderGroup($multidatabaseMetadataGroups,1,2); ?>
	</div>
	<div class="col-xs-12 col-sm-6">
		<?php echo $this->MultidatabaseContentEdit->renderGroup($multidatabaseMetadataGroups,2,2); ?>
	</div>
</div>

<div class="row">
	<div class="col-xs-12 col-sm-12">
		<?php echo $this->MultidatabaseContentEdit->renderGroup($multidatabaseMetadataGroups,3,1); ?>
	</div>
</div>


