<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * 게시판 컨트롤러
 */
class Board extends WB_Controller
{
    /**
     * 생성자
     */
    function __construct()
    {
        // 기존 컨트롤러 생성자 실행
        parent::__construct();

        // 게시판 라이브러리 불러오기
        $this->load->library('boardlib');
    }

    /**
     * INDEX 페이지는 접근금지
     */
    function index()
    {
        alert(langs( 'board/msg/invalid_access' ));
    }

    /**
     * 게시판 보기 페이지
     * @param $brd_key
     * @param $post_idx
     */
    public function view($brd_key, $post_idx)
    {
        $this->board_common($brd_key, 'read');
        $this->boardlib->read_process($brd_key, $post_idx);
    }

    /**
     * 게시판 목록
     * @param $brd_key
     */
    public function lists($brd_key)
    {
        $this->board_common($brd_key, 'list');

        // 메타태그 설정
        $this->site->meta_title         = $this->data['board']['brd_title'] . ' - ' . $this->data['page'] . '페이지'; // 이 페이지의 타이틀
        $this->site->meta_description 	= $this->data['board']['brd_description'];   // 이 페이지의 요약 설명
        $this->site->meta_keywords 		= $this->data['board']['brd_keywords'];   // 이 페이지에서 추가할 키워드 메타 태그
        $this->site->meta_image			= "";   // 이 페이지에서 표시할 대표이미지

        // 레이아웃 & 뷰파일 설정
        $this->view     = "list";

        $this->skin_type = "board/list";
        $this->skin     = $this->data['board']['brd_skin_l'];
    }

    /**
     * 코멘트 등록/수정 처리
     * @param $brd_key
     * @param $post_idx
     * @param string $cmt_idx
     */
    public function comment($brd_key, $post_idx)
    {
        $this->board_common($brd_key,'comment');
        $this->boardlib->comment_process($brd_key, $post_idx);
    }


    /**
     * 코멘트 수정 폼
     * @param string $cmt_idx
     */
    public function comment_modify($cmt_idx="")
    {
        if( ! $comment = $this->db->where('cmt_idx', $cmt_idx)->where('cmt_status', 'Y')->get('board_comment')->row_array() )
        {
            alert_close(langs('게시판/msg/invalid_comment'));
            exit;
        }

        $this->board_common($comment['brd_key']);

        $this->boardlib->comment_modify_form($cmt_idx,$comment);
    }

    /**
     * 댓글 삭제
     * @param $brd_key
     * @param $post_idx
     * @param $cmt_idx
     */
    public function comment_delete($brd_key, $post_idx, $cmt_idx)
    {
        $this->board_common($brd_key);
        $this->boardlib->comment_delete_process($brd_key, $post_idx, $cmt_idx);
    }


    /**********************************************************
     *
     * 게시판 암호 확인 페이지
     * @param string $brd_key
     * @param string $post_idx
     *
     *********************************************************/
    function password($brd_key="",$post_idx="")
    {
        $this->board_common($brd_key);
        // 폼검증 라이브러리 로드
        $this->load->library("form_validation");
        // 폼검증 규칙 설정
        $this->form_validation->set_rules("password", langs('게시판/form/password'), "trim|required|min_length[4]|max_length[16]");
        if( $this->form_validation->run() == FALSE )
        {
            $hidden = array("reurl"=>$this->input->get('reurl', TRUE));
            $action_url = base_url("board/{$brd_key}/password/{$post_idx}");
            $this->data['form_open'] = form_open($action_url,array("id"=>"form-post-password","data-form"=>"post-password-form"), $hidden);
            $this->data['form_close']= form_close();

            $this->view = "password";
            $this->skin_type = "board/view";
            $this->skin = $this->data['board']['brd_skin_v'];
        }
        else
        {
            $reurl = $this->input->post("reurl", TRUE, base_url("board/{$brd_key}/{$post_idx}") );
            $password = $this->input->post("password", TRUE);
            $post = $this->boardlib->get($brd_key, $post_idx);
            if( get_password_hash($password) == $post['mem_password'] )
            {
                $this->session->set_userdata('post_password_'.$post_idx, TRUE);
                redirect($reurl);
                exit;
            }
            else
            {
                alert( langs('게시판/msg/invalid_password') );
                exit;
            }
        }
    }

    /**
     * 글쓰기 페이지
     * @param $brd_key
     * @param string $post_idx
     */
    public function write($brd_key, $post_idx="")
    {
        $this->board_common($brd_key, 'write');
        $this->boardlib->write_process($brd_key, $post_idx);
    }

