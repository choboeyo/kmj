<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require APPPATH . '/libraries/REST_Controller.php';
/**************************************************************
 * Board API
 *************************************************************/
class Board extends REST_Controller
{
    function __construct()
    {
        parent::__construct();

        if (!$this->input->is_ajax_request()) $this->response(array("result" => FALSE, "message" => langs('공통/msg/invalid_access')), 400);
    }

    function info_get()
    {
        $brd_key = $this->get('brd_key', TRUE);
        $is_raw = $this->get('is_raw', TRUE) == TRUE ? TRUE : FALSE;

        if (empty($brd_key)) $this->error_return("FAQ 고유키값이 없습니다.", 400);

        $this->load->library('boardlib');
        $board = $this->boardlib->get($brd_key, $is_raw);

        $this->response($board, 200);
    }

    /**
     * 게시물 삭제
     */
    function posts_delete()
    {
        $idxs = $this->delete('post_idx', TRUE);

        if(count($idxs) <= 0)
            $this->response(array('status'=>FALSE, 'message'=>'삭제할 게시물을 선택해주세요'), 400);

        $this->db->where_in('post_idx', $idxs);
        $this->db->set('post_status', 'N');
        $this->db->update('board_post');
    }

    /**
     * 게시물 승인
     */
    function assign_post()
    {
        $post_idx = $this->post('post_idx', TRUE);
        $post_assign = $this->post('post_assign', TRUE);

        if(empty($post_idx))
            $this->response(array('status'=>FALSE, 'message'=>'잘못된 접근입니다.'), 400);

        if(! in_array($post_assign, array('Y','N')))
            $this->response(array('status'=>FALSE, 'message'=>'잘못된 접근입니다.'), 400);

        $this->db->where('post_idx', $post_idx);
        $this->db->set('post_assign',$post_assign);
        $this->db->update('board_post');
    }

}





