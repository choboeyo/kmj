<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Statics extends WB_Controller {

    function __construct()
    {
        parent::__construct();

        $this->load->model('statics_model');
        $this->data['startdate'] = $this->input->get('startdate', TRUE, date('Y-m-d', strtotime("-1 month")));
        $this->data['enddate'] = $this->input->get('enddate', TRUE, date('Y-m-d', strtotime("-1 days")));
    }

    /**
     * 사용자 접속 로그
     */
    public function visit()
    {
        // 메타태그 설정
        $this->site->meta_title = "사용자 접속 로그";            // 이 페이지의 타이틀

        // 레이아웃 & 뷰파일 설정
        $this->theme    = "admin";
        $this->view     = "statics/visit";
        $this->active   = "statics/visit";
    }

    /**
     * 키워드별 통계
     */
    public function keyword()
    {
        $this->data['statics'] = $this->statics_model->statics_group('sta_keyword', $this->data['startdate'], $this->data['enddate'] );

        // 메타태그 설정
        $this->site->meta_title = "키워드별 통계";

        // 레이아웃 & 뷰파일 설정
        $this->theme    = "admin";
        $this->view     = "statics/keyword";
        $this->active   = "statics/keyword";
    }

    /**
     * 방문시간별 통계
     * @todo 작업해야됨
     */
    public function times()
    {
        $this->data['statics'] = $this->statics_model->statics_times( $this->data['startdate'], $this->data['enddate'] );

        // 메타태그 설정
        $this->site->meta_title = "방문 시간별 통계";

        // 레이아웃 & 뷰파일 설정
        $this->theme    = "admin";
        $this->view     = "statics/times";
        $this->active   = "statics/times";
    }

    /**
     * 유입 경로별 통계
     */
    public function referrer()
    {
        $this->data['statics'] = $this->statics_model->statics_group('sta_referrer_host', $this->data['startdate'], $this->data['enddate'] );

        // 메타태그 설정
        $this->site->meta_title = "유입 경로별 통계";

        // 레이아웃 & 뷰파일 설정
        $this->theme    = "admin";
        $this->view     = "statics/referrer";
        $this->active   = "statics/referrer";
    }

    /**
     * PC/MOBILE 통계
     */
    public function device()
    {
        $this->data['statics'] = $this->statics_model->statics_device( $this->data['startdate'], $this->data['enddate'] );

        // 메타태그 설정
        $this->site->meta_title = "PC/MOBILE 통계";

        // 레이아웃 & 뷰파일 설정
        $this->theme    = "admin";
        $this->view     = "statics/device";
        $this->active   = "statics/device";
    }

    /**
     * 브라우져별 통계
     */
    public function browser()
    {
        $this->data['statics'] = $this->statics_model->statics_group('sta_browser', $this->data['startdate'], $this->data['enddate'] );

        // 메타태그 설정
        $this->site->meta_title = "브라우져별 통계";

        // 레이아웃 & 뷰파일 설정
        $this->theme    = "admin";
        $this->view     = "statics/browser";
        $this->active   = "statics/browser";
    }

    /**
     * OS별 통계
     */
    public function os()
    {
        $this->data['statics'] = $this->statics_model->statics_group('sta_platform', $this->data['startdate'], $this->data['enddate'] );
        // 메타태그 설정
        $this->site->meta_title = "OS별 통계";

        // 레이아웃 & 뷰파일 설정
        $this->theme    = "admin";
        $this->view     = "statics/os";
        $this->active   = "statics/os";
    }
}
