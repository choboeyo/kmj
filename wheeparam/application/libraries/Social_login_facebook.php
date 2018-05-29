<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require_once APPPATH . "libraries/Social_login.php";

class Social_login_facebook extends Social_login {

    function __construct()
    {
        parent::__construct();

        $CI =& get_instance();

        $this->social_setting->client_id        = $CI->site->config('social_facebook_appid');
        $this->social_setting->client_secret    = $CI->site->config('social_facebook_appsecret');
        $this->social_setting->redirect_url     = base_url("members/social-login/facebook");
        $this->social_setting->authorize_url    = "https://www.facebook.com/dialog/oauth";
        $this->social_setting->token_url        = "https://graph.facebook.com/v2.4/oauth/access_token";
        $this->social_setting->info_url         = "https://graph.facebook.com/v2.4/me";
        $this->social_setting->token_request_post = FALSE;
    }


    /**
     * oAuth 코드를 받아올때 필요한 패러미터를 가져온다.
     */
    protected function _get_authorize_param()
    {
        $param = parent::_get_authorize_param();
        $param['scope'] = "public_profile,email";
        return $param;
    }

    /**
     * 사용자 프로필 받아오기
     */
    protected function _get_info( $access_token, $add_param=""  )
    {
        $fields = 'id,name,picture.width(1000).height(1000),link,email,verified,about,website,birthday,gender';
        $add_param = sprintf('?access_token=%s&fields=%s',$access_token, $fields);

        $result = json_decode(parent::_get_info($access_token, $add_param), TRUE);

        if( $result['id'] ) {
            return $result;
        }
        else {
            return NULL;
        }
    }


    protected function _generate_profile($profile)
    {
        $return['provider'] = "facebook";
        $return['id']       = $profile['id'];
        $return['name']     = $profile['name'];
        $return['profile']  = $profile['picture']['data']['url'];
        $return['email']    = $profile['email'];
        $return['gender']   = $profile['gender'] == 'male' ? 'M' : ( $profile['gender'] == 'female' ? 'F' : 'U' );
        $return['extra']    = json_encode($profile, JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES);

        return $return;
    }
}