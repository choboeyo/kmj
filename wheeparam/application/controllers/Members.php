<?php
/**
 * Class Members
 * -------------------------------------------
 * 회원 관련 페이지
 *
 * @property Shop_model $shop_model
 */
class Members extends WB_Controller {

    function __construct()
    {
        parent::__construct();

        $this->load->model('products_model');
    }

    /**
     * 회원가입 페이지
     */
    public function register()
    {
        if( $this->member->is_login() ) {
            alert(langs('회원/login/already'), base_url("members/info"));
            exit;
        }

        $form_attributes['id'] = "form-register";
        $form_attributes['autocomplete'] = "off";
        $form_attributes['name'] = "form_register";
        $form_attributes['data-form'] = "form-register";
        $form_hidden_inputs['reurl'] = set_value('reurl', $this->input->get("reurl", TRUE, base_url()));

        $action_url = base_url( 'members/register');
        $this->data['form_open'] = form_open($action_url, $form_attributes, $form_hidden_inputs);
        $this->data['form_close'] = form_close();

        $this->site->meta_title = langs('회원/register');
        $this->theme = $this->site->get_layout();
        $this->skin_type = "members";
        $this->skin =  $this->site->config('skin_members' . ($this->site->viewmode === DEVICE_MOBILE ? '_m' : ''));
        $this->view = "register";
    }

    /**********************************************************
     * 사용자 로그인
     **********************************************************/
    public function login()
    {
        if( $this->member->is_login() ) {
            alert(langs('회원/login/already'), base_url("members/info"));
            exit;
        }

        $form_attributes['id'] = "form-login";
        $form_attributes['autocomplete'] = "off";
        $form_attributes['name'] = "form_login";
        $form_attributes['data-role'] = "form-login";
        $form_hidden_inputs['reurl'] = set_value('reurl', $this->input->get("reurl", TRUE, base_url()));

        $action_url = base_url( 'members/login');
        $this->data['form_open'] = form_open($action_url, $form_attributes, $form_hidden_inputs);
        $this->data['form_close'] = form_close();

        $this->site->meta_title = langs('회원/signin');
        $this->theme = $this->site->get_layout();
        $this->skin_type = "members";
        $this->skin =  $this->site->config('skin_members' . ($this->site->viewmode === DEVICE_MOBILE ? '_m' : ''));        $this->view = "login";
    }

    /**********************************************************
     * 사용자 로그아웃
     **********************************************************/
    public function logout()
    {
        $reurl = $this->input->get("reurl", TRUE, base_url());

        if( get_cookie(COOKIE_AUTOLOGIN) )
        {
            $this->member->remove_autologin($this->member->is_login());
        }
        $this->session->sess_destroy();
        redirect( $reurl );
        exit;
    }

    /**********************************************************
     * 사용자 정보 페이지
     **********************************************************/
    public function info($page="")
    {
        if(! $this->member->is_login())
        {
            alert_login(langs('회원/login/only'));
            exit;
        }

        $this->data['mem'] = $this->member->info();

        if( $page == 'social' )
        {
            $this->info_social();
        }
        else {
            $this->site->meta_title = langs('회원/info/profile');
            $this->theme = $this->site->get_layout();
            $this->active = "members/info";
            $this->asides['member'] = "aside";
            $this->skin_type = "members";
            $this->skin =  $this->site->config('skin_members' . ($this->site->viewmode === DEVICE_MOBILE ? '_m' : ''));            $this->view = "info";
        }
    }

