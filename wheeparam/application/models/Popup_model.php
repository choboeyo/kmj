<?php
/**
 * Class Banner_model
 * ----------------------------------------------------------
 * 배너 그룹 및  내용에 대한 Model
 */
class Popup_model extends WB_Model
{
    function get_popups()
    {
        $param['from'] = "popup";
        $param['where']['pop_status'] = 'Y';
        $param['where']['pop_start <='] = date('Y-m-d H:i:s');
        $param['where']['pop_end >='] = date('Y-m-d H:i:s');
        $param['order_by'] = "pop_idx ASC";
        $param['limit'] = TRUE;

        return $this->get_list($param);
    }

    function get_popup($pop_idx) {
        $param['idx'] = $pop_idx;
        $param['column'] = "pop_idx";
        $param['from'] = "popup";
        $param['where']['pop_status'] = 'Y';
        $param['where']['pop_start <='] = date('Y-m-d H:i:s');
        $param['where']['pop_end >='] = date('Y-m-d H:i:s');

        return $this->get_one($param);
    }
}