<?php
/**
 * Class Site
 * ------------------------------------------------------------------
 * 사이트 전역설정및 레이아웃과 관련된 라이브러리
 */
class Site {
    public $viewmode;
    public $device;
    public $lang;
    protected $config;
    protected $css_before = array();
    protected $css_after = array();
    protected $js_before = array();
    protected $js_after = array();
    public $meta_title 			= "";
    public $meta_description 	= "";
    public $meta_keywords 		= "";
    public $meta_image			= "";

    /**********************************************************
     * 사이트 전역설정중 특정 컬럼의 값을 반환한다.
     * @param $column 반활할 컬럼 이름
     * @return var 컬럼의 값
     *********************************************************/
    public function config($column) {
        // 컬럼값이 없으면 리턴한다.
        if( empty($column) ) return NULL;
        // 캐시 드라이버 로드
        $CI =& get_instance();
        $CI->load->driver('cache', array('adapter' => 'apc', 'backup' => 'file', 'key_prefix' => PROJECT));
        if( ! $config = $CI->cache->get('site_config') )
        {
            $result = $CI->db->get("config");
            $config_list = $result->result_array();
            $config = array();
            foreach( $config_list as $row ) {
                $config[$row['cfg_key']] = $row['cfg_value'];
            }
            $CI->cache->save('site_config', $config);
        }
        return element($column, $config, NULL);
    }

    /**
     * 사이트 메뉴를 가져온다
     */

    public function menu()
    {
        $CI =& get_instance();
        $CI->load->driver('cache', array('adapter' => 'apc', 'backup' => 'file', 'key_prefix' => PROJECT));

        $menu_key = $this->viewmode == DEVICE_MOBILE ? 'menu_mobile' : 'menu_desktop';
        $where_key = $this->viewmode == DEVICE_MOBILE ? 'mnu_mobile' : 'mnu_desktop';
        if( ! $menu_list = $CI->cache->get($menu_key) )
        {
            $CI->db->where($where_key, 'Y');
            $result = $CI->db->get("menu");
            $menu_list = $result->result_array();

            $menu_list = $this->menu_arrange($menu_list);

            $CI->cache->save($menu_key, $menu_list);
        }

        foreach($menu_list as &$menu1) {
            $menu1['active'] =  $CI->active == $menu1['mnu_active_key'];
            foreach($menu1['children'] as &$mnu2)
            {
                $mnu2['active'] = $CI->active == $mnu2['mnu_active_key'];

                foreach($mnu2['children'] as &$mnu3)
                {
                    $mnu3['active'] = $CI->active == $mnu3['mnu_active_key'];

                    if( $mnu3['active'] ) {
                        $mnu2['active'] = TRUE;
                        $menu1['active'] = TRUE;
                        break;
                    }
                }

                if($CI->active == $mnu2['mnu_active_key'] )
                {
                    $mnu2['active'] = TRUE;
                    $menu1['active'] = TRUE;
                }
            }

            if( $CI->active == $menu1['mnu_active_key'] )
            {
                $menu1['active'] = TRUE;
            }
        }

        //print_r2($menu_list);

        return $menu_list;
    }


    private function menu_arrange($array, $parent_id=0)
    {
        $return = array();

        foreach($array as $arr) {
            $arr['active'] = FALSE;
            if( $arr['mnu_parent'] ==  $parent_id ) {
                $children = $this->menu_arrange( $array, $arr['mnu_idx'] );
                if( $children ) {
                    $arr['children']  = $children;
                }
                $return[] = $arr;
            }
        }
        return $return;
    }

    /*********************************************************
     * 현재 접속 기기에 따라 필요한 레이아웃을 가져온다.
     *********************************************************/
    public function get_layout()
    {
        return ( $this->viewmode == DEVICE_MOBILE ) ? THEME_MOBILE : THEME_DESKTOP;
    }


    /*********************************************************
     * 사이트에 사용할 CSS를 추가합니다.
     * @param $url 추가할 CSS
     * @param bool $insert_last 마지막에 추가할지 처음에 추가할지
     ********************************************************/
    public function add_css( $url, $insert_first = FALSE) {
        if(!empty($url) && ! in_array($url, $this->css_after) && !in_array($url, $this->css_before)) {
            if( $insert_first ) {
                array_push($this->css_before, $url);
            }
            else {
                array_push($this->css_after, $url);
            }
        }
    }

