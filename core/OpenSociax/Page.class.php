<?php
/**
 * 分頁顯示類
 * @category   ORG
 * @package  ORG
 * @subpackage  Util
 * @author    liu21st <liu21st@gmail.com>
 * @version   $Id$
 */
class Page
{//類定義開始

    /**
     * 分頁起始行數
     * @var integer
     * @access protected
     */
    public $firstRow ;

    /**
     * 列表每頁顯示行數
     * @var integer
     * @access protected
     */
    public $listRows ;

    /**
     * 頁數跳轉時要帶的參數
     * @var integer
     * @access protected
     */
    public $parameter  ;

    /**
     * 分頁總頁面數
     * @var integer
     * @access protected
     */
    public $totalPages  ;

    /**
     * 總行數
     * @var integer
     * @access protected
     */
    public $totalRows  ;

    /**
     * 當前頁數
     * @var integer
     * @access protected
     */
    public $nowPage    ;

    /**
     * 分頁的欄的總頁數
     * @var integer
     * @access protected
     */
    public $coolPages   ;

    /**
     * 分頁欄每頁顯示的頁數
     * @var integer
     * @access protected
     */
    public $rollPage   ;

    /**
     * 分頁記錄名稱
     * @var integer
     * @access protected
     */

    // 分頁顯示定製
    public $config   =   array('header'=>'條記錄','prev'=>'上一頁','next'=>'下一頁','first'=>'第一頁','last'=>'最後一頁');

    /**
     * 架構函數
     * @access public
     * @param array $totalRows  總的記錄數
     * @param array $firstRow  起始記錄位置
     * @param array $listRows  每頁顯示記錄數
     * @param array $parameter  分頁跳轉的參數
     */
    public function __construct($totalRows,$listRows='',$parameter='')
    {
        $this->totalRows = $totalRows;
        $this->parameter = $parameter;
        $this->rollPage = 5;
        $this->listRows = !empty($listRows)?$listRows:20;
        $this->totalPages = ceil($this->totalRows/$this->listRows);     //總頁數
        $this->coolPages  = ceil($this->totalPages/$this->rollPage);

        if( (!empty($this->totalPages) && $_REQUEST[C('VAR_PAGE')]>$this->totalPages) || $_REQUEST[C('VAR_PAGE')]==='last' ) {
            $this->nowPage = $this->totalPages;
            $_REQUEST[C('VAR_PAGE')] = $this->totalPages;
        }else{
            $this->nowPage  = intval($_REQUEST[C('VAR_PAGE')])>0?intval($_REQUEST[C('VAR_PAGE')]):1;
        }

        $this->firstRow = $this->listRows*($this->nowPage-1);

    }

    public function setConfig($name,$value) {
        if(isset($this->config[$name])) {
            $this->config[$name]    =   $value;
        }
    }

