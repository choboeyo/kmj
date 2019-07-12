<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Management extends WB_Controller {

    public function __construct()
    {
        parent::__construct();

        $this->theme = "admin";
    }

    /**
     * 메뉴 관리
     */
    public function menu()
    {
        // 게시판 리스트 가져오기
        $this->data['menu_list'] = $this->db->where('mnu_parent','0')->order_by('mnu_order ASC')->get('menu')->result_array();

        // 2차메뉴 가져오기
        foreach($this->data['menu_list'] as &$row)
        {
            $row['children']= $this->db->where('mnu_parent',$row['mnu_idx'])->order_by('mnu_order ASC')->get('menu')->result_array();

            foreach( $row['children'] as &$rw )
            {
                $rw['children']= $this->db->where('mnu_parent',$rw['mnu_idx'])->order_by('mnu_order ASC')->get('menu')->result_array();
            }
        }

        // 레이아웃 & 뷰파일 설정
        $this->theme    = "admin";
        $this->view     = "management/menu";
        $this->active   = "management/menu";
    }

    /**
     * 메뉴 등록/수정 폼
     */
    public function menu_form()
    {
        $this->load->library('form_validation');

        $this->form_validation->set_rules("mnu_name", "메뉴 이름", "required|trim|max_length[30]");
        $this->form_validation->set_rules("mnu_link", "메뉴 링크", "required|trim");

        if( $this->form_validation->run() != FALSE )
        {
            $data['mnu_idx'] = $this->input->post('mnu_idx', TRUE);
            $data['mnu_parent'] = $this->input->post('mnu_parent', TRUE);
            $data['mnu_name'] = $this->input->post('mnu_name', TRUE);
            $data['mnu_parent'] = $this->input->post('mnu_parent', TRUE);
            $data['mnu_link'] = str_replace(base_url(), '/', $this->input->post('mnu_link', TRUE));
            $data['mnu_newtab'] = $this->input->post('mnu_newtab', TRUE) == 'Y' ? 'Y' : 'N';
            $data['mnu_desktop'] = $this->input->post('mnu_desktop', TRUE) == 'N' ? 'N' : 'Y';
            $data['mnu_mobile'] = $this->input->post('mnu_mobile', TRUE) == 'N' ? 'N' : 'Y';
            $data['mnu_active_key'] = $this->input->post('mnu_active_key', TRUE);

            if(empty($data['mnu_idx']))
            {
                $sort = (int)$this->db->select_max('mnu_order', 'max')->where('mnu_parent', $data['mnu_parent'])->get('menu')->row(0)->max ;
                $data['mnu_order'] = $sort+1;

                $this->db->insert('menu', $data);
            }
            else
            {
                $this->db->where('mnu_idx', $data['mnu_idx'] );
                $this->db->update('menu', $data);
            }

            $this->load->driver('cache', array('adapter' => 'apc', 'backup' => 'file', 'key_prefix' => PROJECT));
            $this->cache->delete('menu_desktop');
            $this->cache->delete('menu_mobile');

            alert_modal_close("메뉴 등록이 완료되었습니다.", TRUE);
            exit;
        }
        else
        {
            // 게시판 목록 가져오기
            $board_list = $this->db->get('board')->result_array();
            $this->data['board_list'] = array();
            foreach($board_list as $row) {
                $this->data['board_list'][] = array(
                    "url" => '/board/'.$row['brd_key'],
                    "name" => $row['brd_title']
                );
            }

            $this->data['mnu_idx'] = $this->input->get('mnu_idx', TRUE);
            $this->data['mnu_parent'] = $this->input->get('mnu_parent', TRUE);
            $this->data['view'] = array();

            if( ! empty($this->data['mnu_idx']) )
            {
                $this->data['view'] = $this->db->where('mnu_idx', $this->data['mnu_idx'])->get('menu')->row_array();
            }

            $this->theme    = "admin";
            $this->theme_file = "iframe";
            $this->view     = "management/menu_form";
        }
    }

    /**
     * 메뉴 삭제
     */
    public function menu_delete($mnu_idx)
    {
        if(empty($mnu_idx))
        {
            alert('잘못된 접근입니다.');
            exit;
        }

        // 하위메뉴가 있는지 확인한다
        $cnt = (int)$this->db->select('COUNT(*) AS cnt')->where('mnu_parent', $mnu_idx)->get('menu')->row(0)->cnt;
        if( $cnt > 0 )
        {
            alert('해당 메뉴에 하위메뉴가 존재합니다. 하위메뉴를 먼저 삭제해주세요');
            exit;
        }

        $this->db->where('mnu_idx', $mnu_idx)->delete('menu');

        $this->load->driver('cache', array('adapter' => 'apc', 'backup' => 'file', 'key_prefix' => PROJECT));
        $this->cache->delete('menu_desktop');
        $this->cache->delete('menu_mobile');

        alert('삭제되었습니다.');
    }

    /**
     * 메뉴 전체 저장
     */
    public function menu_multi_update()
    {
        $mnu_idx = $this->input->post('mnu_idx', TRUE);
        $mnu_name = $this->input->post('mnu_name', TRUE);
        $mnu_link = $this->input->post('mnu_link', TRUE);
        $mnu_order = $this->input->post('mnu_order', TRUE);
        $mnu_newtab = $this->input->post('mnu_newtab', TRUE);
        $mnu_desktop = $this->input->post('mnu_desktop', TRUE);
        $mnu_mobile = $this->input->post('mnu_mobile', TRUE);
        $mnu_active_key = $this->input->post('mnu_active_key', TRUE);

        $data = array();
        for($i=0; $i<count($mnu_idx); $i++)
        {
            $data[] = array(
                "mnu_idx" => $mnu_idx[$i],
                "mnu_name" => $mnu_name[$i],
                "mnu_link" => $mnu_link[$i],
                "mnu_order"=> $mnu_order[$i],
                "mnu_desktop" => $mnu_desktop[$i],
                "mnu_newtab" => $mnu_newtab[$i],
                "mnu_mobile" => $mnu_mobile[$i],
                "mnu_active_key" => $mnu_active_key[$i]
            );
        }

        $this->db->update_batch("menu", $data, "mnu_idx");

        $this->load->driver('cache', array('adapter' => 'apc', 'backup' => 'file', 'key_prefix' => PROJECT));
        $this->cache->delete('menu_desktop');
        $this->cache->delete('menu_mobile');

        alert('저장 되었습니다.', base_url('admin/management/menu'));
        exit;
    }

    /**
     * 사이트맵 기능
     */
    public function sitemap()
    {
        $this->view = "management/sitemap";
        $this->active   = "management/sitemap";
    }

    /**
     * 사이트맵 폼
     */
    public function sitemap_form()
    {
        $this->load->library('form_validation');

        $this->form_validation->set_rules('sit_loc', 'URL', 'required|trim');

        if( $this->form_validation->run() != FALSE )
        {
            $data['sit_loc'] = '/'.ltrim($this->input->post('sit_loc', TRUE),'/');
            $data['sit_priority'] = $this->input->post('sit_priority', TRUE);
            $data['sit_changefreq'] = $this->input->post('sit_changefreq', TRUE);
            $data['sit_memo'] = $this->input->post('sit_memo', TRUE);
            $data['reg_user'] = $data['upd_user'] = $this->member->is_login();
            $data['reg_datetime'] = $data['upd_datetime'] = date('Y-m-d H:i:s');

            $this->db->insert("sitemap", $data);
            alert_modal_close("등록되었습니다.",FALSE);
            exit;
        }
        else
        {
            $this->theme_file = "iframe";
            $this->view = "management/sitemap_form";
        }
    }

    /**
     * FAQ 관리
     * @param string $faq_idx
     */
    public function faq($fac_idx="")
    {
        // FAQ 모델
        $this->load->model('faq_model');

        // faq_idx 여부에 따라 하위 FAQ 목록 불러오기
        $this->data['fac_idx'] = $fac_idx;
        $this->data['faq_list'] = NULL;
        if( $this->data['fac_idx'] )
        {
            $this->data['faq_list'] = $this->faq_model->get_detail_list($fac_idx);
            $this->data['faq_group'] = $this->faq_model->get_category($fac_idx);
        }

        // 데이타 불러오기
        $this->data['faq_category'] = $this->faq_model->get_category_list();

        // 메타태그 설정
        $this->site->meta_title = "사이트 관리 - FAQ 관리";            // 이 페이지의 타이틀

        // 레이아웃 & 뷰파일 설정
        $this->theme    = "admin";
        $this->view     = "management/faq";
        $this->active   = "management/faq";
    }

    /**
     * FAQ 분류 등록/수정 폼
     */
    public function faq_category_form()
    {
        $this->load->model('faq_model');
        $this->load->library("form_validation");

        $this->form_validation->set_rules("fac_title", "제목", "required|trim");
        $this->form_validation->set_rules("fac_idx", "고유키", "required|trim");

        if( $this->form_validation->run() != FALSE )
        {
            $data['fac_idx'] = $this->input->post('fac_idx', TRUE);
            $data['fac_title'] = trim($this->input->post('fac_title', TRUE));
            $mode = $this->input->post('mode', TRUE);

            if( $mode == 'INSERT' )
            {
                // 가장큰 순서값을 가져온다.
                $data['fac_sort'] = ((int) $this->db->select_max("fac_sort","max")->where('fac_status','Y')->get('faq_category')->row(0)->max)  + 1;

                if(( $exist = $this->faq_model->get_category($data['fac_idx'])) && isset($exist['fac_idx']) )
                {
                    alert('이미 존재하는 고유키 입니다.');
                    exit;
                }
                if( $this->db->insert('faq_category', $data) ) {
                    alert_modal_close("새로운 FAQ 분류를 추가하였습니다.");
                }
                else {
                    alert('DB입력도중 오류가 발생하였습니다.');
                }

            }
            else if ( $mode == 'UPDATE' ) {
                $this->db->where('fac_idx', $data['fac_idx']);
                if( $this->db->update('faq_category', $data)) {
                    alert_modal_close("FAQ 분류 정보를 수정하였습니다.");
                }
                else {
                    alert('DB입력도중 오류가 발생하였습니다.');
                }
            }
            else {
                alert('잘못된 접근입니다.');
            }
        }
        else
        {
            $fac_idx = $this->input->get('fac_idx', TRUE);
            $this->data['view'] = ( empty($fac_idx) ) ? array() : $this->faq_model->get_category($fac_idx);
            $this->data['is_edit'] = ! ( empty($fac_idx) );

            if( $fac_idx && ! $this->data['view'] && ($this->data['view']['fac_idx'] != $fac_idx) )
            {
                alert_modal_close("잘못된 접근입니다.");
                exit();
            }

            $this->theme    = "admin";
            $this->theme_file = "iframe";
            $this->view     = "management/faq_category_form";
        }
    }

    /**
     * FAQ 등록/수정 폼
     */
    public function faq_form()
    {
        $this->load->model('faq_model');
        $this->load->library("form_validation");

        $this->form_validation->set_rules("fac_idx", "FAQ 분류", "required|trim");
        $this->form_validation->set_rules("faq_title", "제목", "required|trim");

        if( $this->form_validation->run() != FALSE )
        {
            $data['faq_idx'] = $this->input->post('faq_idx', TRUE);
            $data['fac_idx'] = $this->input->post('fac_idx', TRUE);
            $data['faq_title'] = $this->input->post('faq_title', TRUE);
            $data['faq_content'] = $this->input->post('faq_content', FALSE);

            if(empty($data['fac_idx']))
            {
                alert('잘못된 접근입니다.');
                exit;
            }

            if(empty($data['faq_idx']))
            {
                $data['faq_sort'] = ((int) $this->db->select_max("faq_sort","max")->where('faq_status','Y')->where('fac_idx', $data['fac_idx'])->get('faq')->row(0)->max)  + 1;
                if( ! $this->db->insert("faq", $data) )
                {
                    alert("DB정보 등록에 실패하였습니다.");
                    exit;
                }
            }
            else
            {
                $this->db->where("faq_idx", $data['faq_idx']);
                if(! $this->db->update("faq", $data) )
                {
                    alert("DB정보 수정에 실패하였습니다.");
                    exit;
                }
            }

            // FAQ 분류에 등록된 FAQ 수를 최신화 한다.
            $this->faq_model->update_category_count($data['fac_idx']);
            alert_modal_close('FAQ 정보가 등록되었습니다.');
            exit;
        }
        else
        {
            $fac_idx = $this->input->get('fac_idx', TRUE);
            $faq_idx = $this->input->get('faq_idx', TRUE);

            $this->data['faq_group'] = $this->faq_model->get_category($fac_idx);
            $this->data['view'] = ( empty($faq_idx) ) ? array() : $this->faq_model->get_faq($faq_idx);

            if( ! $this->data['faq_group'] )
            {
                alert_modal_close("잘못된 접근입니다.");
                exit();
            }

            $this->theme    = "admin";
            $this->theme_file = "iframe";
            $this->view     = "management/faq_form";
        }
    }

    /**
     * 팝업 관리
     */
    public function popup()
    {
        // 메타태그 설정
        $this->site->meta_title = "팝업 관리";

        // 레이아웃 & 뷰파일 설정
        $this->active = $this->view = "management/popup";
    }

    /**
     * 팝업 등록/수정
     */
    public function popup_form($pop_idx="")
    {
        $this->load->model('popup_model');
        $this->load->library('form_validation');

        $this->form_validation->set_rules('pop_title', '팝업 이름', 'required|trim');
        $this->form_validation->set_rules('pop_width', '팝업 너비', 'required|trim|is_natural_no_zero');
        $this->form_validation->set_rules('pop_height', '팝업 높이', 'required|trim|is_natural_no_zero');
        $this->form_validation->set_rules('pop_type', '팝업 종류', 'required|trim|in_list[Y,N]');
        $this->form_validation->set_rules('pop_start', '표시 시작 시간', 'required|trim');
        $this->form_validation->set_rules('pop_start', '표시 종료 시간', 'required|trim');

        if( $this->form_validation->run() != FALSE )
        {

            $data['pop_title'] = $this->input->post('pop_title', TRUE);
            $data['pop_width'] = $this->input->post('pop_width', TRUE);
            $data['pop_height'] = str_replace(',','',$this->input->post('pop_height', TRUE));
            $data['pop_content'] = str_replace(',','',$this->input->post('pop_content', FALSE));
            $data['pop_status'] = 'Y';
            $data['pop_start'] = str_replace("T"," ", $this->input->post('pop_start', TRUE));
            $data['pop_end'] = str_replace("T"," ", $this->input->post('pop_end', TRUE));
            $data['upd_datetime'] = date('Y-m-d H:i:s');
            $data['upd_user'] = $this->member->is_login();
            $data['pop_type'] = $this->input->post('pop_type', TRUE) == 'N' ? 'N' : 'Y';

            if( empty($pop_idx) )
            {
                $data['reg_datetime'] = $data['upd_datetime'];
                $data['reg_user'] = $data['upd_user'];
                $this->db->insert('popup', $data);
            }
            else
            {
                $this->db->where('pop_idx', $pop_idx);
                $this->db->update('popup', $data);
            }

            alert_modal_close('팝업 정보 입력이 완료되었습니다.', FALSE);
            exit;
        }
        else
        {
            $this->data['view'] = array();
            if(! empty($pop_idx))
            {
                if(! $this->data['view'] = $this->db->where('pop_idx', $pop_idx)->get('popup')->row_array())
                {
                    alert_modal_close('잘못된 접근입니다.',false);
                }
            }

            // 메타태그 설정
            $this->site->meta_title = "팝업 관리";

            // 레이아웃 & 뷰파일 설정
            $this->theme_file = "iframe";
            $this->view     = "management/popup_form";
        }
    }

    function datetime_regex($str)
    {
        if(!preg_match('/(\d{4})-(\d{2})-(\d{2}) (\d{2}):(\d{2})/',$str, $matches))
        {
            $this->form_validation->set_message('datetime_regex', '올바른 형식의 날짜/시간 형태가 아닙니다 : {field}');
            return FALSE;
        }

        return TRUE;
    }

    /****************************************************************************
     * 배너 관리 - 목록
     ***************************************************************************/
    function banner($bng_key="")
    {
        $this->load->model('basic_model');
        // bng_key 여부에 따라 하위 FAQ 목록 불러오기
        $this->data['bng_key'] = $bng_key;
        $this->data['faq_list'] = NULL;

        if( $this->data['bng_key'] )
        {
            $this->data['banner_list'] = array();
            $param['limit'] = FALSE;
            $param['from'] = "banner";
            $param['order_by'] = "ban_sort ASC";
            $param['where']['bng_key'] = $bng_key;
            $this->data['banner_list'] = $this->basic_model->get_list($param);
            unset($param);

            $this->data['banner_group'] = $this->db->where('bng_key', $this->data['bng_key'])->get('banner_group')->row_array();
        }

        // 배너 그룹 목록 가져오기
        $param['limit'] = FALSE;
        $param['order_by'] = "bng_name ASC";
        $param['from'] = "banner_group";
        $this->data['banner_group_list'] = $this->basic_model->get_list($param);

        // 메타태그 설정
        $this->site->meta_title = "사이트 관리 - 배너 관리";            // 이 페이지의 타이틀

        // 레이아웃 & 뷰파일 설정
        $this->theme    = "admin";
        $this->view     = "management/banner";
        $this->active   = "management/banner";
    }

    /****************************************************************************
     * 배너 관리 - 그룹 추가/수정
     ***************************************************************************/
    function banner_group_form()
    {
        $this->load->library('form_validation');

        $this->form_validation->set_rules('bng_key', "배너 그룹 고유 키", "required|trim|max_length[20]|alpha_dash");
        $this->form_validation->set_rules('bng_name', "배너 이름","required|trim|max_length[50]");

        if( $this->form_validation->run() != FALSE )
        {
            $data['bng_idx'] = $this->input->post('bng_idx', TRUE);
            $data['bng_key'] = $this->input->post('bng_key', TRUE);
            $data['bng_name'] = $this->input->post('bng_name', TRUE);
            $data['bng_width'] = $this->input->post('bng_width', TRUE, 0);
            $data['bng_height'] = $this->input->post('bng_height', TRUE, 0);
            for($i=1; $i<=5; $i++)
            {
                $data["bng_ext{$i}"] = $this->input->post("bng_ext{$i}",TRUE,'');
                $data["bng_ext{$i}_use"] = $this->input->post("bng_ext{$i}_use",TRUE,'N');
            }

            if(empty($data['bng_idx']))
            {
                $tmp = (int)$this->db->select('COUNT(*) AS cnt')->where('bng_key', $data['bng_key'])->get('banner_group')->row(0)->cnt;
                if($tmp > 0) {
                    alert('이미 존재하는 고유키 입니다.');
                    exit;
                }

                if( $this->db->insert('banner_group', $data))
                {
                    alert_modal_close('배너 그룹이 추가 되었습니다.', TRUE);
                    exit;
                }
            }
            else
            {
                $this->db->where('bng_idx', $data['bng_idx']);
                if( $this->db->update('banner_group', $data) )
                {
                    alert_modal_close('배너 그룹이 수정 되었습니다.', TRUE);
                    exit;
                }
            }

            alert('서버 오류가 발생하였습니다.');
            exit;
        }
        else
        {
            $bng_idx = $this->input->get('bng_idx', TRUE);

            $this->data['view'] = array();
            if(! empty($bng_idx)) {
                $this->data['view'] = $this->db->where('bng_idx', $bng_idx)->get('banner_group')->row_array();
            }

            $this->theme    = "admin";
            $this->theme_file = "iframe";
            $this->view     = "management/banner_group_form";
        }

    }

    /****************************************************************************
     * 배너 관리 - 그룹 삭제
     ***************************************************************************/
    function banner_group_delete($bng_idx="")
    {
        if(empty($bng_idx)) {
            alert('잘못된 접근입니다.');
            exit;
        }

        $banner_group = $this->db->where('bng_idx', $bng_idx)->get('banner_group')->row_array();

        if(empty($banner_group) OR empty($banner_group['bng_key'])) {
            alert('존재하지 않는 그룹이거나, 이미 삭제된 그룹입니다.');
            exit;
        }

        // 배너에 포함된 파일을 삭제하기 위해 배너 목록을 가져와서 파일을 삭제한다.
        $banner_list = $this->db->where('bng_key', $banner_group['bng_key'])->get('banner')->result_array();

        // 트랜젝션 시작
        $this->db->trans_begin();

        $this->db->where('bng_idx', $bng_idx);
        $this->db->delete('banner_group');

        $this->db->where('bng_key', $banner_group['bng_key']);
        $this->db->delete('banner');

        if ($this->db->trans_status() === FALSE)
        {
            $this->db->trans_rollback();
            alert('그룹 삭제에 실패하였습니다. 관리자에게 문의하세요');
            exit;
        }
        else
        {
            $this->db->trans_commit();

            // 배너 파일들을 삭제
            if(count($banner_list) > 0)
            {
                foreach($banner_list as $row)
                {
                    if(empty($row['ban_filepath'])) continue;

                    file_delete($row['ban_filepath']);
                }
            }

            alert('배너 그룹을 삭제하였습니다.');
            exit;
        }
    }

    /****************************************************************************
     * 배너 관리 - 배너 추가/수정
     ***************************************************************************/
    function banner_form()
    {
        $this->load->library('form_validation');

        $this->form_validation->set_rules('bng_key', "배너 그룹 고유 키", "required|trim");
        $this->form_validation->set_rules('ban_name', "배너 이름","required|trim|max_length[50]");

        if($this->form_validation->run() != FALSE)
        {
            $data['bng_key'] = $this->input->post('bng_key', TRUE);
            $data['ban_idx'] = $this->input->post('ban_idx', TRUE);
            $data['ban_name'] = $this->input->post('ban_name', TRUE);
            $data['ban_link_use'] = $this->input->post('ban_link_use', TRUE) == 'Y' ? 'Y' : 'N';
            $data['ban_link_url'] = $this->input->post('ban_link_url', TRUE, "");
            $data['ban_link_type'] = $this->input->post('ban_link_url', TRUE) == 'Y' ? 'Y': 'N';
            $data['ban_modtime'] = date('Y-m-d H:i:s');
            $data['ban_status'] = $this->input->post('ban_status', TRUE) == 'H' ? 'H' : 'Y';
            $data['ban_timer_use'] = $this->input->post('ban_timer_use', TRUE) == 'Y' ? 'Y' : 'N';
            $data['ban_timer_start'] = $this->input->post('ban_timer_start', TRUE, '0000-00-00 00:00:00');
            $data['ban_timer_end'] = $this->input->post('ban_timer_end', TRUE, '0000-00-00 00:00:00');
            for($i=1; $i<=5; $i++)
            {
                $data["ban_ext{$i}"] = $this->input->post("ban_ext{$i}",TRUE,'');
            }

            // 업로드된 파일이 있을경우 처리
            if( isset($_FILES['userfile']) && $_FILES['userfile'] && $_FILES['userfile']['tmp_name'] )
            {
                $up_dir = DIR_UPLOAD . DIRECTORY_SEPARATOR .  'banners';
                $up_dir = make_dir($up_dir, TRUE, TRUE);

                $config['upload_path']      =  './'.ltrim($up_dir,'/');
                $config['allowed_types']    = 'gif|jpg|png';
                $config['file_ext_tolower'] = TRUE;
                $config['encrypt_name']     = TRUE;

                $this->load->library("upload", $config);
                $this->upload->initialize($config);
                if( ! $this->upload->do_upload('userfile') )
                {
                    alert("이미지 업로드중 오류가 발생하였습니다.".$this->upload->display_errors('업로드 오류:', ' ').$config['upload_path'] );
                }
                else
                {
                    $data['ban_filepath'] = rtrim(str_replace(DIRECTORY_SEPARATOR, "/", $up_dir), "/") . "/" . $this->upload->data('file_name');
                    // 수정일경우 원래 이미지를 삭제한다
                    if(! empty($data['ban_idx']))
                    {
                        db_file_delete("banner","ban_idx", $data['ban_idx'], 'ban_filepath');
                    }
                }
            }

            if(empty($data['ban_idx']))
            {
                $data['ban_regtime'] = date('Y-m-d H:i:s');

                $sort = (int)$this->db->select_max('ban_sort', 'max')->where('bng_key', $data['bng_key'])->get('banner')->row(0)->max;
                $data['ban_sort'] = $sort+1;

                $this->db->insert('banner', $data);
            }
            else
            {
                $this->db->where('ban_idx', $data['ban_idx']);
                $this->db->update('banner', $data);
            }

            alert_modal_close("등록이 완료되었습니다.", TRUE);
            exit;
        }
        else
        {
            $this->data['bng_key'] = $this->input->get('bng_key', TRUE);
            $this->data['ban_idx'] = $this->input->get('ban_idx', TRUE);
            $this->data['view'] = array();

            if(empty($this->data['bng_key']))
            {
                alert_modal_close('잘못된 접근입니다.');
                exit;
            }

            if( ! $this->data['banner_group'] = $this->db->where('bng_key', $this->data['bng_key'])->get('banner_group')->row_array())
            {
                alert_modal_close('잘못된 접근입니다.');
                exit;
            }

            if(! empty($this->data['ban_idx'])) {
                if(! $this->data['view'] = $this->db->where('ban_idx', $this->data['ban_idx'])->get('banner')->row_array())
                {
                    alert('삭제되었거나 존재하지 않는 배너 입니다.');
                    exit;
                }
            }

            $this->theme    = "admin";
            $this->theme_file = "iframe";
            $this->view     = "management/banner_form";
        }
    }

    /****************************************************************************
     * 배너 관리 - 배너 삭제
     ***************************************************************************/
    function banner_delete($ban_idx="")
    {
        if(empty($ban_idx)) {
            alert('잘못된 접근입니다.');
            exit;
        }

        $banner = $this->db->where('ban_idx', $ban_idx)->get('banner')->row_array();

        if(empty($banner) OR empty($banner['ban_idx'])) {
            alert('존재하지 않는 배너거나, 이미 삭제된 배너입니다.');
            exit;
        }


        $this->db->where('ban_idx', $ban_idx);
        if( $this->db->delete('banner') )
        {
            if(! empty($banner['ban_filepath']))
            {
                file_delete($banner['ban_filepath']);
            }

            alert('배너를 삭제하였습니다.');
        }
        else {
            alert('배너 삭제도중 오류가 발생하였습니다.');
        }
    }

    /****************************************************************************
     * 배너 관리 - 배너 순서변경
     ***************************************************************************/
    function banner_sort()
    {
        $sort_idx = $this->input->post("sort_idx", TRUE);
        for($i=1; $i<=count($sort_idx); $i++)
        {
            $this->db->where("ban_idx", $sort_idx[$i-1]);
            $this->db->set("ban_sort", $i);
            $this->db->update("banner");
        }
    }

    /*--------------------------------------------------------------------------*/

}
