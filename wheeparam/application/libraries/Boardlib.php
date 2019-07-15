<?php
/*********************************************************************************************************
 * Class Boardlib
 * ======================================================================================================
 *
 * 게시판용 라이브러리
 *********************************************************************************************************/
class Boardlib {

    protected $CI;

    function __construct()
    {
        $this->CI =& get_instance();
    }

    function initialize($brd_key = "")
    {
        if(empty($brd_key)) return;
    }

    /******************************************************************************************************
     * 새글이 올라온 게시글 목록을 보여줍니다.
     *****************************************************************************************************/
    function getNewPostBoards ()
    {
        $cnt_query =
            $this->CI->db
                ->select('brd_key, COUNT(brd_key) AS new_cnt')
                ->from('board_post')
                ->where('reg_datetime >=', date('Y-m-d H:i:s', strtotime('-1 days')))
                ->where('post_status','Y')
                ->group_by('brd_key')
                ->get_compiled_select();

        $board_list =
            $this->CI->db
                ->select('B.*, IF(ISNULL(BC.new_cnt), 0, new_cnt) AS new_cnt')
                ->from('board AS B')
                ->join("($cnt_query) AS BC", 'BC.brd_key=B.brd_key','left')
                ->get()
                ->result_array();

        return $board_list;
    }
}