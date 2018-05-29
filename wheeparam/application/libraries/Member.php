<?php
/**
 * Class Member
 * ---------------------------------------------------------
 * 회원 관련 라이브러리
 *
 */
class Member {
    protected $CI;
    protected $member_info;

    function __construct()
    {
        $this->CI =& get_instance();
    }

    /***********************************************************
     * 현재 로그인 여부를 확인하고,
     * 로그인중이라면 회원고유 PK를 얻어온다.
     ***********************************************************/
    function is_login()
    {
        if ($this->CI->session->userdata('ss_mem_idx')) {
            return $this->CI->session->userdata('ss_mem_idx');
        } else {
            return FALSE;
        }
    }

    /***********************************************************
     * 현재 접속중인 회원의 레벨을 확인한다.
     ***********************************************************/
    function level()
    {
        if( ! $this->is_login() ) return 0;

        return (int) $this->info('auth');
    }

    /***********************************************************
     * 해당 회원이 슈퍼관리자 권한이 있는지 확인한다
     ***********************************************************/
    function is_super($mem_idx="")
    {
        // 지정된 mem_idx가 없으면 기본적으로 로그인된 idx를 가져옴
        if( ! $mem_idx ) $mem_idx = $this->is_login();

        // 현재 로그인중이 아니라면 return
        if( ! $mem_idx ) return FALSE;

        $member = $this->info();

        if( isset($member['auth']['SUPER']) && $member['auth']['SUPER'] ) {
            return TRUE;
        }
    }

    /***********************************************************
     * 현재 로그인 중인 사용자의 정보를 얻어온다.
     ***********************************************************/
    function info($column="")
    {
        $prefix = "mem_";
        if(! $mem_idx = $this->is_login() ) return NULL;

        if( ! $this->member_info )
        {
            $this->member_info = $this->get_member($mem_idx, "mem_idx");
        }

        if( $column )
        {
            return $this->member_info[$prefix.$column];
        }
        else {
            return $this->member_info;
        }
    }

    /***********************************************************
     * 해당 회원이 해당 게시판 관리자 권한이 있는지 확인한다
     ***********************************************************/
    function is_board_admin($brd_key, $mem_idx="")
    {
        // 지정된 mem_idx가 없으면 기본적으로 로그인된 idx를 가져옴
        if( ! $mem_idx ) $mem_idx = $this->is_login();

        // 현재 로그인중이 아니라면 return
        if( ! $mem_idx ) return FALSE;

        $member = $this->get_member( $mem_idx, "mem_idx" );

        // 슈퍼 관리자라면
        if( isset($member['auth']['SUPER']) && $member['auth']['SUPER'] ) {
            return TRUE;
        }
        // 게시판 슈퍼 관리자라면
        else if ( isset($member['auth']['BOARD']['SUPER']) && $member['auth']['BOARD']['SUPER'])
        {
            return TRUE;
        }
        // 해당 게시판의 관리자라면
        else if ( isset($member['auth']['BOARD'][$brd_key]) && $member['auth']['BOARD'][$brd_key])
        {
            return TRUE;
        }
        else {
            return FALSE;
        }
    }

    /***********************************************************
     * 특정 ID의 사용자 정보를 획득한다.
     ***********************************************************/
    function get_member($mem_userid="", $mem_column="mem_userid")
    {
        if(empty($mem_userid)) return NULL;

        $result = $this->CI->db
            ->from("member")
            ->where($mem_column, $mem_userid)
            ->limit(1)
            ->get();

        $member = $result->row_array();

        // 권한정보를 가져온다
        $result = $this->CI->db
            ->from('member_auth')
            ->where("mem_idx", $member['mem_idx'])
            ->get();

        $ath_list = $result->result_array();
        foreach($ath_list as $ath)
        {
            $member['auth'][$ath['ath_type']] = $ath['ath_key'] ? $ath['ath_key'] : TRUE;
        }

        // 소셜로그인 정보를 가져온다.
        $result = $this->CI->db
            ->from('member_social')
            ->where('mem_idx', $member['mem_idx'])
            ->get();
        $social_list = $result->result_array();
        foreach($social_list as $social)
        {
            $member['social'][$social['soc_provider']] = $social;
        }

        return $member;
    }

