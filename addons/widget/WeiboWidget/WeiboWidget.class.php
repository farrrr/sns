<?php
/**
 * 釋出微博Widget
 *
 * @author daniel <desheng.young@gmail.com>
 */
class WeiboWidget extends Widget {

	/**
	 * 釋出微博Widget, 用法包括分享等
	 *
	 * $data接受的參數:
	 * <code>
	 * array(
	 * 	'page_title'(可選)	=> '彈出窗標題',		 // 默認為"分享到我的微博"
	 * 	'button_title'(可選)	=> '彈出窗內按鈕的標題', // 默認為"釋出"
	 * 	'tpl_name'(必須)		=> '模版名稱',		 // 管理後臺配置的模版的名稱
	 * )
	 * </code>
	 * <br/>
	 * 與其它Widget不同的是, WeiboWidget必須手動觸發, 觸發方法是在JS中呼叫_widget_weibo_start(page_title, tpl_data, param_data)方法,
	 * 參數說明:
	 * page_title: string					  與WeiboWidget的page_title相同
	 * tpl_data:   先serialize再urlencode的陣列 模版資料, 用以填充WeiboWidget的tpl_name參數標示的模版變數
	 * param_data: 先serialize再urlencode的陣列 widget參數, 格式為:
	 * 										  array(
	 * 										  	'has_status' 		=> 1,		   // 或0. 是否含有狀態.
	 * 										  	'is_success_status' => 1,		   // 或0. 狀態是否為成功. 當has_status=1時有效.
	 * 										  	'status_title' 		=> '狀態的標題', // 狀態的標題, 如"釋出成功", "釋出失敗"等. 當has_status=1時有效.
	 * 										  )
	 *
	 * @see Widget::render()
	 */
	public function render($data) {
		// 預設值
		$data['page_title']		= isset($data['page_title']) 		? $data['page_title'] 		: '分享';
		$data['button_title']	= isset($data['button_title'])		? $data['button_title']		: L('PUBLISH');
		$data['status_title']	= isset($data['status_title'])		? t($data['status_title'])	: '';
		$data['addon_info']		= isset($data['addon_info'])		? $data['addon_info']	: '';
		$data['url'] = U('public/Share/shareToFeed').'&initHTML='.$data['tpl_data'].'&attachId='.$data['attachid'].'&from='.$data['from'].'&appname=public&source_url='.urlencode($data['source_url']);
// 		$data['url']	= U('public/Widget/weibo',array('button_title'=>urlencode($data['button_title']),'tpl_name'=>$data['tpl_name'],'addon_info'=>$data['addon_info']));
		$content = $this->renderFile(dirname(__FILE__).'/Weibo.html', $data);
		return $content;
	}
}