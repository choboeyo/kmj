<?php
/******************************************************************************************
 * print_r 을 예쁘게 출력해준다.
 * @param $str
 *****************************************************************************************/
function print_r2($str) {
    echo "<pre>";
    print_r($str);
    echo "</pre>";
}

/*****************************************************************************************
 * Alert 창을 띄우고 특정 URL로 이동합니다.
 * @param string $msg
 * @param string $url
 ****************************************************************************************/
function alert($msg = '', $url = '')
{
    $CI =&get_instance();
    if (empty($msg)) {
        $msg = lang('common_invalid_request');
    }
    echo '<meta http-equiv="content-type" content="text/html; charset=utf-8">';
    echo '<script type="text/javascript">alert("' . $msg . '");';
    if (empty($url)) {
        echo 'history.go(-1);';
    }
    if ($url) {
        echo 'document.location.href="' . $url . '"';
    }
    echo '</script>';
    exit;
}

/*****************************************************************************************
 * Alert 창을 띄우고 현재 팝업창을 닫습니다.
 * @param string $msg
 ****************************************************************************************/
function alert_close($msg='', $refresh_parent = FALSE)
{
    $CI =&get_instance();
    if (empty($msg)) {
        $msg = lang('common_invalid_request');
    }
    echo '<meta http-equiv="content-type" content="text/html; charset=utf-8">';
    echo '<script type="text/javascript">alert("' . $msg . '");';
    if( $refresh_parent ) {
        echo 'opener.location.reload();';
    }
    echo 'window.close();';
    echo '</script>';
    exit;
}

/*****************************************************************************************
 * 로그인이 필요한 페이지임을 알리고, 로그인 페이지로 이동합니다.
 * @param string $msg
 ****************************************************************************************/
function alert_login($url="members/login")
{
    $CI =&get_instance();
    $url = base_url($url)."?reurl=".current_full_url(TRUE);
    echo '<meta http-equiv="content-type" content="text/html; charset=utf-8">';
    echo '<script type="text/javascript">';
    echo 'document.location.href="' . $url . '"';
    echo '</script>';
    exit;
}

/*****************************************************************************************
 * 관리자용 MODAL 창을 닫고, 메시지를 띄운다.
 * @param string $msg
 * @param mixed $refresh TRUE : 부모창을 새로고침 FALSE : 아무행동안함  String : 입력한 String을 자바스크립트로 실행
 ****************************************************************************************/
function alert_modal_close($msg="", $refresh=FALSE)
{
    if (empty($msg)) {
        $msg = lang('common_invalid_request');
    }
    echo '<meta http-equiv="content-type" content="text/html; charset=utf-8">';
    echo '<script type="text/javascript">alert("' . $msg . '");';
    echo 'parent.APP.MODAL.callback();';

    if($refresh === TRUE) {
        echo "parent.location.reload();";
    }
    else if (is_string($refresh) && $refresh ) {
        echo $refresh;
    }

    echo '</script>';
    exit;
}

/*****************************************************************************************
 * 관리자용 MODAL 창을 닫고, 메시지를 띄운다.
 * @param string $msg
 * @param mixed $refresh TRUE : 부모창을 새로고침 FALSE : 아무행동안함  String : 입력한 String을 자바스크립트로 실행
 ****************************************************************************************/
function alert_modal2_close($msg="", $refresh=TRUE)
{
    $CI =&get_instance();
    if (empty($msg)) {
        $msg = lang('common_invalid_request');
    }
    echo '<meta http-equiv="content-type" content="text/html; charset=utf-8">';
    echo '<script type="text/javascript">alert("' . $msg . '");';
    echo 'parent.APP.MODAL2.callback();';

    if($refresh === TRUE) {
        echo "parent.location.reload();";
    }
    else if (is_string($refresh) && $refresh ) {
        echo $refresh;
    }


    echo '</script>';
    exit;
}


/******************************************************************************************
 * 특정문자열을 암호화하여 내보낸다.
 * @param $string
 * @return string
 *****************************************************************************************/
function get_password_hash($string)
{
    $CI =& get_instance();
    return hash('md5', $CI->config->item('encryption_key') . $string );
}

/****************************************************************************************
 * 배열의 특정 키값을 가져옵니다.
 * @param $item
 * @param $array
 * @param null $default
 * @return mixed|null
 ***************************************************************************************/
function element($item, $array, $default = NULL)
{
    return is_array($array) && array_key_exists($item, $array) &&  $array[$item] ? $array[$item] : $default;
}

/*****************************************************************************************
 * 현재 주소를 Parameter 포함해서 가져온다.
 * @return string
 ****************************************************************************************/
function current_full_url($urlencode = FALSE)
{
    $CI =& get_instance();
    $url = $CI->config->site_url($CI->uri->uri_string());
    $return = ($CI->input->server('QUERY_STRING'))
        ? $url . '?' . $CI->input->server('QUERY_STRING') : $url;
    return $urlencode ?  urlencode($return) : $return;
}

/******************************************************************************************
 * 해당 URL이 우리 서버 도메인을 가르키는지 확인한다.
 * @param $url 체크할 URL
 * @param bool $check_file_exist 파일존재 여부까지 확인한다.
 * @return bool
 *****************************************************************************************/
function is_my_domain($url, $check_file_exist = TRUE) {
    // 처음 시작이 / 이고 두번제 문자가 /이 아닐경우
    if( substr($url,0,1) === '/' && substr($url,1,1) !== '/' )
    {
        if( $check_file_exist ) {
            return file_exists( FCPATH . $url );
        }
        return TRUE;
    }
    if( strpos( $url, base_url()) !== FALSE ) {
        if( $check_file_exist ) {
            return file_exists( FCPATH . str_replace( base_url(), "", $url ));
        }
        return TRUE;
    }
    return FALSE;
}



