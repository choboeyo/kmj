<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require APPPATH . '/libraries/REST_Controller.php';

class Setting extends REST_Controller
{
    function admins_get()
    {
        $page_rows = $this->input->get('take', TRUE, 15);
        $start = $this->input->get('skip', TRUE);

        $this->db
            ->select('SQL_CALC_FOUND_ROWS M.*', FALSE)
            ->from('member_auth AS MA')
            ->join('member AS M','MA.mem_idx=M.mem_idx','inner')
            ->where('MA.ath_type','SUPER')
            ->where('M.mem_status','Y')
            ->order_by('M.mem_nickname ASC')
            ->limit($page_rows, $start);
        $result = $this->db->get();

        $return['lists'] = $result->result_array();
        $return['totalCount'] = (int)$this->db->query("SELECT FOUND_ROWS() AS cnt")->row(0)->cnt;

        foreach($return['lists'] as $i=>&$row)
        {
            $row['nums'] = $return['totalCount'] - $i - $start;
            $row['logip'] = long2ip((int)$row['mem_logip']);
            $row['regip'] = long2ip((int)$row['mem_regip']);
        }

        $this->response($return, 200);
    }

    /**************************************************************
     * 사용자를 관리자로 추가
     ***************************************************************/
    function admins_post()
    {
        if(! $this->member->is_super() )
            $this->response(array('status'=>FALSE, 'message'=>'권한이 없습니다.'), 400);

        $mem_idx = $this->post('mem_idx', TRUE);
        if(empty($mem_idx))
            $this->response(array('status'=>FALSE, 'message'=>'관리자로 설정할 회원이 존재하지 않습니다.'), 400);

        if(! $mem = $this->member->get_member($mem_idx,'mem_idx') )
        {
            $this->response(array('status'=>FALSE, 'message'=>'존재하지 않는 회원이거나 이미 탈퇴한 회원입니다.'), 400);
        }

        if($mem['mem_status'] != 'Y')
        {
            $this->response(array('status'=>FALSE, 'message'=>'존재하지 않는 회원이거나 이미 탈퇴한 회원입니다.'), 400);
        }

        $this->db->trans_start();

        $this->db->set('mem_idx', $mem_idx)->set('ath_type', 'SUPER')->insert('member_auth');
        $this->db->set('mem_auth',10)->where('mem_idx', $mem_idx)->update('member');

        if ($this->db->trans_status() === FALSE)
        {
            $this->db->trans_rollback();
            $this->response(array('status'=>FALSE, 'message'=>'관리자로 설정하는데 실패하였습니다. 이미 관리자로 설정된 회원이가나, DB오류입니다.'), 500);
        }
        else
        {
            $this->db->trans_commit();
            $this->response(array('status'=>TRUE, 'message'=>'관리자 추가 완료'), 200);
        }
    }

    /**************************************************************
     * 관리자 권한 삭제
     ***************************************************************/
    function admins_delete()
    {
        if(! $this->member->is_super() ) $this->response(array('status'=>FALSE, 'message'=>'권한이 없습니다.'), 400);
        $mem_idx = $this->delete('mem_idx', TRUE);
        if(empty($mem_idx)) $this->response(array('status'=>FALSE, 'message'=>'회원이 존재하지 않습니다.'), 400);
        if(! $mem = $this->member->get_member($mem_idx,'mem_idx') ) $this->response(array('status'=>FALSE, 'message'=>'존재하지 않는 회원이거나 이미 탈퇴한 회원입니다.'), 400);

        $this->db->trans_start();

        $this->db->where('mem_idx', $mem_idx)->where('ath_type', 'SUPER')->delete('member_auth');
        $this->db->set('mem_auth',1)->where('mem_idx', $mem_idx)->update('member');

        if ($this->db->trans_status() === FALSE)
        {
            $this->db->trans_rollback();
            $this->response(array('status'=>FALSE, 'message'=>'권한을 삭제하는데 실패하였습니다. 관리자 권한이 없는 회원이거나, DB오류입니다.'), 500);
        }
        else
        {
            $this->db->trans_commit();
            $this->response(array('status'=>TRUE, 'message'=>'관리자 삭제 완료'), 200);
        }
    }
}