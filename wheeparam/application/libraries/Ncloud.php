<?php
/**
 * Ncloud 관련 라이브러리
 */
class Ncloud
{
    protected $CI;

    /** 공통 사용 */
    protected $accKeyId = '';
    protected $accSecKey = "";

    /** 카카오알림톡 */
    protected $plusFriend = "";
    protected $kakaoServiceID = "";

    /** SMS 관련  */
    protected $smsServiceID = "";
    protected $smsCallback = "";

    function __construct()
    {
        $this->CI =& get_instance();

        $this->accKeyId = $this->CI->site->config('shop_nc_k_accessKey');
        $this->accSecKey = $this->CI->site->config('shop_nc_k_accessSecret');

        $this->smsServiceID = $this->CI->site->config('shop_nc_s_sid');
        $this->smsCallback = $this->CI->site->config('shop_nc_s_callback');

        $this->plusFriend = $this->CI->site->config('shop_nc_k_plusFriend');
        $this->kakaoServiceID = $this->CI->site->config('shop_nc_k_sid');
    }

    function send($params=[])
    {
        // 넘어온값
        $data['sml_type'] = element('type',$params, '');
        $data['sml_phone'] = str_replace("-","",element('phone', $params, ''));
        $data['sml_content'] = element('content', $params,'');
        $data['sml_code'] = element('code', $params, '');

        // Default 값
        $data['sml_regtime'] = date('Y-m-d H:i:s');
        $data['sml_result'] = "실패";
        $data['sml_message'] = "";

        if(empty($data['sml_type'])) {
            $data['sml_type'] = $this->CI->site->config('shop_sms_type');
        }

        if($data['sml_type'] === '' OR $data['sml_type'] === 'NONE') {
            return;
        }

        if(empty($data['sml_phone'])) {
            $data['sml_message'] = "받는사람의 휴대폰 번호가 지정되지 않았습니다.";
            $this->CI->db->insert('sms_log', $data);
            return;
        }

        if(empty($data['sml_content'])) {
            $data['sml_message'] = "발송 내용이 비어있습니다.";
            $this->CI->db->insert('sms_log', $data);
            return;
        }

        if($data['sml_type']=='KAKAO' && empty($data['sml_code'])) {
            $data['sml_message'] = "카카오알림톡 템플릿 코드가 지정되어 있지 않습니다.";
            $this->CI->db->insert('sms_log', $data);
            return;
        }

        if($data['sml_type'] == 'SMS' && empty($this->smsCallback)) {
            $data['sml_message'] = "발신번호가 입력되지 않았습니다.";
            $this->CI->db->insert('sms_log', $data);
            return;
        }

        $postData= [
            "content" => $data['sml_content'],
            "messages"=> [
                [
                    "to"=>$data['sml_phone'],
                    "content"=>$data['sml_content']
                ]
            ]
        ];

        $sTime = floor(microtime(true) * 1000);
        if($data['sml_type'] === 'KAKAO') {
            $smsURL = "https://sens.apigw.ntruss.com/alimtalk/v2/services/{$this->kakaoServiceID}/messages";
            $smsUri = "/alimtalk/v2/services/{$this->kakaoServiceID}/messages";
        } else {
            $smsURL = "https://sens.apigw.ntruss.com/sms/v2/services/{$this->smsServiceID}/messages";
            $smsUri = "/sms/v2/services/{$this->smsServiceID}/messages";
        }
        $hashString = "POST {$smsUri}\n{$sTime}\n{$this->accKeyId}";
        $dHash = base64_encode( hash_hmac('sha256', $hashString, $this->accSecKey, true) );

        if($data['sml_type'] === 'KAKAO') {
            $postData['plusFriendId'] = $this->plusFriend;
            $postData['templateCode'] = $data['sml_code'];

            if($this->CI->site->config('shop_sms_delivery_button')=='Y')
            {
                $postData['messages'][0]["buttons"] = [["type"=>"DS","name"=>"배송조회"]];
            }
        }
        else if ($data['sml_type'] === 'SMS') {
            $postData['type'] = mb_strwidth($data['sml_content']) > 79 ? 'LMS' : 'SMS';
            $postData['contentType'] = "COMM";
            $postData['from'] = $this->smsCallback;
            $data['sml_type'] = $postData['type'];
        }

        // 전송시작하자
        $postFields = json_encode($postData) ;

        $ch = curl_init();                                 //curl 초기화
        curl_setopt($ch, CURLOPT_URL, $smsURL);               //URL 지정하기
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);    //요청 결과를 문자열로 반환
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);      //connection timeout 10초
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);   //원격 서버의 인증서가 유효한지 검사 안함
        curl_setopt($ch, CURLOPT_POSTFIELDS, $postFields);       //POST data
        curl_setopt($ch, CURLOPT_POST, true);              //true시 post 전송
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Content-Type: application/json; charset=utf-8',
            'x-ncp-apigw-timestamp: '.$sTime,
            "x-ncp-iam-access-key: ".$this->accKeyId,
            "x-ncp-apigw-signature-v2: ".$dHash
        ));

        $response = curl_exec($ch);
        curl_close($ch);

        if(! $response ) {
            $data['sml_message'] = "메시지 전송에 실패하였습니다.";
            $this->CI->db->insert('sms_log', $data);
            return;
        }

        $json = json_decode($response, TRUE);

        if(! element('statusCode', $json) == '202' && !element('status', $json) == '202') {
            $data['sml_message'] = "메시지 전송에 실패하였습니다";
            $this->CI->db->insert('sms_log', $data);
            return;
        }

        if( element('statusCode', $json) == '202' || element('status', $json) == '202') {
            $data['sml_result'] = "성공";
            $data['sml_message'] = $json['statusName'] ?? '';
            $this->CI->db->insert('sms_log', $data);
            return;
        }
        else {
            $data['sml_message'] = $json['errorMessage'] ?? ($json['statusName'] ?? '');
            $this->CI->db->insert('sms_log', $data);
            return;
        }

    }
}