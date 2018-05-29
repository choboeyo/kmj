<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require APPPATH . '/libraries/REST_Controller.php';
/**************************************************************
 * 회원관련 API
 *************************************************************/
class Members extends REST_Controller  {

    function __construct()
    {
        parent::__construct();

        if( !$this->input->is_ajax_request() ) $this->response(array("result"=>FALSE,"message"=>langs('공통/msg/invalid_access')), 400);
    }

    /**************************************************************
     * 로그인 처리
     *************************************************************/
    function login_post()
    {
        if( $this->member->is_login() ) $this->response(array("result"=>FALSE,"message"=>langs('회원/login/already')), 400);

        $login_id = trim($this->post('login_id', TRUE));
        $login_pass = trim($this->post('login_pass', TRUE));
        $login_keep	= trim($this->post('login_keep', TRUE) === 'Y') ? TRUE : FALSE;
        $reurl = $this->post('reurl', TRUE) ? $this->post('reurl', TRUE) : base_url();

        if( empty($login_id) ) $this->response(array("result"=>TRUE,"message"=>langs('회원/login/userid_required')), 400);
        if( empty($login_pass) ) $this->response(array("result"=>TRUE,"message"=>langs('회원/login/password_required')), 400);
        if( ! $info = $this->member->get_member($login_id) ) $this->response(array("result"=>TRUE,"message"=>langs('회원/login/user_not_exist')), 400);
        if( $info['mem_password'] != get_password_hash($login_pass) OR $info['mem_status'] == 'N' ) $this->response(array("result"=>TRUE,"message"=>langs('회원/login/user_not_exist')), 400);
        if( $info['mem_status'] == 'D' ) $this->response(array("result"=>TRUE,"message"=>langs('회원/login/user_denied')), 400);
        //if( $info['mem_status'] == 'H' ) $this->response(["result"=>TRUE,"message"=>"해당 사용자는 장기간 미접속으로 인하여 휴먼계정으로 전환된 아이디 입니다."], 400);

        $this->member->login_process($info, $login_keep);
        $this->response(array("result"=>TRUE,"message"=>langs('회원/login/success'),"reurl"=>$reurl));
        exit;
    }

    /**************************************************************
     * 사용자 정보 획득
     ***************************************************************/
    function info_get()
    {
        $key    = $this->get('key', TRUE);
        $value  = $this->get('value', TRUE);

        if( empty($key) ) $this->response(array("result"=>TRUE,"message"=>langs('공통/msg/invalid_access')), 400);
        if( empty($value) ) $this->response(array("result"=>TRUE,"message"=>langs('공통/msg/invalid_access')), 400);

        $member = $this->member->get_member($value, $key);

        $this->response(array("result"=>$member), 200);
    }

    function word_check_get()
    {
        $key    = $this->get('key', TRUE);
        $value  = $this->get('value', TRUE);

        if( empty($key) ) $this->response(array("result"=>TRUE,"message"=>langs('공통/msg/invalid_access')), 400);
        if( empty($value) ) $this->response(array("result"=>TRUE,"message"=>langs('공통/msg/invalid_access')), 400);

        if( $key == 'mem_userid' && USE_EMAIL_ID )
        {
            $this->load->helper('email');
            if( ! filter_var($value, FILTER_VALIDATE_EMAIL))
            {
                $this->response(array("result"=>'VALID_EMAIL',"message"=>langs('회원/join/no_valid_email_address')), 200);
            }
        }

        $deny_nickname = explode(',',$this->site->config('deny_nickname'));
        $deny_word = explode(',', $this->site->config('deny_word'));
        $deny_id = explode(',', $this->site->config('deny_id'));

        $deny = array();
        foreach($deny_nickname as $d) $deny[] = trim($d);
        foreach($deny_word as $d) $deny[] = trim($d);
        foreach($deny_id as $d) $deny[] = trim($d);

        $this->response(array("result"=>(! in_array($value, $deny))), 200);
    }