    /***********************************************************
     * 회원 등록/수정을 담당한다.
     ***********************************************************/
    function info_process($param)
    {
        // 등록인지 수정인지 여부가 정의되지 않았다면 false;
        if( ! isset($param) OR ($param['mode'] != 'INSERT' && $param['mode'] != 'MODIFY' )) return FALSE;

        // 모드를 정의하고 기존 변수는 unset
        $is_insert = ($param['mode'] == 'INSERT');
        unset($param['mode']);

        // 이메일 아이디 사용이라면, email값을 아이디로 사용한다.
        if( USE_EMAIL_ID ) {
            if( $is_insert ) {
                $param['mem_email'] = $param['mem_userid'];
            }
        }

        // 이메일 인증을 사용하지 않으면 인증여부를 기본 Y로 설정한다.
        if( ! USE_EMAIL_VERFY )
        {
            $param['mem_verfy_email'] = 'Y';
        }

        // 신규 등록모드라면 필요 데이타를 추가한다.
        if( $is_insert )
        {
            $param['mem_regtime']   = date('Y-m-d H:i:s');
            $param['mem_regip']     = ip2long($this->CI->input->ip_address());
            $param['mem_logtime']   = date('Y-m-d H:i:s');
            $param['mem_logip']     = ip2long($this->CI->input->ip_address());
            $param['mem_point']     = 0;

            if(! $this->CI->db->insert('member', $param)) return FALSE;

            // 포인트 사용 및 가입시 포인트값이 있다면?
            if( $this->CI->site->config('point_use') == 'Y' && (int)$this->CI->site->config('point_member_register') > 0 )
            {
                $member_info = $this->get_member($param['mem_userid']);
                $this->add_point($member_info['mem_idx'], $this->CI->site->config('point_member_register'), FALSE, "JOIN", point_type("JOIN"), 0);
            }

            return TRUE;
        }
        // 수정모드라면
        else 
        {
            // mem_idx가 정의되어 있지 않으면 리턴
            if( ! isset($param['mem_idx']) ) return FALSE;

            // 비밀번호 변경이 체크되있지 않다면 비밀번호를 없앤다.
            if( element('mem_password_change', $param, FALSE) !== TRUE )
            {
                unset($param['mem_password']);
            }
            else
            {
                $param['mem_password'] = get_password_hash($param['mem_password']);
                unset($param['mem_password_change']);
            }

            $this->CI->db->where('mem_idx', $param['mem_idx']);
            return $this->CI->db->update('member', $param);
        }
    }

    /***********************************************************
     * 로그인 처리를 진행한다.
     ***********************************************************/
    function login_process($member_info, $login_keep=FALSE, $login_keep_update=FALSE)
    {
        if( empty($member_info) ) return FALSE;

        // 로그인 세션 저장
        $this->CI->session->set_userdata('ss_mem_idx', $member_info['mem_idx']);

        // DB에 로그 기록 작성
        $log_data['mem_idx'] 		= $member_info['mem_idx'];
        $log_data['mlg_ip']			= ip2long( $this->CI->input->ip_address() );
        $log_data['mlg_regtime']	= date('Y-m-d H:i:s');
        $log_data['mlg_browser']	= $this->CI->agent->browser();
        $log_data['mlg_version']	= $this->CI->agent->version();
        $log_data['mlg_platform']	= $this->CI->agent->platform();
        $log_data['mlg_is_mobile']	= $this->CI->agent->is_mobile() ? 'Y' : 'N';
        $log_data['mlg_mobile']		= $this->CI->agent->mobile();
        $this->CI->db->insert('member_log', $log_data);

        // 최종로그인 시간 업데이트
        $this->CI->db
            ->where('mem_idx', $member_info['mem_idx'])
            ->set('mem_logtime', date('Y-m-d H:i:s'))
            ->set('mem_logip', ip2long( $this->CI->input->ip_address() ))
            ->set('mem_logcount', 'mem_logcount+1', FALSE)
            ->update("member");

        // 로그인시 포인트 부여
        if( $this->CI->site->config('point_use') == 'Y' && $this->CI->site->config('point_member_login') > 0 )
        {
            $this->add_point($member_info['mem_idx'], $this->CI->site->config('point_member_login'), TRUE, "TODAY_LOGIN", point_type("TODAY_LOGIN"), 0);
        }

        // 자동로그인 설정시 자동로그인 처리
        if( $login_keep )
        {
            // 자동로그인 날짜 갱신이라면?
            if($login_keep_update)
            {
                $this->CI->db
                    ->where('aul_key', get_password_hash( $member_info['mem_userid'] ))
                    ->where('aul_ip', ip2long( $this->CI->input->ip_address() ))
                    ->set('aul_regtime', date('Y-m-d H:i:s'))
                    ->update("member_autologin");
            }
            // 자동로그인 신규 추가라면?
            else
            {
                // 자동 로그인 DB 입력
                $aul_data['mem_idx']	= $member_info['mem_idx'];
                $aul_data['aul_key']	= get_password_hash( $member_info['mem_userid'] );
                $aul_data['aul_ip']		= ip2long( $this->CI->input->ip_address() );
                $aul_data['aul_regtime']= date('Y-m-d H:i:s');
                $this->CI->db->insert("member_autologin", $aul_data);
            }

            // 쿠키 생성 (한달만료)
            set_cookie(COOKIE_AUTOLOGIN, get_password_hash( $member_info['mem_userid'] ), 60*60*24*30);
        }
    }

