<?php
/**
 * 勳章列表前臺展示
 * @author Stream
 *
 */
class MedalListWidget extends Widget{
	public function render($data) {
		if ( !CheckTaskSwitch() ){
			return;
		}
		// TODO Auto-generated method stub
		$map['uid'] = $data['uid'] ? $data['uid'] : $GLOBALS['ts']['mid'];
		$user_info = model('User')->getUserInfo( $map['uid'] );
		$medals = $user_info['medals'];
		if ( !$medals ){
			return;
		}
		$medalids = getSubByKey( $medals , 'id' );
		$map['medal_id'] = array( 'in' , $medalids );
		//加入快取 如果勳章數目有變化的話 重新獲取在快取
		$key = 'medal_user_'.$map['uid'].'_'.count( $medalids );
		$usermedal = model( 'Cache' )->get( $key );
		if ( !$usermedal ){
			$umedal = D('medal_user')->where($map)->field('medal_id,`desc`,ctime')->findAll();
			$usermedal = array();
			foreach ( $umedal as $u ){
				$usermedal[$u['medal_id']]['desc'] = $u['desc'];
				$usermedal[$u['medal_id']]['ctime'] = $u['ctime'];
			}
			model( 'Cache' )->set( $key , $usermedal );
		}
		foreach ($medals as &$m){
			
			$usermedal[$m['id']]['desc'] && $m['desc'] = $usermedal[$m['id']]['desc'];
			$m['ctime'] = date( 'Y-m-d H:i:s',$usermedal[$m['id']]['ctime']);
		}
		$var['medals'] = $medals;
		$var['isme'] = $map['uid'] == $GLOBALS['ts']['mid'] ? true : false;
		$var['uid'] = $map['uid'];
		$content = $this->renderFile(dirname(__FILE__)."/list.html",$var);
		return $content;
	}

	
}
?>