    /**************************************************************
     * 사용자 정보 추가
     ***************************************************************/
    function info_put()
    {
        $agree = trim($this->put('agree', TRUE));
        $mem_userid     = trim($this->put('userid', TRUE));
        $mem_password   = trim($this->put('userpass', TRUE));
        $mem_password_confirm = trim($this->put('userpass_confirm', TRUE));
        $mem_nickname   = trim($this->put('usernick', TRUE));
        $mem_recv_email = $this->put('recv_email', TRUE) == 'Y' OR $this->put('recv_email', TRUE) == 'N' ? $this->put('recv_email', TRUE) : 'N';
        $mem_recv_sms = $this->put('recv_sms', TRUE) == 'Y' OR $this->put('recv_sms', TRUE) == 'N' ? $this->put('recv_sms', TRUE) : 'N';
        $mem_email = trim($this->put('useremail',TRUE));
        $mem_phone = trim($this->put('userphone', TRUE));
        $mem_auth = (int) $this->put('userauth', TRUE) > 0 ? (int)$this->put('userauth', TRUE) : 1;
        $mem_gender = ( $this->put('usergender', TRUE) == 'M' OR $this->put('usergender', TRUE) == 'F' ) ? $this->put('usergender', TRUE) : 'U';

        // 약관동의
        if( $agree !== 'Y' ) $this->response(array("result"=>FALSE,"message"=>langs('회원/join/agreement_required')), 400);

        // 아이디 체크
        $regex_email = '/^(?!(?:(?:\x22?\x5C[\x00-\x7E]\x22?)|(?:\x22?[^\x5C\x22]\x22?)){255,})(?!(?:(?:\x22?\x5C[\x00-\x7E]\x22?)|(?:\x22?[^\x5C\x22]\x22?)){65,}@)(?:(?:[\x21\x23-\x27\x2A\x2B\x2D\x2F-\x39\x3D\x3F\x5E-\x7E]+)|(?:\x22(?:[\x01-\x08\x0B\x0C\x0E-\x1F\x21\x23-\x5B\x5D-\x7F]|(?:\x5C[\x00-\x7F]))*\x22))(?:\.(?:(?:[\x21\x23-\x27\x2A\x2B\x2D\x2F-\x39\x3D\x3F\x5E-\x7E]+)|(?:\x22(?:[\x01-\x08\x0B\x0C\x0E-\x1F\x21\x23-\x5B\x5D-\x7F]|(?:\x5C[\x00-\x7F]))*\x22)))*@(?:(?:(?!.*[^.]{64,})(?:(?:(?:xn--)?[a-z0-9]+(?:-[a-z0-9]+)*\.){1,126}){1,}(?:(?:[a-z][a-z0-9]*)|(?:(?:xn--)[a-z0-9]+))(?:-[a-z0-9]+)*)|(?:\[(?:(?:IPv6:(?:(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){7})|(?:(?!(?:.*[a-f0-9][:\]]){7,})(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){0,5})?::(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){0,5})?)))|(?:(?:IPv6:(?:(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){5}:)|(?:(?!(?:.*[a-f0-9]:){5,})(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){0,3})?::(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){0,3}:)?)))?(?:(?:25[0-5])|(?:2[0-4][0-9])|(?:1[0-9]{2})|(?:[1-9]?[0-9]))(?:\.(?:(?:25[0-5])|(?:2[0-4][0-9])|(?:1[0-9]{2})|(?:[1-9]?[0-9]))){3}))\]))$/iD';
        if( empty($mem_userid) ) $this->response(array("result"=>FALSE,"message"=>langs('회원/join/user_id_required')), 400);
        if( USE_EMAIL_ID ) {
            if( ! preg_match($regex_email, $mem_userid) ) $this->response(array("result"=>FALSE,"message"=>langs('회원/join/no_valid_email_address')), 400);
            $id_tmp = explode("@", $mem_userid);
            $id = $id_tmp[0];
            $mem_email = $mem_userid;
        }
        else {
            $id = $mem_userid;
        }
        $deny_id = explode(",", $this->site->config('deny_id'));

        if( in_array($id, $deny_id) ) $this->response(array("result"=>FALSE,"message"=> langs('회원/join/user_id_contains_deny_word').  " : ". $id), 400);
        if( $this->member->get_member($mem_userid) ) $this->response(array("result"=>FALSE,"message"=>langs('회원/join/user_id_already_exists')), 400);

        // 비밀번호 체크
        if( empty($mem_password) ) $this->response(array("result"=>FALSE,"message"=>langs('회원/join/user_password_required')), 400);
        if( strlen($mem_password) < 6 ) $this->response(array("result"=>FALSE,"message"=>langs('회원/join/user_password_min_length')), 400);
        if( strlen($mem_password) > 20 ) $this->response(array("result"=>FALSE,"message"=>langs('회원/join/user_password_max_length')), 400);

        // 비밀번호 확인 체크
        if( $mem_password != $mem_password_confirm) $this->response(array("result"=>FALSE,"message"=>langs('회원/join/user_password_diffrerent')), 400);

        // 닉네임 체크
        if( empty($mem_nickname)) $this->response(array("result"=>FALSE,"message"=>langs('회원/join/user_nickname_required')), 400);
        if( mb_strlen($mem_nickname) > 20) $this->response(array("result"=>FALSE,"message"=>langs('회원/join/user_nickname_max_length')), 400);
        if( mb_strlen($mem_nickname) < 2) $this->response(array("result"=>FALSE,"message"=>langs('회원/join/user_nickname_min_length')), 400);

        $deny_nickname = explode(",", $this->site->config('deny_nickname'));
        if( in_array($mem_nickname, $deny_nickname)) $this->response(array("result"=>FALSE,"message"=> langs('회원/join/user_nickname_contains_deny_word') ." : ". $mem_nickname), 400);

        if( $this->member->get_member($mem_nickname, 'mem_nickname') ) $this->response(array("result"=>FALSE,"message"=>langs('회원/join/user_nickname_already_exists')), 400);

        // 입력시킬 데이타를 정리한다.
        $data['mode']           = "INSERT";
        $data['mem_userid'] = $mem_userid;
        $data['mem_password'] = get_password_hash($mem_password);
        $data['mem_nickname'] = $mem_nickname;
        $data['mem_email'] = $mem_email;
        $data['mem_phone'] = $mem_phone;
        $data['mem_auth'] = $mem_auth;
        $data['mem_gender'] = $mem_gender;
        $data['mem_verfy_email'] = USE_EMAIL_VERFY ? 'N' : 'Y';
        $data['mem_recv_email'] = $mem_recv_email ? $mem_recv_email : 'N';
        $data['mem_recv_sms'] = $mem_recv_sms? $mem_recv_sms : 'N';
        $data['mem_photo'] = '';

        if(! $this->member->info_process($data) ) {
            $this->response(array("result"=>FALSE,"message"=>langs('공통/msg/server_error')), 500);
        }
        else {
            $this->response(array("result"=>TRUE,"message"=>langs('회원/join/success')), 201);
        }
    }