/******************************************************************************************
 * 업로드를 위한 폴더를 생성한다.
 * @param string $dir
 *****************************************************************************************/
function make_dir($dir = "", $make_date = TRUE, $return_only_filepath = FALSE)
{
    $dir = str_replace("/", DIRECTORY_SEPARATOR, $dir);

    $dirs = explode(DIRECTORY_SEPARATOR, $dir);
    $now_dir = FCPATH;
    foreach($dirs as $dr)
    {
        if( empty($dr) ) continue;
        $now_dir .= DIRECTORY_SEPARATOR . $dr;
        if (is_dir($now_dir) === false) {
            $old = umask(0);
            mkdir($now_dir, 0777);
            umask($old);
        }
    }

    if( $make_date )
    {
        $now_dir .= DIRECTORY_SEPARATOR . date('Y');
        if( is_dir($now_dir) === false )
        {
            $old = umask(0);
            mkdir($now_dir, 0777);
            umask($old);
        }

        $now_dir .= DIRECTORY_SEPARATOR . date('m');
        if( is_dir($now_dir) === false )
        {
            $old = umask(0);
            mkdir($now_dir, 0777);
            umask($old);
        }
    }

    $now_dir .= DIRECTORY_SEPARATOR;

    $now_dir = str_replace(DIRECTORY_SEPARATOR, "/", $now_dir);

    if($return_only_filepath) {
        $fcpath = str_replace(DIRECTORY_SEPARATOR, "/", FCPATH);
        $now_dir = str_replace($fcpath, "", $now_dir);
        $now_dir = str_replace(FCPATH, "", $now_dir);
    }

    return $now_dir;
}

/******************************************************************************************
 * HTML 태그를 제거하고 일반 텍스트로 변경
 *****************************************************************************************/
function get_text($str, $html=0, $restore=false)
{
    $source[] = "<";
    $target[] = "&lt;";
    $source[] = ">";
    $target[] = "&gt;";
    $source[] = "\"";
    $target[] = "&#034;";
    $source[] = "\'";
    $target[] = "&#039;";

    if($restore)
        $str = str_replace($target, $source, $str);

    // 3.31
    // TEXT 출력일 경우 &amp; &nbsp; 등의 코드를 정상으로 출력해 주기 위함
    if ($html === 0) {
        $str = html_symbol($str);
    }

    if ($html) {
        $source[] = "\n";
        $target[] = "<br/>";
    }

    return str_replace($source, $target, $str);
}

/**
 * HTML 이 포함된 내용에서 요약본을 TEXT 형태로 뽑아낸다.
 * @param $str
 * @param bool $nl2br
 * @return mixed
 */
function get_summary($str, $nl2br = FALSE)
{
    $str = html_entity_decode($str);
    $str = strip_tags($str);

    if($nl2br) {
        $str = nl2br($str);
    }
    else {
        $str = str_replace("\n","",$str);
    }
    return get_text($str, $nl2br);
}

/**
 * 파일 사이즈를 알기쉽게 표기
 * @param $bytes
 * @param int $decimals
 * @return string
 */
function format_size($bytes, $decimals = 2) {
    $size = array('B', 'KB', 'MB', 'GB', 'TB', 'PB', 'EB', 'ZB', 'YB');
    $factor = floor((strlen($bytes) - 1) / 3);
    return sprintf("%.{$decimals}f", $bytes / pow(1024, $factor)) . @$size[$factor];
}

/******************************************************************************************
 * HTML SYMBOL 변환
 * &nbsp; &amp; &middot; 등을 정상으로 출력
 *****************************************************************************************/
function html_symbol($str)
{
    return preg_replace("/\&([a-z0-9]{1,20}|\#[0-9]{0,3});/i", "&#038;\\1;", $str);
}
/******************************************************************************************
 * 에디터를 호출한다.
 *****************************************************************************************/
function get_editor($name, $contents="", $class="", $is_dhtml_editor = true, $editor_type = 'ckeditor')
{
    $param['id'] = $name;
    $param['name'] = $name;
    $param['height'] = '300px';
    $param['contents'] = $contents;
    $CI =& get_instance();
    if( $editor_type == 'smarteditor' && $is_dhtml_editor )
    {
        $param['editor_url'] = base_url('/plugins/smarteditor-2.9.0');
        $CI->site->add_js( $param['editor_url'] . "/js/service/HuskyEZCreator.js");
        $return = $CI->load->view("tools/".$editor_type, $param, TRUE);
    }
    else if ( $editor_type == 'ckeditor' && $is_dhtml_editor )
    {
        $param['editor_url'] = base_url('/plugins/ckeditor');
        $CI->site->add_js( $param['editor_url'] . "/ckeditor.js");
        $CI->site->add_js( $param['editor_url'] . "/config.js");
        $return = $CI->load->view("tools/".$editor_type, $param, TRUE);
    }
    else {
        $return = "\n<textarea id=\"" . $name . "\" name=\"" . $name . "\" class=\"" . $class . "\">" . $contents . "</textarea>";
    }

    return $return;
}

/*****************************************************************************************
 * 글자수를 잘라서 보여줍니다.
 * @param string $msg
 ****************************************************************************************/
function cut_str($str = '', $len = '', $suffix = '…')
{
    return mb_substr($str,0,$len) . $suffix;
}

/***************************************************************************************
 * 날짜를 일정 형식으로 보여줍니다.
 * @param $date
 * @return false|string
 **************************************************************************************/
