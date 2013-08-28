<?php
/**
  * 評論釋出/顯示框
  * @example W('Comment',array('tpl'=>'detail','row_id'=>72,'order'=>'DESC','app_uid'=>'14983','cancomment'=>1,'cancomment_old'=>0,'showlist'=>1,'canrepost'=>1))
  * @author jason <yangjs17@yeah.net>
  * @version TS3.0
  */
class CommentWidget extends Widget {
	private static $rand = 1;

	/**
	 *
	 * @param
	 *        	string tpl 顯示模版 默認為comment，一般使用detail表示詳細資源頁面的評論
	 * @param
	 *        	integer row_id 評論物件所在的表的ID
	 * @param
	 *        	string order 評論的排序，默認為ASC 表示從早到晚,應用中一般是DESC
	 * @param
	 *        	integer app_uid 評論的物件的作者ID
	 * @param
	 *        	integer cancomment 是否可以評論 默認為1,由應用中判斷好許可權之後傳入給wigdet
	 * @param
	 *        	integer cancomment_old 是否可以評論給原作者 默認為1,應用開發時統一使用0
	 * @param
	 *        	integer showlist 是否顯示評論列表 默認為1
	 * @param
	 *        	integer canrepost 是否允許轉發 默認為1,應用開發的時候根據應用需求設定1、0
	 */
	public function render($data) {
		$var = array ();
		// 默認配置資料
		$var ['cancomment'] = 1; // 是否可以評論
		$var ['canrepost'] = 1; // 是否允許轉發
		$var ['cancomment_old'] = 1; // 是否可以評論給原作者
		$var ['showlist'] = 1; // 默認顯示原評論列表
		$var ['tpl'] = 'Comment'; // 顯示模板
		$var ['app_name'] = 'public';
		$var ['table'] = 'feed';
		$var ['limit'] = 10;
		$var ['order'] = 'DESC';
		$var ['initNums'] = model ( 'Xdata' )->getConfig ( 'weibo_nums', 'feed' );
		$_REQUEST ['p'] = intval ( $_GET ['p'] ) ? intval ( $_GET ['p'] ) : intval ( $_POST ['p'] );
  		if (empty($data)) {
  			$data['app_uid'] = intval($_POST['app_uid']);
  			$data['row_id'] = intval($_POST['row_id']);
  			$data['app_row_id'] = intval($_POST['app_row_id']);
  			$data['app_row_table'] = t($_POST['app_row_table']);
  			$data['isAjax'] = intval($_POST['isAjax']);
  			$data['showlist'] = intval($_POST['showlist']);
  			$data['cancomment'] = intval($_POST['cancomment']);
  			$data['cancomment_old'] = intval($_POST['cancomment_old']);
  			$data['app_name'] = t($_POST['app_name']);
  			$data['table'] = t($_POST['table']);
  			$data['canrepost'] = intval($_POST['canrepost']);
  		}
		// empty ( $data ) && $data = $_POST;
		is_array ( $data ) && $var = array_merge ( $var, $data );
		$var['app_uid'] = intval($var['app_uid']);
		$var['row_id'] = intval($var['row_id']);
		if ($var ['table'] == 'feed' && $this->mid != $var ['app_uid']) {
			if ($this->mid != $var ['app_uid']) {
				$userPrivacy = model ( 'UserPrivacy' )->getPrivacy ( $this->mid, $var ['app_uid'] );
				if ($userPrivacy ['comment_weibo'] == 1) {
					$return = array (
							'status' => 0,
							'data' => L ( 'PUBLIC_CONCENT_TIPES' )
					);
					return $var ['isAjax'] == 1 ? json_encode ( $return ) : $return ['data'];
				}
			}
			// 獲取資源類型
			$sourceInfo = model ( 'Feed' )->get ( $var ['row_id'] );
			$var ['feedtype'] = $sourceInfo ['type'];
			// 獲取源資源作者使用者資訊
			$appRowData = model('Feed')->get(intval($rowData['app_row_id']));
			$var['user_info'] = $appRowData['user_info'];
		}
		if ($var ['showlist'] == 1) { // 默認只取出前10條
			$map = array ();
			$map ['app'] = t ( $var ['app_name'] );
			$map ['table'] = t ( $var ['table'] );
			$map ['row_id'] = intval ( $var ['row_id'] ); // 必須存在
			if (! empty ( $map ['row_id'] )) {
				// 分頁形式資料
				$var ['list'] = model ( 'Comment' )->getCommentList ( $map, 'comment_id ' . t($var ['order']), intval($var ['limit']) );
			}
		} // 渲染模版

		// 轉發許可權判斷
		$weiboSet = model ( 'Xdata' )->get ( 'admin_Config:feed' );
		if (! CheckPermission ( 'core_normal', 'feed_share' ) || ! in_array ( 'repost', $weiboSet ['weibo_premission'] )) {
			$var ['canrepost'] = 0;
		}
		$content = $this->renderFile ( dirname ( __FILE__ ) . "/" . $var ['tpl'] . '.html', $var );
		self::$rand ++;
		$ajax = $var ['isAjax'];
		unset ( $var, $data );
		// 輸出資料
		$return = array (
				'status' => 1,
				'data' => $content
		);

		return $ajax == 1 ? json_encode ( $return ) : $return ['data'];
	}

