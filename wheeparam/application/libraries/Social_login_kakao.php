<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require_once APPPATH . "libraries/Social_login.php";

class Social_login_kakao extends Social_login {


    function __construct()
    {
        parent::__construct();
        $CI=&get_instance();

        $this->social_setting->client_id        = $CI->site->config('social_kakao_clientid');
        $this->social_setting->client_secret    = NULL;
        $this->social_setting->redirect_url     = base_url("members/social-login/kakao");
        $this->social_setting->authorize_url    = "https://kauth.kakao.com/oauth/authorize";
        $this->social_setting->token_url        = "https://kauth.kakao.com/oauth/token";
        $this->social_setting->info_url         = "https://kapi.kakao.com/v1/user/me";
        $this->social_setting->token_request_post = FALSE;
    }

    /**
     * 사용자 프로필 받아오기
     */
    protected function _get_info( $access_token, $add_param=""  )
    {
        $result = json_decode(parent::_get_info($access_token), TRUE);

        if( empty($result['id'] )) {
            return NULL;
        }
        else {
            return $result;
        }
    }


    protected function _generate_profile($profile)
    {
        $return['provider'] = "kakao";
        $return['id']       = $profile['id'];
        $return['name']     = $profile['properties']['nickname'];
        $return['profile']  = $profile['properties']['profile_image'];
        $return['email']    = $profile['kaccount_email'];
        $return['gender']   = 'U';
        $return['extra']    = json_encode($profile, JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES);

        return $return;
    }

}