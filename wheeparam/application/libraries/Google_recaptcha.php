<?php
class Google_recaptcha {
    protected $secret = "";
    protected $api_url = "https://www.google.com/recaptcha/api/siteverify";

    function __construct()
    {
        $CI=& get_instance();
        $this->secret = $CI->site->config('google_recaptcha_secret_key');
    }

    public function check_response( $response="")
    {
        if(empty($response)) return FALSE;
        $curl_opt['response'] = $response;
        $curl_opt['secret'] = $this->secret;
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $this->api_url );
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($curl_opt));
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POST , TRUE);
        $output=curl_exec($ch);
        if(curl_errno($ch)) return FALSE;
        curl_close($ch);
        $output = json_decode($output, TRUE);
        if( isset($output['success']) && $output['success'] == true ) return TRUE;
        else return FALSE;
    }
}