	/**
	 * 獲取評論列表
	 *
	 * @return array
	 */
	public function getCommentList() {
		$map = array ();
		$map ['app'] = t ( $_POST ['app_name'] );
		$map ['table'] = t ( $_POST ['table'] );
		$map ['row_id'] = intval ( $_POST ['row_id'] ); // 必須存在
		if (! empty ( $map ['row_id'] )) {
			// 分頁形式資料
			$var ['limit'] = 10;
			$var ['order'] = 'DESC';
			$var ['cancomment'] = $_POST ['cancomment'];
			$var ['showlist'] = $_POST ['showlist'];
			$var ['app_name'] = t ( $_POST ['app_name'] );
			$var ['table'] = t ( $_POST ['table'] );
			$var ['row_id'] = intval ( $_POST ['row_id'] );
			$var ['list'] = model ( 'Comment' )->getCommentList ( $map, 'comment_id ' . $var ['order'], $var ['limit'] );
		}
		$content = $this->renderFile ( dirname ( __FILE__ ) . '/commentList.html', $var );
		exit ( $content );
	}

	/**
	 * 添加評論的操作
	 *
	 * @return array 評論添加狀態和提示資訊
	 */
	public function addcomment() {
		// 返回結果集預設值
		$return = array (
				'status' => 0,
				'data' => L ( 'PUBLIC_CONCENT_IS_ERROR' )
		);
		// 獲取接收資料
		$data = $_POST;
		// 安全過濾
		foreach ( $data as $key => $val ) {
			$data [$key] = t ( $data [$key] );
		}
		// 評論所屬與評論內容
		$data ['app'] = $data ['app_name'];
		$data ['table'] = $data ['table_name'];
		$data ['content'] = h ( $data ['content'] );
		// 判斷資源是否被刪除
		$dao = M ( $data ['table'] );
		$idField = $dao->getPk ();
		$map [$idField] = $data ['row_id'];
		$sourceInfo = $dao->where ( $map )->find ();

		if (! $sourceInfo) {
			$return ['status'] = 0;
			$return ['data'] = '內容已被刪除，評論失敗';
			exit ( json_encode ( $return ) );
		}
		//相容舊方法
		if(empty($data['app_detail_summary'])){
			$source = model ( 'Source' )->getSourceInfo ( $data ['table'], $data ['row_id'], false, $data ['app'] );
			$data['app_detail_summary'] = $source['source_body'];
			$data['app_detail_url']     = $source['source_url'];
			$data['app_uid']            = $source['source_user_info']['uid'];
		}else{
			$data['app_detail_summary'] = $data ['app_detail_summary'] . '<a class="ico-details" href="' . $data['app_detail_url'] . '"></a>';
		}
		// 添加評論操作
		$data ['comment_id'] = model ( 'Comment' )->addComment ( $data );
		if ($data ['comment_id']) {
			$return ['status'] = 1;
			$return ['data'] = $this->parseComment ( $data );

			// 同步到微吧
			if ($data ['app'] == 'weiba')
				$this->_upateToweiba ( $data );

			// 去掉回複使用者@
			$lessUids = array ();
			if (! empty ( $data ['to_uid'] )) {
				$lessUids [] = $data ['to_uid'];
			}

			if ($_POST ['ifShareFeed'] == 1) {  // 轉發到我的微博
				//解鎖內容釋出
				//unlockSubmit();
				$this->_updateToweibo ( $data, $sourceInfo, $lessUids );
			} else if ($data ['comment_old'] != 0) {  // 是否評論給原來作者
				//unlockSubmit();
				$this->_updateToComment ( $data, $sourceInfo, $lessUids );
			}
		}
		!$data['comment_id'] && $return['data'] = model('Comment')->getError();
		exit ( json_encode ( $return ) );
	}

	/**
	 * 刪除評論
	 *
	 * @return bool true or false
	 */
	public function delcomment() {
		// if( !CheckPermission('core_normal','comment_del') && !CheckPermission('core_admin','comment_del')){
		// return false;
		// }
		$comment_id = intval ( $_POST ['comment_id'] );
		$comment = model ( 'Comment' )->getCommentInfo ( $comment_id );
		// 不存在時
		if (! $comment) {
			return false;
		}
		// 非作者時
		if ($comment ['uid'] != $this->mid) {
			// 沒有管理許可權不可以刪除
			if (! CheckPermission ( 'core_admin', 'comment_del' )) {
				return false;
			}
			// 是作者時
		} else {
			// 沒有前臺許可權不可以刪除
			if (! CheckPermission ( 'core_normal', 'comment_del' )) {
				return false;
			}
		}

		if (! empty ( $comment_id )) {
			return model ( 'Comment' )->deleteComment ( $comment_id, $this->mid );
		}
		return false;
	}

