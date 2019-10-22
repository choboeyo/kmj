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

        $this->CI->load->driver('cache', array('adapter' => 'apc', 'backup' => 'file', 'key_prefix' => PROJECT));
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
                $this->CI->cache->save('board_'.$brd_key, $board, 60*5);
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
        $this->CI->data['board']['auth']['read'] = (  $this->CI->data['board']['auth']['admin']  OR ($this->CI->member->level() >= $this->CI->data['board']['brd_lv_read']) );
        $this->CI->data['board']['auth']['list'] = (  $this->CI->data['board']['auth']['admin']  OR ($this->CI->member->level() >= $this->CI->data['board']['brd_lv_list']) );
        $this->CI->data['board']['auth']['write'] = (  $this->CI->data['board']['auth']['admin']  OR ($this->CI->member->level() >= $this->CI->data['board']['brd_lv_write']) );
        $this->CI->data['board']['auth']['download'] = ( $this->CI->data['board']['auth']['admin'] OR ($this->CI->member->level() >= $this->CI->data['board']['brd_lv_download']) );
        $this->CI->data['board']['auth']['comment'] = ( $this->CI->data['board']['auth']['admin'] OR ($this->CI->member->level() >= $this->CI->data['board']['brd_lv_comment']) );
        $this->CI->data['board']['auth']['reply'] = ( $this->CI->data['board']['auth']['admin'] OR ($this->CI->member->level() >= $this->CI->data['board']['brd_lv_reply']) );
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
    function comment_list($brd_key, $post_idx, $board_admin=FALSE, $mem_useridx="")
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
            $row['auth'] = $board_admin || ( $row['reg_user'] >0 && $mem_useridx == $row['reg_user'] ) || $row['reg_user']==0;
            $row['ip'] = display_ipaddress(long2ip($row['cmt_ip']), '1001');
        }

        return $list;
    }

    /**
     * 게시글 작성 처리
     */
    function write_process($brd_key, $post_idx="")
    {
        // 수정이나 삭제할경우 권한을 확인한다.
        if(! empty($post_idx)) $this->_check_modify_auth($brd_key, $post_idx);

        $this->CI->load->library('form_validation');

        $this->CI->form_validation->set_rules('post_title', langs('게시판/form/post_title') ,'required|trim');
        $this->CI->form_validation->set_rules('post_content', langs('게시판/form/post_content'),'required|trim');
        if( ! $this->CI->member->is_login() )
        {
            $this->CI->form_validation->set_rules('post_nickname', langs('게시판/form/mem_nickname') ,'required|trim');
            $this->CI->form_validation->set_rules('post_password', langs('게시판/form/password') ,'required|trim|min_length[4]|max_length[16]');
        }

        if( $this->CI->form_validation->run() != FALSE )
        {
            // 수정글이면 기존 글의 정보를 가져온다.
            $post = array();

            if( $post_idx ) {
                $post = $this->get_post($brd_key, $post_idx, FALSE);
            }

            // 비회원이로 리캡쳐 설정이 되있을 경우 구글 리캡챠 확인
            if( ! $this->CI->member->is_login() )
            {
                // 비회원이고 리캡쳐 설정이 되있을 경우 경우 구글 리캡챠확인
                if( $this->CI->site->config('google_recaptcha_site_key') && $this->CI->site->config('google_recaptcha_secret_key') )
                {
                    $this->CI->load->library('google_recaptcha');
                    $response = $this->CI->input->post('g-recaptcha-response', TRUE);

                    if( empty($response) OR ! $this->CI->google_recaptcha->check_response( $response ) )
                    {
                        alert('자동등록 방지 인증에 실패하였습니다.');
                        exit;
                    }
                }
                // 비회원일이고 수정일 경우 입력한 패스워드와 기존 패스워드 확인
                if( $post_idx )
                {
                    if( get_password_hash( $this->CI->input->post('post_password', TRUE) ) != $post['post_password'] )
                    {
                        alert('잘못된 비밀번호 입니다.');
                        exit;
                    }
                }
            }

            // 받아온 값을 정리한다.
            $data['post_title'] = $this->CI->input->post('post_title', TRUE);
            $data['post_category'] = $this->CI->input->post('post_category', TRUE, '');
            $data['post_parent'] = $this->CI->input->post('post_parent', TRUE, 0);
            $data['post_secret'] = $this->CI->input->post('post_secret', TRUE, 'N') == 'Y' ? "Y":'N';
            $data['post_content'] = $this->CI->input->post('post_content', FALSE);
            $data['brd_key'] = $brd_key;
            $data['upd_datetime'] = date('Y-m-d H:i:s');
            $data['upd_user'] = $this->CI->member->is_login();
            $data['post_notice'] = $this->CI->input->post('post_notice', TRUE) == 'Y' ? 'Y' : 'N';
            $data['post_ip'] = ip2long( $this->CI->input->ip_address() );
            $data['post_mobile'] = $this->CI->site->viewmode == DEVICE_MOBILE ? 'Y' : 'N';
            $data['post_keywords'] = $this->CI->input->post('post_keywords', TRUE);
            for($i=1; $i<=9; $i++) $data['post_ext'.$i] = $this->CI->input->post('post_ext'.$i, TRUE,'');

            $parent = array();
            if(! empty( $data['post_parent'] ) )
            {
                $parent = $this->get_post($brd_key, $data['post_parent'], FALSE);
            }

            // 관리자가 아니라면 사용할수 없는 옵션 끄기
            if( ! PAGE_ADMIN && ! $this->CI->member->is_super() )
            {
                $data['post_notice'] = 'N';
            }

            // 익명전용 게시판이라면 게시글도 익명처리
            if($this->CI->input->post('post_annonymous', TRUE) == 'Y' OR $this->CI->data['board']['brd_use_anonymous'] == 'A')
            {
                $data['post_nickname'] = "익명";
            }

            // 로그인 상태에 따라 값을 수정
            if( $this->CI->member->is_login() )
            {
                $data['post_nickname'] = $this->CI->member->info('nickname');
                $data['post_password'] = $this->CI->member->info('password');
            }
            else
            {
                $data['upd_user'] = 0;
                $data['post_nickname'] = $this->CI->input->post('post_nickname', TRUE);
                $data['post_password'] = get_password_hash( $this->CI->input->post('post_password', TRUE) );
            }

            // 게시판 설정을 이용해서 값 정리
            if( $this->CI->data['board']['brd_use_secret'] == 'N' ) $data['post_secret'] = 'N';
            else if ( $this->CI->data['board']['brd_use_secret'] == 'A' ) $data['post_secret'] = 'Y';
            // 답글인경우 원글이 비밀글이면 답글도 비밀글
            else if ( ! empty($data['post_parent']) && $parent['post_secret'] == 'Y' ) $data['post_secret'] = 'Y';


            // 파일 업로드가 있다면
            $upload_array = array();
            if( isset($_FILES) && isset($_FILES['userfile']) && count($_FILES['userfile']) > 0 )
            {
                $dir_path = DIR_UPLOAD . "/board/{$brd_key}/".date('Y')."/".date('m');
                make_dir($dir_path,FALSE);

                $upload_config['upload_path'] = "./".$dir_path;
                $upload_config['file_ext_tolower'] = TRUE;
                $upload_config['allowed_types'] = FILE_UPLOAD_ALLOW;
                $upload_config['encrypt_name'] = TRUE;

                $this->CI->load->library("upload", $upload_config);

                // FOR문으로 업로드하기 위해 돌리기
                $files = NULL;
                foreach ($_FILES['userfile'] as $key => $value) {
                    foreach ($value as $noKey => $noValue) {
                        $files[$noKey][$key] = $noValue;
                    }
                }
                unset($_FILES);

                // FOR 문 돌면서 정리
                foreach ($files as $file) {
                    $_FILES['userfile'] = $file;
                    $this->CI->upload->initialize($upload_config);
                    if( ! isset($_FILES['userfile']['tmp_name']) OR ! $_FILES['userfile']['tmp_name']) continue;
                    if (! $this->CI->upload->do_upload('userfile') )
                    {
                        alert('파일 업로드에 실패하였습니다.\\n'.$this->CI->upload->display_errors(' ',' '));
                        exit;
                    }
                    else
                    {
                        $filedata = $this->CI->upload->data();
                        $upload_array[] = array(
                            "att_target_type" => 'BOARD',
                            "att_origin" => $filedata['orig_name'],
                            "att_filepath" => $dir_path . "/" . $filedata['file_name'],
                            "att_downloads" => 0,
                            "att_filesize" => $filedata['file_size'] * 1024,
                            "att_width" => $filedata['image_width'] ? $filedata['image_width'] : 0,
                            "att_height" => $filedata['image_height'] ? $filedata['image_height'] : 0,
                            "att_ext" => $filedata['file_ext'],
                            "att_is_image" => ($filedata['is_image'] == 1) ? 'Y' : 'N',
                            "reg_user" => $this->CI->member->is_login(),
                            "reg_datetime" => date('Y-m-d H:i:s')
                        );
                    }
                }
            }

            // 첨부파일 삭제가 있다면 삭제한다.
            $del_file = $this->CI->input->post("del_file", TRUE);
            if( $del_file && count($del_file) > 0 ) {
                foreach($del_file as $att_idx) {
                    $this->attach_remove($att_idx);
                }
            }

            // 수정이냐 신규냐에 따른 값 결정
            if( empty($post_idx) ) {
                $data['reg_user'] = $data['upd_user'];
                $data['reg_datetime'] = $data['upd_datetime'];
                $data['post_status'] = 'Y';

                $data['post_count_comment'] = 0;
                $data['post_hit'] = 0;

                // 답글일경우
                if(! empty( $data['post_parent'] ) ) {
                    if( strlen($parent['post_reply']) >= 10 )
                    {
                        alert('더 이상 답변하실 수 없습니다.\\n답변은 10단계 까지만 가능합니다.');
                        exit;
                    }

                    $reply_len = strlen($parent['post_reply']) + 1;

                    $begin_reply_char = 'A';
                    $end_reply_char = 'Z';
                    $reply_number = +1;

                    $reply_char = "";
                    $this->CI->db->select("MAX(SUBSTRING(post_reply, {$reply_len}, 1)) AS reply")->from('board_post')->where('post_num', $parent['post_num'])->where('brd_key', $brd_key)->where("SUBSTRING(post_reply, {$reply_len}, 1) <>", '');
                    if($parent['post_reply']) $this->CI->db->like('post_reply', $parent['post_reply'],'after');
                    $row = $this->CI->db->get()->row_array();

                    if(! $row['reply']) {
                        $reply_char = $begin_reply_char;
                    }
                    else if ($row['reply'] == $end_reply_char) {
                        alert("더 이상 답변하실 수 없습니다.\\n답변은 26개 까지만 가능합니다.");
                        exit;
                    }
                    else {
                        $reply_char = chr(ord($row['reply']) + $reply_number);
                    }

                    $data['post_reply'] = $parent['post_reply'] . $reply_char;

                    // 답변의 원글이 비밀글이라면, 비밀번호는 원글과 동일하게 넣는다.
                    if( $parent['post_secret'] == 'Y' ) {
                        $data['post_password'] = $parent['post_password'];
                    }

                    $data['post_num'] = $parent['post_num'];
                }
                else {
                    $tmp  = (int)$this->CI->db->select_max('post_num','max')->from('board_post')->where('brd_key',$brd_key)->get()->row(0)->max;
                    $data['post_reply'] = "";
                    $data['post_num'] = $tmp+1;
                }

                if(! $this->CI->db->insert('board_post', $data) ) {
                    alert(langs('게시판/msg/write_failed'));
                    exit;
                }
                $post_idx = $this->CI->db->insert_id();
            }
            else {
                $this->CI->db->where('brd_key', $brd_key)->where('post_idx', $post_idx);
                if(! $this->CI->db->update('board_post', $data))
                {
                    alert(langs('게시판/msg/write_failed'));
                    exit;
                }
            }

            // 업로드된 데이타가 있을경우에 DB에 기록
            if(isset($upload_array) && count($upload_array) >0 )
            {
                foreach($upload_array as &$arr) {
                    $arr['att_target'] = $post_idx;
                }
                $this->CI->db->insert_batch("attach", $upload_array);
            }

            // 게시글의 대표 이미지를 가져온다.
            $attach_list = $this->get_attach_list($data['brd_key'], $post_idx);
            if ( count($attach_list) > 0 ) {
                foreach($attach_list as $row) {
                    if($row['att_is_image'] == 'Y') {
                        $data['post_thumbnail'] = base_url($row['att_filepath']);
                        break;
                    }
                }
            }

            // 첨부파일중에 없다면 HTML 코드에서 이미지를 찾아낸다.
            if( empty($data['post_thumbnail']) ) {
                $matches = get_editor_image($data['post_content']);

                if(! empty($matches)) {
                    $img = element(0, element(1, $matches));

                    if(! empty($img)) {
                        preg_match("/src=[\'\"]?([^>\'\"]+[^>\'\"]+)/i", $img, $m);
                        $src = isset($m[1]) ? $m[1] : '';

                        if(! empty($src)) {
                            $data['post_thumbnail'] = str_replace(base_url()."/", "", $src);
                        }
                    }
                }
            }
            $matches = null;

            // 거기서도 없으면 본문내용에 포함된 iframe 동영상에서..
            if( empty($data['post_thumbnail']) ) {
                preg_match_all("/<iframe[^>]*src=[\'\"]?([^>\'\"]+[^>\'\"]+)[\'\"]?[^>]*>/i", $data['post_content'], $matches);
                for ($i = 0; $i < count($matches[1]); $i++) {
                    if (!isset($matches[1][$i])) continue;

                    $video = get_video_info($matches[1][$i]);

                    // 비디오 타입이 아니거나, 알려지지 않은 비디오 일경우 건너뛴다.
                    if (!$video['type'] OR !$video['thumb']) continue;

                    if ($video['thumb']) {
                        $data['post_thumbnail'] = $video['thumb'];
                    }
                }
            }

            // 그래도 없으면 embed 태그 포함여부 확인해서..
            $matches = null;
            if( empty($data['post_thumbnail']) ) {
                preg_match_all("/<embed[^>]*src=[\'\"]?([^>\'\"]+[^>\'\"]+)[\'\"]?[^>]*>/i", $data['post_content'], $matches);
                for($i=0; $i<count($matches[1]); $i++) {
                    if(! isset($matches[1][$i]) ) continue;

                    $video = get_video_info( $matches[1][$i] );

                    // 비디오 타입이 아니거나, 알려지지 않은 비디오 일경우 건너뛴다.
                    if(! $video['type'] OR ! $video['thumb']) continue;

                    if($video['thumb']) {
                        $data['post_thumbnail'] = $video['thumb'];
                    }
                }
            }

            $this->CI->db->where('post_idx', $post_idx)->set('post_thumbnail', $data['post_thumbnail'])->update('board_post');


            // 자신의 글은 비밀글이더라도 바로 보거나, 아니면 수정/삭제를 할수 있도록 세션처리
            if($this->CI->member->is_login() ) {
                if( ! PAGE_ADMIN && ! $this->CI->member->is_super() )
                {
                    $this->point_process('brd_point_write', "POST_WRITE", "게시글 등록", $post_idx, FALSE);
                }

            } else {
                $this->CI->session->set_userdata('post_password_'.$post_idx, TRUE);
            }

            // 이동할 페이지 정의
            $reurl = PAGE_ADMIN ? base_url("admin/board/read/{$brd_key}/{$post_idx}") : base_url("board/{$brd_key}/{$post_idx}");
            alert(langs('게시판/msg/write_success'), $reurl);
            exit;
        }
        else
        {
            $this->CI->data['post_idx'] = (int)$post_idx;
            $this->CI->data['post_parent'] = $this->CI->input->get('post_parent', TRUE);
            $this->CI->data['view'] = empty($post_idx) ? array() : $this->get_post($brd_key, $post_idx, FALSE);
            $this->CI->data['parent'] = empty($this->CI->data['post_parent']) ? array() : $this->get_post($brd_key, $this->CI->data['post_parent'], FALSE);

            if( $this->CI->data['post_idx'] && (! $this->CI->data['view'] OR ! isset($this->CI->data['view']['post_idx']) OR !$this->CI->data['view']['post_idx'] ) )
            {
                alert(langs('게시판/msg/invalid_access'));
                exit;
            }

            $hidden = array();

            // 답글작성일경우 부모 번호 넘겨주기
            // 카테고리도 같은 카테고리로
            if($this->CI->data['post_parent']) {
                $hidden['post_parent'] = $this->CI->data['post_parent'];

                $this->CI->data['view']['post_title'] = "RE : ". $this->CI->data['parent']['post_title'];
                $this->CI->data['view']['post_category'] = $this->CI->data['parent']['post_category'];
            }

            $write_url = PAGE_ADMIN ? base_url("admin/board/write/{$brd_key}/{$post_idx}") : base_url("board/{$brd_key}/write/{$post_idx}");
            $this->CI->data['form_open'] = form_open_multipart($write_url, array("autocomplete"=>"off","data-form"=>"post","id"=>"form-post"), $hidden);
            $this->CI->data['form_close'] = form_close();

            $this->CI->view     = PAGE_ADMIN ? "board/write" : "write";
            $this->CI->theme    = PAGE_ADMIN ? "admin" : $this->CI->site->get_layout();
            $this->CI->active   = "board/{$brd_key}";

            if(! PAGE_ADMIN) {
                $this->CI->site->meta_title         = $this->CI->data['board']['brd_title'] . ' - '. ((empty($post_idx)) ? '새 글 작성' : '글 수정'); // 이 페이지의 타이틀
                $this->CI->site->meta_description 	= $this->CI->data['board']['brd_description'];   // 이 페이지의 요약 설명
                $this->CI->site->meta_keywords 		= $this->CI->data['board']['brd_keywords'];   // 이 페이지에서 추가할 키워드 메타 태그
                $this->CI->site->meta_image			= "";   // 이 페이지에서 표시할 대표이미지

                $this->CI->skin_type = "board/write";
                $this->CI->skin     = $this->CI->data['board']['brd_skin_w'];
            }
        }

    }

    /**
     * 게시글 읽기 처리
     */
    function read_process($brd_key, $post_idx)
    {
        $this->CI->data['view'] = $this->get_post($brd_key, $post_idx, FALSE);

        if(! in_array( $this->CI->data['view']['post_status'], array("Y","B")))
        {
            alert(langs('게시판/msg/invalid_post'));
            exit;
        }

        // 비밀글일 경우 처리
        if( $this->CI->data['view']['post_secret'] == 'Y' )
        {
            $is_auth = FALSE;

            if( PAGE_ADMIN OR $this->CI->member->is_super() )
            {
                $is_auth = TRUE;
            }

            if( !empty($this->CI->data['view']['reg_user']) && $this->CI->data['view']['reg_user'] == $this->CI->member->info('idx') )
            {
                $is_auth = TRUE;
            }

            // 해당 글이 답글일 경우
            if( strlen($this->CI->data['view']['post_reply']) > 0 && $this->CI->member->is_login())
            {
                // 원글중에 작성자가 있는경우 글을 볼 권한이 있다!
                $tmp = $this->CI->db->where('post_num', $this->CI->data['view']['post_num'])->where('brd_key', $brd_key)->get('board_post')->result_array();
                foreach($tmp as $t)
                {
                    if( $t['reg_user'] && $t['reg_user'] == $this->CI->member->info('idx') )
                    {
                        $is_auth = TRUE;
                        break;
                    }
                }
            }

            // 이 과정을 전부했는데도 권한이 없으면 비밀번호 확인
            if(! $is_auth)
            {
                if( ! $this->CI->session->userdata('post_password_'.$post_idx) )
                {
                    redirect(base_url("board/{$brd_key}/password/{$post_idx}?w=s&reurl=".current_full_url()));
                }
            }
        }

        // 게시판 조회수 상승
        if( ! PAGE_ADMIN ) {
            if( ! $this->CI->session->userdata('post_hit_'.$post_idx) OR (int)$this->CI->session->userdata('post_hit_'.$post_idx) + 60*60*24 < time() )
            {
                $this->CI->db->where('post_idx', $post_idx)->set('post_hit', 'post_hit+1', FALSE)->update('board_post');
                $this->CI->data['view']['post_hit'] += 1;
                $this->CI->session->set_userdata('post_hit_'.$post_idx, time());
            }

            // 포인트 관련 프로세스
            $this->point_process('brd_point_read', 'POST_READ', '게시글 읽기', $post_idx, ($this->CI->data['view']['reg_user'] == $this->CI->member->info('idx')) );


            // 메타태그 설정
            $this->CI->site->meta_title         = $this->CI->data['view']['post_title'] . ' - ' . $this->CI->data['board']['brd_title']; // 이 페이지의 타이틀
            $this->CI->site->meta_description 	= cut_str(get_summary($this->CI->data['view']['post_content'],FALSE),80);   // 이 페이지의 요약 설명
            $this->CI->site->meta_keywords 		= $this->CI->data['view']['post_keywords'];   // 이 페이지에서 추가할 키워드 메타 태그
            $this->CI->site->meta_image			= $this->CI->data['view']['post_thumbnail'];   // 이 페이지에서 표시할 대표이미지
        }

        // 링크 만들기
        $this->CI->data['board']['link']['reply'] = PAGE_ADMIN ? base_url("admin/board/write/{$brd_key}/?post_parent={$post_idx}") : base_url("board/{$brd_key}/write/?post_parent={$post_idx}");
        $this->CI->data['board']['link']['modify'] = PAGE_ADMIN ? base_url("admin/board/write/{$brd_key}/{$post_idx}") : base_url("board/{$brd_key}/write/{$post_idx}");
        $this->CI->data['board']['link']['delete'] = PAGE_ADMIN ? '': base_url("board/{$brd_key}/delete/{$post_idx}");

        // 댓글 입력폼
        $write_skin_path = DIR_SKIN . "/board/comment/" . $this->CI->data['board']['brd_skin_c'] . "/c_write";
        $comment_hidden = array("reurl"=>current_full_url(),"cmt_idx"=>"","cmt_parent"=>"");
        $comment_action_url = PAGE_ADMIN ? base_url("admin/board/comment/{$brd_key}/{$post_idx}") : base_url( "board/{$brd_key}/comment/{$post_idx}");

        $tmp['comment_view'] = array();
        $tmp['comment_form_open'] = form_open($comment_action_url,array("id"=>"form-board-comment","data-form"=>"board-comment"), $comment_hidden);
        $tmp['comment_form_close'] = form_close();
        $this->CI->data['comment_write'] =  $this->CI->data['board']['brd_use_comment'] == 'Y' && $this->CI->data['board']['auth']['comment'] ? $this->CI->load->view($write_skin_path, $tmp, TRUE) : NULL;

        // 댓글 목록
        $list_skin_path = DIR_SKIN . "/board/comment/" . $this->CI->data['board']['brd_skin_c'] . "/c_list";
        $this->CI->data['comment_list'] = array();
        if( $this->CI->data['board']['brd_use_comment'] == 'Y' )
        {
            $mem_useridx = $this->CI->member->is_login();
            $this->CI->data['comment_list'] = $this->comment_list($brd_key, $post_idx, $this->CI->data['board']['auth']['admin'], $mem_useridx);

            // 각 댓글마다 대댓글 폼을 만든다.
            foreach($this->CI->data['comment_list']['list'] as &$row)
            {
                unset($tmp);
                $row['comment_form'] = "";
                if(strlen($row['cmt_reply']) < 5)
                {
                    $comment_hidden = array("reurl"=>current_full_url(),"cmt_idx"=>"","cmt_parent"=>$row['cmt_idx']);
                    $tmp['comment_view'] = array();
                    $tmp['comment_form_open'] = form_open($comment_action_url, array("data-form"=>"board-comment"), $comment_hidden);
                    $tmp['comment_form_close'] = form_close();
                    $row['comment_form'] =  $this->CI->data['board']['brd_use_comment'] == 'Y' && $this->CI->data['board']['auth']['comment'] ? $this->CI->load->view($write_skin_path, $tmp, TRUE) : NULL;
                }
            }
        }
        $tmp2['board'] = $this->CI->data['board'];
        $this->CI->data['comments'] =  $this->CI->data['comment_list'];
        $tmp2['comment_list'] = $this->CI->data['comment_list'];

        $this->CI->data['comment_list'] = $this->CI->data['board']['brd_use_comment'] == 'Y' ? $this->CI->load->view($list_skin_path, $tmp2, TRUE) : NULL;

        $this->CI->view = PAGE_ADMIN ? "board/read" : "view";
        $this->CI->active = "board/" . $brd_key;
        $this->CI->theme    = PAGE_ADMIN ? "admin" : $this->CI->site->get_layout();

        if(! PAGE_ADMIN) {
            $this->CI->skin_type = "board/view";
            $this->CI->skin = $this->CI->data['board']['brd_skin_v'];
        }
    }

    /**
     * 댓글 입력 처리
     */
    function comment_process($brd_key, $post_idx)
    {
        $this->CI->load->library('form_validation');

        $this->CI->form_validation->set_rules('cmt_content', langs('게시판/comment/form_content'), 'trim|required');

        if( empty($brd_key) OR empty($post_idx) )
        {
            alert(langs('게시판/msg/invalid_access'));
            exit;
        }

        $data['brd_key'] = $brd_key;
        $data['post_idx'] = $post_idx;
        $data['cmt_idx'] = $this->CI->input->post('cmt_idx', TRUE);
        $data['cmt_parent'] = $this->CI->input->post('cmt_parent', TRUE, 0);
        $data['cmt_content'] = $this->CI->input->post('cmt_content', FALSE);
        $data['upd_user']  = $this->CI->member->is_login();
        $data['cmt_password'] = ( $this->CI->member->is_login() ) ?  $this->CI->member->info('password') : get_password_hash( $this->CI->input->post('cmt_password', FALSE) );
        $data['cmt_nickname'] = ( $this->CI->member->is_login() ) ? $this->CI->member->info('nickname') : $this->CI->input->post('cmt_nickname');
        $data['upd_datetime'] = date('Y-m-d H:i:s');
        $data['cmt_ip'] = ip2long( $this->CI->input->ip_address() );
        $data['cmt_status'] = 'Y';
        $data['cmt_mobile'] = $this->CI->site->viewmode == DEVICE_MOBILE ? 'Y' : 'N';
        $base_reurl = PAGE_ADMIN ? base_url("admin/board/read/{$brd_key}/{$post_idx}") : base_url("board/{$brd_key}/{$post_idx}");
        $reurl = $this->CI->input->post('reurl', TRUE, $base_reurl );

        // 값 유효성 체크
        if( empty($data['cmt_content']) )
        {
            alert(langs('게시판/comment/content_required'));
            exit;
        }

        if( empty($data['cmt_nickname']) )
        {
            alert(langs('게시판/comment/nickname_required'));
            exit;
        }

        if( empty($data['cmt_password']) )
        {
            alert(langs('게시판/comment/password_required'));
            exit;
        }

        // 신규 등록일 경우
        if( empty($data['cmt_idx']) ) {
            $data['reg_datetime'] = $data['upd_datetime'];
            $data['reg_user'] = $data['upd_user'];

            if(! empty($data['cmt_parent'])) {
                $parent = $this->CI->db->where('cmt_idx', $data['cmt_parent'])->where_in('cmt_status', array('Y','B'))->where('post_idx', $data['post_idx'])->get('board_comment')->row_array();

                if(! $parent OR !isset($parent['cmt_idx']) OR ! $parent['cmt_idx']) {
                    alert('답변할 댓글이 없습니다.\\n답변하는 동안 댓글이 삭제되었을 수 있습니다.');
                    exit;
                }

                if($parent['post_idx'] != $data['post_idx']) {
                    alert('댓글을 등록할 수 없습니다.\\n잘못된 방법으로 등록을 요청하였습니다.');
                    exit;
                }

                if(strlen($parent['cmt_reply']) >= 5) {
                    alert('더이상 답변을 달수 없습니다.\\n\\n답변은 5단계 까지만 가능합니다.');
                    exit;
                }

                $reply_len = strlen($parent['cmt_reply']) + 1;

                $begin_reply_char = 'A';
                $end_reply_char = 'Z';
                $reply_number = +1;

                $this->CI->db->select("MAX(SUBSTRING(cmt_reply, {$reply_len}, 1)) AS reply")->from('board_comment')->where('cmt_num', $parent['cmt_num'])->where('post_idx', $data['post_idx'])->where("SUBSTRING(cmt_reply, {$reply_len}, 1) <>", '');
                if($parent['cmt_reply']) $this->CI->db->like('cmt_reply', $parent['cmt_reply'],'after');
                $row = $this->CI->db->get()->row_array();


                $reply_char ="";

                if(!$row['reply']) $reply_char = $begin_reply_char;
                else if ($row['reply'] == $end_reply_char) {
                    alert('더이상 답변을 달수 없습니다.\\n\\n답변은 26개까지만 가능합니다.');
                    exit;
                }
                else $reply_char = chr(ord($row['reply']) + $reply_number);

                $data['cmt_reply'] = $parent['cmt_reply'] . $reply_char;
                $data['cmt_num'] = $parent['cmt_num'];
            } else {
                $tmp = (int)$this->CI->db->select_max('cmt_num','max')->from('board_comment')->where('post_idx',$data['post_idx'])->get()->row(0)->max;

                $data['cmt_reply'] = "";
                $data['cmt_num'] = $tmp+1;
            }

            if( $this->CI->db->insert('board_comment', $data) )
            {
                // 대댓글을 위한 정보입력
                $cmt_idx = $this->CI->db->insert_id();

                // 포인트 입력처리
                $this->point_process('brd_point_comment', "CMT_WRITE", "댓글 등록", $cmt_idx, FALSE);

                $this->update_post_comment_count($brd_key, $post_idx);
                alert('댓글 작성이 완료되었습니다.', $reurl);
            } else {
                alert(langs('게시판/msg/comment_failed'));
                exit;
            }

        } else {
            // 기존 댓글 정보를 가져온다
            $comment = $this->CI->db->where("cmt_idx", $data['cmt_idx'])->where('brd_key', $brd_key)->where('post_idx', $post_idx)->get('board_comment')->row_array();
            if( ! $comment || ! isset($comment['cmt_idx']) || $comment['cmt_idx'] != $data['cmt_idx'] )
            {
                alert(langs('게시판/msg/invalid_comment'));
                exit;
            }

            if( ! PAGE_ADMIN && ! $this->CI->member->is_super() )
            {
                // 기존 댓글과 수정권한이 있는지 확인한다.
                if( ! empty( $comment['reg_user']) )
                {
                    if( $this->CI->member->is_login() )
                    {
                        if( $this->CI->member->is_login() != $comment['reg_user'] )
                        {
                            alert(langs('게시판/msg/comment_modify_unauthorize'));
                            exit;
                        }
                    }
                    else
                    {
                        alert_login();
                        exit;
                    }
                }
                else
                {
                    if( $data['cmt_password'] != $comment['cmt_password'] )
                    {
                        alert(langs('게시판/msg/invalid_password'));
                        exit;
                    }
                }
            }

            // 수정일 경우는 바뀌어선 안되는 정보들은 unset
            unset($data['brd_key'], $data['post_idx']);

            $this->CI->db->where('brd_key', $brd_key)->where('post_idx', $post_idx)->where('cmt_idx', $data['cmt_idx']);
            if( $this->CI->db->update('board_comment', $data) )
            {
                $this->update_post_comment_count($brd_key, $post_idx);
                alert_close(langs('게시판/msg/comment_modify_success'), TRUE);
                exit;
            } else {
                alert(langs('게시판/msg/comment_failed'));
                exit;
            }
        }
    }

    /**
     * 댓글 입력폼
     */
    function comment_modify_form($cmt_idx="", $comment)
    {
        if( ($result = $this->_check_comment_modify($comment)) !== TRUE )
        {
            alert_close($result);
            exit;
        }

        $this->CI->site->meta_title = "댓글 수정";

        $hidden=array("cmt_nickname"=>$comment['cmt_nickname'],"cmt_idx"=>$comment['cmt_idx'],"cmt_parent"=>$comment['cmt_parent']);
        $action_url = PAGE_ADMIN ? base_url("admin/board/comment/{$comment['brd_key']}/{$comment['post_idx']}/{$cmt_idx}"): base_url('board/'.$comment['brd_key'].'/comment/'.$comment['post_idx'].'/'.$cmt_idx);
        $this->CI->data['comment_form_open'] = form_open($action_url, array("id"=>"form-board-comment","data-form"=>"board-comment"), $hidden);
        $this->CI->data['comment_form_close'] = form_close();
        $this->CI->data['comment_view'] = $comment;
        $this->CI->data['is_reply'] = FALSE;

        $this->CI->theme = $this->CI->site->get_layout();
        $this->CI->theme_file = "popup";
        $this->CI->skin_type = "board/comment";
        $this->CI->skin = $this->CI->data['board']['brd_skin_c'];
        $this->CI->view = "c_write";
    }

    /**
     * 댓글 삭제 처리
     */
    public function comment_delete_process($brd_key, $post_idx, $cmt_idx)
    {

        if( ! $comment = $this->CI->db->where('cmt_idx', $cmt_idx)->where('cmt_status', 'Y')->get('board_comment')->row_array() )
        {
            alert(langs('게시판/msg/invalid_comment'));
            exit;
        }


        // 권한 확인
        if( ! PAGE_ADMIN && ! $this->CI->member->is_super() )
        {
            if( ($result = $this->_check_comment_modify($comment)) !== TRUE )
            {
                alert($result);
                exit;
            }

            if( empty($comment['reg_user']) )
            {
                alert(langs('게시판/msg/cannot_delete_guest_comment'));
                exit;
            }
        }

        // 원본 가져오기
        $original = $this->CI->db->where('cmt_idx', $cmt_idx)->get('board_comment')->row_array();
        if(!$original OR !isset($original['cmt_idx']))
            alert('삭제할 원본 댓글이 없습니다.\\ 이미 삭제되엇거나 존재하지 않는 댓글입니다.');


        // 이 댓글의 하위 댓글이 있는지 확인
        $len = strlen($original['cmt_reply']);
        if ($len < 0) $len = 0;
        $comment_reply = substr($original['cmt_reply'], 0, $len);


        $cnt =
            $this->CI->db
                ->select('COUNT(*) AS cnt')
                ->from('board_comment')
                ->like('cmt_reply', $comment_reply,'after')
                ->where('cmt_idx <>', $cmt_idx)
                ->where('cmt_num', $original['cmt_num'])
                ->where('post_idx', $original['post_idx'])
                ->where('cmt_status', 'Y')
                ->where('cmt_parent', $cmt_idx)
                ->get()->row(0)->cnt;


        if($cnt > 0)
            alert('삭제하려는 댓글에 답변이 달려있어 삭제할 수 없습니다.');

        if( $this->CI->db->where('brd_key', $brd_key)->where('post_idx', $post_idx)->where('cmt_idx', $cmt_idx)->set('cmt_status', 'N')->update('board_comment') )
        {
            $this->update_post_comment_count($brd_key, $post_idx);

            // 댓글등록으로 증가한 포인트가 있다면 다시 감소
            $this->point_cancel("CMT_WRITE",$cmt_idx, "댓글삭제");

            alert(langs('게시판/msg/comment_delete_success'));
            exit;
        }

    }


    /**
     * 코멘트 수정/삭제 권한 확인
     * @param $comment
     * @return bool
     */
    public function _check_comment_modify($comment)
    {
        if( PAGE_ADMIN OR $this->CI->member->is_super() ) return TRUE;

        // 댓글 수정/삭제 권한 확인
        if( $comment['reg_user'] && ! $this->CI->member->is_login() )
        {
            return langs('게시판/msg/comment_modify_unauthorize');
        }
        else if ( $comment['reg_user'] && $this->CI->member->is_login() && $this->CI->member->is_login() != $comment['reg_user'])
        {
            return langs('게시판/msg/comment_modify_unauthorize');
        }

        return TRUE;
    }

    /**
     * 수정이나 삭제 권한을 확인한다.
     */
    public function _check_modify_auth($brd_key, $post_idx="")
    {
        if(empty($post_idx)) return;
        if(PAGE_ADMIN) return;  // 관리자 페이지일 경우도 리턴

        $post = $this->get_post($brd_key, $post_idx, FALSE);

        if( $this->CI->member->is_super()) return;  // 관리자일경우 체크하지 않는다.
        if(! empty($post['reg_user']) )
        {
            if( ! $this->CI->member->is_login() )
            {
                alert_login( langs('게시판/msg/modify_require_login') );
                exit;
            }
            else if ( $post['reg_user'] != $this->CI->member->info('idx') )
            {
                alert(langs('게시판/msg/modify_unauthorize'));
                exit;
            }
        }
        else
        {
            if(! $this->CI->session->userdata('post_password_'.$post_idx))
            {
                redirect(base_url("board/{$brd_key}/password/{$post_idx}?reurl=".current_full_url()));
            }
        }
    }

    /**********************************************************
     * 첨부파일 삭제
     * @param $bfi_idx
     * @return mixed
     *********************************************************/
    function attach_remove($att_idx)
    {
        if(empty($att_idx)) return false;
        $this->CI->db->where("att_idx", $att_idx);
        $result = $this->CI->db->get('attach');
        $attach = $result->row_array();
        if(! $attach) return false;
        if( file_exists(FCPATH. $attach['att_filepath']) )
        {
            @unlink(FCPATH.$attach['att_filepath']);
        }
        $this->CI->db->where("att_idx", $att_idx);
        $this->CI->db->delete("attach");
    }

    /**
     * 게시판에 관련된 포인트를 처리합니다.
     * @param $type
     * @param $mpo_type
     * @param $msg
     * @param $target_idx
     */
    public function point_process($type, $mpo_type, $msg, $target_idx, $check_writer=FALSE)
    {
        // 첨부파일 다운시 필요한 포인트가 있다면 확인
        if( $this->CI->data['board'][$type] != 0 )
        {
            // 회원일 경우만 실행한다.
            if( $this->CI->member->is_login() )
            {
                // 본인의 것은 실행하지 않는다.
                if( ! $check_writer )
                {
                    // 이미 처리된 포인트 내역이 있는지 확인한다. (포인트는 한번만 차감/등록한다.)
                    $res = (int) $this->CI->db->select('COUNT(*) as cnt')->where('target_type', $mpo_type)->where('target_idx', $target_idx)->where('mem_idx', $this->CI->member->is_login())->get('member_point')->row(0)->cnt;
                    if( $res <= 0)
                    {
                        // 포인트 차감일 경우, 해당 포인트가 있는지 확인한다
                        if( (int)$this->CI->data['board'][$type] != 0)
                        {
                            if( $this->CI->data['board'][$type."_flag"] > 0) {
                                // 포인트 상승일 경우 처리

                            } else {
                                // 포인트 감소일 경우 회원에게 남은 포인트가 있는지 확인한다.

                                if( (int)$this->CI->member->info('point') < (int)$this->CI->data['board'][$type] )
                                {
                                    alert(langs('회원/point/not_enough') . "({$this->CI->data['board'][$type]})");
                                    exit;
                                }
                            }

                        }

                        // 포인트 실제 처리
                        $this->CI->member->add_point($this->CI->member->is_login(),$this->CI->data['board'][$type."_flag"], $this->CI->data['board'][$type], FALSE, $mpo_type, $msg, $target_idx);
                    }
                }
            }
            // 비회원일 경우 아예 실행이 불가능하다.
            else
            {
                alert(langs('공통/msg/login_required'));
                exit;
            }
        }
    }

    /**
     * 게시판에 관련된 포인트를 삭제등의 행동시 취소합니다.
     * @param $target_type
     * @param $target_idx
     * @param $msg
     */
    public function point_cancel($target_type, $target_idx, $msg)
    {
        // 댓글등록으로 증가한 포인트가 있다면 다시 감소
        // 포인트 입력처리
        if( $this->CI->member->is_login() )
        {
            $ret = $this->CI->db->where('target_type', $target_type)->where('mem_idx', $this->CI->member->is_login() )->where('target_idx', $target_idx)->where('mpo_value !=','0')->get('member_point')->row_array();
            if( $ret && isset($ret['mpo_value']) && $ret['mpo_value'] != 0 )
            {
                $this->CI->member->add_point($this->CI->member->is_login(),$ret['mpo_flag']*-1, $ret['mpo_value'], FALSE, $target_type, $msg, $target_idx);
            }
        }
    }

    /**
     * 해당 게시글의 코멘트가 몇개인지 확인한다.
     * @param $brd_key
     * @param $post_idx
     */
    function get_comment_count($brd_key, $post_idx)
    {
        $count = (int)$this->CI->db->select('COUNT(*) AS cnt')->from('board_comment')->where_in('cmt_status', array('Y','B'))->where('brd_key',$brd_key)->where('post_idx',$post_idx)->get()->row(0)->cnt;
        return $count;
    }


    /**
     * 해당 게시물의 댓글수를 최신화 한다
     * @param $brd_key
     * @param $post_idx
     */
    function update_post_comment_count($brd_key, $post_idx)
    {
        $count = $this->get_comment_count($brd_key, $post_idx);

        $this->CI->db->where('brd_key', $brd_key)->where('post_idx', $post_idx)->set('post_count_comment', (int)$count);
        return $this->CI->db->update('board_post');
    }
}