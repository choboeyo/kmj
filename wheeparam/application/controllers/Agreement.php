<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * Class Agreement
 * ------------------------------------------------------------------------------
 * 사이트이용약관 페이지
 * 개인정보취급방침 페이지
 */
class Agreement extends WB_Controller {

    /**
     * 사이트 이용약관 페이지
     */
    public function site()
    {
        // 메타태그 설정
        $this->site->meta_title = "사이트 이용약관";            // 이 페이지의 타이틀
        $this->site->meta_description 	= get_summary($this->site->config('agreement_site'), FALSE);   // 이 페이지의 요약 설명

        // 레이아웃 & 뷰파일 설정
        $this->theme    = $this->site->get_layout();
        $this->view     = "agreement/site";
        $this->active   = "agreement/site";
    }

    /**
     * 개인정보 처리방침 페이지
     */
    public function privacy()
    {
        // 메타태그 설정
        $this->site->meta_title = "개인정보 처리방침";            // 이 페이지의 타이틀
        $this->site->meta_description 	= get_summary($this->site->config('agreement_privacy'), FALSE);   // 이 페이지의 요약 설명

        // 레이아웃 & 뷰파일 설정
        $this->theme    = $this->site->get_layout();
        $this->view     = "agreement/privacy";
        $this->active   = "agreement/privacy";
    }
}
