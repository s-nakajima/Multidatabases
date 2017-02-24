<?php
/**
 * MultidatabaseSettings edit template
 *
 * @author Noriko Arai <arai@nii.ac.jp>
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */
echo $this->NetCommonsHtml->css('/multidatabases/css/style.css');
?>



<div class="block-setting-body">
	<?php echo $this->BlockTabs->main(BlockTabsHelper::MAIN_TAB_BLOCK_INDEX); ?>

	<div class="tab-content">
		<?php echo $this->BlockTabs->block('metadata_settings'); ?>
		<?php echo $this->element(
			'MultidatabaseMetadataSettings/edit_form', array(
				'action' => NetCommonsUrl::actionUrl(array(
					'controller' => $this->params['controller'],
					'action' => 'edit',
					'frame_id' => Current::read('Frame.id')
				)),
				'callback' => 'Multidatabases.MultidatabaseMetadataSettings/edit_form',
				'cancelUrl' => NetCommonsUrl::backToPageUrl(),
			)
		); ?>
	</div>
</div>
