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

    /******************************************************************************************************
     * 게시판의 정보를 가져옵니다.
     *****************************************************************************************************/
    function get($brd_key, $get_raw_data = FALSE) {
        return $get_raw_data ? $this->_get_board_raw($brd_key) : $this->_get_board_mixed($brd_key);
    }

    /******************************************************************************************************
     * 가공되지 않은 게시판 정보를 가져옵니다.
     *****************************************************************************************************/
    private function _get_board_raw($brd_key = "")
    {
        if(empty($brd_key)) return array();

        // 캐시된 데이타가 없으면 DB에서 새로 가져옴
        if( ! $board = $this->CI->cache->get('board_raw_'.$brd_key) )
        {
            $result =
                $this->CI->db
                    ->from('board')
                    ->where('brd_key', $brd_key)
                    ->limit(1)
                    ->get();

            $board = $result->row_array();

            // 개발환경에선 테스트를 위해 CACHE 저장을 하지 않는다.
            if( ENVIRONMENT !== 'development') {
                $this->CI->cache->save('board_raw_'.$brd_key, $board, 60*5);
            }
        }

        return $board;
    }

    /******************************************************************************************************
     * 가공된 게시판 정보를 가져옵니다.
     *****************************************************************************************************/
    private function _get_board_mixed($brd_key ="")
    {
        if( empty($brd_key) ) return array();

        // 캐시된 데이타가 없으면 DB에서 새로 가져옴
        if( ! $board = $this->CI->cache->get('board_'.$brd_key) ) {

            $board = $this->_get_board_raw($brd_key);

            $board['category'] = explode(";", rtrim($board['brd_category'],';'));

            if(count($board['category']) > 0) {
                foreach($board['category'] as &$row)
                {
                    $row = trim($row);
                }
            }


            // 개발환경에선 테스트를 위해 CACHE 저장을 하지 않는다.
            if( ENVIRONMENT !== 'development') {
                $this->cache->save('board_'.$brd_key, $board, 60*5);
            }

        }

        return $board;
    }

    /******************************************************************************************************
     * 특정 게시판의 캐시 데이타를 삭제한다.
     *****************************************************************************************************/
    function delete_cache($brd_key)
    {
        $this->CI->cache->delete('board_raw_'.$brd_key);
        $this->CI->cache->delete('board_'.$brd_key);
    }

    /******************************************************************************************************
     * 특정 게시판의 캐시 데이타를 삭제한다.
     *****************************************************************************************************/
    function common_data($brd_key)
    {
        $this->CI->data['board'] = $this->get($brd_key, FALSE);

        if( empty($this->CI->data['board']) OR ! isset($this->CI->data['board']))
        {
            alert('존재하지 않는 게시판 또는 삭제된 게시판입니다.');
            exit;
        }

        $this->CI->data['use_secret'] = ($this->CI->data['board']['brd_use_secret'] == 'Y' OR $this->CI->data['board']['brd_use_secret'] == 'A');
        $this->CI->data['use_notice'] = PAGE_ADMIN OR $this->CI->member->is_super();
        $this->CI->data['use_category'] = (($this->CI->data['board']['brd_use_category'] == 'Y') && (count($this->CI->data['board']['category']) > 0));

        $this->CI->data['board']['brd_skin_l'] = ($this->CI->site->viewmode == DEVICE_MOBILE) ? $this->CI->data['board']['brd_skin_l_m'] : $this->CI->data['board']['brd_skin_l'];
        $this->CI->data['board']['brd_skin_w'] = ($this->CI->site->viewmode == DEVICE_MOBILE) ? $this->CI->data['board']['brd_skin_w_m'] : $this->CI->data['board']['brd_skin_w'];
        $this->CI->data['board']['brd_skin_c'] = ($this->CI->site->viewmode == DEVICE_MOBILE) ? $this->CI->data['board']['brd_skin_c_m'] : $this->CI->data['board']['brd_skin_c'];
        $this->CI->data['board']['brd_skin_v'] = ($this->CI->site->viewmode == DEVICE_MOBILE) ? $this->CI->data['board']['brd_skin_v_m'] : $this->CI->data['board']['brd_skin_v'];
        $this->CI->data['board']['brd_page_rows'] = ($this->CI->site->viewmode == DEVICE_MOBILE) ? $this->CI->data['board']['brd_page_rows_m'] : $this->CI->data['board']['brd_page_rows'];
        $this->CI->data['board']['brd_fixed_num'] = ($this->CI->site->viewmode == DEVICE_MOBILE) ? $this->CI->data['board']['brd_fixed_num_m'] : $this->CI->data['board']['brd_fixed_num'];

        unset($this->CI->data['board']['brd_skin_m'], $this->CI->data['board']['brd_page_rows_m'], $this->CI->data['board']['brd_fixed_num_m']);

        // 카테고리 목록
        $this->CI->data['category_list'] = ( $this->CI->data['use_category'] && count($this->CI->data['board']['category']) > 0 ) ? $this->CI->data['board']['category'] : NULL;

        // GET 데이타 정리
        $this->CI->param['page'] = $this->CI->data['page'] = (int)$this->CI->input->get('page', TRUE, 1) >= 1 ? $this->CI->input->get('page', TRUE, 1) : 1;
        $this->CI->param['scol'] = $this->CI->data['scol'] = $this->CI->input->get('scol', TRUE, '');
        $this->CI->param['stxt'] = $this->CI->data['stxt'] = $this->CI->input->get('stxt', TRUE, '');
        $this->CI->param['category'] = $this->CI->data['category'] = $this->CI->input->get('category', TRUE, '');

        // 링크 주소 정리
        $queryParam = array();
        foreach($this->CI->param as $key=>$val) if(! empty($val)) $queryParam[$key] = $val;

        $param = ( $queryParam && is_array($queryParam) ) ? '?'.http_build_query($queryParam): '';

        $this->CI->data['board']['link']['base_url'] = PAGE_ADMIN ? base_url("admin/board/posts/{$brd_key}") : base_url("board/{$brd_key}");
        $this->CI->data['board']['link']['list'] =  PAGE_ADMIN ? base_url("admin/board/posts/{$brd_key}{$param}") : base_url("board/{$brd_key}$param");
        $this->CI->data['board']['link']['write'] = PAGE_ADMIN ? base_url("admin/board/write/{$brd_key}/{$param}") : base_url( "board/{$brd_key}/write{$param}");
        $this->CI->data['board']['link']['rss'] = base_url("rss/{$brd_key}");

        if(! empty($post_idx))
        {
            $this->CI->data['board']['link']['modify'] = PAGE_ADMIN ? base_url( "admin/board/write/{$brd_key}/{$post_idx}{$param}" ) :  base_url( "board/{$brd_key}/write/{$post_idx}{$param}");
            $this->CI->data['board']['link']['delete'] = base_url( "board/{$brd_key}/delete/{$post_idx}".$param );
            $this->CI->data['board']['link']['reply'] = base_url( "board/{$brd_key}/reply/{$post_idx}". $param );
        }

        $this->CI->data['board']['auth']['admin'] = $this->CI->member->is_super();
        $this->CI->data['board']['auth']['read'] = (  $this->CI->data['board']['auth']['admin']  OR ($this->member->level() >= $this->CI->data['board']['brd_lv_read']) );
        $this->CI->data['board']['auth']['list'] = (  $this->CI->data['board']['auth']['admin']  OR ($this->member->level() >= $this->CI->data['board']['brd_lv_list']) );
        $this->CI->data['board']['auth']['write'] = (  $this->CI->data['board']['auth']['admin']  OR ($this->member->level() >= $this->CI->data['board']['brd_lv_write']) );
        $this->CI->data['board']['auth']['download'] = ( $this->CI->data['board']['auth']['admin'] OR ($this->member->level() >= $this->CI->data['board']['brd_lv_download']) );
        $this->CI->data['board']['auth']['comment'] = ( $this->CI->data['board']['auth']['admin'] OR ($this->member->level() >= $this->CI->data['board']['brd_lv_comment']) );
        $this->CI->data['board']['auth']['reply'] = ( $this->CI->data['board']['auth']['admin'] OR ($this->member->level() >= $this->CI->data['board']['brd_lv_reply']) );
    }

    /**
     * 게시글 목록을 가져온다.
     * @param $board
     * @param array $param
     * @return mixed
     */
    function post_list($board, $param)
    {
        $this->CI->db
            ->select('SQL_CALC_FOUND_ROWS P.*, IF(ISNULL(M.mem_nickname), P.post_nickname, M.mem_nickname) AS post_nickname', FALSE)
            ->from('board_post AS P')
            ->join("member AS M", "M.mem_idx=P.upd_user","left")
            ->where('brd_key', $board['brd_key'])
            ->where_in('post_status', array('Y'))
            ->where('post_notice', 'Y')
            ->order_by("post_num DESC, post_reply ASC, post_idx ASC");

        $result= $this->CI->db->get();
        $notice_list['list'] = $result->result_array();

        $this->CI->db
            ->select('SQL_CALC_FOUND_ROWS P.*, IF(ISNULL(M.mem_nickname), P.post_nickname, M.mem_nickname) AS post_nickname', FALSE)
            ->from('board_post AS P')
            ->join("member AS M", "M.mem_idx=P.upd_user","left")
            ->where('brd_key', $board['brd_key'])
            ->where_in('post_status', array('Y','B'))
            ->where('post_notice', 'N')
            ->order_by("post_num DESC, post_reply ASC, post_idx ASC");

        $start = 0;
        if( $board['brd_page_limit'] == 'Y' ) {
            $page_rows = $board['brd_page_rows'];
            $page = element('page', $param, 1);
            $start = ($page - 1) * $page_rows;
            $this->CI->db->limit($page_rows, $start);
        }

        if( element('category', $param) ) {
            $this->CI->db->where('post_category', $param['category']);
        }

        if( element('scol', $param) && element('stxt', $param) )
        {
            if( $param['scol'] == 'title' ) {
                $this->CI->db->like('post_title', $param['stxt']);
            }
            else if ( $param['scol'] == 'nickname' )
            {
                $this->CI->db->like('post_nickname', $param['stxt']);
            }
        }

        $list['list'] = $this->CI->db->get()->result_array();
        $list['total_count'] = (int)$this->CI->db->query("SELECT FOUND_ROWS() AS cnt")->row(0)->cnt;

        foreach($list['list'] as $i=>&$row)
        {
            $row['nums'] = $list['total_count'] - $i - $start;
        }

        $list['list'] = array_merge($notice_list['list'], $list['list']);
        $query_param = $this->get_param();

        foreach($list['list'] as &$row)
        {
            $row = $this->post_process($board, $row, $query_param);
        }

        return $list;
    }


    /**
     * 게시글 하나를 가져온다.
     * @param $brd_key
     * @param $post_idx
     * @param bool $get_raw_data
     * @return mixed
     */
    function get_post($brd_key, $post_idx, $get_raw_data=FALSE)
    {
        $this->CI->db
            ->select('P.*, IF(ISNULL(M.mem_nickname), P.post_nickname, M.mem_nickname) AS post_nickname')
            ->from('board_post AS P')
            ->join("member AS M", "M.mem_idx=P.upd_user","left")
            ->where('post_idx', $post_idx)
            ->where_in('post_status', array('Y','N'))
            ->where('brd_key', $brd_key);

        $result =$this->CI->db->get();

        if(! $post = $result->row_array() )
        {
            return NULL;
        }

        if( ! $get_raw_data ) {
            $board = $this->get($brd_key, FALSE);
            $post = $this->post_process($board, $post, $this->CI->param, TRUE);

            $np = $this->get_np($brd_key, $post_idx, $post['post_num'], $post['post_reply']);
            $post['prev'] = ( isset($np['prev']) && isset($np['prev']['post_idx']) )?  $np['prev'] : NULL;
            $post['next'] = ( isset($np['next']) && isset($np['next']['post_idx']) )?  $np['next'] : NULL;
        }

        return $post;
    }

    /**
     * 패러미터 정보를 정리해서 가져옴
     * @return string
     */
    function get_param()
    {
        // 링크를 위한 자료정리
        $queryParam = array();
        if( $this->CI->input->get('category', TRUE) ) $queryParam['category'] = $this->CI->input->get('category', TRUE);
        if( $this->CI->input->get('page', TRUE) )  $queryParam['page'] = $this->CI->input->get('page', TRUE);
        if( $this->CI->input->get('scol', TRUE) )  $queryParam['scol'] = $this->CI->input->get('scol', TRUE);
        if( $this->CI->input->get('stxt', TRUE) )  $queryParam['stxt'] = $this->CI->input->get('stxt', TRUE);

        $param = "";
        if( $queryParam && is_array($queryParam) )
        {
            $param = http_build_query($queryParam);
            $param = "?".  $param;
        }
        return $param;
    }

    /**
     * 게시글의 내용을 정리한다.
     * @param $board
     * @param $post
     * @param $param
     * @param bool $extra
     * @param bool $files
     * @return mixed
     */
    function post_process($board, $post, $param, $files=FALSE)
    {
        if(is_array($param))
        {
            $param = '?' . http_build_query($param);
        }

        $post['post_notice'] = ($post['post_notice']=='Y');
        $post['link'] = PAGE_ADMIN ? base_url("admin/board/read/{$board['brd_key']}/{$post['post_idx']}{$param}") : base_url("board/{$board['brd_key']}/{$post['post_idx']}{$param}");
        $post['link_modify'] = PAGE_ADMIN ?  base_url("admin/board/write/{$board['brd_key']}/{$post['post_idx']}") : base_url("board/{$board['brd_key']}/write/{$post['post_idx']}");
        $post['link_delete'] = base_url("board/{$board['brd_key']}/delete/{$post['post_idx']}");
        $post['is_new'] = ((time() - strtotime($post['reg_datetime']) ) <= (24 * 60 * 60));
        $post['is_hot'] = ($post['post_hit'] >= 300) ;
        $post['is_secret'] = ($post['post_secret'] == 'Y');
        $post['post_datetime'] = display_datetime($post['reg_datetime'], $board['brd_display_time']);

        if( $files) {
            $post['file'] = $this->get_attach_list($board['brd_key'], $post['post_idx']);
        }

        return $post;
    }

    /**
     * 해당 게시글의 이전글과 다음글을 가져온다.
     * @param $brd_key
     * @param $post_idx
     * @param $post_num
     * @param $post_depth
     * @return mixed
     */
    function get_np($brd_key, $post_idx,$post_num,$post_reply)
    {
        $param = $this->get_param();

        $this->CI->db->group_start();
        $this->CI->db->or_group_start();
        $this->CI->db->where("post_num =", (int)$post_num);
        $this->CI->db->where('post_reply >', $post_reply);
        $this->CI->db->where('post_idx >', $post_idx);
        $this->CI->db->group_end();
        $this->CI->db->or_group_start();
        $this->CI->db->where('post_num <', $post_num);
        $this->CI->db->group_end();
        $this->CI->db->group_end();

        // 이전글 가져오기
        $return['prev'] = $this->CI->db->where_in("post_status", array("Y","B"))
            ->where('post_notice', "N")
            ->where("brd_key", $brd_key)
            ->where('post_idx !=', $post_idx)
            ->limit(1)
            ->order_by("post_num DESC, post_reply ASC, post_idx ASC")
            ->get("board_post")
            ->row_array();

        if(isset($return['prev']['post_idx']))
        {
            $return['prev']['link'] = PAGE_ADMIN ? base_url("admin/board/read/{$brd_key}/{$return['prev']['post_idx']}{$param}") : base_url("board/{$brd_key}/{$return['prev']['post_idx']}{$param}");
        }

        $this->CI->db->group_start();
        $this->CI->db->or_group_start();
        $this->CI->db->where("post_num =", (int)$post_num);
        $this->CI->db->where('post_reply <', $post_reply);
        $this->CI->db->where('post_idx <', $post_idx);
        $this->CI->db->group_end();
        $this->CI->db->or_group_start();
        $this->CI->db->where('post_num >', $post_num);
        $this->CI->db->group_end();
        $this->CI->db->group_end();

        // 다음글 가져오기
        $return['next']  =
            $this->CI->db->where_in("post_status", array("Y","B"))
                ->where('post_notice', "N")
                ->where("brd_key", $brd_key)
                ->where('post_idx !=', $post_idx)
                ->limit(1)
                ->order_by("post_num ASC, post_reply DESC, post_idx DESC")
                ->get("board_post")->row_array();

        if(isset($return['next']['post_idx']))
        {
            $return['next']['link'] = PAGE_ADMIN ? base_url("admin/board/read/{$brd_key}/{$return['next']['post_idx']}{$param}") : base_url("board/{$brd_key}/{$return['next']['post_idx']}{$param}");
        }

        return $return;
    }

    /**
     * 게시글에 포함된 첨부파일 목록을 가져온다.
     * @param $brd_key
     * @param $post_idx
     * @return array
     */
    function get_attach_list($brd_key, $post_idx)
    {
        if(empty($brd_key) OR empty($post_idx)) return array();
        $file_list =  $this->CI->db->where('att_target_type', 'BOARD')->where('att_target', $post_idx)->get('attach')->result_array();
        foreach($file_list as &$f)
        {
            $f['link'] = base_url("board/{$brd_key}/download/{$post_idx}/{$f['att_idx']}");
        }
        return $file_list;
    }


    /**
     * 댓글 목록 가져오기
     * @param $brd_key
     * @param $post_idx
     */
    function comment_list($brd_key, $post_idx, $board_admin=FALSE, $mem_userid="")
    {
        $board = $this->get($brd_key, TRUE);

        $this->CI->db
            ->select('SQL_CALC_FOUND_ROWS C.*, IF(ISNULL(C.cmt_nickname), M.mem_nickname, C.cmt_nickname) AS cmt_nickname', FALSE)
            ->from('board_comment AS C')
            ->join('member AS M','M.mem_idx=C.reg_user','left')
            ->where('brd_key', $brd_key)
            ->where('post_idx', $post_idx)
            ->where_in('cmt_status', array('Y','B'))
            ->order_by("cmt_num DESC,cmt_reply ASC, cmt_idx ASC");

        $list['list'] = $this->CI->db->get()->result_array();
        $list['total_count'] = (int)$this->CI->db->query('SELECT FOUND_ROWS() AS cnt')->row(0)->cnt;

        foreach($list['list'] as &$row)
        {
            $row['cmt_datetime'] = display_datetime( $row['reg_datetime'], $board['brd_display_time']);
            $row['link']['delete'] = base_url("board/{$brd_key}/comment/{$post_idx}/{$row['cmt_idx']}/delete");
            $row['link']['blind'] = base_url("board/{$brd_key}/comment/{$post_idx}/{$row['cmt_idx']}/blind");
            $row['auth'] = $board_admin || ( $row['reg_user'] >0 && $mem_userid == $row['reg_user'] ) || $row['reg_user']==0;
            $row['ip'] = display_ipaddress(long2ip($row['cmt_ip']), '1001');
        }

        return $list;
    }

}