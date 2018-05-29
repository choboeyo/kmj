<?php
/**
 * 배너 라이브러리
 */
class Banner {
    protected $CI;
    protected $banners = NULL;

    function __construct()
    {
        $this->CI =& get_instance();
    }

    /**
     * 특정 Key를 가진 배너를 하나만 가져온다.
     * @param string $bng_key
     */
    function one( $bng_key ="")
    {
        if(empty($bng_key)) return array();
        $banner_list = $this->lists($bng_key);

        if(isset($banner_list[0]) && is_array($banner_list[0]) && $banner_list[0])
            return $banner_list[0];
        else
            return array();
    }

    /**
     * 특정 Key를 가진 배너의 목록을 모두 가져온다.
     * @param string $bng_key
     * @return array
     */
    function lists( $bng_key="" )
    {
        if( empty($bng_key) ) return array();

        if( empty($this->banners))
        {
            $this->CI->db->where('ban_status', 'Y');

            $this->CI->db->group_start();

                $this->CI->db->or_group_start();
                    $this->CI->db->where('ban_timer_use', 'N');
                $this->CI->db->group_end();

                $this->CI->db->or_group_start();
                    $this->CI->db->where('ban_timer_use','Y');
                    $this->CI->db->where('ban_timer_start <=', date('Y-m-d H:i:s'));
                    $this->CI->db->where('ban_timer_end >=', date('Y-m-d H:i:s'));
                $this->CI->db->group_end();

            $this->CI->db->group_end();

            $this->CI->db->order_by('ban_sort ASC');
            $result = $this->CI->db->get("banner");
            $this->banners = $result->result_array();
        }

        $return = array();
        foreach($this->banners as &$banner)
        {
            if($banner['bng_key'] == $bng_key)
            {
                $banner['tag'] = "";
                $banner['tag'] .= ( $banner['ban_link_use'] && $banner['ban_link_url'] ) ? " href=\"{$banner['ban_link_url']}\"" : '';
                $banner['tag'] .= ( $banner['ban_link_use'] && $banner['ban_link_url']  && $banner['ban_link_type'] == 'Y' ) ? ' target="_blank"' :'';
                $return[] = $banner;
            }
        }

        return $return;
    }
}