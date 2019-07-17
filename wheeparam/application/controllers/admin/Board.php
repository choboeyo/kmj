<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Board extends WB_Controller
{
    public function __construct()
    {
        parent::__construct();

        $this->theme = 'admin';

        $this->load->library('boardlib');
    }

    /******************************************************************************************************
     * 게시판 목록
     ******************************************************************************************************/
    public function lists()
    {
        // 메타태그 설정
        $this->site->meta_title = "게시판 관리";

        // 레이아웃 & 뷰파일 설정
        $this->view = $this->active = "board/lists";
    }

    /**
     * 게시판 등록/수정
     * @param string $brd_key
     */
    public function form($brd_key="")
    {
        $this->load->model('board_model');
        $this->load->library('form_validation');

        $this->form_validation->set_rules('brd_key','게시판 고유 키',"required|trim|min_length[3]|max_length[20]". (empty($brd_key)?"|callback_brd_key_check":""));

        if( $this->form_validation->run() != FALSE )
        {
            $data['brd_key'] = $this->input->post('brd_key', TRUE);
            $data['brd_title'] = $this->input->post('brd_title', TRUE,'');
            $data['brd_keywords'] = $this->input->post('brd_keywords', TRUE);
            $data['brd_description'] = $this->input->post('brd_description', TRUE);
            $data['brd_skin_l'] = $this->input->post('brd_skin_l', TRUE);
            $data['brd_skin_l_m'] = $this->input->post('brd_skin_l_m', TRUE);
            $data['brd_skin_w'] = $this->input->post('brd_skin_w', TRUE);
            $data['brd_skin_w_m'] = $this->input->post('brd_skin_w_m', TRUE);
            $data['brd_skin_v'] = $this->input->post('brd_skin_v', TRUE);
            $data['brd_skin_v_m'] = $this->input->post('brd_skin_v_m', TRUE);
            $data['brd_skin_c'] = $this->input->post('brd_skin_c', TRUE);
            $data['brd_skin_c_m'] = $this->input->post('brd_skin_c_m', TRUE);
            $data['brd_use_category'] = $this->input->post('brd_use_category', TRUE, "N");
            $data['brd_category'] = rtrim(trim($this->input->post('brd_category', TRUE, '')), ';');
            $data['brd_lv_list'] = $this->input->post('brd_lv_list', TRUE);
            $data['brd_lv_read'] = $this->input->post('brd_lv_read', TRUE);
            $data['brd_lv_write'] = $this->input->post('brd_lv_write', TRUE);
            $data['brd_lv_reply'] = $this->input->post('brd_lv_reply', TRUE);
            $data['brd_lv_comment'] = $this->input->post('brd_lv_comment', TRUE);
            $data['brd_lv_download'] = $this->input->post('brd_lv_download', TRUE);
            $data['brd_page_limit'] = $this->input->post('brd_page_limit', TRUE);
            $data['brd_page_rows'] = $this->input->post('brd_page_rows', TRUE);
            $data['brd_page_rows_m'] = $this->input->post('brd_page_rows_m', TRUE);
            $data['brd_fixed_num'] = $this->input->post('brd_fixed_num', TRUE);
            $data['brd_fixed_num_m'] = $this->input->post('brd_fixed_num_m', TRUE);
            $data['brd_display_time'] = $this->input->post('brd_display_time', TRUE);
            $data['brd_use_anonymous'] = $this->input->post('brd_use_anonymous', TRUE);
            $data['brd_use_secret'] = $this->input->post('brd_use_secret', TRUE);
            $data['brd_use_reply'] = $this->input->post('brd_use_reply', TRUE);
            $data['brd_use_comment'] = $this->input->post('brd_use_comment', TRUE);
            $data['brd_point_read'] = $this->input->post('brd_point_read', TRUE, 0);
            $data['brd_point_write'] = $this->input->post('brd_point_write', TRUE, 0);
            $data['brd_point_comment'] = $this->input->post('brd_point_comment', TRUE, 0);
            $data['brd_point_download'] =  $this->input->post('brd_point_download', TRUE, 0);
            $data['brd_point_reply'] =  $this->input->post('brd_point_reply', TRUE, 0);
            $data['brd_point_read_flag'] = $this->input->post('brd_point_read_flag', TRUE, -1);
            $data['brd_point_write_flag'] = $this->input->post('brd_point_write_flag', TRUE, 1);
            $data['brd_point_comment_flag'] = $this->input->post('brd_point_comment_flag', TRUE, 1);
            $data['brd_point_download_flag'] =  $this->input->post('brd_point_download_flag', TRUE, -1);
            $data['brd_point_reply_flag'] =  $this->input->post('brd_point_reply_flag', TRUE, 1);
            $data['upd_user'] = $this->member->is_login();
            $data['upd_datetime'] = date('Y-m-d H:i:s');

            if(empty($brd_key))
            {
                $data['reg_user'] = $data['upd_user'];
                $data['reg_datetime'] = $data['upd_datetime'];
                $data['brd_count_post'] = 0;

                if( $this->db->insert('board', $data) )
                {
                    alert_modal_close('게시판 생성이 완료되었습니다.');
                    exit;
                }
            }
            else
            {
                $this->db->where('brd_key', $brd_key);
                if( $this->db->update('board', $data) ) {
                    $this->boardlib->delete_cache($brd_key);
                    alert_modal_close('게시판 정보 수정이 완료되었습니다.');
                    exit;
                }
            }

            alert('DB입력도중 오류가 발생하였습니다.');
            exit;
        }
        else
        {
            $this->data['view'] = (empty($brd_key)) ? array() : $this->boardlib->get($brd_key, TRUE);
            $this->data['brd_key'] = $brd_key;
            $this->data['skin_list_l'] = get_skin_list('board/list');
            $this->data['skin_list_w'] = get_skin_list('board/write');
            $this->data['skin_list_v'] = get_skin_list('board/view');
            $this->data['skin_list_c'] = get_skin_list('board/comment');

            // 메타태그 설정
            $this->site->meta_title = "게시판 관리";

            // 레이아웃 & 뷰파일 설정
            $this->view     = "board/form";
            $this->theme_file = 'iframe';
        }
    }

    /**
     * 게시판 삭제
     */
    public function remove($brd_key)
    {
        if(empty($brd_key))
        {
            alert('잘못된 접근입니다.');
            exit;
        }

        $this->db->where('brd_key', $brd_key)->delete('board');
        alert('게시판이 삭제되었습니다.');
        exit;
    }

    /**
     * 게시판 복사
     */
    public function board_copy($brd_key)
    {
        $this->load->model('board_model');
        $this->load->library('form_validation');

        $this->form_validation->set_rules('original', "원본 게시판", "required|trim");
        $this->form_validation->set_rules('brd_key','게시판 고유 키',"required|trim|min_length[3]|max_length[20]|callback_brd_key_check");

        if( $this->form_validation->run() != FALSE )
        {
            $data = $this->boardlib->get( $this->input->post('original', TRUE) , TRUE);

            if(! $data || !isset($data['brd_key']) || !$data['brd_key'])
            {
                alert_modal_close('원본 게시판 설정을 찾을수 없습니다.');
                exit;
            }

            $data['brd_key'] = $this->input->post('brd_key', TRUE);
            $data['brd_title'] = $this->input->post('brd_title', TRUE);
            $data['brd_count_post'] = 0;
            $data['upd_user'] = $data['reg_user'] = $this->member->is_login();
            $data['upd_datetime'] = $data['reg_datetime'] = date('Y-m-d H:i:s');

            $this->db->insert('board', $data);

            alert_modal_close('게시판 복사가 완료되었습니다.');
            exit;

        }
        else
        {
            $this->data['view'] = $this->boardlib->get($brd_key, TRUE);
            if(! $this->data['view'] || !isset($this->data['view']['brd_key']) || ! $this->data['view']['brd_key'])
            {
                alert_modal_close('원본 게시판 설정을 찾을수 없습니다.');
                exit;
            }
            $this->data['brd_key'] = $brd_key;
            $this->data['skin_list'] = get_skin_list('board');

            $this->theme = "admin";
            $this->theme_file = "iframe";
            $this->view = "board/board_copy";
        }
    }

    /**
     * 게시판 키 중복여부 확인
     */
    function brd_key_check($str)
    {
        $this->load->model('board_model');

        if(! preg_match("/^[a-z][a-z0-9_]{2,19}$/", $str))
        {
            $this->form_validation->set_message('brd_key_check', "게시판 고유키는 영어 소문자로 시작하는 3~20 글자로 영어와 숫자만 사용가능합니다. : {$str}");
            return FALSE;
        }

        if( $board = $this->boardlib->get($str, TRUE) ) {
            $this->form_validation->set_message('brd_key_check', "이미 사용중인 {field} 입니다 : {$str}");
            return FALSE;
        }

        return TRUE;
    }

    /**
     * 게시판 글 목록
     */
    function posts($brd_key)
    {
        $this->boardlib->common_data($brd_key);

        $this->data['list'] = $this->boardlib->post_list($this->data['board'], $this->param);

        $paging['page'] = $this->param['page'];
        $paging['page_rows'] = 20;
        $paging['total_rows'] = $this->data['list']['total_count'];
        $this->load->library('paging', $paging);
        $this->data['pagination'] = $this->paging->create();

        $this->active = "board/" . $brd_key;
        $this->theme = "admin";
        $this->view = "board/posts";
    }


    /**
     * 게시글 읽기
     */
    function read($brd_key, $post_idx="")
    {
        $this->boardlib->common_data($brd_key);
        $this->boardlib->read_process($brd_key, $post_idx);
        $this->theme = "admin";
    }

    /**
     * 게시글 작성/수정
     */
    function write($brd_key, $post_idx="")
    {
        $this->boardlib->common_data($brd_key);
        $this->boardlib->write_process($brd_key, $post_idx);
    }

    /**
     * 댓글 작성/수정 처리부분
     */
    function comment($brd_key, $post_idx)
    {
        $this->boardlib->common_data($brd_key);
        $this->boardlib->comment_process($brd_key, $post_idx);
    }

    /**
     * 댓글 작성/수정 폼
     */
    function comment_modify($cmt_idx="")
    {
        if( ! $comment = $this->db->where('cmt_idx', $cmt_idx)->where('cmt_status', 'Y')->get('board_comment')->row_array() )
        {
            alert_close(langs('게시판/msg/invalid_comment'));
            exit;
        }

        $this->boardlib->common_data($comment['brd_key']);
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
        $this->boardlib->common_data($brd_key);
        $this->boardlib->comment_delete_process($brd_key, $post_idx, $cmt_idx);
    }
}
