<?php
/**
 * 아임포트 라이브러리
 */
class Import_model extends WB_Model
{
    private $imp_key = "";
    private $imp_secret = "";

    function __construct()
    {
        parent::__construct();

        $this->imp_key = $this->site->config('shop_portone_api_key');
        $this->imp_secret = $this->site->config('shop_portone_api_secret');
    }

    function getAccessToken()
    {
        // AccessToken 취득
        $accessToken = "";
        $url = 'https://api.iamport.kr//users/getToken';

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query([
            "imp_key" => $this->imp_key,
            "imp_secret" => $this->imp_secret
        ]));
        curl_setopt($ch, CURLOPT_POST, true);
        $response = curl_exec($ch);
        curl_close ($ch);

        if($response) {
            $res_json = json_decode($response, TRUE);
            $accessToken = $res_json['response']['access_token'];

            if($accessToken) {
                return $accessToken;
            }
        }

        return NULL;
    }

    function CancelPayment($imp_uid, $merchant_uid, $reason="결제 검증 실패", $cancel_amount=NULL)
    {
        if(empty($imp_uid)) return;

        // AccessToken 취득
        $accessToken = $this->getAccessToken();
        if($accessToken) {
            $url = 'https://api.iamport.kr/payments/cancel';

            $fields['imp_uid'] = $imp_uid;
            $fields['merchant_uid'] = $merchant_uid;
            $fields['reason'] = $reason;

            if($cancel_amount && $cancel_amount > 0) {
                $fields['amount'] = $cancel_amount * 1;
            }

            $post_field_string = http_build_query($fields, '', '&');
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $post_field_string);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                "Authorization: {$accessToken}"
            ));
            $response = curl_exec($ch);
            curl_close ($ch);
        }
    }

    function getPaymentData ($imp_uid)
    {
        $accessToken = $this->getAccessToken();
        if($accessToken) {
            $url = "https://api.iamport.kr/payments/{$imp_uid}";
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_POST, FALSE);
            curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                "Authorization: {$accessToken}"
            ));
            $response = curl_exec($ch);
            curl_close ($ch);

            if($response) {
                $res_json = json_decode($response, TRUE);
                $result = isset($res_json['response']) && $res_json['response'] ? $res_json['response'] : NULL;

                if($result) {
                    return $result;
                }
            }
        }

        return NULL;
    }
}