function display_datetime($datetime = '', $type = '', $custom = '')
{
    $CI =& get_instance();

    if (empty($datetime)) {
        return false;
    }

    $datetime = is_int($datetime) ? $datetime :  strtotime($datetime);

    if ($type === 'sns') {

        $diff = time() - $datetime;

        $s = 60; //1분 = 60초
        $h = $s * 60; //1시간 = 60분
        $d = $h * 24; //1일 = 24시간
        $y = $d * 10; //1년 = 1일 * 10일

        if ($diff < $s) {
            $result = $diff . langs('공통/time/second_ago');
        } elseif ($h > $diff && $diff >= $s) {
            $result = round($diff/$s) . langs('공통/time/minute_ago');
        } elseif ($d > $diff && $diff >= $h) {
            $result = round($diff/$h) . langs('공통/time/hour_ago');
        } elseif ($y > $diff && $diff >= $d) {
            $result = round($diff/$d) . langs('공통/time/days_ago');
        } else {
            if (date('Y-m-d', $datetime) == date('Y-m-d')) {
                $result = date('H:i', $datetime);
            } else {
                $result = date('Y.m.d', $datetime);
            }
        }
    } elseif ($type === 'user' && $custom) {
        return date($custom, $datetime);
    } elseif ($type === 'full') {
        if (date('Y-m-d', $datetime) == date('Y-m-d')) {
            $result = date('H:i', $datetime);
        } elseif (date('Y', $datetime) === date('Y')) {
            $result = date('m-d H:i', $datetime);
        } else {
            $result = date('Y-m-d', $datetime);
        }
    } else {
        if (date('Y-m-d', $datetime) === date('Y-m-d')) {
            $result = date('H:i', $datetime);
        } else {
            $result = date('Y.m.d', $datetime);
        }
    }

    return $result;
}


/****************************************************
 * IP를 일정형식으로 변환하여 보여줍니다.
 * @param string $ip
 * @param string $type
 * @return bool|mixed
 ***************************************************/
function display_ipaddress($ip = '', $type = '0001')
{
    if( empty($type) )
    {
        $CI =& get_instance();
        $type = $CI->site->config('style_ip_display');
    }

    if (empty($ip)) {
        return false;
    }

    $regex = '';
    $regex .= ($type[0] === '1') ? '\\1' : '&#9825;';
    $regex .= '.';
    $regex .= ($type[1] === '1') ? '\\2' : '&#9825;';
    $regex .= '.';
    $regex .= ($type[2] === '1') ? '\\3' : '&#9825;';
    $regex .= '.';
    $regex .= ($type[3] === '1') ? '\\4' : '&#9825;';

    return preg_replace("/([0-9]+).([0-9]+).([0-9]+).([0-9]+)/", $regex, $ip);
}

/**
 * HTML 스타일의 내용을 가공해서 가져온다.
 * @param string $content HTML태그를 포함한 내용
 * @param int $thumb_width  썸네일 너비
 * @param bool $autolink    URL에 자동링크 여부
 * @param bool $popup   링크를 팝업으로 띄울것인가?
 * @param bool $writer_is_admin 글 작성자가 관리자인가?
 * @return mixed|string
 */
function display_html_content($content = '', $thumb_width=700)
{
    $source = array();
    $target = array();

    $source[] = '//';
    $target[] = '';

    $source[] = "/<\?xml:namespace prefix = o ns = \"urn:schemas-microsoft-com:office:office\" \/>/";
    $target[] = '';

    // 테이블 태그의 갯수를 세어 테이블이 깨지지 않도록 한다.
    $table_begin_count = substr_count(strtolower($content), '<table');
    $table_end_count = substr_count(strtolower($content), '</table');
    for ($i = $table_end_count; $i < $table_begin_count; $i++) {
        $content .= '</table>';
    }

    $content = preg_replace($source, $target, $content);
    $content = url_auto_link($content);
    $content = html_purifier($content);
    $content = get_view_thumbnail($content, $thumb_width);
    $content = preg_replace_callback(
        "/{&#51648;&#46020;\:([^}]*)}/is", function($match) {
            global $thumb_width;
            return get_google_map($match[1], $thumb_width);
        }, $content);

    return $content;
}

function get_view_thumbnail($contents = '', $thumb_width= 0)
{
    if (empty($contents)) {
        return false;
    }

    $CI = & get_instance();

    if (empty($thumb_width)) {
        $thumb_width = 700;
    }

    // $contents 중 img 태그 추출
    $matches = get_editor_image($contents, true);

    if (empty($matches) ) {
        return $contents;
    }

    $end = count(element(1, $matches, array()));


    for ($i = 0; $i < $end; $i++) {
        $img = $matches[1][$i];
        preg_match("/src=[\'\"]?([^>\'\"]+[^>\'\"]+)/i", $img, $m);
        $src = isset($m[1]) ? $m[1] : '';
        preg_match("/style=[\"\']?([^\"\'>]+)/i", $img, $m);
        $style = isset($m[1]) ? $m[1] : '';
        preg_match("/width:\s*(\d+)px/", $style, $m);
        $width = isset($m[1]) ? $m[1] : '';
        preg_match("/height:\s*(\d+)px/", $style, $m);
        $height = isset($m[1]) ? $m[1] : '';
        preg_match("/alt=[\"\']?([^\"\']*)[\"\']?/", $img, $m);
        $alt = isset($m[1]) ? html_escape($m[1]) : '';
        if (empty($width)) {
            preg_match("/width=[\"\']?([^\"\']*)[\"\']?/", $img, $m);
            $width = isset($m[1]) ? html_escape($m[1]) : '';
        }
        if (empty($height)) {
            preg_match("/height=[\"\']?([^\"\']*)[\"\']?/", $img, $m);
            $height = isset($m[1]) ? html_escape($m[1]) : '';
        }

        // 이미지 path 구함
        $p = parse_url($src);
        if (isset($p['host']) && $p['host'] === $CI->input->server('HTTP_HOST') && strpos($p['path'], '/' . DIR_UPLOAD) !== false) {
            $src = str_replace(base_url('/') , '', $src);
            $thumb_tag = '<img src="' . thumbnail($src, $thumb_width) . '" ';
        } else {
            $thumb_tag = '<img src="' . $src . '" ';
        }
        if ($width) {
            $thumb_tag .= ' width="' . $width . '" ';
        }
        $thumb_tag .= 'alt="' . $alt . '" style="max-width:100%;"/>';

        $img_tag = $matches[0][$i];
        $contents = str_replace($img_tag, $thumb_tag, $contents);
        if ($width) {
            $thumb_tag .= ' width="' . $width . '" ';
        }
        $thumb_tag .= 'alt="' . $alt . '" style="max-width:100%;"/>';

        $img_tag = $matches[0][$i];
        $contents = str_replace($img_tag, $thumb_tag, $contents);
    }

    return $contents;
}