    /**********************************************************
     * 회원포토 변경 페이지
     **********************************************************/
    public function photo_change()
    {
        if(! $this->member->is_login())
        {
            alert_close(langs('회원/login/only'));
            exit;
        }

        $this->load->library('form_validation');

        $this->form_validation->set_rules("mem_userid", "mem_userid", "required");

        if( $this->form_validation->run() != FALSE )
        {
            if(! isset($_FILES) OR ! isset($_FILES['userfile']) OR ! $_FILES['userfile'] OR !isset($_FILES['userfile']['name']) OR ! $_FILES['userfile']['name'] )
            {
                alert(langs('회원/msg/change_photo_required'));
                exit;
            }

            // 폴더 생성
            make_dir(DIR_UPLOAD . DIRECTORY_SEPARATOR . "member_photo");
            $upload_path =  DIR_UPLOAD . '/member_photo/' . date('Y') . '/' . date('m') . '/';

            $uploadconfig = array(
                'upload_path' => "./" . $upload_path,
                'allowed_types' => 'jpg|jpeg|png|gif',
                'max_size' => 10 * 1024,
                'encrypt_name' => true,
            );
            $this->load->library('upload');
            $this->upload->initialize($uploadconfig);

            if ($this->upload->do_upload()) {

                $filedata = $this->upload->data();

                // 기존 회원아이콘이 있다면 삭제
                if( $this->member->info('photo') )
                {
                    if( is_file( FCPATH . $this->member->info('photo') ))
                    {
                        @unlink( FCPATH . $this->member->info('photo') );
                    }
                }

                $this->db->where('mem_idx', $this->member->is_login() )->set('mem_photo', $upload_path . $filedata['file_name'])->update('member');

                alert_close(langs('회원/msg/change_photo_success'), TRUE);
                exit;
            }
            else
            {
                alert($this->upload->display_errors(' ', ' '));
            }

        }
        else
        {
            $this->site->meta_title = langs('회원/info/change_photo');
            $this->theme = $this->site->get_layout();
            $this->theme_file = "popup";

            $this->skin_type = "members";
            $this->skin =  $this->site->config('skin_members' . ($this->site->viewmode === DEVICE_MOBILE ? '_m' : ''));
            $this->view = "photo_change";
        }

    }

    /**********************************************************
     * 회원정보 수정
     **********************************************************/
    public function modify()
    {
        if(! $this->member->is_login())
        {
            alert(langs('회원/login/only'));
            exit;
        }

        $form_attributes['id'] = "form-member-modify";
        $form_attributes['autocomplete'] = "off";
        $form_attributes['name'] = "form_member_modify";
        $form_attributes['data-form'] = "form-member-modify";
        $form_hidden_inputs['reurl'] = set_value('reurl', $this->input->get("reurl", TRUE, base_url()));

        $action_url = base_url( 'members/modify');
        $this->data['form_open'] = form_open($action_url, $form_attributes, $form_hidden_inputs);
        $this->data['form_close'] = form_close();

        $this->site->meta_title = langs('회원/info/modify');
        $this->theme = $this->site->get_layout();
        $this->asides['member'] = "aside";
        $this->active = "members/modify";

        $this->skin_type = "members";
        $this->skin =  $this->site->config('skin_members' . ($this->site->viewmode === DEVICE_MOBILE ? '_m' : ''));
        $this->view = "modify";
    }

    /**********************************************************
     * 회원 비밀번호 변경
     **********************************************************/
    public function password_change()
    {
        if(! $this->member->is_login())
        {
            alert(langs('회원/login/only'));
            exit;
        }

        $this->load->library('form_validation');

        $this->form_validation->set_rules("old_password", langs('회원/info/old_password'), "required|trim|min_length[6]|max_length[20]");
        $this->form_validation->set_rules("new_password", langs('회원/info/new_password'), "required|trim|min_length[6]|max_length[20]|differs[old_password]");
        $this->form_validation->set_rules("new_password_confirm", langs('회원/info/new_password_confirm'), "required|trim|min_length[6]|max_length[20]|matches[new_password]");

        if( $this->form_validation->run() != FALSE )
        {
            $current_password = $this->input->post('old_password', TRUE);
            $new_password = $this->input->post('new_password', TRUE);

            if( $this->member->info('password') != get_password_hash($current_password) )
            {
                alert(langs('회원/login/user_not_exist'));
                exit;
            }

            $this->db->set('mem_password', get_password_hash($new_password) );
            $this->db->where('mem_idx', $this->member->is_login() );
            $this->db->update('member');

            alert(langs('회원/msg/password_change_success'), base_url('members/logout') );
            exit;
        }
        else {
            $action_url = base_url('members/password_change');
            $this->data['form_open'] = form_open($action_url, array('data-form'=>'form-password-change'));
            $this->data['form_close'] = form_close();

            $this->site->meta_title = langs('회원/info/password_change');
            $this->theme = $this->site->get_layout();
            $this->active = "members/password_change";
            $this->asides['member'] = "aside";

            $this->skin_type = "members";
            $this->skin =  $this->site->config('skin_members' . ($this->site->viewmode === DEVICE_MOBILE ? '_m' : ''));
            $this->view = "password_change";
        }
    }