    /**
     * 첨부파일 다운로드 하기
     * @param $brd_key
     * @param $post_idx
     * @param $bmt_idx
     */
    public function download($brd_key, $post_idx, $att_idx)
    {
        if(empty($brd_key) OR empty($post_idx) OR empty($att_idx))
        {
            alert(langs( 'board/msg/invalid_access' ));
        }

        $this->board_common($brd_key, 'download');

        if(! $att = $this->db->where('att_idx', $att_idx)->where('att_target_type', 'BOARD')->where('att_target', $post_idx)->get('attach')->row_array())
        {
            alert(langs( 'board/msg/invalid_attach_file' ));
            exit;
        }

        $post = $this->boardlib->get_post($brd_key, $post_idx, TRUE);

        $this->boardlib->point_process('brd_point_download', "POST_ATTACH_DOWNLOAD", "첨부파일 다운로드", $post_idx, ($post['reg_user'] == $this->member->info('idx')) );

        $this->db->where('att_idx', $att['att_idx'])->set('att_downloads', 'att_downloads + 1', FALSE)->update('attach');

        $this->load->helper('download');
        $data = file_get_contents(FCPATH.$att['att_filepath']);
        $name = urlencode($att['att_origin']);
        force_download($name, $data);
    }

    /**
     * 게시글 삭제
     * @param $brd_key
     * @param $post_idx
     */
    public function delete($brd_key, $post_idx)
    {
        $this->board_common($brd_key);
        $this->boardlib->_check_modify_auth($brd_key, $post_idx);

        $post = $this->boardlib->get($brd_key, $post_idx, FALSE);

        $len = strlen($post['post_reply']);
        if( $len < 0 ) $len = 0;
        $reply = substr($post['post_reply'], 0, $len);

        // 게시글에 답글이 달려있는경우 삭제할 수 없다
        $count = (int) $this->db->select('COUNT(*) AS cnt')
            ->where('post_idx <>', $post['post_idx'])
            ->where('post_num', $post['post_num'])
            ->where('brd_key', $post['brd_key'])
            ->like('post_reply', $reply, 'after')
            ->where_in('post_status',array('Y','B'))
            ->get('board_post')
            ->row(0)
            ->cnt;

        if( $count > 1 )
        {
            alert(langs('게시판/msg/cant_delete_because_child'));
            exit;
        }

        if( $this->db->where('post_idx', $post_idx)->set('post_status', 'N')->update('board_post') )
        {
            $this->boardlib->point_cancel("POST_WRITE", $post_idx, "게시글 삭제");

            alert( langs('게시판/msg/delete_success'), base_url("board/{$brd_key}") );
            exit;
        }
        else
        {
            alert( langs('게시판/msg/delete_failed') );
            exit;
        }
    }

    /**
     * 게시판마다 공통으로 불러오기
     * @param $brd_key
     * @param string $check_type
     */
    private function board_common($brd_key, $check_type="")
    {
        $this->boardlib->common_data($brd_key);

        if( $check_type && ! $this->data['board']['auth'][$check_type] )
        {
            $msg = langs('게시판/msg/list_unauthorize');;
            if( $check_type == 'write' ) $msg = langs('게시판/msg/write_unauthorize');
            else if ($check_type == 'view' || $check_type == 'read') $msg = langs('게시판/msg/read_unauthorize');
            else if ($check_type == 'download') $msg = langs('게시판/msg/download_unauthorize');
            else if ($check_type == 'reply') $msg = langs('게시판/msg/reply_unauthorize');
            else if ($check_type == 'comment') $msg = langs('게시판/msg/comment_unauthorize');

            alert($msg);
            exit;
        }

        $use_list = $check_type == 'list';

        $this->data['list'] = array(
            "list"=>array(),
            "total_count" => 0
        );
        $this->data['pagination'] = "";

        if( $use_list )
        {
            // 게시글 목록 가져오기
            $this->data['list'] = $this->boardlib->post_list($this->data['board'], $this->param);

            // 페이지네이션 세팅
            $paging['page'] = $this->param['page'];
            $paging['page_rows'] = $this->data['board']['brd_page_rows'];
            $paging['total_rows'] = $this->data['list']['total_count'];
            $paging['fixed_page_num'] = $this->data['board']['brd_fixed_num'];

            $this->load->library('paging', $paging);
            $this->data['pagination'] = $this->paging->create();
        }

        // 레이아웃 정의
        $this->theme    = $this->site->get_layout();
        $this->skin_type = "board";
        $this->active   = "board/".$this->data['board']['brd_key'];
    }

}