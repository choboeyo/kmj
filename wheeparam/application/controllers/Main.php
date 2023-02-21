<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * 사이트 메인페이지
 */
class Main extends WB_Controller {

    /**
     * 사이트 메인페이지
     */
    public function index()
    {
        // 메타태그 설정
        // $this->site->meta_title = "";            // 이 페이지의 타이틀
        // $this->site->meta_description 	= "";   // 이 페이지의 요약 설명
        // $this->site->meta_keywords 		= "";   // 이 페이지에서 추가할 키워드 메타 태그
        // $this->site->meta_image			= "";   // 이 페이지에서 표시할 대표이미지

        // 팝업 불러오기
        $this->load->model('popup_model');
        $this->data['popup_list'] = $this->popup_model->get_popups();
        $this->asides['popup'] = "main/asides_popup";

        // 레이아웃 & 뷰파일 설정
        $this->theme    = $this->site->get_layout();
        $this->view     = "main/index";
    }

    /**
     * 새 윈도우창으로 팝업을 열었을경우 처리하는 페이지
     * @param $pop_idx
     * @return void
     */
    public function popup($pop_idx)
    {
        if(empty($pop_idx))
        {
            alert_close(langs('공통/msg/invalid_access'));
            exit;
        }

        $this->load->model('popup_model');
        if(! $this->data['view'] = $this->popup_model->get_popup($pop_idx))
        {
            alert_close(langs('공통/msg/invalid_access'));
            exit;
        }

        $this->theme = $this->site->get_layout();
        $this->theme_file = "popup";
        $this->view = "main/popup";
    }
}
