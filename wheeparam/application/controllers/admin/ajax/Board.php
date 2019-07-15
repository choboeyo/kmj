<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require APPPATH . '/libraries/REST_Controller.php';

class Board extends REST_Controller
{
    /********************************************************************
     * 게시판 목록 가져오기
     *******************************************************************/
    function index_get()
    {
        $return['lists'] =
            $this->db
                ->select('SQL_CALC_FOUND_ROWS *', FALSE)
                ->order_by('reg_datetime DESC')
                ->get('board')
                ->result_array();
        $return['totalCount'] = (int)$this->db->query("SELECT FOUND_ROWS() AS cnt")->row(0)->cnt;

        $this->response($return, 200);
    }
}