	/**
	 * 渲染評論頁面 在addcomment方法中呼叫
	 */
	public function parseComment($data) {
		$data ['userInfo'] = model ( 'User' )->getUserInfo ( $GLOBALS ['ts'] ['uid'] );
		// 獲取使用者組資訊
		$data ['userInfo'] ['groupData'] = model ( 'UserGroupLink' )->getUserGroupData ( $GLOBALS ['ts'] ['uid'] );
		$data ['content'] = preg_html ( $data ['content'] );
		$data ['content'] = parse_html ( $data ['content'] );
		$data ['iscommentdel'] = CheckPermission ( 'core_normal', 'comment_del' );
		return $this->renderFile ( dirname ( __FILE__ ) . "/_parseComment.html", $data );
	}

	// 同步到微吧
	function _upateToweiba($data) {
		$postDetail = D ( 'weiba_post' )->where ( 'feed_id=' . $data ['row_id'] )->find ();
		if (! $postDetail)
			return false;

		$datas ['weiba_id'] = $postDetail ['weiba_id'];
		$datas ['post_id'] = $postDetail ['post_id'];
		$datas ['post_uid'] = $postDetail ['post_uid'];
		$datas ['to_reply_id'] = $data ['to_comment_id'] ? D ( 'weiba_reply' )->where ( 'comment_id=' . $data ['to_comment_id'] )->getField ( 'reply_id' ) : 0;
		$datas ['to_uid'] = $data ['to_uid'];
		$datas ['uid'] = $this->mid;
		$datas ['ctime'] = time ();
		$datas ['content'] = $data ['content'];
		$datas ['comment_id'] = $data ['comment_id'];
		$datas ['storey'] = model ( 'comment' )->where ( 'comment_id=' . $data ['comment_id'] )->getField ( 'storey' );
		if (D ( 'weiba_reply' )->add ( $datas )) {
			$map ['last_reply_uid'] = $this->mid;
			$map ['last_reply_time'] = $datas ['ctime'];
			$map ['reply_count'] = array (
					'exp',
					"reply_count+1"
			);
			D ( 'weiba_post' )->where ( 'post_id=' . $datas ['post_id'] )->save ( $map );
		}
	}

	// 轉發到我的微博
	function _updateToweibo($data, $sourceInfo, $lessUids) {
		$commentInfo = model ( 'Source' )->getSourceInfo ( $data ['table'], $data ['row_id'], false, $data ['app'] );
		$oldInfo = isset ( $commentInfo ['sourceInfo'] ) ? $commentInfo ['sourceInfo'] : $commentInfo;

		// 根據評論的物件獲取原來的內容
		$arr = array (
				'post',
				'postimage',
				'postfile',
				'weiba_post',
				'postvideo'
		);
		$scream = '';
		if (! in_array ( $sourceInfo ['type'], $arr )) {
			$scream = '//@' . $commentInfo ['source_user_info'] ['uname'] . '：' . $commentInfo ['source_content'];
		}
		if (! empty ( $data ['to_comment_id'] )) {
			$replyInfo = model ( 'Comment' )->init ( $data ['app'], $data ['table'] )->getCommentInfo ( $data ['to_comment_id'], false );
			$replyScream = '//@' . $replyInfo ['user_info'] ['uname'] . ' ：';
			$data ['content'] .= $replyScream . $replyInfo ['content'];
		}
		$s ['body'] = $data ['content'] . $scream;

		$s ['sid'] = $oldInfo ['source_id'];
		$s ['app_name'] = $oldInfo ['app'];
		$s ['type'] = $oldInfo ['source_table'];
		$s ['comment'] = $data ['comment_old'];
		$s ['comment_touid'] = $data ['app_uid'];

		// 如果為原創微博，不給原創使用者發送@資訊
		if ($sourceInfo ['type'] == 'post' && empty ( $data ['to_uid'] )) {
			$lessUids [] = $this->mid;
		}
		model ( 'Share' )->shareFeed ( $s, 'comment', $lessUids );
		model ( 'Credit' )->setUserCredit ( $this->mid, 'forwarded_weibo' );
	}

	// 評論給原來作者
	function _updateToComment($data, $sourceInfo, $lessUids) {
		$commentInfo = model ( 'Source' )->getSourceInfo ( $data ['app_row_table'], $data ['app_row_id'], false, $data ['app'] );
		$oldInfo = isset ( $commentInfo ['sourceInfo'] ) ? $commentInfo ['sourceInfo'] : $commentInfo;
		// 發表評論
		$c ['app'] = $data ['app'];
		$c ['table'] = 'feed'; // 2013/3/27
		$c ['app_uid'] = ! empty ( $oldInfo ['source_user_info'] ['uid'] ) ? $oldInfo ['source_user_info'] ['uid'] : $oldInfo ['uid'];
		$c ['content'] = $data ['content'];
		$c ['row_id'] = ! empty ( $oldInfo ['sourceInfo'] ) ? $oldInfo ['sourceInfo'] ['source_id'] : $oldInfo ['source_id'];
		if ($data ['app']) {
			$c ['row_id'] = $oldInfo ['feed_id'];
		}
		$c ['client_type'] = getVisitorClient ();

		model ( 'Comment' )->addComment ( $c, false, false, $lessUids );
	}
}
