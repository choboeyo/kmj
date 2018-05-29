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

        $this->load->model('board_model');
        $board = $this->board_model->get_board($brd_key, $is_raw);

        $this->response($board, 200);
    }

    function category_delete()
    {
        $bca_idx = $this->delete('bca_idx', TRUE);

        if( empty($bca_idx) ) $this->error_return("FAQ 고유키값이 없습니다.", 400);

        $this->db->where('bca_idx', $bca_idx);
        $result = $this->db->delete('board_category');

        $this->response(array('result'=>$result), 200);
    }

    /**
     * 카테고리 순서 정렬
     */
    function category_sort_post()
    {
        $this->load->model('board_model');

        $brd_key = $this->post('brd_key', TRUE);
        $idxs = $this->post('idxs', TRUE);

        $update_array = array();
        foreach($idxs as $i=>$idx)
        {
            $update_array[] = array(
                'bca_idx' => $idx,
                'bca_sort' => $i+1
            );
        }

        $this->db->update_batch("board_category", $update_array, "bca_idx");

        $this->board_model->delete_cache($brd_key);
    }

    function category_count_get()
    {
        $bca_idx = $this->get('bca_idx', TRUE);
        if( empty($bca_idx) ) $this->error_return("카테고리 지정이 잘못되었습니다.", 400);

        $count = (int) $this->db->select('COUNT(*) AS count')->where('bca_parent', $bca_idx)->get('board_category')->row(0)->count;

        $this->response(array("result"=>$count), 200);
    }

    function category_post_count_get()
    {
        $bca_idx = $this->get('bca_idx', TRUE);
        if( empty($bca_idx) ) $this->error_return("카테고리 지정이 잘못되었습니다.", 400);

        $count = (int) $this->db->select('COUNT(*) AS count')->where('bca_idx', $bca_idx)->where_in('post_status',array('Y','B'))->get('board_post')->row(0)->count;

        $this->response(array("result"=>$count), 200);
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