    /**********************************************************
     * 회원 탈퇴
     **********************************************************/
    public function withdrawals()
    {
        if(! $this->member->is_login())
        {
            alert(langs('회원/login/only'));
            exit;
        }

        $this->load->library('form_validation');

        $this->form_validation->set_rules("current_password", langs('회원/login/user_not_exist'), "required|trim|min_length[6]|max_length[20]");

        if( $this->form_validation->run() != FALSE )
        {
            $current_password = $this->input->post('current_password', TRUE);

            if( $this->member->info('password') != get_password_hash($current_password) )
            {
                alert(langs('회원/login/user_not_exist'));
                exit;
            }

            // 회원정보에서 삭제
            $this->db->where('mem_idx', $this->member->is_login() );
            $this->db->set('mem_status', 'N');
            $this->db->update('member');

            alert(langs('회원/msg/withdrawals_success'), base_url('members/logout') );
            exit;
        }
        else {
            $action_url = base_url('members/withdrawals');
            $this->data['form_open'] = form_open($action_url, array('data-form'=>'form-withdrawals'));
            $this->data['form_close'] = form_close();

            $this->site->meta_title = langs('회원/info/withdrawals');
            $this->theme = $this->site->get_layout();
            $this->active = "members/withdrawals";
            $this->asides['member'] = "aside";

            $this->skin_type = "members";
            $this->skin =  $this->site->config('skin_members' . ($this->site->viewmode === DEVICE_MOBILE ? '_m' : ''));
            $this->view = "withdrawals";
        }
    }

    /**********************************************************
     * 소셜 정보
     **********************************************************/
    public function info_social()
    {
        if(! $this->member->is_login())
        {
            alert_login(langs('회원/login/only'));
            exit;
        }

        $this->site->meta_title = langs('회원/info/social');
        $this->theme = $this->site->get_layout();
        $this->asides['member'] = "aside";
        $this->active = "members/info/social";

        $this->skin_type = "members";
        $this->skin =  $this->site->config('skin_members' . ($this->site->viewmode === DEVICE_MOBILE ? '_m' : ''));
        $this->view = "info_social";
    }

    /**********************************************************
     * 소셜 로그인 처리
     **********************************************************/
    public function social_login($provider)
    {
        if(empty($provider))
        {
            alert(langs('공통/msg/invalid_access'));
            exit;
        }

        if(!check_social_setting($provider))
        {
            alert(strtoupper($provider) . " " . langs('회원/social/not_set') );
            exit;
        }

        $this->load->library("social_login_". $provider);
        $result = $this->{"social_login_".$provider}->get_profile();

        if(empty($result))
        {
            alert( langs('회원/social/failed'), base_url());
        }

        // 만약 현재 로그인 중인상태라면
        // 현재 아이디에 소셜로그인을 추가하는것으로 간주한다.
        if( $this->member->is_login() )
        {
            // 이미 등록된 소셜이 있을경우
            if( $social = $this->member->get_social($result['provider'], $result['id']) )
            {
                // 현재 로그인과 이미 연결되어 있는경우
                if( $this->member->is_login() == $social['mem_idx'] )
                {
                    alert(langs('회원/social/already_linked'), base_url('members/info/social'));
                    exit;
                }
                // 아예 다른아이디와 연결되어 있는 경우
                else
                {
                    alert(langs('회원/social/already_another'), base_url('members/info/social'));
                    exit;
                }
            }
            // 등록된 소셜이 없는경우
            else
            {
                // 현재 로그인과 연결설정
                $data['soc_provider']   = $result['provider'];
                $data['soc_id']         = $result['id'];
                $data['mem_idx']        = $this->member->is_login();
                $data['soc_profile']    = $result['profile'];
                $data['soc_gender']     = $result['gender'];
                $data['soc_email']      = $result['email'];
                $data['soc_content']    = $result['extra'];
                $data['soc_regtime']    = date('Y-m-d H:i:s');

                $this->db->insert('member_social', $data);

                alert(langs('회원/social/success_link'), base_url('members/info/social'));
                exit;
            }
        }
        // 현재 로그인중이 아니라면?
        // 새로운 아이디 생성 / 로그인 요청 으로 받아들인다.
        else
        {
            // 이미 등록된 소셜 계정이 있다면?
            if( $social = $this->member->get_social($result['provider'], $result['id']) )
            {
                $member = $this->member->get_member($social['mem_idx'], 'mem_idx');

                $this->member->login_process($member);
                redirect(base_url());
            }
            else
            {
                // 이미 등록된 이메일 주소라면
                if( $tmp = $this->member->get_member($result['email'], "mem_email"))
                {
                    alert(langs('회원/social/already_email'), base_url());
                    exit;
                }
                unset($tmp);

                $mem_userid = USE_EMAIL_ID ? $result['email'] : strtoupper(substr($result['provider'],0,1)).$result['id'];
                // 해당 아이디가 이미 존재하는지 확인한다.
                if( $tmp = $this->member->get_member($mem_userid, 'mem_userid') )
                {
                    alert(langs('회원/social/already'), base_url());
                    exit;
                }
                unset($tmp);

                $data['mode']           = "INSERT";
                $data['mem_userid']     = $mem_userid;
                $data['mem_password']   = $result['id'];
                $data['mem_nickname']   = $result['name'];
                $data['mem_email']      = $result['email'];
                $data['mem_verfy_email'] = 'Y';
                $data['mem_phone']      = "";
                $data['mem_auth']       = 1;
                $data['mem_gender']     = $result['gender'];
                $data['mem_recv_email'] = 'N';
                $data['mem_recv_sms']   = 'N';

                $this->member->info_process($data);
                unset($data);

                $member = $this->member->get_member($mem_userid, "mem_userid");

                $data['soc_provider']   = $result['provider'];
                $data['soc_id']         = $result['id'];
                $data['mem_idx']        = $member['mem_idx'];
                $data['soc_profile']    = $result['profile'];
                $data['soc_gender']     = $result['gender'];
                $data['soc_email']      = $result['email'];
                $data['soc_content']    = $result['extra'];
                $data['soc_regtime']    = date('Y-m-d H:i:s');

                $this->db->insert('member_social', $data);

                $this->member->login_process($member);
                redirect(base_url());
                exit;
            }
        }
    }

