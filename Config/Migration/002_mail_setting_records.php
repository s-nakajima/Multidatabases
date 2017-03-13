<?php
/**
 * MutidatabaseMailSettingRecords Migration
 * メール設定データのためのMigration
 *
 * @author Tomoyuki OHNO (Ricksoft Co., Ltd.) <ohno.tomoyuki@ricksoft.jp>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */

App::uses('MailsMigration', 'Mails.Config/Migration');

/**
 * MultidatabaseMailSettingRecords Migration
 *
 * @author Tomoyuki OHNO (Ricksoft, Co., LTD.) <ohno.tomoyuki@ricksoft.jp>
 * @package NetCommons\Mails\Config\Migration
 */
class MultidatabaseMailSettingRecords extends MailsMigration {

/**
 * プラグインキー
 *
 * @var string
 */
	const PLUGIN_KEY = 'multidatabases';

/**
 * Migration description
 *
 * @var string
 */
	public $description = 'mail_setting_records';

/**
 * Actions to be performed
 *
 * @var array $migration
 */
	public $migration = [
		'up' => [],
		'down' => [],
	];

/**
 * plugin data
 *
 * @var array $migration
 */
	public $records = [
		'MailSetting' => [
			//コンテンツ通知 - 設定
			[
				'plugin_key' => self::PLUGIN_KEY,
				'block_key' => null,
				'is_mail_send' => false,
				'is_mail_send_approval' => true,
			],
		],
		'MailSettingFixedPhrase' => [
			//コンテンツ通知 - 定型文
			// * 英語
			[
				'language_id' => '1',
				'plugin_key' => self::PLUGIN_KEY,
				'block_key' => null,
				'type_key' => 'contents',
				'mail_fixed_phrase_subject'
					=> '[{X-SITE_NAME}-{X-PLUGIN_NAME}]{X-SUBJECT}({X-ROOM} {X-MULTIDATABASE_NAME})',
				'mail_fixed_phrase_body' => <<< EOM
You are receiving this email because a message was posted to MULTIDATABASE.
Room's name:{X-ROOM}
MULTIDATABASE title:{X-MULTIDATABASE_NAME}
title:{X-SUBJECT}
user:{X-USER}
date:{X-TO_DATE}

{X-BODY}

Click on the link below to reply to this article.
{X-URL}
EOM
				,
			],
			// * 日本語
			[
				'language_id' => '2',
				'plugin_key' => self::PLUGIN_KEY,
				'block_key' => null,
				'type_key' => 'contents',
				'mail_fixed_phrase_subject'
					=> '[{X-SITE_NAME}-{X-PLUGIN_NAME}]{X-SUBJECT}({X-ROOM} {X-MULTIDATABASE_NAME})',
				'mail_fixed_phrase_body' => <<< EOM
{X-PLUGIN_NAME}に投稿されたのでお知らせします。
ルーム名:{X-ROOM}
掲示板タイトル:{X-MULTIDATABASE_NAME}
記事タイトル:{X-SUBJECT}
投稿者:{X-USER}
投稿日時:{X-TO_DATE}

{X-BODY}

この記事に返信するには、下記アドレスへ
{X-URL}
EOM
				,
			],
		],
	];

/**
 * Before migration callback
 *
 * @param string $direction Direction of migration process (up or down)
 * @return bool Should process continue
 */
	public function before($direction) {
		return true;
	}

/**
 * After migration callback
 *
 * @param string $direction Direction of migration process (up or down)
 * @return bool Should process continue
 */
	public function after($direction) {
		return parent::updateAndDelete($direction, self::PLUGIN_KEY);
	}
}
