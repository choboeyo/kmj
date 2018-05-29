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

        $accept_lang = $CI->site->config('accept_languages');
        $accept_lang = explode(',', $accept_lang);

        foreach($accept_lang as $val) {
            $langs[$val] = array();
        }

        foreach( $result as $row )
        {
            foreach($accept_lang as $ln) {
                $langs[$ln][ $row['loc_key'] ] = $row['loc_value_'.$ln];
            }
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