    /**************************************************************
     * 사용자 정보 변경
     ***************************************************************/
    function info_post()
    {
        $mem_nickname   = trim($this->post('usernick', TRUE));
        $mem_recv_email = $this->post('recv_email', TRUE) == 'Y' OR $this->post('recv_email', TRUE) == 'N' ? $this->post('recv_email', TRUE) : 'N';
        $mem_recv_sms = $this->post('recv_sms', TRUE) == 'Y' OR $this->post('recv_sms', TRUE) == 'N' ? $this->post('recv_sms', TRUE) : 'N';
        $mem_email = trim($this->post('useremail',TRUE));
        $mem_phone = trim($this->post('userphone', TRUE));
        $mem_gender = ( $this->post('usergender', TRUE) == 'M' OR $this->post('usergender', TRUE) == 'F' ) ? $this->post('usergender', TRUE) : 'U';


        // 닉네임 체크
        if( empty($mem_nickname)) $this->response(array("result"=>FALSE,"message"=>langs('회원/join/user_nickname_required')), 400);
        if( mb_strlen($mem_nickname) > 20) $this->response(array("result"=>FALSE,"message"=>langs('회원/join/user_nickname_max_length')), 400);
        if( mb_strlen($mem_nickname) < 2) $this->response(array("result"=>FALSE,"message"=>langs('회원/join/user_nickname_min_length')), 400);

        $deny_nickname = explode(",", $this->site->config('deny_nickname'));
        if( in_array($mem_nickname, $deny_nickname)) $this->response(array("result"=>FALSE,"message"=> langs('회원/join/user_nickname_contains_deny_word') ." : ". $mem_nickname), 400);

        if( $mem_nickname != $this->member->info('nickname') && $this->member->get_member($mem_nickname, 'mem_nickname') )
            $this->response(array("result"=>FALSE,"message"=>langs('회원/join/user_nickname_already_exists')), 400);

        // 입력시킬 데이타를 정리한다.
        $data['mem_nickname'] = $mem_nickname;
        $data['mem_email'] = $mem_email;
        $data['mem_phone'] = $mem_phone;
        $data['mem_gender'] = $mem_gender;
        $data['mem_recv_email'] = $mem_recv_email ? $mem_recv_email : 'N';
        $data['mem_recv_sms'] = $mem_recv_sms? $mem_recv_sms : 'N';

        $this->db->where('mem_idx', $this->member->is_login() );
        $this->db->update('member', $data);
        $this->response(array("result"=>TRUE,"message"=>langs('회원/msg/modify_success')), 200);
    }

