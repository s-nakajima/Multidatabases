<?php
/**
 * MutidatabaseMailSettingRecords Migration
 * メール定型文の初期値データを更新するためのMigration
 *
 * @author Mitsuru Mutaguchi <mutaguchi@opensource-workshop.jp>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */

App::uses('MailsMigration', 'Mails.Config/Migration');

/**
 * MultidatabaseMailSettingRecords Migration
 *
 * @author Mitsuru Mutaguchi <mutaguchi@opensource-workshop.jp>
 * @package NetCommons\Multidatabases\Config\Migration
 */
class MailSettingFixedPhraseUpdate extends MailsMigration {

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
	public $description = 'MailSettingFixedPhraseUpdate';

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
		'MailSettingFixedPhrase' => [
			//コンテンツ通知 - 定型文
			// * 英語
			[
				'language_id' => '1',
				'plugin_key' => self::PLUGIN_KEY,
				'block_key' => null,
				'type_key' => 'contents',
				'mail_fixed_phrase_subject'
					=> '[{X-SITE_NAME}-{X-PLUGIN_NAME}]{X-SUBJECT}({X-ROOM} {X-BLOCK_NAME})',
				'mail_fixed_phrase_body' => '
You are receiving this email because a message was posted to MULTIDATABASE.
Room\'s name:{X-ROOM}
MULTIDATABASE title:{X-BLOCK_NAME}
title:{X-SUBJECT}
user:{X-USER}
date:{X-TO_DATE}


{X-DATA}


Click on the link below to reply to this article.
{X-URL}',
			],
			// * 日本語
			[
				'language_id' => '2',
				'plugin_key' => self::PLUGIN_KEY,
				'block_key' => null,
				'type_key' => 'contents',
				'mail_fixed_phrase_subject'
					=> '[{X-SITE_NAME}-{X-PLUGIN_NAME}]{X-SUBJECT}({X-ROOM} {X-BLOCK_NAME})',
				'mail_fixed_phrase_body' => '
{X-PLUGIN_NAME}に投稿されたのでお知らせします。
ルーム名:{X-ROOM}
汎用データベースタイトル:{X-BLOCK_NAME}
コンテンツタイトル:{X-SUBJECT}
投稿者:{X-USER}
投稿日時:{X-TO_DATE}


{X-DATA}


この記事に返信するには、下記アドレスへ
{X-URL}',
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
		// delete -> insertするために、一度down, その後upする
		if (! parent::updateAndDelete('down', self::PLUGIN_KEY)) {
			return false;
		}
		return parent::updateAndDelete('up', self::PLUGIN_KEY);
	}
}
