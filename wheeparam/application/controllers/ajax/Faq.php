<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require APPPATH . '/libraries/REST_Controller.php';
/**************************************************************
 * FAQ API
 *************************************************************/
class Faq extends REST_Controller  {

    function __construct()
    {
        parent::__construct();

        if( !$this->input->is_ajax_request() ) $this->response(["result"=>FALSE,"message"=>langs('공통/msg/invalid_access')], 400);
    }

    function info_delete()
    {
        $this->load->model('faq_model');
        $faq_idx = $this->delete('faq_idx', TRUE);
        if (empty($faq_idx)) $this->error_return("FAQ 고유키값이 없습니다.", 400);

        // 기존 FAQ 값을 불러온다.
        $faq = $this->faq_model->get_faq($faq_idx);

        $this->db->where('faq_idx', $faq_idx)->set('faq_status','N')->update('faq');
        $this->faq_model->update_category_count($faq['fac_idx']);
    }

    function sort_post()
    {
        $sort_idx = $this->input->post("sort_idx", TRUE);
        for($i=1; $i<=count($sort_idx); $i++)
        {
            $this->db->where("faq_idx", $sort_idx[$i-1]);
            $this->db->set("faq_sort", $i);
            $this->db->update("faq");
        }
    }

    function category_get()
    {
        $this->load->model('faq_model');

        $fac_idx = trim($this->get('fac_idx', TRUE));
        if (empty($fac_idx)) $this->error_return("FAQ 그룹 고유키값이 없습니다.", 400);

        $result = $this->faq_model->get_category($fac_idx);

        $this->response($result, 200);
    }

    function category_sort_post()
    {
        $sort_idx = $this->input->post("sort_idx", TRUE);
        for($i=1; $i<=count($sort_idx); $i++)
        {
            $this->db->where("fac_idx", $sort_idx[$i-1]);
            $this->db->set("fac_sort", $i);
            $this->db->update("faq_category");
        }
    }

    function category_delete()
    {
        $fac_idx = $this->delete('fac_idx', TRUE);
        if (empty($fac_idx)) $this->error_return("FAQ 그룹 고유키값이 없습니다.", 400);

        $this->db->where('fac_idx', $fac_idx)->set('fac_status','N')->set('fac_sort','0')->update('faq_category');
        $this->db->where('fac_idx', $fac_idx)->set('faq_status','N')->set('faq_sort','0')->update('faq');
    }

    function lists_get()
    {
        $this->load->model('faq_model');

        $fac_idx = trim($this->get('fac_idx', TRUE));
        if (empty($fac_idx)) $this->error_return("FAQ 그룹 고유키값이 없습니다.", 400);

        $faq_list = $this->faq_model->get_detail_list($fac_idx);
        $this->response($faq_list, 200);
    }
}