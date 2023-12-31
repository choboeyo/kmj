<?php
/**
 * 최신글 추출 함수
 * @param string $skin_name 스킨이름
 * @param string $brd_key   게시판 고유 키
 * @param int $rows         가져올 행 수
 * @param bool $get_thumb_img   썸네일 이미지를 가져올 것인가?
 */
function latest($skin_name="", $brd_key="", $rows=5, $get_thumb_img=FALSE, $file_list=FALSE, $cache_time=1)
{
    $CI =& get_instance();

    if(empty($skin_name)) return "<p class='alert alert-danger'>".langs('게시판/latest/not_set_skin')."</p>";
    if(empty($brd_key)) return "<p class='alert alert-danger'>".langs('게시판/latest/not_set_board')."</p>";

    $skin_dir = rtrim(VIEWPATH, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR . DIR_SKIN . DIRECTORY_SEPARATOR . 'latest'. DIRECTORY_SEPARATOR . $skin_name;
    $skin_file = $skin_dir . DIRECTORY_SEPARATOR . "skin.php";

    // 스킨 폴더나 스킨 파일이 존재하지 않을때
    if(! is_dir($skin_dir) OR !is_file($skin_file )) return '<p class="alert alert-danger">'.langs('게시판/latest/not_exist_skin').'</p>';

    $cache_name = "board-{$brd_key}-{$rows}-".($get_thumb_img ? 'thumb' : 'nothumb');

    $CI->load->library('boardlib');
    $data['board'] = $CI->boardlib->get($brd_key, FALSE);

    if(empty($data['board']) OR !isset($data['board']['brd_key'])) return '<p class="alert alert-danger">'.langs('게시판/msg/not_exist').'</p>';

    if( ! $data['list'] = $CI->cache->get($cache_name) )
    {
        $CI->db
            ->select("P.*, B.brd_title")
            ->from('board_post AS P')
            ->join("board AS B","B.brd_key=P.brd_key","inner")
            ->where('P.brd_key', $brd_key)
            ->where('post_status','Y')
            ->where('post_notice', 'N')
            ->order_by("post_num DESC, post_reply ASC, post_idx ASC")
            ->limit(5);

        $post_list = $CI->db->get()->Result_array();

        foreach($post_list as &$row)
        {
            $row = $CI->boardlib->post_process($data['board'], $row, "",  $file_list);
        }

        $data['list'] = $post_list;

        if(! IS_TEST) {
            $CI->cache->save($cache_name, $data['list'], 60*5);
        }
    }

    $data['brd_key'] = $brd_key;

    // 스킨 불러오기
    $skin = $CI->load->view( DIR_SKIN . DIRECTORY_SEPARATOR . 'latest'. DIRECTORY_SEPARATOR . $skin_name . DIRECTORY_SEPARATOR . "skin.php", $data, TRUE );

    return $skin;
}

/**
 * 상품 진열장
 * @param string $skin_name 스킨이름
 * @param string $dsp_key 진열장 고유키
 * @return void
 */
function shop_display($skin_name="", $dsp_key="")
{
    $CI=&get_instance();

    $CI->load->model('shop_model');
    $CI->load->model('products_model');

    if(empty($skin_name)) {
        echo '<p class="alert alert-danger">스킨이 지정되지 않았습니다.</p>';
        return;
    }

    if(empty($dsp_key)) {
        echo '<p class="alert alert-danger">진열장 고유키가 지정되지 않았습니다.</p>';
        return;
    }

    if(! file_exists(VIEWPATH . DIR_SKIN . DIRECTORY_SEPARATOR . "shop_list". DIRECTORY_SEPARATOR . $skin_name . DIRECTORY_SEPARATOR . "list.php"))
    {
        echo '<p class="alert alert-danger">지정한 스킨이 존재하지 않습니다.</p>';
        return;
    }

    if(! $display_info = $CI->db->where('dsp_key', $dsp_key)->get('products_display')->row_array())
    {
        echo '<p class="alert alert-danger">진열장 정보가 존재하지 않습니다.</p>';
        return;
    }


    $data['list'] = $CI->shop_model->getDisplayList($dsp_key, $display_info['dsp_idx']);
    $data['list'] = $CI->products_model->generateProductList($data['list']);

    $skin_dir = rtrim(VIEWPATH, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR . DIR_SKIN . DIRECTORY_SEPARATOR . 'shop_list'. DIRECTORY_SEPARATOR . $skin_name;

    // 스킨 불러오기
    $skin = $CI->load->view( DIR_SKIN . DIRECTORY_SEPARATOR . 'shop_list'. DIRECTORY_SEPARATOR . $skin_name . DIRECTORY_SEPARATOR . "list.php", $data, TRUE );
    if(is_file($skin_dir.DIRECTORY_SEPARATOR."skin.css")) {
        $skin .= "<style>";
        $skin .= file_get_contents($skin_dir.DIRECTORY_SEPARATOR."skin.css");
        $skin .= "</style>";
    }
    if(is_file($skin_dir.DIRECTORY_SEPARATOR."skin.js")) {
        $skin .= "<script>";
        $skin .= file_get_contents($skin_dir.DIRECTORY_SEPARATOR."skin.js");
        $skin .= "</script>";
    }
    return $skin;
}

/**
 * 전체 게시판 불러오기
 * @param string $skin_name 스킨 이름
 * @param array $except_brd_key 제외할 게시판 키
 * @param int $rows 몇줄 불러올지
 * @param bool $get_thumb_img 썸네일 이미지 불러올지
 * @param bool $extra 추가 입력필드 불러올지
 * @param bool $file_list 첨부파일 목록 불러올지
 * @param int $cache_time 캐시 저장시간
 * @return string
 */
function latest_multi($skin_name="", $except_brd_key=array(), $rows=5, $get_thumb_img=FALSE, $cache_time=1)
{
    $CI =& get_instance();

    if(empty($skin_name)) return "<p class='alert alert-danger'>".langs('게시판/latest/not_set_skin')."</p>";

    $skin_dir = rtrim(VIEWPATH, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR . DIR_SKIN . DIRECTORY_SEPARATOR . 'latest'. DIRECTORY_SEPARATOR . $skin_name;
    $skin_file = $skin_dir . DIRECTORY_SEPARATOR . "skin.php";

    // 스킨 폴더나 스킨 파일이 존재하지 않을때
    if(! is_dir($skin_dir) OR !is_file($skin_file )) return '<p class="alert alert-danger">'.langs('게시판/latest/not_exist_skin').'</p>';

    $cache_name = "board-multiples-{$rows}-".($get_thumb_img ? 'thumb' : 'nothumb');

    $CI->load->library('boardlib');

    if( ! $data['list'] = $CI->cache->get($cache_name) ) {
        // 일반 글 목록 가져오기
        if(is_array($except_brd_key) && count($except_brd_key) > 0)
        {
            foreach( $except_brd_key as $brd_key ) {
                $CI->db->where('P.brd_key !=', $brd_key);
            }
        }

        $CI->db
            ->select("P.*, PC.bca_name, B.brd_title")
            ->where('post_status','Y')
            ->where('post_notice', 'N')
            ->order_by('post_num DESC, post_reply ASC, post_idx ASC')
            ->from('board_post AS P')
            ->join("board AS B","B.brd_key=P.brd_key","inner")
            ->limit($rows);

        $post_list['list'] = $CI->db->get()->result_array();

        foreach($post_list['list'] as &$row)
        {
            $row = $CI->boardlib->post_process($row['brd_key'], $row, "",  FALSE);
        }

        $data['list'] = $post_list['list'];

        if(! IS_TEST) {
            $CI->cache->save($cache_name, $data['list'], 60*5);
        }
    }

    // 스킨 불러오기
    $skin = $CI->load->view( DIR_SKIN . DIRECTORY_SEPARATOR . 'latest'. DIRECTORY_SEPARATOR . $skin_name . DIRECTORY_SEPARATOR . "skin.php", $data, TRUE );

    return $skin;
}


/**
 * 문의하기 스킨 함수
 * @param string $skin_name
 * @return string
 */
function contact_form($skin_name="",$complete_msg="문의 작성이 완료되었습니다.")
{
    $CI =& get_instance();

    if( ! $CI->site->config('email_send_address') )
    {
        return '<p class="alert alert-danger">관리자 이메일 주소가 설정되어 있지 않습니다.<br>[환경설정] > [사이트 기본 설정] 에서 관리자 이메일을 설정해주세요</p>';
    }

    if( ! filter_var($CI->site->config('email_send_address'), FILTER_VALIDATE_EMAIL))
    {
        return '<p class="alert alert-danger">관리자 이메일 주소가 올바른 메일 주소가 아닙니다.<br>[환경설정] > [사이트 기본 설정] 에서 관리자 이메일을 설정해주세요</p>';
    }

    // 스킨 이름을 지정하지 않았을때
    if(empty($skin_name)) {
        return '<p class="alert alert-danger">스킨 디렉토리가 지정되지 않았습니다.</p>';
    }

    $skin_dir = rtrim(VIEWPATH, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR . DIR_SKIN . DIRECTORY_SEPARATOR . 'contact'. DIRECTORY_SEPARATOR . $skin_name;
    $skin_file = $skin_dir . DIRECTORY_SEPARATOR . "skin.php";



    // 스킨 폴더나 스킨 파일이 존재하지 않을때
    if(! is_dir($skin_dir) OR !is_file($skin_file )) return '<p class="alert alert-danger">지정한 스킨이 존재하지 않습니다.</p>';

    // form 태그 세팅
    $hidden = array('reurl'=>current_url(), "complete_msg"=>$complete_msg);
    $data['form_open'] = form_open('contact', array("id"=>"form-contact", "data-form"=>"contact"), $hidden);
    $data['form_close'] = form_close();

    // 스킨 불러오기
    $skin = $CI->load->view( DIR_SKIN . DIRECTORY_SEPARATOR . 'contact'. DIRECTORY_SEPARATOR . $skin_name . DIRECTORY_SEPARATOR . "skin.php", $data, TRUE );
    if(is_file($skin_dir.DIRECTORY_SEPARATOR."skin.css")) {
        $skin .= "<style>";
        $skin .= file_get_contents($skin_dir.DIRECTORY_SEPARATOR."skin.css");
        $skin .= "</style>";
    }
    if(is_file($skin_dir.DIRECTORY_SEPARATOR."skin.js")) {
        $skin .= "<script>";
        $skin .= file_get_contents($skin_dir.DIRECTORY_SEPARATOR."skin.js");
        $skin .= "</script>";
    }
    return $skin;
}

/**
 * 아웃로그인 스킨
 * @param string $skin_name
 * @return string
 */
function outlogin($skin_name="") {
    $CI =& get_instance();

    if(empty($skin_name)) return "<p class='alert alert-danger'>".langs('회원/outlogin/not_set_skin')."</p>";

    $skin_dir = rtrim(VIEWPATH, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR . DIR_SKIN . DIRECTORY_SEPARATOR . 'outlogin'. DIRECTORY_SEPARATOR . $skin_name;
    $skin_file = ($CI->member->is_login()) ?  "skin.member.php" : "skin.guest.php";

    // 스킨 폴더나 스킨 파일이 존재하지 않을때
    if(! is_dir($skin_dir) OR !is_file($skin_dir . DIRECTORY_SEPARATOR .$skin_file )) return '<p class="alert alert-danger">'.langs('회원/outlogin/not_exist_skin').'</p>';

    $data = array();

    if( ! $CI->member->is_login() )
    {
        $form_attributes['id'] = "form-outlogin";
        $form_attributes['autocomplete'] = "off";
        $form_attributes['name'] = "form_login";
        $form_attributes['data-role'] = "form-login";
        $form_hidden_inputs['reurl'] = set_value('reurl', current_full_url());

        $action_url = base_url( 'members/login');
        $data['form_open'] = form_open($action_url, $form_attributes, $form_hidden_inputs);
        $data['form_close'] = form_close();
    }

    // 스킨 불러오기
    $skin = $CI->load->view( DIR_SKIN . DIRECTORY_SEPARATOR . 'outlogin'. DIRECTORY_SEPARATOR . $skin_name . DIRECTORY_SEPARATOR . $skin_file, $data, TRUE );

    return $skin;
}

/**
 * 일반 위젯
 * @param string $widget_name               로드할 위젯 이름
 * @param object|array|null $widget_vars    위젯에 넘겨줄 변수 데이타
 * @return string
 */
function widget($widget_name = "", $widget_vars = [])
{
    $CI =& get_instance();
    if(empty($widget_name)) return "<p class='alert alert-danger'>불러올 위젯이 정확하게 설정되지 않았습니다.</p>";

    // 위젯의 파일위치를 구해온다.
    $skin_dir = rtrim(VIEWPATH, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR . DIR_SKIN . DIRECTORY_SEPARATOR . 'widgets'. DIRECTORY_SEPARATOR . $widget_name;
    $skin_file = "widget.php";

    // 위젯파일이 없다면 오류 표시
    if(! is_dir($skin_dir) OR !is_file($skin_dir . DIRECTORY_SEPARATOR .$skin_file )) return '<p class="alert alert-danger">위젯 파일이 존재하지 않습니다.</p>';

    $return = "";
    if(is_file($skin_dir.DIRECTORY_SEPARATOR."widget.css")) {
        $return .= "<style>";
        $return .= file_get_contents($skin_dir.DIRECTORY_SEPARATOR."widget.css");
        $return .= "</style>";
    }

    $return .= $CI->load->view( DIR_SKIN . DIRECTORY_SEPARATOR . 'widgets'. DIRECTORY_SEPARATOR . $widget_name . DIRECTORY_SEPARATOR . $skin_file, $widget_vars, TRUE );

    if(is_file($skin_dir.DIRECTORY_SEPARATOR."widget.js")) {
        $return .= "<script>";
        $return .= file_get_contents($skin_dir.DIRECTORY_SEPARATOR."widget.js");
        $return .= "</script>";
    }

    return $return;
}

/**
 * 연혁 위젯
 * @param string $skin_name
 * @param string $order_year
 * @param string $order_month
 * @return string
 */
function history($skin_name = "", $order_year = "DESC", $order_month = "DESC")
{
    // 년도 순서와 월 순서 정렬방식을 대문자로 변환한다.
    $order_year = strtoupper($order_year);
    $order_month = strtoupper($order_month);

    // 순서정렬값이 ASC나 DESC가 아니면 기본값이 DESC로 처리한다.
    if($order_year !== 'ASC' && $order_year !== "DESC") $order_year = "DESC";
    if($order_month !== 'ASC' && $order_month !== "DESC") $order_month = "DESC";

    $CI =& get_instance();

    // 스킨이름이 비어있을경우 오류 표시
    if(empty($skin_name)) return "<p class='alert alert-danger'>연혁 스킨이 설정되지 않았습니다.</p>";

    // 스킨파일위치를 구해온다.
    $skin_dir = rtrim(VIEWPATH, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR . DIR_SKIN . DIRECTORY_SEPARATOR . 'history'. DIRECTORY_SEPARATOR . $skin_name;
    $skin_file = "skin.php";

    // 스킨파일이 없다면 오류 표시
    if(! is_dir($skin_dir) OR !is_file($skin_dir . DIRECTORY_SEPARATOR .$skin_file )) return '<p class="alert alert-danger">연혁 스킨파일이 존재하지 않습니다.</p>';

    // 연혁 목록 구해오기
    $list = $CI->db
        ->where('his_status','Y')
        ->order_by('his_year DESC, his_month DESC, his_idx DESC')
        ->get('history')
        ->result_array();

    $return = [];
    foreach($list as $row) {
        if(! isset($return["{$row['his_year']}"])) {
            $return["{$row['his_year']}"] = [];
        }
        if(! isset($return["{$row['his_year']}"]["{$row['his_month']}"]))
        {
            $return["{$row['his_year']}"]["{$row['his_month']}"] = [];
        }
        $return["{$row['his_year']}"]["{$row['his_month']}"][] = $row['his_content'];
    }

    $skin = $CI->load->view( DIR_SKIN . DIRECTORY_SEPARATOR . 'history'. DIRECTORY_SEPARATOR . $skin_name . DIRECTORY_SEPARATOR . $skin_file, ["list"=>$return], TRUE );
    if(is_file($skin_dir.DIRECTORY_SEPARATOR."skin.css")) {
        $skin .= "<style>";
        $skin .= file_get_contents($skin_dir.DIRECTORY_SEPARATOR."skin.css");
        $skin .= "</style>";
    }
    if(is_file($skin_dir.DIRECTORY_SEPARATOR."skin.js")) {
        $skin .= "<script>";
        $skin .= file_get_contents($skin_dir.DIRECTORY_SEPARATOR."skin.js");
        $skin .= "</script>";
    }
    return $skin;
}