<?php
/**
 * Multidatabase Helper
 *
 * @author Noriko Arai <arai@nii.ac.jp>
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */

App::uses('AppHelper', 'View/Helper');
App::uses('MultidatabaseMetadata', 'Multidatabases.Model');

/**
 * 汎用データベースメタデータ設定で使用するヘルパー
 *
 * このHelperを使う場合、
 * [Multidatabases.MultidatabaseLayoutComponent](./MultidatabaseLayoutComponent.html)
 * が読み込まれている必要がある。
 *
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 * @package NetCommons\Multidatabase\View\Helper
 */
class MultidatabaseHelper extends AppHelper {

/**
 * 使用するHelpsers
 *
 * - [NetCommons.ButtonHelper](../../NetCommons/classes/ButtonHelper.html)
 * - [NetCommons.NetCommonsHtml](../../NetCommons/classes/NetCommonsHtml.html)
 * - [NetCommons.NetCommonsForm](../../NetCommons/classes/NetCommonsForm.html)
 *
 * @var array
 */
	public $helpers = array(
		'NetCommons.Button',
		'NetCommons.NetCommonsHtml',
		'NetCommons.NetCommonsForm'
	);

/**
 * 表示列の変更HTMLを出力する
 *
 * @param array $layout multidatabaseLayoutデータ配列
 * @return string HTML
 */
	public function editCol($layout) {
		$output = '';

		$url = NetCommonsUrl::actionUrlAsArray(array(
			'controller' => 'multidatabase_layouts',
			'action' => 'edit',
			$layout['MultidatabaseLayout']['id']
		));
		$output .= $this->NetCommonsForm->create(
			'MultidatabaseLayout', array('type' => 'put', 'url' => $url)
		);

		$output .= $this->NetCommonsForm->hidden('MultidatabaseLayout.id',
				array('value' => $layout['MultidatabaseLayout']['id']));

		$options = array();
		for ($col = 1; $col <= MultidatabaseLayout::LAYOUT_COL_NUMBER; $col++) {
			if ($col === 1) {
				$options['1'] = __d('multidatabases', '%s Col', $col);
			} else {
				$options[(string)$col] = __d('multidatabases', '%s Cols', $col);
			}
		}

		$output .= $this->NetCommonsForm->select('MultidatabaseLayout.col', $options, array(
			'value' => $layout['MultidatabaseLayout']['col'],
			'class' => 'form-control',
			'empty' => false,
			'onchange' => 'submit()'
		));

		$output .= $this->NetCommonsForm->end();
		return $output;
	}



/**
 * 項目の移動HTMLを出力する
 *
 * @param array $layout multidatabaseLayoutデータ配列
 * @param array $multidatabase multidatabaseデータ配列
 * @return string HTML
 */
	public function moveSetting($layout, $multidatabase) {
		$output = '';

		$output .= '<button type="button" ' .
							'class="btn btn-xs btn-default dropdown-toggle" ' .
							'data-toggle="dropdown" ' .
							'aria-haspopup="true" ' .
							'aria-expanded="false" ' .
							'ng-disabled="sending">' .
					__d('multidatabases', 'Move') .
					' <span class="caret"></span>' .
				'</button>';

		$output .= '<ul class="dropdown-menu">';
		$output .= $this->moveSettingTopMenu($layout, $multidatabase);
		$output .= $this->moveSettingBottomMenu($layout, $multidatabase);
		$output .= $this->moveSettingLeftMenu($layout, $multidatabase);
		$output .= $this->moveSettingRightMenu($layout, $multidatabase);

		//区切り線
		$output .= '<li class="divider"></li>';

		$output .= $this->moveSettingRowMenu($layout, $multidatabase);

		$output .= '</ul>';

		return $output;
	}

/**
 * 項目の移動メニューHTMLを出力する
 *
 * @param string $formName フォーム名
 * @param int $multidatabaseSettingId MultidatabaseSetting.id
 * @param int $updWeight 順序
 * @param int $updRow ○段目
 * @param int $updCol 行
 * @param string $disabled disabledのCSS
 * @param string $class ボタンのCSS
 * @param string $message メッセージ
 * @return string HTML
 */
	private function __moveSettingForm($formName, $multidatabaseSettingId, $updWeight, $updRow, $updCol,
											$disabled, $class, $message) {
		$output = '';

		$output .= '<li' . $disabled . '>';
		if ($disabled) {
			$output .= '<a href=""> ';
		} else {
			$output .= '<a href="" onclick="$(\'form[name=' . $formName . ']\')[0].submit()"> ';
		}

		if ($class) {
			$output .= '<span class="glyphicon ' . $class . '">' . $message . '</span>';
		} else {
			$output .= '<span>' . $message . '</span>';
		}

		$output .= $this->NetCommonsForm->create(null, array('type' => 'put', 'name' => $formName,
			'url' => NetCommonsUrl::actionUrlAsArray(array(
				'controller' => 'multidatabase_metadata_settings',
				'action' => 'move',
				'key' => $multidatabaseSettingId
			)),
		));

		$output .= $this->NetCommonsForm->hidden(
			'MultidatabaseSetting.id', array('value' => $multidatabaseSettingId)
		);
		$output .= $this->NetCommonsForm->hidden(
			'MultidatabaseSetting.row', array('value' => $updRow)
		);
		if ($updCol) {
			$output .= $this->NetCommonsForm->hidden(
				'MultidatabaseSetting.col', array('value' => $updCol)
			);
		}
		if ($updWeight) {
			$output .= $this->NetCommonsForm->hidden(
				'MultidatabaseSetting.weight', array('value' => $updWeight)
			);
		}

		$output .= $this->NetCommonsForm->end();

		$output .= '</a></li>';

		return $output;
	}

/**
 * 項目の移動メニューHTMLを出力する(上へ)
 *
 * @param array $layout multidatabaseLayoutデータ配列
 * @param array $multidatabase multidatabaseデータ配列
 * @return string HTML
 */
	public function moveSettingTopMenu($layout, $multidatabase) {
		$output = '';

		//データを変数にセット
		$multidatabaseSettingId = $multidatabase['MultidatabaseSetting']['id'];
		$weight = (int)$multidatabase['MultidatabaseSetting']['weight'];
		$row = (int)$layout['MultidatabaseLayout']['id'];
		$col = (int)$multidatabase['MultidatabaseSetting']['col'];
		$formName = 'MultidatabaseMoveForm' . $multidatabaseSettingId . 'Top';

		//上に移動
		if ($weight === 1) {
			if ((int)$layout['MultidatabaseLayout']['col'] === 2 ||
					$col === 1 || ! isset($this->_View->viewVars['multidatabases'][$row][1])) {
				$disabled = ' class="disabled"';
				$updCol = $col;
				$updRow = $row;
				$updWeight = $weight;
			} else {
				$disabled = '';
				$updCol = $col - 1;
				$updRow = $row;
				$updWeight = count($this->_View->viewVars['multidatabases'][$row][($col - 1)]);
			}
		} else {
			$disabled = '';
			$updCol = $col;
			$updRow = $row;
			$updWeight = $weight - 1;
		}

		//HTML出力
		$output .= $this->__moveSettingForm(
			$formName, $multidatabaseSettingId, $updWeight, $updRow, $updCol,
			$disabled, 'glyphicon-arrow-up', __d('multidatabases', 'Go to Up')
		);

		return $output;
	}

/**
 * 項目の移動メニューHTMLを出力する(下へ)
 *
 * @param array $layout multidatabaseLayoutデータ配列
 * @param array $multidatabase multidatabaseデータ配列
 * @return string HTML
 */
	public function moveSettingBottomMenu($layout, $multidatabase) {
		$output = '';

		//データを変数にセット
		$multidatabaseSettingId = $multidatabase['MultidatabaseSetting']['id'];
		$weight = (int)$multidatabase['MultidatabaseSetting']['weight'];
		$row = (int)$layout['MultidatabaseLayout']['id'];
		$col = (int)$multidatabase['MultidatabaseSetting']['col'];
		$formName = 'MultidatabaseMoveForm' . $multidatabaseSettingId . 'Bottom';

		//下に移動
		if ($weight === count($this->_View->viewVars['multidatabases'][$row][$col])) {
			if ((int)$layout['MultidatabaseLayout']['col'] === 2 ||
					$col === 2 || ! isset($this->_View->viewVars['multidatabases'][$row][2])) {
				$disabled = ' class="disabled"';
				$updCol = $col;
				$updRow = $row;
				$updWeight = $weight;
			} else {
				//レイアウトが2列から1列の場合で、2列目がある場合の処理で、
				//その1列目の末尾だった項目に対する移動のため、2列目の2番目に移動する
				$disabled = '';

				$updCol = $col + 1;
				$updRow = $row;
				$updWeight = 2;
			}
		} else {
			$disabled = '';
			$updCol = $col;
			$updRow = $row;
			$updWeight = $weight + 1;
		}

		//HTML出力
		$output .= $this->__moveSettingForm(
			$formName, $multidatabaseSettingId, $updWeight, $updRow, $updCol,
			$disabled, 'glyphicon-arrow-down', __d('multidatabases', 'Go to Down')
		);

		return $output;
	}

/**
 * 項目の移動メニューHTMLを出力する(左へ)
 *
 * @param array $layout multidatabaseLayoutデータ配列
 * @param array $multidatabase multidatabaseデータ配列
 * @return string HTML
 */
	public function moveSettingLeftMenu($layout, $multidatabase) {
		$output = '';

		//データを変数にセット
		$multidatabaseSettingId = $multidatabase['MultidatabaseSetting']['id'];
		$weight = (int)$multidatabase['MultidatabaseSetting']['weight'];
		$row = (int)$layout['MultidatabaseLayout']['id'];
		$col = (int)$multidatabase['MultidatabaseSetting']['col'];
		$formName = 'MultidatabaseMoveForm' . $multidatabaseSettingId . 'Left';

		if ((int)$layout['MultidatabaseLayout']['col'] === 2) {
			//左に移動
			if ($col === 1) {
				$disabled = ' class="disabled"';
				$updCol = $col;
				$updRow = $row;
				$updWeight = $weight;
			} else {
				$disabled = '';
				$updCol = $col - 1;
				$updRow = $row;
				if (! isset($this->_View->viewVars['multidatabases'][$row][1])) {
					$updWeight = 1;
				} elseif ($weight > count($this->_View->viewVars['multidatabases'][$row][1])) {
					$updWeight = count($this->_View->viewVars['multidatabases'][$row][1]) + 1;
				} else {
					$updWeight = $weight;
				}
			}

			//HTML出力
			$output .= $this->__moveSettingForm(
				$formName, $multidatabaseSettingId, $updWeight, $updRow, $updCol,
				$disabled, 'glyphicon-arrow-left', __d('multidatabases', 'Go to Left')
			);
		}

		return $output;
	}

/**
 * 項目の移動メニューHTMLを出力する(右へ)
 *
 * @param array $layout multidatabaseLayoutデータ配列
 * @param array $multidatabase multidatabaseデータ配列
 * @return string HTML
 */
	public function moveSettingRightMenu($layout, $multidatabase) {
		$output = '';

		//データを変数にセット
		$multidatabaseSettingId = $multidatabase['MultidatabaseSetting']['id'];
		$weight = (int)$multidatabase['MultidatabaseSetting']['weight'];
		$row = (int)$layout['MultidatabaseLayout']['id'];
		$col = (int)$multidatabase['MultidatabaseSetting']['col'];
		$formName = 'MultidatabaseMoveForm' . $multidatabaseSettingId . 'Right';

		if ((int)$layout['MultidatabaseLayout']['col'] === 2) {
			//右に移動
			if ($col === 2) {
				$disabled = ' class="disabled"';
				$updCol = $col;
				$updRow = $row;
				$updWeight = $weight;
			} else {
				$disabled = '';
				$updCol = $col + 1;
				$updRow = $row;
				if (! isset($this->_View->viewVars['multidatabases'][$row][2])) {
					$updWeight = 1;
				} elseif ($weight > count($this->_View->viewVars['multidatabases'][$row][2])) {
					$updWeight = count($this->_View->viewVars['multidatabases'][$row][2]) + 1;
				} else {
					$updWeight = $weight;
				}
			}

			//HTML出力
			$output .= $this->__moveSettingForm(
				$formName, $multidatabaseSettingId, $updWeight, $updRow, $updCol,
				$disabled, 'glyphicon-arrow-right', __d('multidatabases', 'Go to Right')
			);
		}

		return $output;
	}

/**
 * 項目の移動メニューHTMLを出力する(○段へ)
 *
 * @param array $layout multidatabaseLayoutデータ配列
 * @param array $multidatabase multidatabaseデータ配列
 * @return string HTML
 */
	public function moveSettingRowMenu($layout, $multidatabase) {
		$output = '';

		//データを変数にセット
		$multidatabaseSettingId = $multidatabase['MultidatabaseSetting']['id'];
		$row = (int)$layout['MultidatabaseLayout']['id'];
		$formName = 'MultidatabaseMoveForm' . $multidatabaseSettingId . 'Row';

		foreach ($this->_View->viewVars['multidatabaseLayouts'] as $moveLayout) {
			//○段目に移動
			if ((int)$moveLayout['MultidatabaseLayout']['id'] === (int)$row) {
				$disabled = ' class="disabled"';
				$updRow = $row;
			} else {
				$disabled = '';
				$updRow = $moveLayout['MultidatabaseLayout']['id'];
			}

			//HTML出力
			$output .= $this->__moveSettingForm(
				$formName . $moveLayout['MultidatabaseLayout']['id'],
				$multidatabaseSettingId,
				null,
				$updRow,
				null,
				$disabled,
				'',
				sprintf(__d('multidatabases', 'Go to %s row'), $moveLayout['MultidatabaseLayout']['id'])
			);
		}

		return $output;
	}

}
