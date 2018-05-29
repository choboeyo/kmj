<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 *
 * HookPostControllerConstructor.php
 *
 * 컨트롤러가 인스턴스화 된 직후 가동되는 후킹 클래스.
 *
 */
class HookPostControllerConstructor {

    protected $CI;

    /************************************************
     * 후킹 초기 실행 지점
     ***********************************************/
    function init() {
        // 인스턴스화 된 컨트롤러를 불러와 참조시킨다.
        $this->CI =& get_instance();

        if( PAGE_INSTALL ) return;
        $install_file =  APPPATH . '..'.DIRECTORY_SEPARATOR.'config'.DIRECTORY_SEPARATOR.'no_install.php';

        if( file_exists($install_file) )
        {
            include_once $install_file;
            exit;
        }

        $this->load_config();
        $this->setup_device_view();
        $this->autologin_check();
        $this->member_status_check();
        $this->admin_check();
        $this->statics();
        $this->ip_block();
        $this->lang_set();
    }

    /************************************************
     * 환경설정 파일을 로드한다.
     ***********************************************/
    function load_config()
    {
        $this->CI->config->set_item('base_url', BASE_URL);
        $this->CI->config->set_item('cookie_domain', COOKIE_DOMAIN);
        $this->CI->load->database();

        $this->CI->load->helper(array('url','form','cookie','common','thumbnail','widgets','statics','lang'));
        $this->CI->load->helper('language');
        $this->CI->load->library('site');
        $this->CI->load->library('session');
        $this->CI->load->library('member');
        $this->CI->load->library('user_agent');
        $this->CI->load->library('banner');

        if( PAGE_ADMIN )
        {
            $this->CI->load->helper('admin');
        }
    }

    /************************************************
     * 현재 접속한 기기정보와, 보기 모드 설정들을 정의한다.
     ***********************************************/
    function setup_device_view()
    {
        // 모바일 접속여부에 따라 device 정보 확인
        $device = $viewmode = $this->CI->agent->is_mobile() ? DEVICE_MOBILE : DEVICE_DESKTOP;

        // 해당 모드로 보기 쿠키가 존재한다면 해당 보기 모드로
        if( get_cookie( COOKIE_VIEWMODE )  && ( get_cookie( COOKIE_VIEWMODE ) == DEVICE_DESKTOP OR get_cookie( COOKIE_VIEWMODE ) == DEVICE_MOBILE) )
        {
            $viewmode = get_cookie(COOKIE_VIEWMODE);
        }

        // 사이트 정보에 저장
        $this->CI->site->device = $device;
        $this->CI->site->viewmode = $viewmode;
    }

    /**************************************************
     * 자동로그인 체크
     ***********************************************/
    function autologin_check()
    {
        if( PAGE_INSTALL ) return;
        if( $this->CI->agent->is_robot() ) return;	// 로봇일경우도 건너뛴다.
        if( $this->CI->member->is_login() ) return;	// 로그인중이라면 건너뛴다.

        // 자동로그인 쿠키가 있다면
        if($aul_key = get_cookie(COOKIE_AUTOLOGIN))
        {
            // DB에 저장된 자동로그인 데이타가 있는지 확인한다.
            $result =
                $this->CI->db
                    ->where('aul_key', $aul_key)
                    ->where('aul_ip', ip2long( $this->CI->input->ip_address() ))
                    ->limit(1)
                    ->get('member_autologin');
            $autologin = $result->row_array();

            if( ! $autologin )
            {
                // DB에 데이타가 없다면 쿠키삭제
                delete_cookie(COOKIE_AUTOLOGIN);
                return FALSE;
            }
            else if( strtotime($autologin['aul_regtime']) + (60*60*24*30) < time() )
            {
                $this->CI->member->remove_autologin($autologin['mem_idx']);
                return FALSE;
            }
            else
            {
                $member_info = $this->CI->member->get_member( $autologin['mem_idx'], "mem_idx" );

                if(! $member_info)
                {
                    // 회원정보가 없다면 삭제
                    $this->CI->member->remove_autologin($autologin['mem_idx']);
                    return FALSE;
                }
                else if( $member_info['mem_status'] != 'Y' )
                {
                    // 회원상태가 '정상'이 아닌경우도 자동로그인 삭제
                    $this->CI->member->remove_autologin($autologin['mem_idx']);
                    return FALSE;
                }
                else
                {
                    $this->CI->member->login_process( $member_info, TRUE, TRUE );
                }
            }
        }
    }

