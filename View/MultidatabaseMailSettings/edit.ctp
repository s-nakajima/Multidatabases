<?php
/**
 * MultidatabasesMailSettings edit view
 * 汎用データベース メール設定 view
 *
 * @author Noriko Arai <arai@nii.ac.jp>
 * @author Tomoyuki OHNO (Ricksoft Co., Ltd.) <ohno.tomoyuki@ricksoft.jp>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */
?>

<div class="block-setting-body">
	<?php echo $this->BlockTabs->main(BlockTabsHelper::MAIN_TAB_BLOCK_INDEX); ?>

	<div class="tab-content">
		<?php echo $this->BlockTabs->block(BlockTabsHelper::MAIN_TAB_MAIL_SETTING); ?>

		<?php /** @see MailFormHelper::editFrom() */ ?>
		<?php echo $this->MailForm->editFrom(
			array(
				array(
					'mailBodyPopoverMessage' => __d('multidatbases', 'Multidatabase.mail_fixed_phrase_body.popover'),
				),
			),
			NetCommonsUrl::backToIndexUrl('default_setting_action')
		); ?>
	</div>
</div>
