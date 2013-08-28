<?php
/**
 * DIY頁面類
 * @author Stream
 *
 */
class PageModel extends Model {
    protected $tableName = 'diy_page';

    /**
     * 返回頁面詳細資訊
     * @param unknown_type $id
     * @param unknown_type $field
     * @return unknown
     */
    public function getPageInfo( $map , $field = 'id,page_name,domain,canvas,manager,status,guest,seo_title,seo_keywords,seo_description'){
        $data = $this->where($map)->field($field)->find();
        return $data;
    }
    /**
     * 返回頁面列表
     * @param array $map
     * @param string $field
     */
    public function getPageList( $limit = 20 , $map , $field='id,page_name,domain,canvas,manager,visit_count'){
        $list = $this->where($map)->field($field)->findPage($limit);
        return $list;
    }
    /**
     * 添加頁面
     * @param array $data
     * @return boolean
     */
    public function addPage( $data ){
        if ( empty( $data['page_name'] ) ){
            $this->error = '頁面標題不能為空';
            return false;
        }
        if ( empty( $data['domain'] ) ){
            $this->error = '連結名稱不能為空';
            return false;
        }
        $map['domain'] = $data['domain'];
        $exsit = $this->where($map)->count();
        if ( $exsit ){
            $this->error = '已有相同的連結名稱';
            return false;
        }
        $data['uid'] = $GLOBALS['ts']['mid'];
        $data['ctime'] = $_SERVER['REQUEST_TIME'];
        $res = $this->add( $data );
        return $res;
    }
    /**
     * 修改頁面
     * @param array $data
     * @return boolean
     */
    public function savePage( $data ){
        if ( empty( $data['page_name'] ) ){
            $this->error = '頁面標題不能為空';
            return false;
        }
        if ( empty( $data['domain'] ) ){
            $this->error = '連結名稱不能為空';
            return false;
        }
        $map['domain'] = $data['domain'];
        $map['id'] = array ( 'neq' , $data['id'] );
        $exsit = $this->where($map)->count();
        if ( $exsit ){
            $this->error = '已有相同的連結名稱';
            return false;
        }
        $res = $this->where('id='.$data['id'])->save( $data );
        return $res;
    }
    /**
     * 刪除頁面
     * @param array $map
     * @return boolean
     */
    public function deletePage( $map ){
        //判斷是否有系統默認頁面
        $map['lock'] = 1;
        $count = $this->where($map)->count();
        if ( $count ){
            return 0;
        }
        unset( $map['lock'] );
        $res = $this->where($map)->delete();
        return $res;
    }
    /**
     * 儲存頁面資料
     */
    public function saveData( $page , $layoutData , $widgetData ){

        $map['domain'] = $page;

        $save['layout_data'] = $layoutData;
        $save['widget_data'] = serialize($widgetData);
        $result = $this->where($map)->save($save);
        return $result;
    }
    /**
     * 返回管理員資訊
     * @param array $map
     * @return Ambigous <返回新的一維陣列, multitype:Ambigous <array, string> >
     */
    public function getManagers( $map ){
        $list = $this->where($map)->field('manager')->findAll();
        return explode( ',' , implode( ',' , getSubByKey( $list , 'manager' )) );
    }
    /**
     * 獲取最後錯誤資訊
     * @return string 最後錯誤資訊
     */
    public function getLastError() {
        return $this->error;
    }
    /**
     * 瀏覽量
     * @param unknown_type $pageId
     */
    public function addReader( $pageId ){
        $this->where('id='.$pageId)->setInc('visit_count');
    }
}
?>