    /**************************************************************
     * 사용자를 관리자로 추가
     ***************************************************************/
    function admin_post()
    {
        if(! $this->member->is_super() )
            $this->response(array('status'=>FALSE, 'message'=>'권한이 없습니다.'), 400);

        $mem_idx = $this->post('mem_idx', TRUE);
        if(empty($mem_idx))
            $this->response(array('status'=>FALSE, 'message'=>'관리자로 설정할 회원이 존재하지 않습니다.'), 400);

        if(! $mem = $this->member->get_member($mem_idx,'mem_idx') )
        {
            $this->response(array('status'=>FALSE, 'message'=>'존재하지 않는 회원이거나 이미 탈퇴한 회원입니다.'), 400);
        }

        if($mem['mem_status'] != 'Y')
        {
            $this->response(array('status'=>FALSE, 'message'=>'존재하지 않는 회원이거나 이미 탈퇴한 회원입니다.'), 400);
        }

        $this->db->trans_start();

        $this->db->set('mem_idx', $mem_idx)->set('ath_type', 'SUPER')->insert('member_auth');
        $this->db->set('mem_auth',10)->where('mem_idx', $mem_idx)->update('member');

        if ($this->db->trans_status() === FALSE)
        {
            $this->db->trans_rollback();
            $this->response(array('status'=>FALSE, 'message'=>'관리자로 설정하는데 실패하였습니다. 이미 관리자로 설정된 회원이가나, DB오류입니다.'), 500);
        }
        else
        {
            $this->db->trans_commit();
            $this->response(array('status'=>TRUE, 'message'=>'관리자 추가 완료'), 200);
        }
    }

    /**************************************************************
     * 관리자 권한 삭제
     ***************************************************************/
    function admin_delete()
    {
        if(! $this->member->is_super() )
            $this->response(array('status'=>FALSE, 'message'=>'권한이 없습니다.'), 400);

        $mem_idx = $this->delete('mem_idx', TRUE);
        if(empty($mem_idx))
            $this->response(array('status'=>FALSE, 'message'=>'회원이 존재하지 않습니다.'), 400);

        if(! $mem = $this->member->get_member($mem_idx,'mem_idx') )
        {
            $this->response(array('status'=>FALSE, 'message'=>'존재하지 않는 회원이거나 이미 탈퇴한 회원입니다.'), 400);
        }

        $this->db->trans_start();

        $this->db->where('mem_idx', $mem_idx)->where('ath_type', 'SUPER')->delete('member_auth');
        $this->db->set('mem_auth',1)->where('mem_idx', $mem_idx)->update('member');

        if ($this->db->trans_status() === FALSE)
        {
            $this->db->trans_rollback();
            $this->response(array('status'=>FALSE, 'message'=>'권한을 삭제하는데 실패하였습니다. 관리자 권한이 없는 회원이거나, DB오류입니다.'), 500);
        }
        else
        {
            $this->db->trans_commit();
            $this->response(array('status'=>TRUE, 'message'=>'관리자 삭제 완료'), 200);
        }
    }

    /**************************************************************
     * 사용자 상태 변경
     ***************************************************************/
    function status_post()
    {
        if(! $this->member->is_super() )
            $this->response(array('status'=>FALSE, 'message'=>'권한이 없습니다.'), 400);

        $mem_idx = $this->post('mem_idx', TRUE);
        $current_status = $this->post('current_status', TRUE);
        $change_status = $this->post('change_status', TRUE);

        if(empty($mem_idx))
            $this->response(array('status'=>FALSE, 'message'=>'관리자로 설정할 회원이 존재하지 않습니다.'), 400);

        if(! $mem = $this->member->get_member($mem_idx,'mem_idx') )
        {
            $this->response(array('status'=>FALSE, 'message'=>'존재하지 않는 회원입니다.'), 400);
        }

        if( $mem['mem_status'] != $current_status )
        {
            $this->response(array('status'=>FALSE, 'message'=>'변경전 회원상태가 실제 DB상 회원상태와 일치하지 않습니다.'), 400);
        }

        if( ! in_array($change_status, array('Y','N','D','H')))
        {
            $this->response(array('status'=>FALSE, 'message'=>'변경하려는 회원상태가 올바르지 않습니다.'), 400);
        }

        $this->db->trans_start();

        if( $change_status == 'Y' ) {
            $this->db->set('mem_leavetime', '0000-00-00 00:00:00');
            $this->db->set('mem_bantime', '0000-00-00 00:00:00');
            $this->db->set('mem_htime', '0000-00-00 00:00:00');
        }
        else if ($change_status == 'B') {
            $this->db->set('mem_bantime', date('Y-m-d H:i:s'));
        }
        else if ($change_status == 'N') {
            $this->db->set('mem_leavetime', date('Y-m-d H:i:s'));
        }
        else if ($change_status == 'H') {
            $this->db->set('mem_htime', date('Y-m-d H:i:s'));
        }

        $this->db->set('mem_status', $change_status)->where('mem_idx', $mem_idx)->update('member');

        if ($this->db->trans_status() === FALSE)
        {
            $this->db->trans_rollback();
            $this->response(array('status'=>FALSE, 'message'=>'시스템 오류가 발생하였습니다.'), 500);
        }
        else
        {
            $this->db->trans_commit();
            $this->response(array('status'=>TRUE, 'message'=>'회원 상태변경이 완료되었습니다.'), 200);
        }
    }
}