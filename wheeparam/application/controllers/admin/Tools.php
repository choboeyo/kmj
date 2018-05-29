<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Tools extends WB_Controller {

    public function index()
    {
        // 메타태그 설정
        $this->site->meta_title = "기타 도구";            // 이 페이지의 타이틀
        // $this->site->meta_description 	= "";   // 이 페이지의 요약 설명
        // $this->site->meta_keywords 		= "";   // 이 페이지에서 추가할 키워드 메타 태그
        // $this->site->meta_image			= "";   // 이 페이지에서 표시할 대표이미지

        // 레이아웃 & 뷰파일 설정
        $this->theme    = "admin";
        $this->view     = "tools/index";
        $this->active   = "tools/index";
    }
}
