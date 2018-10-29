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
        // 모델 가져오기
        $this->load->model('statics_model');

        // 넘어온 검색값 정리
        $this->data['startdate'] = $this->input->get('startdate', TRUE, date('Y-m-d', strtotime("-1 month", time())));
        $this->data['enddate'] = $this->input->get('enddate', TRUE, date('Y-m-d'));
        $this->data['is_mobile'] = $this->input->get('is_mobile', TRUE, array('Y','N'));
        $this->data['ip'] = $this->input->get('ip', TRUE, '');

        // 값 가져오기
        $param['page'] = $this->input->get('page', TRUE, 1);
        $param['page_rows'] = 15;
        $param['limit'] = TRUE;
        $param['where']['sta_regtime >='] = $this->data['startdate'] ." 00:00:00";
        $param['where']['sta_regtime <='] = $this->data['enddate'] ." 23:59:59";
        if(! empty($this->data['ip'])) $param['where']['INET_NTOA(sta_ip)'] = $this->data['ip'];
        $param['where_in']['sta_is_mobile'] = $this->data['is_mobile'];
        $this->data['visit_list'] = $this->statics_model->visit_list($param);

        // 페이지네이션 세팅
        $this->load->library('paging');
        $this->paging->initialize(array(
            "page" => $param['page'],
            "page_rows" => $param['page_rows'],
            "total_rows" => $this->data['visit_list']['total_count'],
            "fixe_nums" => 10,
            'full_tag_open' => '<ul class="pagination pagination-sm">'
        ));
        $this->data['pagination'] = $this->paging->create();


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
