<?php
/**
 * 舉報彈窗Widget
 * @example {:W('Denouce',array())}
 * @author jason
 * @version TS3.0
 */
class DenouceWidget extends Widget 
{	
	private static $rand = 1;

    /**
     * @return bool false
     */
	public function render($data){
		return  false;
    }
    
    /**
     * 舉報彈框
     * @return string 彈窗頁面HTML
     */
    public function index()
    {
        // 獲取相關資料
    	$var = $this->getVar();
        $content = $this->renderFile(dirname(__FILE__)."/index.html",$var);
    	return $content;
    }

    /**
     * 判斷資源是否已經被舉報
     * @return json 判斷後的相關資訊
     */
    public function isDenounce()
    {
        $map['from'] = t($_REQUEST['type']);
        $map['aid'] = t($_REQUEST['aid']);
        $map['uid'] = $GLOBALS['ts']['mid'];
        $count = model('Denounce')->where($map)->count();
        $res = array();
        if($count) {
            $res['status'] = 1;
            $res['data'] = '您已經舉報過此資訊';
        } else {
            $res['status'] = 0;
            $res['data'] = L('PUBLIC_REPORT_ERROR');
        }
        exit(json_encode($res));
    }
    
    /**
     * 格式化模板變數
     * @return array 被舉報的資訊
     */
    public function getVar(){
    	if(empty($_GET['aid']) || empty($_GET['fuid']) || empty($_GET['type'])){
    		return false;
    	}
        foreach($_GET as $k=>$v){
            $var[$k] = t($v);
        }
    	$var['uid'] = $GLOBALS['ts']['mid'];
        empty($var['app']) &&  $var['app'] = 'public';
    	$var['source'] = model('Source')->getSourceInfo($var['type'],$var['aid'],false,$var['app']);
    	return $var;
    }
    
    /**
     * 提交舉報
     * @return array 舉報資訊和操作狀態
     */
    public function post(){
    	$map['from'] = trim( $_POST['from'] );
		$map['aid'] = intval( $_POST['aid'] );
		$map['uid'] = intval( $_POST['uid'] );
		$map['fuid'] = intval( $_POST['fuid'] );
        // 判斷資源是否刪除
        $fmap['feed_id'] = intval($_POST['aid']);
        $fmap['is_del'] = 0;
        $isExist = model('Feed')->where($fmap)->count();
        if($isExist == 0) {
            $return['status'] = 0;
            $return['data'] = '內容已被刪除，舉報失敗';
            exit(json_encode($return));
        }
		$return = array();
		if($isDenounce = model('Denounce')->where($map)->count()) {
			$return = array('status'=>0,'data'=>L('PUBLIC_REPORTING_INFO'));
		}else{
			$map['content'] = h( $_POST['content'] );
			$map['reason'] = t( $_POST['reason'] );
			$map['source_url'] = str_replace(SITE_URL,'[SITE_URL]',t($_POST['source_url']));
			$map['ctime'] = time();
			if( $id = model( 'Denounce' )->add( $map ) ){
                //添加積分
                model('Credit')->setUserCredit($_POST['uid'],'report_weibo');
                model('Credit')->setUserCredit($_POST['fuid'],'reported_weibo');

                $touid = D('user_group_link')->where('user_group_id=1')->field('uid')->findAll();
                foreach($touid as $k=>$v){
                    model('Notify')->sendNotify($v['uid'], 'denouce_audit');
                }
				$return = array('status'=>1,'data'=>'您已經成功舉報此資訊');
			}else{
				$return = array('status'=>0,'data'=>L('PUBLIC_REPORT_ERROR'));
			}
		}
        exit(json_encode($return));
    }
}