function html_purifier($html)
{
    $CI = & get_instance();

    $white_iframe = $CI->site->config('allow_host');;
    $white_iframe = preg_replace("/[\r|\n|\r\n]+/", ",", $white_iframe);
    $white_iframe = preg_replace("/\s+/", "", $white_iframe);
    if ($white_iframe) {
        $white_iframe = explode(',', trim($white_iframe, ','));
        $white_iframe = array_unique($white_iframe);
    }
    $domains = array();
    if ($white_iframe) {
        foreach ($white_iframe as $domain) {
            $domain = trim($domain);
            if ($domain) {
                array_push($domains, $domain);
            }
        }
    }
    // 내 도메인도 추가
    array_push($domains, $CI->input->server('HTTP_HOST') . '/');
    $safeiframe = implode('|', $domains);

    if ( ! defined('INC_HTMLPurifier')) {
        include_once(APPPATH . 'third_party/htmlpurifier/HTMLPurifier.standalone.php');
        define('INC_HTMLPurifier', true);
    }
    $config = HTMLPurifier_Config::createDefault();
    // cache 디렉토리에 CSS, HTML, URI 디렉토리 등을 만든다.

    $cache_path = config_item('cache_path') ? config_item('cache_path') : APPPATH . 'cache/';

    $config->set('Cache.SerializerPath', $cache_path);
    $config->set('HTML.SafeEmbed', false);
    $config->set('HTML.SafeObject', false);
    $config->set('HTML.SafeIframe', true);
    $config->set('URI.SafeIframeRegexp','%^(https?:)?//(' . $safeiframe . ')%');
    $config->set('Attr.AllowedFrameTargets', array('_blank'));
    $config->set('Core.Encoding', 'utf-8');
    $config->set('Core.EscapeNonASCIICharacters', true);
    $config->set('HTML.MaxImgLength', null);
    $config->set('CSS.MaxImgLength', null);
    $purifier = new HTMLPurifier($config);

    return $purifier->purify($html);
}

function url_auto_link($str = '', $popup = true)
{
    if (empty($str)) {
        return false;
    }
    $target = $popup ? 'target="_blank"' : '';
    $str = str_replace(
        array("&lt;", "&gt;", "&amp;", "&quot;", "&nbsp;", "&#039;"),
        array("\t_lt_\t", "\t_gt_\t", "&", "\"", "\t_nbsp_\t", "'"),
        $str
    );
    $str = preg_replace(
        "/([^(href=\"?'?)|(src=\"?'?)]|\(|^)((http|https|ftp|telnet|news|mms):\/\/[a-zA-Z0-9\.-]+\.[가-힣\xA1-\xFEa-zA-Z0-9\.:&#=_\?\/~\+%@;\-\|\,\(\)]+)/i",
        "\\1<a href=\"\\2\" {$target}>\\2</A>",
        $str
    );
    $str = preg_replace(
        "/(^|[\"'\s(])(www\.[^\"'\s()]+)/i",
        "\\1<a href=\"http://\\2\" {$target}>\\2</A>",
        $str
    );
    $str = preg_replace(
        "/[0-9a-z_-]+@[a-z0-9._-]{4,}/i",
        "<a href=\"mailto:\\0\">\\0</a>",
        $str
    );
    $str = str_replace(
        array("\t_nbsp_\t", "\t_lt_\t", "\t_gt_\t", "'"),
        array("&nbsp;", "&lt;", "&gt;", "&#039;"),
        $str
    );
    return $str;
}

function change_key_case($str)
{
    $str = stripcslashes($str);
    preg_match_all('@(?P<attribute>[^\s\'\"]+)\s*=\s*(\'|\")?(?P<value>[^\s\'\"]+)(\'|\")?@i', $str, $match);
    $value = @array_change_key_case(array_combine($match['attribute'], $match['value']));

    return $value;
}

function get_google_map($geo_data = '', $maxwidth = '')
{
    if (empty($geo_data)) {
        return;
    }

    $maxwidth = (int) $maxwidth;
    if (empty($maxwidth)) {
        $maxwidth = 700;
    }

    $geo_data = stripslashes($geo_data);
    $geo_data = str_replace('&quot;', '', $geo_data);

    if (empty($geo_data)) {
        return;
    }

    $map = array();
    $map = change_key_case($geo_data);

    if (isset($map['loc'])) {
        list($lat, $lng) = explode(',', element('loc', $map));
        $zoom = element('z', $map);
    } else {
        list($lat, $lng, $zoom) = explode(',', element('geo', $map));
    }

    if (empty($lat) OR empty($lng)) {
        return;
    }

    //Map
    $map['geo'] = $lat . ',' . $lng . ',' . $zoom;

    //Marker
    preg_match("/m=\"([^\"]*)\"/is", $geo_data, $marker);
    $map['m'] = element(1, $marker);

    $google_map = '<div style="width:100%; margin:0 auto 15px; max-width:'
        . $maxwidth . 'px;">' . PHP_EOL;
    $google_map .= '<iframe width="100%" height="480" src="'
        . base_url('popup/googlemap?geo=' . urlencode($map['geo'])
            . '&marker=' . urlencode($map['m']))
        . '" frameborder="0" scrolling="no"></iframe>' . PHP_EOL;
    $google_map .= '</div>' . PHP_EOL;

    return $google_map;
}


