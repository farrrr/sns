<?php
/**
 * 畫布類
 * @author Stream
 *
 */
class CanvasModel extends Model{

    protected $tableName = 'diy_canvas';

    protected $path;

    public function _initialize(){
        $this->path = CANVAS_PATH;
        parent::_initialize();
    }
    /**
     * 返回畫布詳細資訊
     * @param unknown_type $map
     * @param unknown_type $fields
     * @return unknown
     */
    function getCanvasInfo($map , $fields = 'id,canvas_name,title,data,description'){
        $data = $this->where($map)->field($fields)->find();
        $data['canvas_name'] = str_replace( '.html' , '', $data['canvas_name']);
        $data['data'] = base64_decode( $data['data'] );
        $data['data'] = $this->convert('lower' , $data['data'] );
        return $data;
    }
    /**
     * 返回畫布列表
     * @param int $limit
     * @param array $map
     * @param string $fields
     * @return array or false
     */
    function getCanvasList( $limit = 20 , $map , $fields='id,title,canvas_name,description'){
        $list = $this->where($map)->field($fields)->findPage($limit);
        return $list;
    }
    /**
     * 添加畫布
     * @param array $data
     * @return boolean or 1
     */
    function addCanvas( $data ){
        if ( empty( $data['title'] ) > 0 ){
            $this->error = '畫布標題不能為空';
            return false;
        }
        if ( empty( $data['canvas_name'] ) ){
            $this->error = '畫布名稱不能為空';
            return false;
        }
        if ( empty( $data['data'] ) ){
            $this->error = '畫布內容不能為空';
            return false;
        }
        $map['canvas_name'] = $data['canvas_name'];
        $count = $this->where($map)->count();
        if ( $count ){
            $this->error = '已存在相同的畫布名稱';
            return false;
        }
        $this->createhtml($data);
        $data['canvas_name'] = trim( $data['canvas_name']) . '.html';
        $data['data'] = base64_encode($data['data']);
        $res = $this->add($data);
        return $res;
    }
    /**
     * 修改畫布
     * @param array $data
     * @return boolean or 1
     */
    function saveCanvas( $data ){
        if ( empty( $data['title'] ) > 0 ){
            $this->error = '畫布標題不能為空';
            return false;
        }
        if ( empty( $data['canvas_name'] ) ){
            $this->error = '畫布名稱不能為空';
            return false;
    }
    if ( empty( $data['data'] ) ){
        $this->error = '畫布內容不能為空';
        return false;
    }
    $map['canvas_name'] = $data['canvas_name'];
    $map['id'] = array( 'neq' , $data['id'] );
    $canvasname = $this->where($map)->getField('canvas_name');
    if ( $canvasname ){
        $this->error = '已存在相同的畫布名稱';
        return false;
    }
    $data['data'] = $this->convert('upper' , $data['data'] );
    //如果修改了頁面名字刪除歷史的快取頁面
    //      if ( $canvasname != trim( $data['name'] ) ){
    //          unlink( $this->path.$canvasname );
    //      }
    //生成快取檔案
    $this->createhtml($data);
    $data['canvas_name'] = trim( $data['canvas_name']) . '.html';
    $data['data'] = base64_encode($data['data']);
    $res = $this->where('id='.$data['id'])->save($data);
    return $res;
    }
    /**
     * 刪除畫布
     * @param array $map
     * @return boolean
     */
    function deleteCanvas($map){
        //刪除對應檔案
        $names = $this->where($map)->field('canvas_name')->findAll();

        foreach ( $names as $n){
            unlink( $this->path.$n['canvas_name'] );
    }
    //刪除對應資料
    $res = $this->where($map)->delete();
    return $res;
    }
    private function convert( $type , $data){
        // 模板內容替換
        $replace =  array(
            '__ROOT__'      =>  '__root__',           // 當前網站地址
            '__UPLOAD__'    =>  '__upload__',         // 上傳檔案地址
            '__PUBLIC__'    =>  '__public__',         // 公共靜態地址
            '__THEME__'     =>  '__theme__',   // 主題靜態地址
            '__APP__'       =>  '__app__',     // 應用靜態地址
        );
        if ( $type == 'lower' ){
            $data = str_replace( array_keys($replace) , array_values($replace) , $data );
    } else {
        $data = str_replace( array_values($replace) , array_keys($replace) , $data );
    }
    return $data;
    }
    /**
     * 創建靜態畫布
     * @param array $data
     */
    private function createhtml( $data ){
        $filename = $this->path.$data['canvas_name'].'.html';
        file_put_contents($filename, $data['data']);
    }
    /**
     * 獲取最後錯誤資訊
     * @return string 最後錯誤資訊
     */
    public function getLastError() {
        return $this->error;
    }
    }
?>
