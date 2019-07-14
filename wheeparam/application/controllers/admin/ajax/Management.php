<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require APPPATH . '/libraries/REST_Controller.php';

class Management extends REST_Controller
{
    /****************************************************************************
     * 공용 순서변경
     ***************************************************************************/
    function sort_post()
    {
        $key = $this->input->post('key', TRUE);
        $sort_idx = $this->input->post("sort_order", TRUE);
        $table = $this->input->post('table', TRUE);
        $sort_col = $this->input->post('sort', TRUE);

        if(empty($key) OR empty($table) or empty($sort_col))
            $this->response(array('message'=>'잘못된 접근입니다.'));

        $update_array = array();
        for($i=1; $i<=count($sort_idx); $i++)
        {
            $update_array[] = array(
                $key => $sort_idx[$i-1],
                $sort_col => $i
            );
        }

        $this->db->update_batch($table, $update_array, $key);
    }

    /**
     * 팝업 목록
     */
    function popups_get() {
        $page_rows = $this->input->get('take', TRUE, 15);
        $start = $this->input->get('skip', TRUE);

        $this->db
            ->select('SQL_CALC_FOUND_ROWS P.*, M.mem_nickname AS upd_username', FALSE)
            ->from('popup AS P')
            ->join('member AS M','M.mem_idx=P.upd_user','inner')
            ->where('pop_status', 'Y')
            ->order_by('pop_idx DESC')
            ->limit($page_rows, $start);
        $result = $this->db->get();

        $return['lists'] = $result->result_array();
        $return['totalCount'] = (int)$this->db->query("SELECT FOUND_ROWS() AS cnt")->row(0)->cnt;

        foreach($return['lists'] as $i=>&$row)
        {
            $row['nums'] = $return['totalCount'] - $i - $start;
            $row['pop_state'] = (strtotime($row['pop_start']) <= time() && strtotime($row['pop_end'])  >= time())?'표시중':'미표시중';
        }

        $this->response($return, 200);
    }

    /**
     * 팝업 삭제
     */
    function popups_delete()
    {
        $pop_idx = $this->delete('pop_idx', TRUE);
        $mem_idx = $this->member->is_login();

        if(empty($pop_idx)) $this->response('잘못된 접근입니다.', 400);

        $data['upd_datetime'] = date('Y-m-d H:i:s');
        $data['upd_user'] = $mem_idx;
        $data['pop_status'] = 'N';
        $this->db->where('pop_idx', $pop_idx);
        $this->db->update('popup', $data);
    }

    /**
     * 사이트맵 목록
     */
    function sitemaps_get()
    {
        $page_rows = $this->input->get('take', TRUE, 15);
        $start = $this->input->get('skip', TRUE);
        $this->db
            ->select('SQL_CALC_FOUND_ROWS S.*, M.mem_nickname AS upd_username', FALSE)
            ->from('sitemap AS S')
            ->join('member AS M','M.mem_idx=S.upd_user','inner')
            ->order_by('sit_idx DESC')
            ->limit($page_rows, $start);
        $result = $this->db->get();

        $return['lists'] = $result->result_array();
        $return['totalCount'] = (int)$this->db->query("SELECT FOUND_ROWS() AS cnt")->row(0)->cnt;
        $this->response($return, 200);
    }

    function sitemaps_delete()
    {
        $sit_idx = $this->delete('sit_idx', TRUE);
        if(empty($sit_idx)) $this->response(array('message'=>'잘못된 접근입니다.'), 400);

        $this->db->where('sit_idx', $sit_idx)->delete('sitemap');
    }

    // Q&A 분류 삭제
    function qna_category_delete()
    {
        $qnc_idx = $this->delete('qnc_idx', TRUE);
        if(empty($qnc_idx)) $this->response(array('message'=>'잘못된 접근입니다.'), 400);

        $data['upd_user'] = $this->member->is_login();
        $data['upd_datetime'] = date('Y-m-d H:i:s');
        $data['qnc_status'] = 'N';

        $this->db->where('qnc_idx', $qnc_idx)->update('qna_category', $data);
    }

    function qna_delete()
    {
        $qna_idx=  $this->delete('qna_idx', TRUE);

        if(empty($qna_idx)) $this->response(array('message'=>'잘못된 접근입니다.'), 400);

        $data['upd_user'] = $this->member->is_login();
        $data['upd_datetime'] = date('Y-m-d H:i:s');
        $data['qna_status'] = 'N';

        $this->db->where('qna_idx', $qna_idx)->update('qna', $data);
    }

    function qna_get()
    {
        $startdate = $this->get('startdate', TRUE);
        $enddate = $this->get('enddate', TRUE);
        $qna_ans_status = $this->get('qna_ans_status', TRUE);
        $st = $this->get('st', TRUE);
        $sc = $this->get('sc', TRUE);

        $page_rows = $this->get('take', TRUE);
        $start = $this->get('skip', TRUE);

        if(! empty($page_rows)) $this->db->limit($page_rows, $start);
        if(! empty($startdate)) $this->db->where('reg_datetime >=', $startdate.' 00:00:00');
        if(! empty($enddate)) $this->db->where('reg_datetime <=', $enddate.' 23:59:59');
        if(! empty($qna_ans_status)) $this->db->where('qna_ans_status', $qna_ans_status);
        if(! empty($st) && ! empty($sc)) $this->db->like($sc, $st);

        $this->db->select("SQL_CALC_FOUND_ROWS Q.*, QC.qnc_title, M.mem_nickname AS qna_ans_upd_username",FALSE);
        $this->db->order_by('qna_idx DESC');
        $this->db->from('qna AS Q');
        $this->db->join('qna_category AS QC','QC.qnc_idx=Q.qnc_idx','left');
        $this->db->join('member AS M','M.mem_idx=Q.qna_ans_user','left');
        $this->db->where('qna_status','Y');


        $result = $this->db->get();
        $return['lists'] = $result->result_array();
        $return['totalCount'] = (int)$this->db->query("SELECT FOUND_ROWS() AS cnt")->row(0)->cnt;

        foreach($return['lists'] as $i=>&$row)
        {
            $row['nums'] = $return['totalCount'] - $i - $start;
        }

        $this->response($return, 200);
    }

    /**
     * 공용 셀 에디트
     */
    function updates_post()
    {
        $table = $this->post('table', TRUE);
        $key_column = $this->post('key_column', TRUE);
        $key = $this->post('key', TRUE);
        $values = $this->post('values', TRUE);

        if(empty($table) OR empty($key_column) OR empty($key)) $this->response(array('message'=>'잘못된 접근입니다.'), 400);

        $values['upd_datetime'] = date('Y-m-d H:i:s');
        $values['upd_user'] = $this->member->is_login();

        $this->db->where($key_column, $key);
        $this->db->update($table, $values);
    }
}