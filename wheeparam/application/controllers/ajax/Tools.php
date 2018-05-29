<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require APPPATH . '/libraries/REST_Controller.php';
/**************************************************************
 * Tools API
 *************************************************************/
class Tools extends REST_Controller  {

    function __construct()
    {
        parent::__construct();

        if( !$this->input->is_ajax_request() ) $this->response(["result"=>FALSE,"message"=>langs('공통/msg/invalid_access')], 400);
    }

    /**
     * 통계 DB 최적화
     */
    function optimize_statics_get()
    {
        // 필요없어 보이는 데이타 정리
        $this->db->where('sta_ip', '0');
        $this->db->or_where('sta_ip', '2130706433');
        $this->db->or_where('sta_browser', '');
        $this->db->or_where('sta_platform', 'Unknown Platform');
        $this->db->delete('statics');

        // 국가 코드가 없는것들은 입히기
        $this->load->model('statics_model');
        $this->statics_model->ip_info_update();

        $this->response(array("result"=>TRUE, "message"=>"통계 테이블 최적화가 완료되었습니다."));
    }

    /**
     * IP 위치 조회
     */
    function ip_info_post()
    {
        $ip = $this->post('ip', TRUE);

        if( $info = get_ip_info($ip) )
        {
            $this->db->where('INET_NTOA(sta_ip)', trim($ip) );
            $this->db->set('sta_country',  $info['country'] );
            $this->db->set('sta_country_code', $info['countryCode']);
            $this->db->set('sta_addr', $info['addr']);
            $this->db->set('sta_org', $info['org']);
            $this->db->update('statics');
            $this->response(array('status'=>TRUE,"result"=>$info), 200);
        }
        else {
            $this->response(array('status'=>FALSE, "result"=>"Can Not load Ip info : ". $ip), 400);
        }
    }
}