/**
 * 게시물중에서
 * @param $post
 * @param string $thumb_width
 * @param string $thumb_height
 * @return string|void
 */
function get_post_thumbnail($post, $thumb_width = '', $thumb_height = '')
{
    $CI = & get_instance();

    if(empty($post) OR !is_array($post)) return '';

    // 첨부파일중 이미지중 첫번째를 가져온다.
    if( isset($post['file'])  && count($post['file'])>0)
    {
        foreach($post['file'] as $file)
        {
            if($file['att_is_image'] == 'Y')
            {
                return thumbnail($file['att_filename'], $thumb_width, $thumb_height);
            }
        }
    }

    $matches = get_editor_image($post['post_content']);
    if (! empty($matches)) {
        $img = element(0, element(1, $matches));
        if (! empty($img)) {

            preg_match("/src=[\'\"]?([^>\'\"]+[^>\'\"]+)/i", $img, $m);
            $src = isset($m[1]) ? $m[1] : '';

            $p = parse_url($src);

            if (isset($p['host']) && $p['host'] === $CI->input->server('HTTP_HOST')
                && strpos($p['path'], '/' . DIR_UPLOAD) !== false) {
                $src = str_replace(base_url('/') , '', $src);
                $src = thumbnail( $src , $thumb_width, $thumb_height);
            }

            return $src;

        }
    }

    // 본문 내용중에 iframe 동영상 포함여부를 확인한다.
    preg_match_all("/<iframe[^>]*src=[\'\"]?([^>\'\"]+[^>\'\"]+)[\'\"]?[^>]*>/i", $post['post_content'], $matches);
    for($i=0; $i<count($matches[1]); $i++) {
        if(! isset($matches[1][$i]) ) continue;

        $video = get_video_info( $matches[1][$i] );

        // 비디오 타입이 아니거나, 알려지지 않은 비디오 일경우 건너뛴다.
        if(! $video['type'] OR ! $video['thumb']) continue;

        if($video['thumb']) {
            return $video['thumb'];
        }
    }

    // 본문내용중에 embed 태그 포함여부를 확인한다.
    preg_match_all("/<embed[^>]*src=[\'\"]?([^>\'\"]+[^>\'\"]+)[\'\"]?[^>]*>/i", $post['post_content'], $matches);
    for($i=0; $i<count($matches[1]); $i++) {
        if(! isset($matches[1][$i]) ) continue;

        $video = get_video_info( $matches[1][$i] );

        // 비디오 타입이 아니거나, 알려지지 않은 비디오 일경우 건너뛴다.
        if(! $video['type'] OR ! $video['thumb']) continue;

        if($video['thumb']) {
            return $video['thumb'];
        }
    }

    return '';
}

function get_array_query($str) {
    $str = stripcslashes($str);
    preg_match_all('@(?P<attribute>[^\s\'\"]+)\s*=\s*(\'|\")?(?P<value>[^\s\'\"]+)(\'|\")?@i', $str, $match);
    $value = @array_change_key_case(array_combine($match['attribute'], $match['value']));
    return $value;
}