    function member_status_check()
    {
        if(! $this->CI->member->is_login()) return;
        if( PAGE_AJAX ) return;

        $member = $this->CI->member->get_member( $this->CI->member->is_login(), "mem_idx" );
        if( $member['mem_status'] == 'H' )
        {
            if( strtolower($this->CI->uri->segment(1)) != 'members' OR (  strtolower($this->CI->uri->segment(2)) != 'activation' && strtolower($this->CI->uri->segment(2)) != 'logout'  )) {
                redirect(base_url('members/activation'));
                exit;
            }
        }
    }

    /**************************************************
     * 관리자 페이지인 경우,
     * 로그인 체크및 관리자 권한을 체크합니다.
     ***********************************************/
    function admin_check()
    {
        if( ! PAGE_ADMIN ) return;
        if( $this->CI->uri->segment(2) == 'members' && $this->CI->uri->segment(3) == 'login' ) return;

        if( ! $this->CI->member->is_login() )
        {
            alert_login("admin/members/login");
            exit;
        }
        else
        {
            if(! $this->CI->member->is_super())
            {
                alert('해당 페이지에 접근할 권한이 없습니다.', base_url());
                exit;
            }
        }
    }

    /**************************************************
     * 통계데이타 기록
     ***********************************************/
    function statics()
    {
        if ( PAGE_INSTALL || PAGE_ADMIN ) return;
        if ( $this->CI->input->is_ajax_request() ) return;	// AJAX 요청인경우도 리턴
        if ( $this->CI->agent->is_robot() ) return;		// 검색봇의 경우도 리턴

        if ( get_cookie(COOKIE_STATICS) ) return;		// 방문자 쿠키가 있는경우 리턴
        if( $this->CI->input->ip_address() == '127.0.0.1' OR $this->CI->input->ip_address() == '0.0.0.0' ) return; // 접속IP가 정확하지 않을경우 리턴

        $sta_data['sta_regtime'] 	= date('Y-m-d H:i:s');
        $sta_data['sta_browser'] 	= $this->CI->agent->browser();
        $sta_data['sta_version'] 	= $this->CI->agent->version();
        $sta_data['sta_is_mobile']	= $this->CI->agent->is_mobile() ? 'Y':'N';
        $sta_data['sta_mobile'] 	= $this->CI->agent->mobile();
        $sta_data['sta_platform']	= $this->CI->agent->platform();
        $sta_data['sta_referrer']	= "";
        $sta_data['sta_referrer_host'] = "";
        $sta_data['sta_keyword']	= "";
        $sta_data['sta_ip']			= ip2long($this->CI->input->ip_address());
        $sta_data['sta_country'] = "";
        $sta_data['sta_country_code'] = "";
        $sta_data['sta_addr'] = "";
        $sta_data['sta_org'] = "";
        $sta_data['sta_agent'] = $this->CI->agent->agent_string();

        // 브라우져가 없다면 로봇으로 파악하고 return
        if( empty($sta_data['sta_browser']) ) return;

        // 플랫폼이 Unknown Platform 이라면 로봇일 가능성이크므로 리턴
        if( $sta_data['sta_platform'] == 'Unknown Platform' ) return;

        if( $this->CI->agent->is_referral() )
        {
            $sta_data['sta_referrer'] = $this->CI->agent->referrer();

            // 리퍼러에서 호스트와 패러미터 추출
            $referrer =  parse_url($sta_data['sta_referrer']);
            $sta_data['sta_referrer_host'] = (isset($referrer['host'])) ? $referrer['host'] : "";

            // 검색키워드 분석
            $keyword = '';
            if(isset($referrer['query']) && $referrer['query'])
            {
                $queries = explode('&', $referrer['query']);
                foreach ($queries as $query) {
                    if (preg_match('/^(query|q|p)=(.+)$/i', $query, $matches)) {
                        $keyword = urldecode($matches[2]);
                        break;
                    }
                }
            }
            $sta_data['sta_keyword'] = trim($keyword);
        }
        // 집계 DB에 저장
        $result = $this->CI->db->insert("statics", $sta_data);

        // 통계 DB에도 저장
        $query = "	INSERT INTO {$this->CI->db->dbprefix}statics_date SET `std_date` = '".date('Y-m-d')."', `std_count` = 1 ";
        if( $this->CI->agent->is_mobile() ) $query .= ", `std_mobile` = 1 ";
        $query .= ' ON DUPLICATE KEY UPDATE `std_count` = `std_count` + 1';
        if( $this->CI->agent->is_mobile() ) $query .= ", `std_mobile` = `std_mobile` + 1";

        $this->CI->db->query($query);

        $expire = strtotime(date('Y-m-d 23:59:59')) - time();
        set_cookie(COOKIE_STATICS, ip2long($this->CI->input->ip_address()), $expire );
    }

