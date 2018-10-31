<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Members extends WB_Controller {

    public function login(){
        if( $this->member->is_login() ) {
            alert(langs('회원/login/already'), base_url("members/info"));
            exit;
        }

        $form_attributes['id'] = "form-login";
        $form_attributes['autocomplete'] = "off";
        $form_attributes['name'] = "form_login";
        $form_attributes['data-role'] = "form-login";
        $form_hidden_inputs['reurl'] = set_value('reurl', $this->input->get("reurl", TRUE, base_url()));

        $action_url = base_url( 'admin/members/login', SSL_VERFIY ? 'https' : 'http' );
        $this->data['form_open'] = form_open($action_url, $form_attributes, $form_hidden_inputs);
        $this->data['form_close'] = form_close();

        $this->site->meta_title = "관리자 로그인";
        $this->theme = "admin";
        $this->theme_file = "iframe";
        $this->view = "members/login";
    }

    /*****************************************************************
     * 회원 목록
     *****************************************************************/
    public function lists()
    {
        $this->load->model('member_model');

        $this->data['sdate'] = $this->input->get('sdate', TRUE);
        $this->data['startdate'] = $this->input->get('startdate', TRUE);
        $this->data['enddate'] = $this->input->get('enddate', TRUE);

        if( $this->data['sdate'] && $this->data['startdate'] ) $param['where']['mem_' . $this->data['sdate'] . ' >=' ] = $this->data['startdate'] . " 00:00:00";
        if( $this->data['sdate'] && $this->data['enddate'] ) $param['where']['mem_' . $this->data['sdate'] . ' <=' ] = $this->data['enddate'] . " 23:59:59";


        // 정보 넣기
        $param['page'] = $this->input->get('page', TRUE, 1);
        $param['page_rows'] = 20;
        $param['limit'] = TRUE;

        // 회원목록 가져오기
        $this->data['member_list'] = $this->member_model->member_list($param);

        // 페이지네이션 세팅
        $this->load->library('paging');
        $this->paging->initialize(array(
            "page" => $param['page'],
            "page_rows" => $param['page_rows'],
            "total_rows" => $this->data['member_list']['total_count'],
            "fixe_nums" => 10,
            'full_tag_open' => '<ul class="pagination pagination-sm">'
        ));
        $this->data['pagination'] = $this->paging->create();

        // 메타태그 설정
        $this->site->meta_title = "회원 목록";            // 이 페이지의 타이틀

        // 레이아웃 & 뷰파일 설정
        $this->theme    = "admin";
        $this->view     = "members/lists";
        $this->active   = "members/lists";
    }

    /**
     * @param $mem_idx
     */
    public function info($mem_idx)
    {
        if(empty($mem_idx))
        {
            alert_modal_close('잘못된 접근입니다.');
            exit;
        }

        $this->data['mem'] = $this->member->get_member($mem_idx,'mem_idx');

        $this->theme    = "admin";
        $this->theme_file = "iframe";
        $this->view     = "members/info";
    }

    /**
     * 회원 포인트 관리
     * @param $mem_idx
     */
    public function point($mem_idx)
    {
        if(empty($mem_idx))
        {
            alert_modal_close('잘못된 접근입니다.');
            exit;
        }

        $this->load->model('member_model');

        $this->data['startdate'] = $param['startdate'] = $this->input->get('startdate', TRUE);
        $this->data['enddate'] = $param['enddate'] = $this->input->get('enddate', TRUE);
        $this->data['target_type'] = $this->input->get('target_type', TRUE);
        if( $this->data['target_type'] )
        {
            $param['where']['target_type'] = $this->data['target_type'];
        }

        // 정보 넣기
        $param['page'] = $this->input->get('page', TRUE, 1);
        $param['page_rows'] = 10;

        // 회원목록 가져오기
        $this->data['point_list'] = $this->member_model->point_list($mem_idx, $param);

        // 페이지네이션 세팅
        $this->load->library('paging');
        $this->paging->initialize(array(
            "page" => $param['page'],
            "page_rows" => $param['page_rows'],
            "total_rows" => $this->data['point_list']['total_count'],
            "fixe_nums" => 10,
            'full_tag_open' => '<ul class="pagination pagination-sm">'
        ));
        $this->data['pagination'] = $this->paging->create();

        // 회원 정보
        $this->data['mem'] = $this->member->get_member($mem_idx,'mem_idx');
        // 포인트 유형
        $this->data['point_type'] = point_type(TRUE);

        $this->theme    = "admin";
        $this->theme_file = "iframe";
        $this->view     = "members/point";
    }

    /**
     * 회원 포인트 추가 폼
     * @param $mem_idx
     */
    public function point_form($mem_idx)
    {

        if(empty($mem_idx))
        {
            alert_modal_close('잘못된 접근입니다.');
            exit;
        }

        $this->data['mem'] = $this->member->get_member($mem_idx,'mem_idx');

        $this->load->library('form_validation');

        $this->form_validation->set_rules('mem_idx', '회원번호', 'required|trim');
        $this->form_validation->set_rules('mpo_value', $this->site->config('point_name'), 'required|trim|numeric');
        $this->form_validation->set_rules('mpo_description', $this->site->config('point_name').' 내용', 'required|trim');

        if( $this->form_validation->run() != FALSE )
        {
            $data['mem_idx'] = $this->input->post('mem_idx', TRUE);
            $data['mpo_value'] = $this->input->post('mpo_value', TRUE);
            $data['mpo_description'] = $this->input->post('mpo_description', TRUE);
            $data['target_type'] = $this->input->post('target_type', TRUE);
            $data['mpo_regtime'] = date('Y-m-d H:i:s');

            if( $this->member->add_point($data['mem_idx'], $data['mpo_value'], FALSE, $data['target_type'], $data['mpo_description'],0))
            {
                alert_modal2_close('등록완료');
                exit;
            }
            else {
                alert('DB 입력도중 오류가 발생하였습니다.');
                exit;
            }
        }
        else
        {
            $this->data['mem_idx'] = $mem_idx;

            $this->theme    = "admin";
            $this->theme_file = "iframe";
            $this->view     = "members/point_form";
        }
    }


    /**
     * 포인트 관리
     */
    function points()
    {
        $this->load->model('basic_model');

        $param['page'] = $this->input->get('page', TRUE, 1);
        $param['page_rows'] = 15;
        $param['limit'] = TRUE;
        $param['join'][] = array('member', 'member.mem_idx=member_point.mem_idx','inner');
        $param['from'] = 'member_point';
        $param['order_by'] = 'mpo_idx DESC';

        $this->data['list'] = $this->basic_model->get_list($param);

        // 페이지네이션 세팅
        $this->load->library('paging');
        $this->paging->initialize(array(
            "page" => $param['page'],
            "page_rows" => $param['page_rows'],
            "total_rows" => $this->data['list']['total_count'],
            "fixe_nums" => 10,
            'full_tag_open' => '<ul class="pagination pagination-sm">'
        ));
        $this->data['pagination'] = $this->paging->create();

        // 메타태그 설정
        $this->site->meta_title = $this->site->config('point_name'). " 관리";

        // 레이아웃 & 뷰파일 설정
        $this->theme    = "admin";
        $this->view     = "members/points";
        $this->active   = "members/points";
    }

    /*****************************************************************
     * 회원 추가
     ****************************************************************/
    public function add()
    {
        $this->load->model('member_model');
        $this->load->library('form_validation');

        $this->form_validation->set_rules('mem_userid', "아이디", "required|trim|min_length[6]" . (USE_EMAIL_ID ? '|valid_email' :'') . '|callback_userid_check' );
        $this->form_validation->set_rules('mem_password', '비밀번호', 'required|trim|min_length[6]');
        $this->form_validation->set_rules('mem_password2', "비밀번호 확인", "required|trim|matches[mem_password]");
        $this->form_validation->set_rules('mem_nickname', "닉네임", "required|trim|callback_nickname_check");
        $this->form_validation->set_rules('mem_email', '이메일', 'required|trim|valid_email');

        if( $this->form_validation->run() != FALSE)
        {
            $data['mode']           = "INSERT";
            $data['mem_userid']     = $this->input->post('mem_userid', TRUE);
            $data['mem_password']   = $this->input->post('mem_password', TRUE);
            $data['mem_nickname']   = $this->input->post('mem_nickname', TRUE);
            $data['mem_email']      = $this->input->post('mem_email', TRUE);
            $data['mem_verfy_email'] = ( USE_EMAIL_VERFY ) ?  ( $this->input->post('mem_verfy_email', TRUE) == 'Y' ? 'Y' : 'N' ) : 'Y';
            $data['mem_phone']      = $this->input->post('mem_phone', TRUE);
            $data['mem_auth']       = $this->input->post('mem_auth', TRUE);
            $data['mem_gender']     = $this->input->post('mem_gender', TRUE);
            $data['mem_recv_email'] = $this->input->post('mem_recv_email', TRUE) == 'Y' ? 'Y' : 'N';
            $data['mem_recv_sms']   = $this->input->post('mem_recv_sms', TRUE) == 'Y' ? 'Y' : 'N';

            $data['mem_password'] = get_password_hash($data['mem_password']);

            if( $this->member->info_process($data) )
            {
                alert('사용자 등록이 완료되었습니다.', base_url('admin/members/lists'));
                exit;
            }
            else {
                alert('등록도중 오류가 발생하였습니다.');
                exit;
            }
        }
        else
        {
            // 메타태그 설정
            $this->site->meta_title = "신규 회원 등록";            // 이 페이지의 타이틀

            // 레이아웃 & 뷰파일 설정
            $this->theme    = "admin";
            $this->view     = "members/add";
            $this->active   = "members/add";
        }
    }

    /*****************************************************************
     * 폼검증 : 회원 아이디 체크
     ****************************************************************/
    public function userid_check($str)
    {
        $deny_id = explode(',', $this->site->config('deny_id'));
        if( in_array($str, $deny_id) )
        {
            $this->form_validation->set_message('userid_check', "{field}에 사용할 수 없는 단어입니다 : {$str}");
            return FALSE;
        }
        if( $member = $this->member->get_member($str, 'mem_userid') )
        {
            $this->form_validation->set_message('userid_check', "이미 사용중인 {field}입니다 : {$str}");
            return FALSE;
        }

        return true;
    }

    /*****************************************************************
     * 폼검증 : 회원 닉네임 체크
     ****************************************************************/
    public function nickname_check_pre($str)
    {
        $deny_nickname = explode(',',$this->site->config('deny_nickname'));
        $deny_word = explode(',', $this->site->config('deny_word'));

        $deny = array();
        foreach($deny_nickname as $d) $deny[] = trim($d);
        foreach($deny_word as $d) $deny[] = trim($d);

        if ( in_array($str, $deny) )
        {
            $this->form_validation->set_message('nickname_check_pre', "{field}에 사용할 수 없는 단어입니다 : {$str}");
            return FALSE;
        }

        return TRUE;
    }

    /*****************************************************************
     * 폼검증 : 회원 닉네임 체크 + 사용여부 체크
     ****************************************************************/
    public function nickname_check($str)
    {
        if(! $this->nickname_check_pre($str) )
        {
            return FALSE;
        }

        if( $member = $this->member->get_member($str, 'mem_nickname') )
        {
            $this->form_validation->set_message('nickname_check', "이미 사용중인 {field} 입니다 : {$str}");
            return FALSE;
        }

        return TRUE;
    }

    /**
     * 사용자 로그인 로그
     */
    public function log()
    {
        // 모델 가져오기
        $this->load->model('member_model');

        // 넘어온 검색값 정리
        $this->data['startdate'] = $this->input->get('startdate', TRUE, date('Y-m-d', strtotime("-1 month", time())));
        $this->data['enddate'] = $this->input->get('enddate', TRUE, date('Y-m-d'));
        $this->data['st']   = $this->input->get('st', TRUE);
        $this->data['sc']   = $this->input->get('sc', TRUE);

        if ( $this->data['st'] && $this->data['sc'] )
        {
            if( $this->data['sc'] ==  'nickname' OR $this->data['sc'] ==  'userid')
            {
                $param['sc'] = "member_log.mem_" . $this->data['sc'];
                $param['st'] = $this->data['st'];
            }
            else if ( $this->data['sc'] == 'idx' )
            {
                $param['where']['member_log.mem_idx'] = $this->data['st'];
            }
        }
        $param['where']['mlg_regtime >='] = $this->data['startdate'] . " 00:00:00";
        $param['where']['mlg_regtime <='] = $this->data['enddate'] . " 23:59:59";

        // 값 가져오기
        $param['page'] = $this->input->get('page', TRUE, 1);
        $param['page_rows'] = 20;
        $this->data['log_list'] = $this->member_model->log_list($param);

        // 페이지네이션 세팅
        $this->load->library('paging');
        $this->paging->initialize(array(
            "page" => $param['page'],
            "page_rows" => $param['page_rows'],
            "total_rows" => $this->data['log_list']['total_count'],
            "fixe_nums" => 10,
            'full_tag_open' => '<ul class="pagination pagination-sm">'
        ));
        $this->data['pagination'] = $this->paging->create();

        // 메타태그 설정
        $this->site->meta_title = "회원 로그인 기록";            // 이 페이지의 타이틀

        // 레이아웃 & 뷰파일 설정
        $this->theme    = "admin";
        $this->view     = "members/log";
        $this->active   = "members/log";
    }

    /**
     * 사용자 비밀번호 변경
     */
    public function password($mem_idx)
    {

        if(empty($mem_idx))
        {
            alert_modal_close('잘못된 접근입니다.');
            exit;
        }

        $this->data['mem'] = $this->member->get_member($mem_idx,'mem_idx');

        $this->load->library('form_validation');

        $this->form_validation->set_rules('mem_password', '새 비밀번호', 'required|trim|min_length[6]');
        $this->form_validation->set_rules('mem_password2', "새 비밀번호 확인", "required|trim|matches[mem_password]");

        if( $this->form_validation->run() != FALSE)
        {
            $data['mem_password']   = $this->input->post('mem_password', TRUE);
            $data['mem_password'] = get_password_hash($data['mem_password']);

            if( $this->db->where('mem_idx', $mem_idx)->set('mem_password', $data['mem_password'])->update('member') )
            {
                alert_modal_close('사용자의 비밀번호가 변경되었습니다.');
                exit;
            }
            else {
                alert('비밀번호 변경도중 오류가 발생하였습니다.');
                exit;
            }
        }
        else
        {
            // 레이아웃 & 뷰파일 설정
            $this->theme    = "admin";
            $this->view     = "members/password";
            $this->theme_file   = "iframe";
        }
    }

    /**
     * 사용자 정보수정
     */
    public function modify($mem_idx)
    {
        if(empty($mem_idx))
        {
            alert_modal_close('잘못된 접근입니다.');
            exit;
        }

        if(! $this->data['mem'] = $this->member->get_member($mem_idx,'mem_idx'))
        {
            alert_modal_close('존재하지 않는 회원입니다.');
            exit;
        }

        $this->load->model('member_model');
        $this->load->library('form_validation');

        $this->form_validation->set_rules('mem_nickname', "닉네임", "required|trim|callback_nickname_check_pre");
        $this->form_validation->set_rules('mem_email', '이메일', 'required|trim|valid_email');

        if( $this->form_validation->run() != FALSE)
        {
            $data['mode']           = "MODIFY";
            $data['mem_idx']        = $mem_idx;
            $data['mem_nickname']   = $this->input->post('mem_nickname', TRUE);
            $data['mem_email']      = $this->input->post('mem_email', TRUE);
            $data['mem_verfy_email'] = ( USE_EMAIL_VERFY ) ?  ( $this->input->post('mem_verfy_email', TRUE) == 'Y' ? 'Y' : 'N' ) : 'Y';
            $data['mem_phone']      = $this->input->post('mem_phone', TRUE);
            $data['mem_auth']       = $this->input->post('mem_auth', TRUE);
            $data['mem_gender']     = $this->input->post('mem_gender', TRUE);
            $data['mem_recv_email'] = $this->input->post('mem_recv_email', TRUE) == 'Y' ? 'Y' : 'N';
            $data['mem_recv_sms']   = $this->input->post('mem_recv_sms', TRUE) == 'Y' ? 'Y' : 'N';

            if( $this->member->info_process($data) )
            {
                alert_modal_close('사용자 정보수정이 완료되었습니다.');
                exit;
            }
            else {
                alert('등록도중 오류가 발생하였습니다.');
                exit;
            }
        }
        else
        {
            // 메타태그 설정
            $this->site->meta_title = "신규 회원 등록";            // 이 페이지의 타이틀

            // 레이아웃 & 뷰파일 설정
            $this->theme    = "admin";
            $this->view     = "members/modify";
            $this->theme_file = "iframe";
        }
    }
}