function get_video_info($video_url) {

    $video = array();
    $query = array();
    $option = array();

    $video_url = trim(strip_tags($video_url));

    list($url, $opt) = explode("|", $video_url."|");

    $url = trim($url);

    if($url) {
        if(!preg_match('/(http|https)\:\/\//i', $url)) {
            $url = 'http:'.$url;
        }
    } else {
        return;
    }

    $video['video'] = str_replace(array("&nbsp;", " "), array("", ""), $url);
    $video['video_url'] = str_replace(array("&nbsp;", "&amp;", " "), array("", "&", ""), $url);

    if($opt) $option = get_array_query($opt);

    if( element("file", $option)) { //jwplayer
        $video['type'] = 'file';
        $video['vid'] = 'file';
        $video['img'] = (isset($option['img']) && $option['img']) ? str_replace(array("&nbsp;", " "), array("", ""), trim(strip_tags($option['img']))) : '';
        $video['caption'] = (isset($option['caption']) && $option['caption']) ? str_replace(array("&nbsp;", " "), array("", ""), trim(strip_tags($option['caption']))) : '';
    } else {
        $info = @parse_url($video['video_url']);
        if(isset($info['query']) && $info['query']) parse_str($info['query'], $query);

        if($info['host'] == "youtu.be") { //유튜브
            $video['type'] = 'youtube';
            $video['vid'] = trim(str_replace("/","", trim($info['path'])));
            $video['vid'] = substr($video['vid'], 0, 11);
            $video['vlist'] =  element("list", $query);
            $query['autoplay'] = element("autoplay", $query);
            $video['auto'] = element("auto", $option, $query['autoplay']);
            $video['s'] = element("s", $option, $query['s']);
        } else if($info['host'] == "www.youtube.com" || $info['host'] == "m.youtube.com") { //유튜브
            $video['type'] = 'youtube';
            if(preg_match('/\/embed\//i', $video['video_url'])) {
                list($youtube_url, $youtube_opt) = explode("/embed/", $video['video_url']);
                $vids = explode("?", $youtube_opt);
                $video['vid'] = $vids[0];
            } else {
                $video['vid'] = element("v", $query);
                $video['vlist'] = element("list", $query);
            }
            $query['autoplay'] = element("autoplay", $query);
            $video['auto'] = element("auto", $option, $query['autoplay']);
            $video['s'] = element("s", $option, element("s", $query));
        } else if($info['host'] == "vimeo.com") { //비메오
            $video['type'] = 'vimeo';
            $vquery = explode("/",$video['video_url']);
            $num = count($vquery) - 1;
            list($video['vid']) = explode("#",$vquery[$num]);
        } else if($info['host'] == "www.ted.com") { //테드
            $video['type'] = 'ted';
            $vids = explode("?", $video['video_url']);
            $vquery = explode("/",$vids[0]);
            $num = count($vquery) - 1;
            list($video['vid']) = explode(".", $vquery[$num]);
            list($rid) = explode(".", trim($info['path']));
            $rid = str_replace($video['vid'], '', $rid);
            $lang = (isset($query['language']) && $query['language']) ? 'lang/'.$query['language'].'/' : '';
            if($lang) {
                $rid = (stripos($rid, $lang) === false) ? $rid.$lang : $rid;
            }
            $video['rid'] = trim($rid.$video['vid']).'.html';
        } else if($info['host'] == "tvpot.daum.net") { //다음tv
            $video['type'] = 'daum';
            if(isset($query['vid']) && $query['vid']) {
                $video['vid'] = $query['vid'];
                $video['rid'] = $video['vid'];
            } else {
                if(isset($query['clipid']) && $query['clipid']) {
                    $video['vid'] = $query['clipid'];
                } else {
                    $video['vid'] = trim(str_replace("/v/","",$info['path']));
                }
                $play = get_vid($video['video_url'], $video['vid'], $video['type']);
                $video['rid'] = $play['rid'];
            }
        } else if($info['host'] == "channel.pandora.tv") { //판도라tv
            $video['type'] = 'pandora';
            $video['ch_userid'] = (isset($query['ch_userid']) && $query['ch_userid']) ? $query['ch_userid'] : '';
            $video['prgid'] = (isset($query['prgid']) && $query['prgid']) ? $query['prgid'] : '';
            $video['vid'] = $video['ch_userid'].'_'.$video['prgid'];
        } else if($info['host'] == "pann.nate.com") { //네이트tv
            $video['type'] = 'nate';
            $video['vid'] = trim(str_replace("/video/","",$info['path']));
            $play = get_vid($video['video_url'], $video['vid'], $video['type']);
            $video['mov_id'] = (isset($play['mov_id']) && $play['mov_id']) ? $play['mov_id'] : '';
            $video['vs_keys'] = (isset($play['vs_keys']) && $play['vs_keys']) ? $play['vs_keys'] : '';
        } else if($info['host'] == "www.tagstory.com") { //Tagstory
            $video['type'] = 'tagstory';
            $vquery = explode("/",$video['video_url']);
            $num = count($vquery) - 1;
            $video['vid'] = $vquery[$num];
        } else if($info['host'] == "dai.ly" || $info['host'] == "www.dailymotion.com") { //Dailymotion
            $video['type'] = 'dailymotion';
            if($info['host'] == "dai.ly") {
                $video['vid'] = trim($info['path']);
            } else {
                $vurl = explode("#", $video['video_url']);
                $vquery = explode("/", $vurl[0]);
                $num = count($vquery) - 1;
                list($video['vid']) = explode("_", $vquery[$num]);
            }
        } else if($info['host'] == "www.facebook.com") { //Facebook - 라니안님 코드 반영
            $video['type'] = 'facebook';
            if(isset($query['video_id']) && $query['video_id']){
                $video['vid'] = $query['video_id'];
            } else if(isset($query['v']) && $query['v']) {
                $video['vid'] = $query['v'];
            } else {
                $vtmp = explode("/videos/", trim($info['path']));
                $vquery = explode("/", $vtmp[1]);
                $video['vid'] = $vquery[0];
            }
            if(!is_numeric($video['vid'])) $video = NULL;
        } else if($info['host'] == "serviceapi.nmv.naver.com") { // 네이버 - 라니안님 코드 반영
            $video['type'] = 'naver';
            $video['vid'] = (isset($query['vid']) && $query['vid']) ? $query['vid'] : '';
            $video['outKey'] = (isset($query['outKey']) && $query['outKey']) ? $query['outKey'] : '';
        } else if($info['host'] == "serviceapi.rmcnmv.naver.com") { // 네이버 - 라니안님 코드 반영
            $video['type'] = 'tvcast';
            $video['vid'] = (isset($query['vid']) && $query['vid']) ? $query['vid'] : '';
            $video['outKey'] = (isset($query['outKey']) && $query['outKey']) ? $query['outKey'] : '';
        } else if($info['host'] == "tvcast.naver.com") { // 네이버 tvcast 단축주소 - 라니안님 코드 반영
            $video['type'] = 'tvcast';
            $video['clipNo'] = trim(str_replace("/v/","",$info['path']));
            $play = get_vid($video['video_url'], $video['clipNo'], $video['type']);
            $video['vid'] = (isset($play['vid']) && $play['vid']) ? $play['vid'] : '';
            $video['outKey'] = (isset($play['outKey']) && $play['outKey']) ? $play['outKey'] : '';
        } else if($info['host'] == "www.slideshare.net") { // slidershare
            $video['type'] = 'slidershare';
            $play = get_vid($video['video_url'], 1, $video['type']);
            $video['play_url'] = (isset($play['play_url']) && $play['play_url']) ? $play['play_url'] : '';
            $video['vid'] = (isset($play['vid']) && $play['vid']) ? $play['vid'] : '';
        } else if($info['host'] == "vid.me") { // vid.me
            $video['type'] = 'vid';
            $video['vid'] = trim(str_replace("/","",$info['path']));
            $query['autoplay'] = (isset($query['autoplay']) && $query['autoplay']) ? $query['autoplay'] : '';
            $video['auto'] = (isset($option['auto']) && $option['auto']) ? $option['auto'] : $query['autoplay'];
        } else if($info['host'] == "sendvid.com") { // sendvid.com
            $video['type'] = 'sendvid';
            $video['vid'] = trim(str_replace("/","",$info['path']));
        } else if($info['host'] == "vine.co") { // vine.co
            $video['type'] = 'vine';
            $vtmp = explode("/v/", trim($info['path']));
            $vquery = explode("/", $vtmp[1]);
            $video['vid'] = $vquery[0];
        }
    }

    $video['thumb'] = "";
    if( isset($video) && isset($video['video_url']) && isset($video['vid']) && $video['type'] ) {
        $video['thumb'] = get_video_imgurl( $video['video_url'], $video['vid'], $video['type'] );
    }

    return $video;
}

