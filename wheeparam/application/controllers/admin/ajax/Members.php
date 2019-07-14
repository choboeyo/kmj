<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require APPPATH . '/libraries/REST_Controller.php';

class Members extends REST_Controller
{
    /**************************************************************
     * 사용자 목록
     ***************************************************************/
    function index_get()
    {
        $page_rows = $this->get('take', TRUE);
        $start = $this->get('skip', TRUE);

        $sdate = $this->input->get('sdate', TRUE);
        $startdate = $this->input->get('startdate', TRUE);
        $enddate = $this->input->get('enddate', TRUE);

        if(! empty($sdate) && !empty($startdate)) $this->db->where('mem_'.$sdate.' >=', $startdate.' 00:00:00');
        if(! empty($sdate) && !empty($enddate)) $this->db->where('mem_'.$sdate.' <=', $enddate.' 23:59:59');

        if(empty($page_rows)) $this->db->limit($page_rows, $start);

        $order_by = 'mem_idx DESC';
        $this->db->order_by($order_by);

        $this->db->select("SQL_CALC_FOUND_ROWS *", FALSE);
        $this->db->from('member');

        $result = $this->db->get();
        $return['lists'] = $result->result_array();
        $return['totalCount'] = (int)$this->db->query("SELECT FOUND_ROWS() AS cnt")->row(0)->cnt;

        foreach($return['lists'] as $i=>&$row) {
            $row['nums'] = $return['totalCount'] - (int)$start - $i;
            $row['mem_regip'] = long2ip((int)$row['mem_regip']);
            $row['mem_logip'] = long2ip((int)$row['mem_logip']);
        }

        $this->response($return, 200);
    }
    
    /**************************************************************
     * 사용자 상태 변경
     ***************************************************************/
    function status_post()
    {
        if(! $this->member->is_super() )
            $this->response(array('status'=>FALSE, 'message'=>'권한이 없습니다.'), 400);

        $mem_idx = $this->post('mem_idx', TRUE);
        $current_status = $this->post('current_status', TRUE);
        $change_status = $this->post('change_status', TRUE);

        if(empty($mem_idx))
            $this->response(array('status'=>FALSE, 'message'=>'관리자로 설정할 회원이 존재하지 않습니다.'), 400);

        if(! $mem = $this->member->get_member($mem_idx,'mem_idx') )
        {
            $this->response(array('status'=>FALSE, 'message'=>'존재하지 않는 회원입니다.'), 400);
        }

        if( $mem['mem_status'] != $current_status )
        {
            $this->response(array('status'=>FALSE, 'message'=>'변경전 회원상태가 실제 DB상 회원상태와 일치하지 않습니다.'), 400);
        }

        if( ! in_array($change_status, array('Y','N','D','H')))
        {
            $this->response(array('status'=>FALSE, 'message'=>'변경하려는 회원상태가 올바르지 않습니다.'), 400);
        }

        $this->db->trans_start();

        if( $change_status == 'Y' ) {
            $this->db->set('mem_leavetime', '0000-00-00 00:00:00');
            $this->db->set('mem_bantime', '0000-00-00 00:00:00');
            $this->db->set('mem_htime', '0000-00-00 00:00:00');
        }
        else if ($change_status == 'B') {
            $this->db->set('mem_bantime', date('Y-m-d H:i:s'));
        }
        else if ($change_status == 'N') {
            $this->db->set('mem_leavetime', date('Y-m-d H:i:s'));
        }
        else if ($change_status == 'H') {
            $this->db->set('mem_htime', date('Y-m-d H:i:s'));
        }

        $this->db->set('mem_status', $change_status)->where('mem_idx', $mem_idx)->update('member');

        if ($this->db->trans_status() === FALSE)
        {
            $this->db->trans_rollback();
            $this->response(array('status'=>FALSE, 'message'=>'시스템 오류가 발생하였습니다.'), 500);
        }
        else
        {
            $this->db->trans_commit();
            $this->response(array('status'=>TRUE, 'message'=>'회원 상태변경이 완료되었습니다.'), 200);
        }
    }
    
    function logs_get()
    {
        $page_rows = $this->get('take', TRUE);
        $start = $this->get('skip', TRUE);

        $start_date = $this->input->get('startdate', TRUE, date('Y-m-d', strtotime("-1 month", time())));
        $end_date = $this->input->get('enddate', TRUE, date('Y-m-d'));
        $st   = $this->input->get('st', TRUE);
        $sc  = $this->input->get('sc', TRUE);

        if ( !empty($st) && !empty($sc) )
        {
            if( $sc ==  'nickname' OR $sc ==  'userid')
            {
                $sc = "member_log.mem_" . $sc;
                $this->db->like($sc, $st);
            }
            else if ( $sc == 'idx' )
            {
                $this->db->where('member_log.mem_idx', $st);
            }
        }

        if(! empty($start_date)) $this->db->where('mlg_regtime >=',$start_date . " 00:00:00");
        if(! empty($end_date)) $this->db->where('mlg_regtime <=',$end_date . " 23:59:59");

        if(! empty($page_rows)) $this->db->limit($page_rows, $start);
        $this->db->select("SQL_CALC_FOUND_ROWS *", FALSE);
        $this->db->from('member_log');
        $this->db->join('member','member.mem_idx=member_log.mem_idx','inner');

        $result = $this->db->get();
        $return['lists'] = $result->result_array();
        $return['totalCount'] = (int)$this->db->query("SELECT FOUND_ROWS() AS cnt")->row(0)->cnt;

        foreach($return['lists'] as $i=>&$row) {
            $row['nums'] = $return['totalCount'] - $i - $start;
            $row['mlg_is_mobile'] = $row['mlg_is_mobile'] == 'Y';
            $row['mlg_ip'] =  long2ip((int)$row['mlg_ip']);
        }

        $this->response($return, 200);
    }

    /**************************************************************
     * 포인트 목록
     ***************************************************************/
    function points_get()
    {
        $start_date = $this->get('startdate', TRUE);
        $end_date = $this->get('enddate', TRUE);
        $target_type = $this->get('target_type', TRUE);
        $mem_idx = $this->get('mem_idx', TRUE);
        $page_rows = $this->get('take', TRUE);
        $start = $this->get('skip', TRUE);

        if( ! empty($target_type) ) $this->db->where('target_type', $target_type);
        if( ! empty($start_date)) $this->db->where('reg_datetime >= ', $start_date . ' 00:00:00');
        if( ! empty($end_date)) $this->db->where('reg_datetime <= ', $end_date . ' 23:59:59');
        if( ! empty($mem_idx)) $this->db->where('MP.mem_idx', $mem_idx);
        if( ! empty($page_rows)) $this->db->limit($page_rows, $start);

        $this->db->order_by('mpo_idx DESC');
        $this->db->select("SQL_CALC_FOUND_ROWS MP.*, M.*", FALSE);
        $this->db->from('member_point AS MP');
        $this->db->join('member AS M','M.mem_idx=MP.mem_idx','inner');

        $result = $this->db->get();
        $return['lists'] = $result->result_array();
        $return['totalCount'] = (int)$this->db->query("SELECT FOUND_ROWS() AS cnt")->row(0)->cnt;



        foreach($return['lists'] as $i=>&$row)
        {
            $row['nums'] = $return['totalCount'] - $i - $start;
            $row['target_type'] = point_type($row['target_type']);
        }

        $this->response($return, 200);
    }

}