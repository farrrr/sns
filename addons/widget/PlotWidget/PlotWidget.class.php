<?php
/**
 * 報表圖形Js圖形Widget,用於生產各種圖形報表
 * @example {:W('Plot', array('type'=>'pieChart', 'id'=>'1', 'pWidth'=>$pWidth, 'pHeight'=>$pHeight, 'key'=>$key, 'value'=>$value1, 'color'=>$color, 'show'=>$show1))}
 * @author zivss
 * @version TS3.0
 **/
class PlotWidget extends Widget{

 	/**
 	 * @param string type 報表類型 分為pieChart(餅狀圖)、pointLabelsChart(折線圖)、barChart(柱狀圖)
 	 * @param integer id //未知
 	 * @param integer pWidth 圖表寬度
 	 * @param integer pHeight 圖表高度
 	 * @param array key //好像沒用
 	 * @param array value
 	 * @param array color
 	 */
	public function render($data) {
		$var['tpl']  = isset($data['tpl']) ? $data['tpl'] : 'chart';
		// 獲取渲染模板
		$type = t($data['type']);
		$var['type'] = $type;
		$var['id'] = t($data['id']);
		$pWidth = isset($data['pWidth']) ? intval($data['pWidth']) : 120;
		$pHeight = isset($data['pHeight']) ? intval($data['pHeight']) : 120;
		// 顯示圖形的div長寬
		$var['plotCls'] = 'width:'.$pWidth.'px;height:'.$pHeight.'px';
		// 獲取圖形顯示資料
		$plotData = $this->getPlotPlugins($type, $data);
		$var = array_merge($var, $plotData);

	    //渲染模版
	    $content = $this->renderFile(dirname(__FILE__)."/".$var['tpl'].".html", $var);


		return $content;
	}

	/*** 私有方法 ***/
	/**
	 * Plot插件路由
	 * @return 統計圖資訊
	 */ 
	private function getPlotPlugins($type, $data) {
		switch($type) {
			case 'pieChart':
				$var = $this->getPieChart($data);
				break;
			case 'pointLabelsChart':
				$var = $this->getPointLabels($data);
				break;
			case 'barChart':
				$var = $this->getBarChart($data);
				break;
			case 'zx':
				$var = $this->getZx($data);
				break;	
		}

		return $var;
	}

	/**
	 * 未知，無顯示
	 */
	public function getZx($data){
		/*
		$data['value'][] = array(-1,4,4,2,5,6,7,13);
		$data['value'][] = array(-1,2,4,5,4,2,3,22);
		$data['ticks']	= array('週一','er','san','si','wu','liu','ri');
		$data['title']  = '一週進展';
		測試資料
		*/
		$var['jsHtml'] = '';
		$most = 0;
		foreach($data['value'] as $k=>$v){
			$tmp = '';
			foreach($v as $kk=>$vv){
				$vv > $most && $most = $vv;
				$tmp[]="[$kk,$vv]";
			}
			$var['jsHtml'] .=" args.obj[$k] =[".implode(',',$tmp)."];";
		}
		foreach($data['ticks'] as $k=>$v){
			$var['jsHtml'] .=" args.ticks[$k] = '{$v}';";
		}
		$most = $most%5 > 0 ? $most+(5-$most%5) : $most;

		$var['jsHtml'] .=" args.title = '{$data['title']}';";
		$var['jsHtml'] .=" args['x'][0] = 0;";
		$var['jsHtml'] .=" args['x'][1] = ".count($data['ticks']).";";
		$var['jsHtml'] .=" args['y'][0] = 0;";
		$var['jsHtml'] .=" args['y'][1] = {$most};";
		$var['jsHtml'] .=" args['y']['numberTicks'] = 5;";

		return $var;
	}

	/**
	 * 獲取餅狀圖顯示資料
	 * @return array 餅狀圖資訊
	 */ 
	private function getPieChart($data) {
		// 配置對應的資料
		$var['key'] = implode(',', $data['key']);
		// 換算資料的百分比
		$sum = array_sum($data['value']);
		if($sum == 0) {
			$var['value'] = '100,0';
		} else {
			foreach($data['value'] as &$value) {
				$value = 100 * $value / $sum;
			}
			$var['value'] = implode(',', $data['value']);
		}
		$var['color'] = implode(',', $data['color']);
		$var['show'] = $data['show'];
		$var['location'] = $data['location'];

		return $var;
	}

	/**
	 * 獲取折線圖顯示資料
	 * @return array 折線圖資訊
	 */ 
	private function getPointLabels($data) {
		// 配置對應的資料
		$var['value'] = implode(',', $data['value']);
		// 報表標題
		$var['title'] = t($data['title']);
		$var['show'] = $data['show'];
		$var['location'] = $data['location'];

		return $var;
	}

	/**
	 * 獲取條形圖顯示資料
	 * @return array 條形圖資訊
	 */ 
	private function getBarChart($data) {
		// 配置對應的資料
		$var['key'] = implode(',', $data['key']);
		$var['value'] = implode(',', $data['value']);
		// 報表標題
		$var['title'] = t($data['title']);
		$var['show'] = $data['show'];
		$var['location'] = $data['location'];

		return $var;
	}
}