    /**********************************************************
     * 휴면 계정 전환
     **********************************************************/
    public function activation()
    {
        $this->load->library('form_validation');

        if( ! $this->member->is_login() )
        {
            alert(langs('공통/msg/invalid_access'));
            exit;
        }

        if( ! $member = $this->member->get_member( $this->member->is_login(), 'mem_idx' ) )
        {
            alert(langs('회원/login/user_not_exist'));
            exit;
        }

        if( $member['mem_status'] != 'H' )
        {
            alert(langs('회원/status/not_dormant'));
            exit;
        }

        $this->form_validation->set_rules('activation','activation','required|trim');

        if( $this->form_validation->run() != FALSE )
        {
            $this->db->where('mem_idx', $this->member->is_login())->set('mem_status', 'Y')->update('member');
            alert(langs('회원/status/activate_complete'),base_url());
            exit;
        }
        else {
            $this->site->meta_title = langs('회원/info/activation');
            $this->theme = $this->site->get_layout();
            $this->skin_type = "members";
            $this->skin =  $this->site->config('skin_members' . ($this->site->viewmode === DEVICE_MOBILE ? '_m' : ''));
            $this->view = "activation";
        }
    }

    /**
     * 내 주문내역
     */
    public function my_order($od_id= "")
    {
        $mem_idx = $this->member->is_login();

        if(empty($od_id)) {
            $this->data['list'] = $this->db
                ->from('shop_order')
                ->where('mem_idx', $mem_idx)
                ->where('od_status <>','')
                ->order_by('od_id DESC')
                ->get()
                ->result_array();

            $this->site->meta_title = '내 주문내역';
            $this->view = "my_order";
        }
        else {
            $this->data['order']=$this->db
                ->from('shop_order')
                ->where('od_id', $od_id)
                ->get()
                ->row_array();

            $this->load->model('shop_model');
            $_temp = $this->shop_model->getCartListByOrder($od_id);

            $this->data['list'] = $_temp['list'];

            $this->site->meta_title = '주문 상세내역';
            $this->view = "my_order_view";
        }

        $this->active = "members/my-order";
        $this->asides['member'] = "aside";
        $this->theme = $this->site->get_layout();
        $this->skin_type = "members";
        $this->skin =  $this->site->config('skin_members' . ($this->site->viewmode === DEVICE_MOBILE ? '_m' : ''));
    }

