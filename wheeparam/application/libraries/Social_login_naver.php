<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require_once APPPATH . "libraries/Social_login.php";

class Social_login_naver extends Social_login {

    function __construct()
    {
        parent::__construct();

        $CI=&get_instance();

        $this->social_setting->client_id        = $CI->site->config('social_naver_clientid');
        $this->social_setting->client_secret    = $CI->site->config('social_naver_clientsecret');
        $this->social_setting->redirect_url     = base_url("members/social-login/naver");
        $this->social_setting->authorize_url    = "https://nid.naver.com/oauth2.0/authorize";
        $this->social_setting->token_url        = "https://nid.naver.com/oauth2.0/token";
        $this->social_setting->info_url         = "https://openapi.naver.com/v1/nid/me";
        $this->social_setting->token_request_post = FALSE;
    }

    /**
     * 사용자 프로필 받아오기
     */
    protected function _get_info( $access_token, $add_param="" )
    {
        $result = json_decode(parent::_get_info($access_token), TRUE);

        if( $result['resultcode'] == '00') {
            return $result;
        }
        else {
            return NULL;
        }
    }

    protected function _generate_profile($profile)
    {
        $return['provider'] = "naver";
        $return['id']       = $profile['response']['id'];
        $return['name']     = $profile['response']['nickname'];
        $return['profile']  = $profile['response']['profile_image'];
        $return['email']    = $profile['response']['email'];
        $return['gender']   = $profile['response']['gender'] == 'M' ? 'M' : ( $profile['response']['gender'] == 'F' ? 'F' : 'U' );
        $return['extra']    = json_encode($profile['response'], JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES);

        return $return;
    }
}