    /**
     * 分頁顯示
     * 用於在頁面顯示的分頁欄的輸出
     * @access public
     * @return string
     */
    public function show($isArray=false){

        if(APP_NAME == 'wap' || APP_NAME == 'WAP'){
            return $this->wapShow($isArray);
        }

        if(0 == $this->totalRows) return;

        //$url  =   eregi_replace("(#.+$|p=[0-9]+)",'',$_SERVER['REQUEST_URI']);
        $url  =   eregi_replace("(#.+$|p=[0-9]+)",'',$_SERVER['PHP_SELF'] .'?'.$_SERVER['QUERY_STRING']);
        $url    =   $url.(strpos($url,'?')?'':"?");
        $url    =   eregi_replace("(&+)",'&',$url);
        $url    =   trim($url,'&');

        //上下翻頁字元串
        $upRow   = $this->nowPage-1;
        $downRow = $this->nowPage+1;
        if ($upRow>0){
            $upPage="<a href='".$url."&".C('VAR_PAGE')."=$upRow' class='pre'>".$this->config['prev']."</a>";
        }else{
            $upPage="";
        }

        if ($downRow <= $this->totalPages){
            $downPage="<a href='".$url."&".C('VAR_PAGE')."=$downRow' class='next'>".$this->config['next']."</a>";
        }else{
            $downPage="";
        }

        // 1 2 [3] 4 5
        $linkPage = "";
        //dump(ceil($this->rollPage/2)-1);
        $halfRoll   =   ceil($this->rollPage/2);

        if( $this->totalPages <= $this->rollPage ){
            $leftPages  =   $this->nowPage-1;
            $rightPages =   $this->totalPages-$leftPages-1;
        }elseif( ($this->nowPage < $halfRoll) && ($this->totalPages > $this->rollPage) ){
            $leftPages  =   $this->nowPage-1;
            $rightPages =   $this->rollPage-$leftPages-1;
        }elseif( ($this->totalPages-$this->nowPage) < $halfRoll ){
            $rightPages =   $this->totalPages-$this->nowPage;
            $leftPages  =   $this->rollPage-$rightPages-1;
        }else{
            $rightPages =   $this->rollPage-$halfRoll;
            $leftPages  =   $this->rollPage-$rightPages-1;
        }

        if($leftPages>0){
            for($i=$this->nowPage-$leftPages;$i<$this->nowPage;$i++){
                $linkPage .= "<a href='".$url."&".C('VAR_PAGE')."=$i'>".$i."</a>";
            }
        }
        $linkPage .= " <a class='current'>".$this->nowPage."</a>";
        if($rightPages>0){
            for($i=$this->nowPage+1;$i<=$this->nowPage+$rightPages;$i++){
                $linkPage .= "<a href='".$url."&".C('VAR_PAGE')."=$i'>".$i."</a>";
            }
        }
        // << < > >>
        if( $this->nowPage <= $halfRoll || $this->totalPages <= $this->rollPage ){
            $theFirst = "";
            $prePage = "";
        }else{
            $preRow =  $this->nowPage-$this->rollPage;
            $prePage = "<a href='".$url."&".C('VAR_PAGE')."=$preRow' >上".$this->rollPage."頁</a>";
            $theFirst = "<a href='".$url."&".C('VAR_PAGE')."=1' >1..</a>";
        }

        if( ($this->totalPages-$this->nowPage) < $halfRoll || $this->totalPages <= $this->rollPage ){
            $nextPage = "";
            $theEnd="";
        }else{
            $nextRow = $this->nowPage+$this->rollPage;
            $theEndRow = $this->totalPages;
            $nextPage = "<a href='".$url."&".C('VAR_PAGE')."=$nextRow' >下".$this->rollPage."頁</a>";
            $theEnd = "<a href='".$url."&".C('VAR_PAGE')."=$theEndRow' >..{$theEndRow}</a>";
        }

        if( ( $this->totalPages+1 - $halfRoll ) == $this->nowPage || $this->totalPages == $this->nowPage ){
            $theEnd = "";
        }

        //$pageStr = $upPage.$downPage.$theFirst.$prePage.$linkPage.$nextPage.$theEnd;
        if( $this->totalPages > 1 )
            $pageStr = $upPage.$theFirst.$linkPage.$theEnd.$downPage;
        if($isArray) {
            $pageArray['totalRows'] =   $this->totalRows;
            $pageArray['upPage']    =   $url.'&'.C('VAR_PAGE')."=$upRow";
            $pageArray['downPage']  =   $url.'&'.C('VAR_PAGE')."=$downRow";
            $pageArray['totalPages']=   $this->totalPages;
            $pageArray['firstPage'] =   $url.'&'.C('VAR_PAGE')."=1";
            $pageArray['endPage']   =   $url.'&'.C('VAR_PAGE')."=$theEndRow";
            $pageArray['nextPages'] =   $url.'&'.C('VAR_PAGE')."=$nextRow";
            $pageArray['prePages']  =   $url.'&'.C('VAR_PAGE')."=$preRow";
            $pageArray['linkPages'] =   $linkPage;
            $pageArray['nowPage'] =   $this->nowPage;
            return $pageArray;
        }
        return $pageStr;
    }

