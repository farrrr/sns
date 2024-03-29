<?php
/**
 * 資訊業務邏輯(為了應付TS的Comment元件,從應用裡複製的，糾了個結了)
 *
 * @version TS3.0
 * @name NewsModel
 * @author 2013-4-11  Tomcat<707514663@qq.com>
 *
 */
class NewsModel extends Model
{
    /**
     * 使用的表名稱
     *
     * @var string
     */
    protected   $tableName  =   'news';

    /**
     * 欄位列表
     *
     * @var unknown_type
     */
    protected   $fields     =   array
        (
            0 => 'news_id',
            1 => 'type_id',
            2 => 'news_title',
            3 => 'news_content',
            4 => 'image',
            5 => "state",
            6 => "is_top",
            7 => "hits",
            8 => "is_del",
            9 => 'created',
            10 => 'updated',
            11 => 'uid',
            '_autoinc' => true,
            '_pk' => 'news_id'
        );

    /**
     * 定義自動驗證
     *
     * @var array
     */
    protected $_validate    =   array
        (
            array('news_title','require','資訊標題不能為空'),
            array('news_content','require','內容不能為空'),
        );

    /**
     * 自動填充
     *
     * @var array
     */
    protected $_auto = array
        (
            array('created','time',1,'function'),
            array('updated','time',2,'function'),
        );

    /**
     * 獲取狀態
     *
     * @param int $state 狀態ID
     * @return mixed
     */
    public function getState($state = null)
    {
        $states = array
            (
                1 => '顯示',
                0 => '不顯示',
            );
        if ($state === null)
        {
            return $states ;
        }
        return isset($states[$state])?$states[$state]:'' ;
    }

    /**
     * 更新資訊
     *
     * @param array $data 欄位值
     * @return mixed
     */
    public function setNews($uid)
    {
        $ret = array( 'ret' => false, 'msg' => '更新資訊失敗' );
        $news   =   D('News');
        if($news->create())
        {
            $news->uid = ($uid)?$uid:0 ;
            if ($news->news_id)
            {
                $result = $news->save();
            }else
            {
                $result = $news->add();
            }
            if($result)
            {
                $ret = array( 'ret' => true, 'msg' => '成功更新資訊' );
            }else
            {
                $ret['msg'] = $news->getError();
            }
        }else
            {
                $ret['msg'] = $news->getError();
            }
        return $ret ;
    }

    /**
     * 獲取子分類的ID串
     *
     * @param int $pid
     * @return array
     */
    private function getChildTids($pid)
    {
        static $_result = array() ;
        if (isset($_result[$pid]))
        {
            return $_result[$pid] ;
        }
        //查找
        $child = model('CategoryTree')->setTable('news_category')->getNetworkList($pid);
        $ids = array_keys($child) ;
        $_result[$pid] = ($ids)?$ids:array();
        return $ids ;
    }

    /**
     * 前臺列表獲取
     *
     */
    public function getList($limit = 20, $type= 0 , $keywords = '',$order = '',$findPage = true)
    {
        $map = array();
        $map['state'] = array('GT',0);

        if ($keywords)
        {
            $map['news_title']=array('like','%'.$keywords.'%');
        }
        if ($order == '')
        {
            $order = 'is_top desc,news_id desc' ;
        }
        if ($type)
        {
            //獲取子分類
            $childs = $this->getChildTids($type);
            if ($childs)
            {
                $childs[] = $type;
                $map['type_id'] = array('in',$childs);
            }else
            {
                $map['type_id'] = $type ;
            }
        }
        $this->where($map)->order( $order ) ;
        if ($findPage)
        {
            $list = $this->findPage($limit);
            $data = $list['data'];
        }else
        {
            $data = $this->findAll(array('limit' => $limit));

        }
        foreach ($data as $k => $v)
        {
            $thumb = APPS_URL.'/'.APP_NAME.'/_static/nopic.jpg';
            if ($v['image'])
            {
                $attach = model('Attach')->getAttachById($v['image']);
                if ($attach)
                {
                    $thumb = getImageUrl($attach['save_path']. $attach['save_name'],100,100);
                }
            }
            $data[$k]['image'] = $thumb ;
            $data[$k]['title_intro'] = msubstr($v['news_title'],0,30);
            $data[$k]['content_intro'] = msubstr(strip_tags($v['news_content']),0,100);
            //獲取評論數量
            $data[$k]['comment_count'] = model('Comment')->where(array('app'=>'news' , 'table' => 'news' ,'is_del' => 0,'row_id' => $v['news_id']))->count();
        }
        if ($findPage)
        {
            $list['data'] = $data ;
            return $list ;
        }else
        {
            return $data ;
        }
    }

    /**
     * 根據ID獲取資料
     *
     * @param int $id
     * @param boolean $is_admin 是否是後臺
     * @return array
     */
    public function getOneyById($id,$is_admin = false,$update_hits = false)
    {
        $map = array
            (
                'news_id' => $id,
            );
        if ($is_admin == false)
        {
            $map['state'] = array('GT',0);
        }
        $v = $this->where($map)->find();
        if ($v)
        {
            $v['attachId'] = $v['image'];
            $thumb = APPS_URL.'/'.APP_NAME.'/_static/nopic.jpg';
            if ($v['image'])
            {
                $attach = model('Attach')->getAttachById($v['image']);
                if ($attach)
                {
                    $thumb = getImageUrl($attach['save_path']. $attach['save_name'],100,100);
        }
        }
        $v['image'] = $thumb ;
        //更新瀏覽量
        if ($is_admin === false && $update_hits == true)
        {
            $this->setInc('hits',array('news_id' => $id),1);
        }
        }
        return $v ;
        }

        /**
         * 獲取資訊
         * @param int $id
         * @return mixed
         */
        public function getSourceInfo($id)
        {
            $find = $this->getOneyById($id);
            if ($find)
            {
                $info = array('source_user_info' => '');
                if ($find['uid'])
                {
                    $info['source_user_info'] = model('User')->getUserInfo( $find['uid'] );
        }
        $info['source_url'] = U('news/Index/detail',array('id'=>$id ));
        $info['source_body'] = $find['news_title'].'<a class="ico-details" href="'.U('news/Index/show',array('id'=>$id)).'"></a>';
        return $info;
        }
        return false ;
        }

        /**
         * 刪除分類後的回撥
         * @param int $cid
         * @return bool
         */
        public function deleteAssociatedData( $cid )
        {
            return $this->where(array('type_id' =>$cid))->delete();
        }

        }