    /*********************************************************
     * 사이트에 사용할 JS를 추가한다.
     * @param $url 추가할 JS
     * @param bool $insert_last 마지막에 추가할것인가?
     ********************************************************/
    public function add_js( $url, $insert_first = FALSE ) {
        if(!empty($url) && ! in_array($url, $this->js_before) && ! in_array($url, $this->js_after)) {
            if( $insert_first ) {
                array_push($this->js_before, $url);
            }
            else {
                array_push($this->js_after, $url);
            }
        }
    }

    /*********************************************************
     * 배열에 담긴 CSS를 메타태그와 함께 같이 출력한다.
     * @return string
     ********************************************************/
    public function display_css() {
        $CI =& get_instance();
        $return = '';

        // Layout 기본 CSS가 있다면 추가한다.
        if( $CI->skin_type && $CI->skin && file_exists(VIEWPATH.'/'.DIR_SKIN.'/'.$CI->skin_type.'/'.$CI->skin.'/skin.min.css')) {
            $this->add_css( base_url("views/".DIR_SKIN."/".$CI->skin_type.'/'.$CI->skin."/skin.min.css"), TRUE);
        }
        else if( $CI->skin_type && $CI->skin && file_exists(VIEWPATH.'/'.DIR_SKIN.'/'.$CI->skin_type.'/'.$CI->skin.'/skin.css')) {
            $this->add_css( base_url("views/".DIR_SKIN."/".$CI->skin_type.'/'.$CI->skin."/skin.css"), TRUE);
        }

        $css_array = array_merge($this->css_before, $this->css_after);
        $css_array = array_unique($css_array);
        foreach($css_array as $css) {

            if( is_my_domain( $css ) ) {
                $filepath = str_replace(base_url(), "/", $css);
                $css .= "?" . date('YmdHis', filemtime( FCPATH.ltrim($filepath,DIRECTORY_SEPARATOR) ));

                if( ! (strpos($css, base_url()) !== FALSE) ) {
                    $css = base_url($css);
                }
            }

            $return .= '<link rel="stylesheet" href="'.$css.'" />'.PHP_EOL;
        }
        return $return;
    }

    /*********************************************************
     * 배열에 담긴 JS를 메타태그와 함께 같이 출력한다.
     * @return string
     ********************************************************/
    public function display_js() {
        $CI =& get_instance();
        $return = '';
        if( $CI->skin_type && $CI->skin && file_exists(VIEWPATH.'/'.DIR_SKIN.'/'.$CI->skin_type.'/'.$CI->skin.'/skin.min.js')) {
            $this->add_js(base_url("views/".DIR_SKIN."/".$CI->skin_type.'/'.$CI->skin."/skin.min.js"), TRUE);
        }
        else if ($CI->skin_type && $CI->skin && file_exists(VIEWPATH.'/'.DIR_SKIN.'/'.$CI->skin_type.'/'.$CI->skin.'/skin.js')) {
            $this->add_js(base_url("views/".DIR_SKIN."/".$CI->skin_type.'/'.$CI->skin."/skin.js"), TRUE);
        }

        $js_array = array_merge($this->js_before, $this->js_after);
        $js_array = array_unique($js_array);
        foreach($js_array as $js) {
            if( is_my_domain( $js ) ) {
                $filepath = str_replace(base_url(), "/", $js);
                $js .= "?" . date('YmdHis', filemtime( FCPATH.ltrim($filepath,DIRECTORY_SEPARATOR) ));

                if( ! (strpos($js, base_url()) !== FALSE) ) {
                    $js = base_url($js);
                }
            }
            $return .= '<script src="'.$js.'"></script>'.PHP_EOL;
        }
        // 사이트를 위한 javascript
        $return .= '<script>';
        $return .= 'var base_url="'.base_url().'";';
        $return .= 'var current_url="'.current_url().'";';
        $return .= 'var cookie_domain="'.COOKIE_DOMAIN.'";';
        $return .= 'var is_admin='.( PAGE_ADMIN ? 'true'  : 'false' ).';';
        $return .= '</script>';
        return $return;
    }

    /*********************************************************
     * 페이지의 타이틀을 가져온다.
     ********************************************************/
    public function page_title() {
        $this->meta_title = str_replace(' :: '.$this->config('site_title'), "", $this->meta_title);
        $this->meta_title = $this->meta_title ? $this->meta_title : $this->config('site_subtitle');
        if( ! empty($this->meta_title) ) $this->meta_title .= ' :: ';
        $this->meta_title .= $this->config('site_title');

        return $this->meta_title;
    }


