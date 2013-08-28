<?php
/**
 * 顏色板Widget
 * @author zivss <guolee226@gmail.com>
 * @version TS3.0
 */
class ColorWidget extends Widget {
	/**
	 * 渲染顏色板頁面
	 * @example
	 * 
	 * @return string 顏色板HTML相關資訊
	 */
	public function render ($data) {
		$var['tpl'] = 'simple';
		$var['value'] = t($data['value']);
		$var['name'] = t($data['id']);

		$content = $this->renderFile(dirname(__FILE__).'/'.$var['tpl'].'.html', $var);

		return $content;
	}
}