function get_vid($url, $vid, $type) {

    $play = array();
    $info = array();
    $query = array();

    if (!$url || !$vid || !$type || ($type == 'file')) return;

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    @curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
    curl_setopt($ch, CURLOPT_HEADER, 0);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_TIMEOUT, 10);
    $output = curl_exec($ch);
    curl_close($ch);

    if($type == "tvcast"){

        preg_match('/nhn.rmcnmv.RMCVideoPlayer\("(?P<vid>[A-Z0-9]+)", "(?P<inKey>[a-z0-9]+)"/i', $output, $video);

        $play['vid'] = element("vid", $video);
        $play['inkey'] = element("inKey", $video);

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "http://serviceapi.rmcnmv.naver.com/flash/getExternSwfUrl.nhn?vid=".$play['vid'].'&inKey='.$play['inkey']);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_TIMEOUT, 10);
        $output = curl_exec($ch);
        curl_close($ch);
        preg_match('/&outKey=(?P<outKey>[a-zA-Z0-9]+)&/i', $output, $video);

        $play['outKey']= (isset($video['outKey']) && $video['outKey']) ? $video['outKey'] : '';

    } else if($type == "daum") {
        preg_match('/\<meta property=\"og\:video\"([^\<\>])*\>/i', $output, $video);
        if($video) {
            $video = get_array_query($video[0]);
            $$video['content'] = preg_replace("/&amp;/", "&", $video['content']);
            $info = @parse_url($video['content']);
            $info['query'] = element("query", $info);
            parse_str($info['query'], $query);
            $play['rid'] = $query['vid'];
        }
    } else if($type == "nate") {
        preg_match('/mov_id = \"([^\"]*)\"/i', $output, $video);
        $play['mov_id'] = $video[0];

        preg_match('/vs_keys = \"([^\"]*)\"/i', $output, $video);
        $play['vs_keys'] = $video[0];

        if($play) {
            $meta = "<meta {$play[mov_id]} {$play[vs_keys]} >";
            $video = get_array_query($meta);
            $play['mov_id'] =  element("mov_id", $video);
            $play['vs_keys'] =	element("vs_keys", $video);;
        }
    } else if($type == "slidershare") {
        preg_match('/\<meta class=\"twitter_player\"([^\<\>])*\>/i', $output, $video);
        if($video) {
            $video = get_array_query($video[0]);
            $play['play_url'] = (isset($video['value']) && $video['value']) ? str_replace("&amp;", "&", $video['value']) : '';
            $info = @parse_url($play['play_url']);
            $play['vid'] = trim(str_replace("/slideshow/embed_code/","",$info['path']));
        }
    }

    return $play;
}

function get_video_imgurl($url, $vid, $type) {

    $imgurl = '';
    if($type == "file") { //JWPLAYER
        return;
    } else if($type == "vimeo") { //비메오
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "http://vimeo.com/api/v2/video/".$vid.".php");
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_TIMEOUT, 10);
        $output = unserialize(curl_exec($ch));
        curl_close($ch);

        $imgurl = $output[0]['thumbnail_large'];

    } else if($type == "youtube") { //유튜브

        $imgurl = 'http://img.youtube.com/vi/'.$vid.'/hqdefault.jpg';

    } else if($type == "sendvid") { //Sendvid

        $imgurl = 'https://sendvid.com/'.$vid.'.jpg';

    } else if($type == "facebook"){
        /*
        if(!defined('APMS_FACEBOOK_ACCESS_TOCKEN') || !APMS_FACEBOOK_ACCESS_TOCKEN) return;

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "https://graph.facebook.com/v2.5/".$vid."?fields=id,picture&access_token=".APMS_FACEBOOK_ACCESS_TOCKEN);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-type:application/json'));
        curl_setopt($ch, CURLOPT_TIMEOUT, 10);
        $output = json_decode(curl_exec($ch));
        curl_close($ch);
        $imgurl = $output->picture;
        */
    } else if($type == "naver" || $type == "tvcast"){ //라니안님 코드 반영

        $info = @parse_url($url);

        if($info['host'] == "tvcast.naver.com") {
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            @curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
            curl_setopt($ch, CURLOPT_HEADER, 0);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_TIMEOUT, 10);
            $output = curl_exec($ch);
            curl_close($ch);

            preg_match('/property=\"og\:image\"[^\<\>]*\>/i', $output, $video);

            if($video) {
                $video = get_array_query($video[0]);
                if($video['content']) $imgurl = str_replace("type=f240", "type=f640", $video['content']); //640 사이즈로 변경
            }
        } else {
            $url_type = ($type == "naver") ? "nmv" : "rmcnmv"; // 네이버 블로그 영상과 tvcast 영상 구분
            parse_str($info['query'], $query);

            $vid .= "&outKey=".$query['outKey'];

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, "http://serviceapi.{$url_type}.naver.com/flash/videoInfo.nhn?vid=".$vid);
            curl_setopt($ch, CURLOPT_HEADER, 0);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_TIMEOUT, 10);
            $output = curl_exec($ch);
            curl_close($ch);

            preg_match('/\<CoverImage\>\<\!\[CDATA\[(?P<img_url>[^\s\'\"]+)\]\]\>\<\/CoverImage\>/i', $output, $video);

            $imgurl = element("img_url", $video);
        }

    } else {

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        @curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_TIMEOUT, 10);
        $output = curl_exec($ch);
        curl_close($ch);

        if($type == "slidershare") {
            preg_match('/<meta name=\"thumbnail\"[^\<\>]*\>/i', $output, $video);
        } else {
            preg_match("/<meta[^>]*content=[\'\"]?([^>\'\"]+[^>\'\"]+)[\'\"]?[^>]*property=\"og\:image\"/i", $output, $video);
            if($video[1]) {
                $imgurl = $video[1];
            } else {
                preg_match('/property=\"og\:image\"[^\<\>]*\>/i', $output, $video);
            }
        }

        if(!$imgurl && $video[0]) {
            $video = get_array_query($video[0]);
            $imgurl = $video['content'];
        }
    }

    return $imgurl;
}

