<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Main extends WB_Controller {

    public function index()
    {
        // 메타태그 설정
        // $this->site->meta_title = "";            // 이 페이지의 타이틀
        // $this->site->meta_description 	= "";   // 이 페이지의 요약 설명
        // $this->site->meta_keywords 		= "";   // 이 페이지에서 추가할 키워드 메타 태그
        // $this->site->meta_image			= "";   // 이 페이지에서 표시할 대표이미지

        // 팝업 불러오기
        $this->load->model('popup_model');
        $param['from'] = "popup";
        $param['where']['pop_status'] = 'Y';
        $param['where']['pop_start <='] = date('Y-m-d H:i:s');
        $param['where']['pop_end >='] = date('Y-m-d H:i:s');
        $param['order_by'] = "pop_idx ASC";
        $param['limit'] = TRUE;

        $this->data['popup_list'] = $this->popup_model->get_list($param);
        $this->asides['popup'] = "main/asides_popup";

        // 레이아웃 & 뷰파일 설정
        $this->theme    = $this->site->get_layout();
        $this->view     = "main/index";
    }

    public function popup($pop_idx)
    {
        if(empty($pop_idx))
        {
            alert_close(langs('공통/msg/invalid_access'));
            exit;
        }

        $this->load->model('popup_model');
        $param['idx'] = $pop_idx;
        $param['column'] = "pop_idx";
        $param['from'] = "popup";
        $param['where']['pop_status'] = 'Y';
        $param['where']['pop_start <='] = date('Y-m-d H:i:s');
        $param['where']['pop_end >='] = date('Y-m-d H:i:s');

        if(! $this->data['view'] = $this->popup_model->get_one($param))
        {
            alert_close(langs('공통/msg/invalid_access'));
            exit;
        }

        $this->theme = $this->site->get_layout();
        $this->theme_file = "popup";
        $this->view = "main/popup";
    }
}