    /**
     * 내가 작성한 리뷰
     */
    public function my_reviews()
    {
        if(! $mem_idx = $this->member->is_login())
        {
            alert_login();
        }


        if(! USE_SHOP) {
            alert('쇼핑몰 사용설정이 되어있지 않습니다.');
            exit;
        }

        $this->load->model('shop_model');
        $this->data['page'] = $this->input->get('page', TRUE, 1);
        $this->data['page_rows'] = 5;
        $this->data['mem_idx'] = $mem_idx;
        $this->data['load_images'] = TRUE;

        $result = $this->shop_model->getProductReviews($this->data);
        $this->data['list'] = $result['list'];
        $this->data['totalCount'] = $result['totalCount'];

        foreach($this->data['list'] as &$row)
        {
            if($row['reg_datetime'] != $row['upd_datetime']) {
                $row['rev_content'] .= PHP_EOL.PHP_EOL."<small class='modified'>({$row['upd_datetime']}) 수정됨</small>";
            }
        }

        // 페이지네이션 세팅
        $paging['page'] = $this->data['page'];
        $paging['page_rows'] = $this->data['page_rows'];
        $paging['total_rows'] = $this->data['totalCount'];

        $this->load->library('paging', $paging);
        $this->data['pagination'] = $this->paging->create();

        $this->active = "members/my-reviews";
        $this->asides['member'] = "aside";


        $this->site->meta_title = '내가 작성한 리뷰';
        $this->theme = $this->site->get_layout();
        $this->skin_type = "members";
        $this->skin =  $this->site->config('skin_members' . ($this->site->viewmode === DEVICE_MOBILE ? '_m' : ''));
        $this->view = "my_reviews";
    }

    /**
     * 내가 작성한 리뷰
     */
    public function my_qna()
    {
        if(! $mem_idx = $this->member->is_login())
        {
            alert_login();
        }


        if(! USE_SHOP) {
            alert('쇼핑몰 사용설정이 되어있지 않습니다.');
            exit;
        }

        $this->load->model('shop_model');
        $this->data['page'] = $this->input->get('page', TRUE, 1);
        $this->data['page_rows'] = 5;
        $this->data['mem_idx'] = $mem_idx;
        $this->data['load_images'] = TRUE;

        $result = $this->shop_model->getProductQna($this->data);
        $this->data['list'] = $result['list'];
        $this->data['totalCount'] = $result['totalCount'];

        // 페이지네이션 세팅
        $paging['page'] = $this->data['page'];
        $paging['page_rows'] = $this->data['page_rows'];
        $paging['total_rows'] = $this->data['totalCount'];

        $this->load->library('paging', $paging);
        $this->data['pagination'] = $this->paging->create();

        $this->active = "members/my-qna";
        $this->asides['member'] = "aside";


        $this->site->meta_title = '내가 작성한 리뷰';
        $this->theme = $this->site->get_layout();
        $this->skin_type = "members";
        $this->skin =  $this->site->config('skin_members' . ($this->site->viewmode === DEVICE_MOBILE ? '_m' : ''));
        $this->view = "my_qna";
    }
    
    /**
     * 찜 보관함
     */
    public function my_wishlist()
    {
        if(! $mem_idx = $this->member->is_login())
        {
            alert_login();
        }

        if(! USE_SHOP) {
            alert('쇼핑몰 사용설정이 되어있지 않습니다.');
            exit;
        }

        // 내 찜 목록 가져오기
        $this->data['list'] = $this->db
            ->select('P.*, PA.att_filepath')
            ->from('products_wish AS PW')
            ->join('products AS P','P.prd_idx=PW.prd_idx')
            ->join('attach AS PA', 'P.prd_thumbnail=PA.att_idx','left')
            ->where('PW.mem_idx', $mem_idx)
            ->get()
            ->result_array();

        foreach($this->data['list'] as &$row)
        {
            $row['thumbnail'] = '';
            if(!empty($row['att_filepath']) && file_exists(FCPATH . $row['att_filepath'])) {
                $row['thumbnail'] = $row['att_filepath'];
            }
            $row['link'] = base_url('products/items/'.$row['prd_idx']);

        }

        $this->active = "members/my-wishlist";
        $this->asides['member'] = "aside";


        $this->site->meta_title = '내 주문내역';
        $this->theme = $this->site->get_layout();
        $this->skin_type = "members";
        $this->skin =  $this->site->config('skin_members' . ($this->site->viewmode === DEVICE_MOBILE ? '_m' : ''));
        $this->view = "my_wishlist";
    }
}