    /*********************************************************
     * 메타태그를 자동으로 생성하여 표시한다.
     ********************************************************/
    public function display_meta(){
        // Default 값 설정
        $this->page_title();

        $this->meta_description = $this->meta_description ? $this->meta_description : $this->config('site_meta_description');
        $this->meta_keywords = $this->meta_keywords ? $this->meta_keywords : "";
        $this->meta_image = $this->meta_image ?
            $this->meta_image : str_replace(DIRECTORY_SEPARATOR, "/",  $this->config('site_meta_image' )
                ? base_url(str_replace(DIRECTORY_SEPARATOR, "/",  $this->config('site_meta_image' ))) : NULL);
        $default_keywords = explode(",", $this->config('site_meta_keywords'));
        $in_keywords = explode(",", $this->meta_keywords);
        foreach($in_keywords as $keyword) {
            $keyword = trim($keyword);
            if(! in_array($keyword, $default_keywords)) {
                array_push($default_keywords, $keyword);
            }
        }
        $default_keywords = array_unique($default_keywords);
        $this->meta_keywords = "";
        // 합친 키워드를 다시 직렬화
        foreach($default_keywords as $keyword) {
            $this->meta_keywords .= $keyword.",";
        }
        $this->meta_keywords = rtrim($this->meta_keywords,",");

        // 기본태그
        $return = "";
        $return .= '<meta charset="utf-8">'.PHP_EOL;

        // 기본 메타 태그
        $return .= '<title>' . $this->meta_title . '</title>'.PHP_EOL;
        $return .= '<meta name="description" content="'.$this->meta_description.'">'.PHP_EOL;
        $return .= '<meta name="keywords" content="'. $this->meta_keywords.'">'.PHP_EOL;
        $return .= ($this->meta_image ? '<link rel="image_src" href="'.$this->meta_image.'">': '') .PHP_EOL;
        // 페이스북 메타 태그
        $return .= '<meta property="og:title" content="'.$this->meta_title.'" />' .PHP_EOL;
        $return .= '<meta property="og:type" content="article" />' .PHP_EOL;
        $return .= '<meta property="og:url" content="'.current_url().'" />' .PHP_EOL;
        $return .= ($this->meta_image ? '<meta property="og:image" content="'.$this->meta_image.'" />': '') .PHP_EOL;
        $return .= '<meta property="og:description" content="'.$this->meta_description.'" />'.PHP_EOL;
        $return .= '<meta property="og:site_name" content="'.$this->config('site_title').'" />'.PHP_EOL;
        // 트위터 메타 태그
        $return .= '<meta name="twitter:card" content="summary"/>'.PHP_EOL;
        $return .= '<meta name="twitter:site" content="'.$this->config('site_title').'"/>'.PHP_EOL;
        $return .= '<meta name="twitter:title" content="'.$this->meta_title.'">'.PHP_EOL;
        $return .= '<meta name="twitter:description" content="'.$this->meta_description.'"/>'.PHP_EOL;
        $return .= '<meta name="twitter:creator" content="'.$this->config('site_title').'"/>'.PHP_EOL;
        $return .= ($this->meta_image ? '<meta name="twitter:image:src" content="'.$this->meta_image.'"/>' : '').PHP_EOL;
        $return .= '<meta name="twitter:domain" content="'.base_url().'"/>'.PHP_EOL;
        // 네이트온 메타 태그
        $return .= '<meta name="nate:title" content="'.$this->meta_title.'" />'.PHP_EOL;
        $return .= '<meta name="nate:description" content="'.$this->meta_description.'" />'.PHP_EOL;
        $return .= '<meta name="nate:site_name" content="'.$this->config('site_title').'" />'.PHP_EOL;
        $return .= '<meta name="nate:url" content="'.current_url().'" />'.PHP_EOL;
        $return .= ($this->meta_image ? '<meta name="nate:image" content="'.$this->meta_image.'" />' : '').PHP_EOL;
        $return .= '<link rel="canonical" href="'.current_full_url().'">'.PHP_EOL;
        $return .= $this->display_css().PHP_EOL;

        if(! empty($this->config('extra_tag_meta')) ) $return .= $this->config('extra_tag_meta') .PHP_EOL;

        return $return;
    }
}