function get_editor_image($contents = '', $view = true)
{
    if (empty($contents)) {
        return false;
    }

    // $contents 중 img 태그 추출
    if ($view) {
        $pattern = "/<img([^>]*)>/iS";
    } else {
        $pattern = "/<img[^>]*src=[\'\"]?([^>\'\"]+[^>\'\"]+)[\'\"]?[^>]*>/i";
    }
    preg_match_all($pattern, $contents, $matchs);

    return $matchs;
}

function check_social_setting($provider="")
{
    $CI=&get_instance();
    if( strtolower($provider) == 'facebook' ) return ($CI->site->config('social_facebook_use')=='Y' && $CI->site->config('social_facebook_appid') && $CI->site->config('social_facebook_appsecret'));
    else if( strtolower($provider) == 'google' ) return ($CI->site->config('social_google_use')=='Y' && $CI->site->config('social_google_clientid') && $CI->site->config('social_google_clientsecret'));
    else if( strtolower($provider) == 'naver')   return ($CI->site->config('social_naver_use')=='Y' && $CI->site->config('social_naver_clientid') && $CI->site->config('social_naver_clientsecret'));
    else if( strtolower($provider) == 'kakao') return  ($CI->site->config('social_kakao_use')=='Y' && $CI->site->config('social_kakao_clientid'));
    else if( empty($provider)) return ( check_social_setting('facebook') OR check_social_setting('google') OR check_social_setting('naver') OR check_social_setting('kakao') );
    else return FALSE;
}

/**
 * 회원 포인트 타입
 * @param bool $return
 * @return array|mixed
 */
function point_type($return = FALSE)
{
    $point = array(
        "NONE" => "기타",
        "POST_READ" => "게시글 읽기",
        "POST_WRITE" => "게시글 작성",
        "POST_LIKE" => "게시글 추천",
        "POST_ATTACH_DOWNLOAD" => "첨부파일 다운로드",
        "CMT_WRITE" => "댓글 작성",
        "CMT_LIKE"  => "댓글 추천",
        "TODAY_LOGIN"   => "오늘 첫 로그인",
        "JOIN" => "회원가입"
    );

    if( is_string($return) )
    {
        return $point[$return];
    }
    else if (is_bool($return) )
    {
        if( $return === TRUE )
        {
            return $point;
        }
        else {
            $return_val = array();
            foreach($point as $key=>$val)
            {
                $return_val[] = $key;
            }
            return $return_val;
        }
    }

    return array();
}

/**
 * 이미지가 존재한다면 해당 이미지의 태그를 리턴한다.
 * @param $thumb_path
 * @param string $add_class
 * @param string $add_attr
 * @return null|string
 */
function thumb_img($thumb_path, $add_class="img-thumbnail", $add_attr="")
{
    if(empty($thumb_path) ) return NULL;
    if(file_exists(FCPATH.$thumb_path)) {
        return '<img class="'.$add_class.'" src="'.base_url($thumb_path).'" '.$add_attr.'>';
    }
}


/**
 * 해당하는 파일을 삭제합니다.
 * @param string $filepath
 */
function file_delete($filepath ="") {

    if(empty($filepath))
        return;

    if($filepath && file_exists(FCPATH.$filepath) && is_file(FCPATH.$filepath)) {
        @unlink(FCPATH.$filepath);
    }
}

/**
 * DB에 등록된 파일을 삭제하고 해당 DB의 파일 부분을 빈값으로 대체한다.
 * @param $table
 * @param $pk_column
 * @param $pk
 * @param $filepath_column
 */
function db_file_delete($table, $pk_column, $pk, $filepath_column)
{
    if(empty($table) OR empty($pk_column) OR empty($pk) OR empty($filepath_column)){
        return FALSE;
    }
    $CI =& get_instance();

    $original = $CI->db->where($pk_column, $pk)->get($table)->row_array();

    if(! $original OR empty($original)) return false;
    if(! isset($original[$filepath_column])) return false;

    file_delete($original[$filepath_column]);

    $CI->db->set($filepath_column, '');
    $CI->db->where($pk_column, $pk);
    $CI->db->update($table);
}