    /**************************************************
     * IP접근 금지
     ***********************************************/
    function ip_block()
    {
        if( $this->CI->site->config('deny_ip') )
        {
            $blacklist = $this->CI->site->config('deny_ip');
            $blacklist = preg_replace("/[\r|\n|\r\n]+/", ',', $blacklist);
            $blacklist = preg_replace("/\s+/", '', $blacklist);
            if (preg_match('/(<\?|<\?php|\?>)/xsm', $blacklist)) {
                $blacklist = '';
            }
            if ($blacklist) {
                $blacklist = explode(',', trim($blacklist, ','));
                $blacklist = array_unique($blacklist);
                if (is_array($blacklist)) {
                    $this->CI->load->library('Ipfilter');
                    $ipfilter = new Ipfilter();
                    if ($ipfilter->filter($blacklist)) {
                        $title = 'Not Allowed IP';
                        show_error("Access Denied", '500', $title);
                        exit;
                    }
                }
            }

        }
    }

    /**************************************************
     * 다국어 설정
     ***********************************************/
    function lang_set()
    {
        // 사용할 언어 리스트
        $accept_lang = $this->CI->site->config('accept_languages');
        $accept_lang = explode(',', $accept_lang);

        // 다국어 사용설정이 FALSE 면 리턴
        if( $this->CI->site->config('use_localize') != 'Y' )
        {
            unset($accept_lang);
            $accept_lang = array();
            $accept_lang[] = $this->CI->site->config('default_language');
        }

        // Default 값으로 언어중 가장 첫번째값
        $lang = $this->CI->site->config('default_language');

        // 현재 브라우져에서 ACCEPT_LANG에 정의된 언어셋값이 있는지 확인한다.
        foreach($accept_lang as $lng)
        {
            if( preg_match('/'.$lng.'/', isset($_SERVER['HTTP_ACCEPT_LANGUAGE']) && $_SERVER['HTTP_ACCEPT_LANGUAGE']) ? $_SERVER['HTTP_ACCEPT_LANGUAGE'] : '')
            {
                $lang = $lng;
            }
        }

        // 쿠키에 저장된 언어값이 있는지 확인하고, 저장된 언어값이 있다면 그언어를 불러온다.
        $cookie_lang = get_cookie('site_lang');
        if( ! empty($cookie_lang) && in_array($cookie_lang, $accept_lang) )
        {
            $lang = $cookie_lang;
        }

        // 만약 GET 값으로 lang 값이 넘어왔고, 이값이 언어 리스트에 있다면
        $get_lang = $this->CI->input->get('lang', TRUE);
        if( ! empty($get_lang) && in_array($get_lang, $accept_lang) )
        {
            set_cookie("site_lang", $get_lang, 60*60*24*30 );
            $lang = $get_lang;
        }

        // 언어를 define
        define('LANG', $lang);
        $this->CI->site->lang = $lang;

    }
}