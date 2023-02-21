<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 *
 *-------------------------------------------------------------
 * WB_Model
 *-------------------------------------------------------------
 * CI_Model 를 확장합니다.
 *
 * @property CI_Loader $load
 * @property CI_DB $db
 * @property CI_Output $output
 * @property CI_Cache $cache
 * @property CI_Email $email
 * @property CI_Session $session
 * @property CI_User_agent $agent
 * @property WB_Input $input
 * @property WB_Form_validation $form_validation
 * @property WB_Upload $upload
 * @property Faq_model $faq_model
 * @property Member_model $member_model
 * @property Popup_model $popup_model
 * @property Search_model $search_model
 * @property Statics_model $statics_model
 * @property Site $site
 * @property Boardlib $boardlib
 * @property Paging $paging
 * @property Member $member
 * @property Banner $banner
 */
class WB_Model extends CI_Model
{
    protected $_table 	= NULL;
    protected $_pk		= NULL;
    protected $_status	= NULL;

    function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    /**
     * PK값으로 검색하여 한 행을 반환합니다.
     */
    function get_one( $param=array())
    {
        $param['idx']       = element('idx', $param);
        $param['column']    = element('column', $param, $this->_pk);
        $param['select']    = element('select', $param, '*');
        $param['from']      = element('from', $param, $this->_table);
        $param['where']     = element('where', $param);
        $param['limit']     = element('limit', $param, 1);

        if(! $param['idx']) return FALSE;

        $this->db->select($param['select']);
        $this->db->from( $param['from'] );
        $this->db->where( $param['column'], $param['idx'] );

        // WHERE 조건이 들어있을경우
        if( is_array($param['where']) && !empty($param['where']) && count($param['where']) > 0 )
        {
            foreach($param['where'] as $key => $val)
            {
                $this->db->where($key, $val);
            }
        }

        // 기본적으로 한줄만 가져온다.
        $this->db->limit( $param['limit'] );

        $result = $this->db->get();

        if( $result->num_rows() > 0 )
        {
            return $result->row_array();
        }
        else
        {
            return FALSE;
        }
    }

    function get_list( $param = array() )
    {
        $param['select'] 	= ( isset($param['select']) && $param['select'] ) ? $param['select'] : "*";
        $param['from']		= ( isset($param['from']) && $param['from'] ) ? $param['from'] : $this->_table;
        $param['join']		= ( isset($param['join']) && $param['join'] && is_array($param['join']) ) ? $param['join'] : NULL;
        $param['page']		= ( isset($param['page']) && $param['page'] ) ? $param['page'] : 1;
        $param['page_rows']	= ( isset($param['page_rows']) && $param['page_rows'] ) ? $param['page_rows'] : 15;
        $param['order_by']	= ( isset($param['order_by']) && $param['order_by'] ) ? $param['order_by'] : $this->_pk . " DESC";
        $param['where']		= ( isset($param['where']) && $param['where'] && is_array($param['where']) ) ? $param['where'] : NULL;
        $param['where_in']	= ( isset($param['where_in']) && $param['where_in'] && is_array($param['where_in']) ) ? $param['where_in'] : NULL;
        $param['sc']		= ( isset($param['sc']) && $param['sc'] ) ? $param['sc'] : NULL;
        $param['st']		= ( isset($param['st']) && $param['st'] ) ? $param['st'] : NULL;
        $param['limit']		= ( isset($param['limit']) && $param['limit'] ) ? $param['limit'] : FALSE;

        $param['start']		= ( $param['page'] -1 ) * $param['page_rows'];
        $this->db->select("SQL_CALC_FOUND_ROWS " . $param['select'], false );
        $this->db->from( $param['from'] );

        // 사용자 정의 WHERE 문 처리
        if( $param['where'] && is_array($param['where']) )
        {
            foreach($param['where'] as $key=>$val)
            {
                $this->db->where($key, $val);
            }
        }

        // 사용자 정의 WHERE_IN 문 처리
        if( $param['where_in'] && is_array($param['where_in']) )
        {
            foreach($param['where_in'] as $key=>$val)
            {
                $this->db->where_in($key, $val);
            }
        }

        // 사용자 정의 WHERE 문 처리
        if( $param['join'] && is_array($param['join']) )
        {
            foreach($param['join'] as $array)
            {
                $this->db->join($array[0], $array[1], $array[2]);
            }
        }

        // 검색어 처리
        if( $param['sc'] != NULL && $param['st'] !=NULL )
        {
            // 띄어쓰기로 분리해서 각각 like를 걸어준다.
            $st = explode(" ", $param['st']);
            foreach($st as $searchs)
            {
                $this->db->like($param['sc'], $searchs);
            }
        }
        if($param['limit'] === TRUE)
        {
            $this->db->limit( $param['page_rows'], $param['start'] );
        }
        $this->db->order_by( $param['order_by'] );

        $result = $this->db->get();
        if(! $result ) {
            echo "ERROR : ".$this->db->error()['message'].PHP_EOL."<br>";
            echo "QUERY : ".$this->db->last_query();
        }

        if(IS_TEST) {
            $return['query'] = $this->db->last_query();
        }

        $return['list'] = $result->result_array();

        $result = $this->db->query("SELECT FOUND_ROWS() AS `cnt`");
        $return['total_count'] = (int) $result->row(0)->cnt;
        $return['total_page'] = ceil($return['total_count'] / $param['page_rows'] );

        $num = 0;
        foreach($return['list'] as &$row)
        {
            $row['nums'] = $return['total_count'] - $num - $param['start'];
            $num++;
        }

        return $return;
    }

    function create( $param = NULL )
    {
        foreach( $param as $k => $v )
        {
            $this->db->set( $k, $v );
        }
        $this->db->from($this->_table);
        $this->db->insert();

        $pk = $this->db->insert_id();

        return $this->get_one($pk);
    }

    function update( $param = NULL, $idx = NULL , $debug = FALSE)
    {
        foreach( $param as $k => $v )
        {
            $this->db->set( $k, $v );
        }
        $this->db->from($this->_table);
        $this->db->where($this->_pk, $idx);
        $this->db->update();

        if( $debug ) {
            return $this->db->last_query();
        }
        return $this->get_one($idx);
    }

    function update_batch($data)
    {
        return $this->db->update_batch($this->_table, $data, $this->_pk);
    }

    function insert_batch($data)
    {
        return $this->db->insert_batch($this->_table, $data);
    }

    function delete( $idx )
    {
        $this->db->where($this->_pk, $idx);
        $this->db->set($this->_status, "N");
        $this->db->update($this->_table);
    }
}
