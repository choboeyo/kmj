<?php
/**
 * 다국어 언어 스트링을 출력합니다.
 * @param $key
 */
function langs($key) {
    $CI = &get_instance();

    if( ! $langs = $CI->cache->get('site_language') )
    {
        $result = $CI->db->get('localize')->result_array();

        $langs = array(
            "ko" => array(),
            "en" => array()
        );

        foreach( $result as $row )
        {
            $langs['ko'][ $row['loc_key'] ] = $row['loc_value_ko'];
            $langs['en'][ $row['loc_key'] ] = $row['loc_value_en'];
        }

        $CI->cache->save('site_language', $langs);
    }

    if( isset($langs[LANG][$key]) ) {
        return $langs[LANG][$key];
    }
    else {
        return '';
    }
}