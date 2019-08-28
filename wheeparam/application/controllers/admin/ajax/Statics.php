<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require APPPATH . '/libraries/REST_Controller.php';

class Statics extends REST_Controller
{
    function visit_get()
    {
        // 모델 가져오기
        $start_date = $this->get('start_date', TRUE);
        $end_date = $this->get('end_date', TRUE);
        $is_mobile = $this->get('is_mobile', TRUE);
        $ip = $this->get('ip', TRUE);

        $page_rows = $this->input->get('take', TRUE, 15);
        $start = $this->input->get('skip', TRUE);

        if(! empty($start_date)) $this->db->where('sta_regtime >=', $start_date . ' 00:00:00');
        if(! empty($end_date)) $this->db->where('sta_regime <=', $end_date, ' 23:59:59');
        if(! empty($ip)) $this->db->like('INET_NTOA(sta_ip)', $ip);
        if(! empty($is_mobile)) $this->db->where('sta_is_mobile', $is_mobile);

        $this->db
            ->select('SQL_CALC_FOUND_ROWS *', FALSE)
            ->from('statics')
            ->order_by('sta_idx DESC')
            ->limit($page_rows, $start);
        $result = $this->db->get();

        $return['lists'] = $result->result_array();
        $return['totalCount'] = (int)$this->db->query("SELECT FOUND_ROWS() AS cnt")->row(0)->cnt;

        foreach($return['lists'] as $i=>&$row)
        {
            $row['nums'] = $return['totalCount'] - $i - $start;
            $row['sta_ip'] = long2ip((int)$row['sta_ip']);
            $row['sta_is_mobile'] = ($row['sta_is_mobile'] == 'Y');
            $row['sta_device'] = $row['sta_is_mobile'] ? $row['sta_mobile'] : $row['sta_platform'];
            $row['sta_browser'] = $row['sta_browser'] == 'Internet Explorer' ? $row['sta_browser'] .' ' .$row['sta_version'] : $row['sta_browser'];
        }

        $this->response($return, 200);
    }
}