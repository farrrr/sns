<?php
/**
 * 簽到WIDGET類
 * @author Stream
 *
 */
class CheckInWidget extends Widget{
/* (non-PHPdoc)
 * @see Widget::render()
 */public function render ( $data ) {
	  if ( !CheckTaskSwitch() ){
		return;
	  }
      $uid = $GLOBALS['ts']['mid'];
      
      $data = model( 'Cache' )->get('check_info_'.$uid.'_'.date('Ymd'));
      if ( !$data ){
         $map['uid'] = $uid;
         $map['ctime'] = array ( 'gt' , strtotime( date('Ymd') ) );
         $res = D('check_info')->where($map)->find();
         //是否簽到
         $data['ischeck'] = $res ? true : false;
         
         $checkinfo = D('check_info')->where('uid='.$uid)->order('ctime desc')->limit(1)->find();
         if ( $checkinfo ){
            if ( $checkinfo['ctime'] > (strtotime( date('Ymd') )-86400 ) ){
               $data['con_num'] = $checkinfo['con_num'];
            } else {
               $data['con_num'] = 0;
            }
            $data['total_num'] = $checkinfo['total_num'];
         } else {
            $data['con_num'] = 0;
            $data['total_num'] = 0;
         }
         $data['day'] = date('m.d');
         model( 'Cache' )->set('check_info_'.$uid.'_'.date('Ymd') , $data );
      }

      $data['tpl'] = 'index';
      $week = date('w');
      switch ( $week ){
         case '0':
            $week = '週日';
            break;
         case '1':
            $week = '週一';
            break;
         case '2':
            $week = '週二';
            break;
         case '3':
            $week = '週三';
            break;
         case '4':
            $week = '週四';
            break;
         case '5':
            $week = '週五';
            break;
         case '6':
            $week = '週六';
            break;
      }
      $data['week'] = $week;
      $content = $this->renderFile (dirname(__FILE__)."/".$data['tpl'].'.html', $data );
      return $content;
   }

   /*
    *簽到
    */
   public function check_in(){
         $uid = $GLOBALS['ts']['mid'];
         $map['ctime'] = array ( 'gt' , strtotime( date('Ymd') ) );
         $map['uid'] = $uid;
         $ischeck = D('check_info')->where($map)->find();
         //清理快取
         model( 'Cache' )->set('check_info_'.$uid.'_'.date('Ymd') , null);
         //是否重複簽到
         if ( !$ischeck ){
            $map['ctime'] = array( 'lt' , strtotime( date('Ymd') ) );
            $last = D('check_info')->where($map)->order('ctime desc')->find();
            $data['uid'] = $uid;
            $data['ctime'] = $_SERVER['REQUEST_TIME'];
            //是否有簽到記錄
            if ( $last ){
               //是否是連續簽到
               if ( $last['ctime'] > ( strtotime( date('Ymd') ) - 86400 ) ){
                  $data['con_num'] = $last['con_num'] + 1;
               } else {
                  $data['con_num'] = 1;
               }
               $data['total_num'] = $last['total_num'] + 1;
            } else {
               $data['con_num'] = 1;
               $data['total_num'] = 1;
            }
            if ( D('check_info')->add($data) ){
               //更新連續簽到和累計簽到的資料
               $connum = D('user_data')->where('uid='.$uid." and `key`='check_connum'")->find();
               if ( $connum ){
                  $connum = D('check_info')->where('uid='.$uid)->getField('max(con_num)');
                  D('user_data')->setField('value' , $connum , "`key`='check_connum' and uid=".$uid);
                  D('user_data')->setField('value' , $data['total_num'] , "`key`='check_totalnum' and uid=".$uid);
                  
               } else {
                  $connumdata['uid'] = $uid;
                  $connumdata['value'] = $data['con_num'];
                  $connumdata['key'] = 'check_connum';
                  D('user_data')->add($connumdata);
                  
                  $totalnumdata['uid'] = $uid;
                  $totalnumdata['value'] = $data['total_num'];
                  $totalnumdata['key'] = 'check_totalnum';
                  D('user_data')->add($totalnumdata);
               }
               echo $data['con_num'];
            }
         }
   }
   
   public function update_user_data(){
         $list = D('check_info')->group('uid')->findAll();
         foreach ( $list as $v ){
            $con = D('user_data')->where('uid='.$v['uid']." and `key`='check_connum'")->find();
            
            $connum = D('check_info')->where('uid='.$v['uid'])->getField('max(con_num)');
            $totalnum = D('check_info')->where('uid='.$v['uid'])->getField('max(total_num)');
            if ( !$con ){
               
               $connumdata['uid'] = $v['uid'];
               $connumdata['value'] = $connum;
               $connumdata['key'] = 'check_connum';
               D('user_data')->add($connumdata);
               
               $totalnumdata['uid'] = $v['uid'];
               $totalnumdata['value'] = $totalnum;
               $totalnumdata['key'] = 'check_totalnum';
               D('user_data')->add($totalnumdata);
            } else {
               D('user_data')->setField('value' , $connum , "`key`='check_connum' and uid=".$v['uid']);
               
               D('user_data')->setField('value' , $totalnum , "`key`='check_totalnum' and uid=".$v['uid']);
            }
         }
   }
      
}
?>