    /**
     * 手機端分頁顯示
     * 用於在頁面顯示的分頁欄的輸出
     * @access public
     * @return string
     */
    public function wapShow($isArray=false){

        if(0 == $this->totalRows) return;

        $url    =   eregi_replace("(#.+$|p=[0-9]+)",'',$_SERVER['REQUEST_URI']);
        $url    =   $url.(strpos($url,'?')?'':"?");
        $url    =   eregi_replace("(&+)",'&',$url);
        $url    =   trim($url,'&');

        //上下翻頁字元串
        $upRow   = $this->nowPage-1;
        $downRow = $this->nowPage+1;
        if ($upRow>0){
            $upPage="<a href='".$url."&".C('VAR_PAGE')."=$upRow' class='pre'>".$this->config['prev']."</a>";
        }else{
            $upPage="";
        }

        if ($downRow <= $this->totalPages){
            $downPage="<a href='".$url."&".C('VAR_PAGE')."=$downRow' class='next'>".$this->config['next']."</a>";
        }else{
            $downPage="";
        }

        $linkPage = "";
        $halfRoll   =   ceil($this->rollPage/2);

        if( $this->totalPages <= $this->rollPage ){
            $leftPages  =   $this->nowPage-1;
            $rightPages =   $this->totalPages-$leftPages-1;
        }elseif( ($this->nowPage < $halfRoll) && ($this->totalPages > $this->rollPage) ){
            $leftPages  =   $this->nowPage-1;
            $rightPages =   $this->rollPage-$leftPages-1;
        }elseif( ($this->totalPages-$this->nowPage) < $halfRoll ){
            $rightPages =   $this->totalPages-$this->nowPage;
            $leftPages  =   $this->rollPage-$rightPages-1;
        }else{
            $rightPages =   $this->rollPage-$halfRoll;
            $leftPages  =   $this->rollPage-$rightPages-1;
        }

        if($leftPages>0){
            for($i=$this->nowPage-$leftPages;$i<$this->nowPage;$i++){
                $linkPage .= "<a href='".$url."&".C('VAR_PAGE')."=$i'>".$i."</a>";
        }
        }
        $linkPage .= " <a class='current'>".$this->nowPage."</a>";
        if($rightPages>0){
            for($i=$this->nowPage+1;$i<=$this->nowPage+$rightPages;$i++){
                $linkPage .= "<a href='".$url."&".C('VAR_PAGE')."=$i'>".$i."</a>";
        }
        }
        // << < > >>
        if( $this->nowPage <= $halfRoll || $this->totalPages <= $this->rollPage ){
            $theFirst = "";
            $prePage = "";
        }else{
            $preRow =  $this->nowPage-$this->rollPage;
            $prePage = "<a href='".$url."&".C('VAR_PAGE')."=$preRow' class='pre'>上".$this->rollPage."頁</a>";
            $theFirst = "<a href='".$url."&".C('VAR_PAGE')."=1' >1..</a>";
        }

        if( ($this->totalPages-$this->nowPage) < $halfRoll || $this->totalPages <= $this->rollPage ){
            $nextPage = "";
            $theEnd="";
        }else{
            $nextRow = $this->nowPage+$this->rollPage;
            $theEndRow = $this->totalPages;
            $nextPage = "<a href='".$url."&".C('VAR_PAGE')."=$nextRow' class='next'>下".$this->rollPage."頁</a>";
            $theEnd = "<a href='".$url."&".C('VAR_PAGE')."=$theEndRow' >..{$theEndRow}</a>";
        }

        if( ( $this->totalPages+1 - $halfRoll ) == $this->nowPage || $this->totalPages == $this->nowPage ){
            $theEnd = "";
        }

        //dump($_SERVER);
        if( $this->totalPages > 1 ){
            //$pageStr = '<div class="L">'.$upPage.'&nbsp;'.$downPage.'&nbsp;'.$this->nowPage.'/'.$this->totalPages.'頁</div>';

            $nowUrl  =  SITE_URL.'/index.php?'.$_SERVER['QUERY_STRING'];
            $nowUrl  =  preg_replace("/&p=[0-9]*/",'',$nowUrl);

            $pageStr = '<form method="post" action="'.$nowUrl.'"><span>'.$upPage.'&nbsp;'.$downPage.'&nbsp;'.$this->nowPage.'/'.$this->totalPages.'頁</span>';
            $pageStr .= '<input type="text" style="margin-left:8px;width:40px"  name="p" id="p" value="'.$this->nowPage.'">';
            $pageStr .= '<input type="submit" value="轉至"/>
                </form>';
        }

        if($isArray) {
            $pageArray['totalRows'] =   $this->totalRows;
            $pageArray['upPage']    =   $url.'&'.C('VAR_PAGE')."=$upRow";
            $pageArray['downPage']  =   $url.'&'.C('VAR_PAGE')."=$downRow";
            $pageArray['totalPages']=   $this->totalPages;
            $pageArray['firstPage'] =   $url.'&'.C('VAR_PAGE')."=1";
            $pageArray['endPage']   =   $url.'&'.C('VAR_PAGE')."=$theEndRow";
            $pageArray['nextPages'] =   $url.'&'.C('VAR_PAGE')."=$nextRow";
            $pageArray['prePages']  =   $url.'&'.C('VAR_PAGE')."=$preRow";
            $pageArray['linkPages'] =   $linkPage;
            $pageArray['nowPage'] =   $this->nowPage;
            return $pageArray;
        }
        return $pageStr;
        }
        }//類定義結束
?>
