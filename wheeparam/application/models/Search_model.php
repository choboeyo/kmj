<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Search_model extends WB_Model
{

    /**
     * 카테고리별 검색 결과를 가져온다.
     * @param $search_text
     * @param $search_type
     */
    function search_result_detail( $search_text, $brd_key ="", $limit=0, $page=1)
    {
        $search_array = explode(" ", $search_text);

        $this->db->select("board_post.*, board.brd_title");
        $this->db->from('board_post');
        $this->db->join("board", "board.brd_key=board_post.brd_key", "inner" );
        $this->db->where('post_status','Y');

        $this->db->group_start();
        foreach( $search_array as $st )
        {
            $this->db->like("post_title", trim($st));
            $this->db->or_like("post_nickname", trim($st));
            //$this->db->or_like("post_content", trim($st));
        }
        $this->db->group_end();
        $this->db->order_by("post_idx DESC");

        if( $brd_key )
        {
            $this->db->where('board_post.brd_key', $brd_key);
        }

        if( $limit > 0 )
        {
            $start = ($page-1) * $limit;
            $this->db->limit($limit, $start);
        }

        $result = $this->db->get();
        $list = $result->result_array();

        $this->load->library('boardlib');

        foreach($list as &$row)
        {
            $b = $this->boardlib->get($row['brd_key']);
            $row = $this->boardlib->post_process($b, $row, '',  TRUE, TRUE);
        }

        return $list;
    }

    function search_result( $search_text, $board_key = "total" ,$page =1 )
    {
        $return = array();

        // 검색어를 공백으로 나누어 OR 검색을 한다.
        $search_array = explode(" ", $search_text);

        // 일단 검색어와 일치하는 각각의 개수를 가져온다.
        $this->db->select("board_post.brd_key,brd_title, COUNT(*) AS `cnt`");
        $this->db->from("board_post");
        $this->db->join("board","board.brd_key=board_post.brd_key","inner");
        $this->db->where('post_status','Y');
        foreach($search_array as $st)
        {
            $this->db->like("post_title", trim($st));
            $this->db->or_like("post_nickname", trim($st));
            //$this->db->or_like("post_content", trim($st));
        }
        $this->db->group_by("board_post.brd_key");
        $result = $this->db->get();
        $search_count = $result->result_array();

        // 각 카테고리별 검색수 초기화
        $return['count']['total'] = 0;
        $return['title'] = array();
        $return['title']['total'] = langs('공통/search/search_total');

        // ROW를 돌면서 카테고리별 검색수를 합산해준다.
        foreach($search_count as $ct)
        {
            $return['count'][ $ct['brd_key'] ] = (int)$ct['cnt'];
            $return['title'][ $ct['brd_key'] ] = $ct['brd_title'];
            $return['count']['total'] += (int)$ct['cnt'];
        }

        // 실제 검색결과를 가져온다.
        $return['list']['total'] = array();

        // 검색타입에 따라 가져오는 숫자를 달리한다.
        if( $board_key == 'total' )
        {
            $return['list']['total'] = $this->search_result_detail($search_text,"",8);
            foreach($return['count'] as $brd=>$row)
            {
                if($brd =='total') continue;
                $return['list'][$brd] = $this->search_result_detail($search_text,$brd, 4);
            }
        }
        else {
            $return['list'][$board_key] = $this->search_result_detail($search_text, $board_key, 5, $page);
            unset($return['list']['total']);
        }

        $return['board_key'] = $board_key;

        return $return;
    }

}