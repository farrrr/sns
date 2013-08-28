<?php
/**
 * 導航模型 - 資料物件模型
 * @author jason <renjianchao@zhishisoft.com>
 * @version TS3.0
 */
class NaviModel extends Model {

    protected $tableName = 'navi';
    protected $fields = array(0=>'navi_id',1=>'navi_name',2=>'app_name',3=>'url',4=>'target',5=>'status',6=>'position',7=>'guest',8=>'is_app_navi',9=>'parent_id',10=>'order_sort');

    /**
     * 獲取頭部導航
     * @return array 頭部導航
     */
    public function getTopNav() {

        if(($topNav = model('Cache')->get('topNav')) === false) {
            $map['status'] = 1;
            $map['position'] = 0;
            $list = $this->where($map)->order('order_sort ASC')->findAll();
            foreach($list as $v){
                $v['url'] = empty($v['url']) ? 'javascript:;' : str_replace('{website}', SITE_URL, $v['url']);
                if ( $v['parent_id'] == 0 ){
                    $navlist[$v['navi_id']] = $v;
                }
            }
            foreach($list as $v){
                if ( $v['parent_id'] > 0 ){
                    $navlist[$v['parent_id']]['child'][] = $v;
                }
            }
            $topNav = $navlist;
            empty($topNav) && $topNav = array();
            model('Cache')->set('topNav', $topNav);
        }

        return $topNav;
    }

    /**
     * 獲取底部導航
     * @return array 底部導航
     */
    public function getBottomNav() {

        if(($bottomNav = model('Cache')->get('bottomNav')) === false) {
            $map['status'] = 1;
            $map['position'] = 1;
            $list = $this->where($map)->order('order_sort ASC')->findAll();
            foreach($list as $v){
                $v['url'] = empty($v['url']) ? 'javascript:;' : str_replace('{website}', SITE_URL, $v['url']);
                if ( $v['parent_id'] == 0 ){
                    $navlist[$v['navi_id']] = $v;
                }
            }
            foreach($list as $v){
                if ( $v['parent_id'] > 0 ){
                    $navlist[$v['parent_id']]['child'][] = $v;
                }
            }
            $bottomNav = $navlist;
            empty($bottomNav) && $bottomNav = array();
            model('Cache')->set('bottomNav', $bottomNav);
        }

        return $bottomNav;
    }

    public function getBottomChildNav($bottomNav){
        foreach ($bottomNav as $v){
            if(isset($v['child']) && !empty($bottomNav)){
                return true;
            }
        }
        return false;
    }
    /**
     * 清除導航快取
     * @return void
     */
    public function cleanCache() {
        model('Cache')->rm('topNav');
        model('Cache')->rm('bottomNav');
    }
}
