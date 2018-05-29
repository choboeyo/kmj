<?php
/**
 * Class Member_model
 * ----------------------------------------------------------
 * 회원 관련 모델
 */
class Member_model extends WB_Model
{
    /**
     * 회원목록
     * @param array $param
     * @return mixed
     */
    function member_list($param=array())
    {
        $social_table = $this->db->dbprefix('member_social');

        $param['from'] = "member";
        $param['limit'] = TRUE;
        $param['page_rows'] = element('page_rows', $param, 20);
        $param['page']  = element('page', $param, 1);
        $param['order_by'] = "member.mem_idx DESC";
        $param['join'][] = array("(SELECT mem_idx AS social_naver FROM {$social_table} WHERE soc_provider = 'naver') AS SN","SN.social_naver=member.mem_idx","left");
        $param['join'][] = array("(SELECT mem_idx AS social_facebook FROM {$social_table} WHERE soc_provider = 'facebook') AS SF","SF.social_facebook=member.mem_idx","left");
        $param['join'][] = array("(SELECT mem_idx AS social_google FROM {$social_table} WHERE soc_provider = 'google') AS SG","SG.social_google=member.mem_idx","left");
        $param['join'][] = array("(SELECT mem_idx AS social_kakao FROM {$social_table} WHERE soc_provider = 'kakao') AS SK","SK.social_kakao=member.mem_idx","left");

        $list = $this->get_list($param);

        // 데이타 가공
        foreach($list['list'] as $i=>&$row)
        {
            $row['nums'] = $list['total_count'] - $i - (($param['page'] - 1) * $param['page_rows']);
        }

        return $list;
    }

    function log_list($param=array())
    {
        $member_table = $this->db->dbprefix('member');
        $member_log_table = $this->db->dbprefix('member_log');

        $param['select'] = "{$member_log_table}.*, {$member_table}.mem_userid, {$member_table}.mem_nickname, {$member_table}.mem_status";
        $param['from'] = "member_log";
        $param['limit'] = TRUE;
        $param['page_rows'] = element('page_rows', $param, 20);
        $param['page']  = element('page', $param, 1);
        $param['order_by'] = "member_log.mlg_idx DESC";
        $param['join'][] = array("member", "member.mem_idx=member_log.mem_idx","inner");

        $list = $this->get_list($param);

        return $list;
    }

    /**
     * 포인트 목록
     * @param $mem_idx
     * @param $param
     * @return mixed
     */
    function point_list($mem_idx, $param)
    {
        $param['from'] = "member_point";
        $param['limit'] = TRUE;
        $param['where']['mem_idx'] = $mem_idx;
        $param['page_rows'] = element('page_rows', $param, 20);
        $param['page']  = element('page', $param, 1);
        $param['order_by'] = "mem_idx DESC";

        if( element('startdate', $param)) $param['where']['mpo_regtime >='] = $param['startdate'] . " 00:00:00";
        if( element('enddate', $param) ) $param['where']['mpo_regtime <='] = $param['enddate'] . " 23:59:59";

        $list = $this->get_list($param);

        // 데이타 가공
        foreach($list['list'] as $i=>&$row)
        {
            $row['nums'] = $list['total_count'] - $i - (($param['page'] - 1) * $param['page_rows']);
        }

        return $list;
    }
}