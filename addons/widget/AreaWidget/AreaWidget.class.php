<?php
/**
 * 地區選擇 widget
 * @example W('Area',array('curPro'=>1,'curCity'=>2,'area'=>3,'tpl'=>'loadCity'))
 * @author Jason
 * @version TS3.0
 */
class AreaWidget extends Widget {

    /**
     * @param integer curPro 當前省的ID
     * @param integer curCity 當前城市的ID
     * @param integer area 當前地區的ID
     * @param string  tpl 選用的地區選擇模版 loadCity(連結方式) loadArea(文字框形式)
     */
    public function render($data) {

        empty($data['tpl']) && $data['tpl'] = 'loadArea';

        if($data['tpl'] =='loadCity'){
            if(empty($data['curPro'])){
                $info = model('Area')->getAreaById($data['curCity']);
                $data['city_ids'] = $info['pid'].','.$data['curCity'];
            }else{
                $data['city_ids'] = $data['curPro'].','.$data['curCity'];
            }
        }
        if(!empty($data['area'])){
            $data['city_ids'] .=','.$data['area'];
        }

        if($data['tpl'] == 'selectArea') {
            $selectedArea = explode(',', t($_GET['selected']));
            if(!empty($selectedArea[0])) {
                $data['selectedarea'] = t($_GET['selected']);
            }

            $list = model('CategoryTree')->setTable('area')->getNetworkList();
            $data['list'] = json_encode($list);
        }
        if($data['curPro'] && $data['curCity'] && $data['area']){
            $data['selected'] = $data['curPro'].','.$data['curCity'].','.$data['area'];
        }
        $content = $this->renderFile (dirname(__FILE__)."/".$data['tpl'].'.html', $data );
        return $content;
    }

    /**
     * 渲染地區選擇彈窗
     */
    public function area() {
        // 已選擇的地區
        $selectedArea = explode(',', t($_GET['selected']));
        if(!empty($selectedArea[0])) {
            $data['selectedarea'] = t($_GET['selected']);
        }

        $list = model('Area')->getNetworkList(0);
        $data['list'] = json_encode($list);
        // 模板選擇
        $tpl = isset($_GET['tpl']) ? t($_GET['tpl']).'_' : 'loadArea_';

        echo $this->renderFile(dirname(__FILE__)."/".$tpl.'.html', $data);
    }
}
