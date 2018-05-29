<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require_once APPPATH . "libraries/Social_login.php";

class Social_login_google extends Social_login {

    function __construct()
    {
        parent::__construct();

        $CI=&get_instance();

        $this->social_setting->client_id        = $CI->site->config('social_google_clientid');
        $this->social_setting->client_secret    = $CI->site->config('social_google_clientsecret');
        $this->social_setting->redirect_url     = base_url("members/social-login/google");
        $this->social_setting->authorize_url    = "https://accounts.google.com/o/oauth2/auth";
        $this->social_setting->token_url        = "https://www.googleapis.com/oauth2/v4/token";
        $this->social_setting->info_url         = "https://www.googleapis.com/plus/v1/people/me";
        $this->social_setting->token_request_post = TRUE;
    }

    protected function _get_authorize_param() {
        $scope_array = array(
            "https://www.googleapis.com/auth/plus.login",
            "https://www.googleapis.com/auth/userinfo.email",
            "https://www.googleapis.com/auth/userinfo.profile",
            "https://www.googleapis.com/auth/plus.me",
            "https://www.googleapis.com/auth/plus.me"
        );

        $param = parent::_get_authorize_param();
        $param['access_type'] = "offline";
        $param['scope'] = implode(" ", $scope_array);

        return $param;
    }

    protected function _get_info($access_token, $add_param="")
    {
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
        $return['provider'] = "google";
        $return['id']       = $profile['id'];
        $return['name']     = $profile['displayName'];
        $return['profile']  = $profile['image']['url'];
        $return['email']    = $profile['emails'][0]['value'];
        $return['gender']   = $profile['gender'] == 'male' ? 'M' : ( $profile['gender'] == 'female' ? 'F' : 'U' );
        $return['extra']    = json_encode($profile, JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES);

        return $return;
    }
}