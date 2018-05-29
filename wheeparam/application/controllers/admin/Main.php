<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Main extends WB_Controller {

    public function index()
    {
        // 메타태그 설정
        $this->site->meta_title = "관리자 대시보드";

        // 최근 방문자수 구해오기
        $this->data['count_list']
            = $this->db
                ->where('std_date >=', date('Y-m-d', strtotime("-1 month")) . ' 00:00:00' )
                ->where('std_date <=', date('Y-m-d') . ' 23:59:59' )
                ->order_by('std_date ASC')
                ->get('statics_date')
                ->result_array();

        $this->data['month_count'] = array(
            "sumT" => 0,
            "sumM" => 0
        );
        $this->data['today_count'] = array(
            "sumT" => 0,
            "sumM" => 0
        );

        $this->data['month_data'] = array();
        $this->data['month_mobile'] = array();
        $this->data['month_label'] = array();

        foreach($this->data['count_list'] as $row)
        {
            if( $row['std_date'] == date('Y-m-d') )
            {
                $this->data['today_count']['sumT'] += $row['std_count'];
                $this->data['today_count']['sumM'] += $row['std_mobile'];
            }

            $this->data['month_count']['sumT'] += $row['std_count'];
            $this->data['month_count']['sumM'] += $row['std_mobile'];

            $this->data['month_data'][] = $row['std_count'] - $row['std_mobile'];
            $this->data['month_mobile'][] = $row['std_mobile'];
            $this->data['month_label'][] = $row['std_date'];
        }

        $this->data['month_data'] = json_encode($this->data['month_data'], JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES);
        $this->data['month_mobile'] = json_encode($this->data['month_mobile'], JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES);
        $this->data['month_label'] = json_encode($this->data['month_label'], JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES);

        // 총 회원수 구해오기
        $member_list = $this->db->select('mem_status, COUNT(*) AS cnt')->where_in('mem_status', array('Y',"H",'D'))->group_by('mem_status')->get('member')->result_array();

        $this->data['total_member'] = 0;
        $this->data['total_member_h'] = 0;
        $this->data['total_member_d'] = 0;
        foreach($member_list as $row)
        {
            if ($row['mem_status'] == 'D') $this->data['total_member_d'] = $row['cnt'];
            else if ($row['mem_status'] == 'H') $this->data['total_member_h'] = $row['cnt'];

            if($row['mem_status'] != 'N') {
                $this->data['total_member']  += $row['cnt'];
            }
        }

        $this->data['total_count']
            = $this->db
                ->select('SUM(std_count) AS sumT, SUM(std_mobile) AS sumM')
                ->get('statics_date')
                ->row_array();

        // 레이아웃 & 뷰파일 설정
        $this->theme    = "admin";
        $this->view     = "main/index";
    }
}