    /**********************************************************
     * 포인트 추가 실제 처리
     *********************************************************/
    public function add_point($mem_idx, $point, $point_on_day=FALSE, $target_type="", $description="",$target_idx="")
    {
        // 포인트 기능 미사용일경우 리턴
        if($this->CI->site->config('point_use') != 'Y') return FALSE;
        
        $target_type = strtoupper($target_type);
        $target_array = point_type(FALSE);
        // 회원 IDX가 잘못된경우 리턴
        if( (int) $mem_idx <= 0 ) return FALSE;
        // 포인트가 0 일경우 리턴
        if( (int) $point == 0 ) return FALSE;
        // 포인트 종류가 다를경우 리턴
        if( !in_array($target_type, $target_array) ) return FALSE;

        // 하루에 한번 입력하는경우 오늘 입력된 데이타가 있는지 확인한다.
        if( $point_on_day && (int) $point > 0 )
        {
            $this->CI->db->select("COUNT(*) AS `cnt`");
            $this->CI->db->where("mem_idx", $mem_idx);
            $this->CI->db->where("target_type", $target_type);
            $this->CI->db->where("mpo_value >", "0");
            $this->CI->db->where("mpo_regtime >=", date('Y-m-d 00:00:00'));
            $this->CI->db->where("mpo_regtime <=", date('Y-m-d 23:59:59'));
            $temp = $this->CI->db->get("member_point");
            $count = (int) $temp->row(0)->cnt;
            if( $count > 0 )
            {
                return FALSE;
            }
        }

        // 입력할 데이타 정리
        $this->CI->db->set('mem_idx', $mem_idx);
        $this->CI->db->set('mpo_value', $point);
        $this->CI->db->set('mpo_description', $description);
        $this->CI->db->set('target_type', $target_type);
        $this->CI->db->set('target_idx', $target_idx);
        $this->CI->db->set('mpo_regtime', date('Y-m-d H:i:s') );
        $this->CI->db->insert('member_point');

        // 회원 DB에 반영
        $point=(int)$this->CI->db->select('SUM(mpo_value) AS sumval')->where('mem_idx', $this->CI->member->is_login())->get('member_point')->row(0)->sumval;
        $this->CI->db->set('mem_point', $point);
        $this->CI->db->where('mem_idx', $mem_idx);
        $this->CI->db->update('member');

        return TRUE;
    }

    /**
     * 소셜 로그인 정보를 가져온다.
     * @param $provider
     * @param $id
     */
    function get_social($provider, $id)
    {
        return $this->CI->db->where('soc_provider', $provider)->where('soc_id', $id)->get('member_social')->row_array();
    }
}