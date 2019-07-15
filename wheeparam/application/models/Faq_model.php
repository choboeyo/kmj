<?php
/**
 * Class Faq_model
 * ----------------------------------------------------------
 * FAQ 그룹 및 FAQ 내용에 대한 Model
 */
class Faq_model extends WB_Model {

    /**
     * FAQ 그룹중 하나를 가져온다.
     */
    function get_category($fac_idx)
    {
        if( empty($fac_idx)) return FALSE;

        $param['idx'] = $fac_idx;
        $param['column'] = "fac_idx";
        $param['from'] = "faq_category";
        return $this->get_one($param);
    }

    /**
     * FAQ 그룹의 목록을 가져온다.
     */
    function get_category_list()
    {
        $param['from'] = "faq_category";
        $param['limit'] = FALSE;
        $param['where']['fac_status'] = "Y";
        $param['order_by'] = "sort ASC";

        return $this->get_list($param);
    }

    /**
     * FAQ 그룹의 등록된 FAQ 개수를 최신화 한다.
     * @param $fac_idx
     * @return bool
     */
    function update_category_count($fac_idx)
    {
        if(empty($fac_idx)) return FALSE;

        $count = ((int) $this->db->select('COUNT(faq_idx) AS count')->where('fac_idx', $fac_idx)->where('faq_status','Y')->get('faq')->row(0)->count);
        $this->db->set('fac_count', $count);
        $this->db->where('fac_idx', $fac_idx);
        return $this->db->update('faq_category');
    }

    /**
     * FAQ중 하나를 가져온다.
     */
    function get_faq($faq_idx)
    {
        if( empty($faq_idx)) return FALSE;

        $param['idx'] = $faq_idx;
        $param['column'] = "faq_idx";
        $param['from'] = "faq";
        return $this->get_one($param);
    }

    function get_detail_list($fac_idx="")
    {
        $param['select'] = 'F.*, M.mem_nickname AS upd_username';
        $param['from'] = "faq AS F";
        $param['join'][] = array('member AS M','M.mem_idx=F.upd_user','left');
        if($fac_idx)
        {
            $param['where']['fac_idx'] = $fac_idx;
        }
        $param['where']['faq_status'] = "Y";
        $param['order_by'] = "sort ASC";

        return $this->get_list($param);
    }
}