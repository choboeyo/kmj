<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Setting extends WB_Controller {

    public function __construct()
    {
        parent::__construct();

        $this->data['lang_name']['ko'] = '한국어';
        $this->data['lang_name']['en'] = 'English';
        $this->data['lang_name']['ja'] = '일본어';
        $this->data['lang_name']['zh-hans'] = '중국어(간체)';
        $this->data['lang_name']['zh-hant'] = '중국어(번체)';
    }

    public function basic()
    {
        // 메타태그 설정
        $this->site->meta_title = "사이트 기본설정";            // 이 페이지의 타이틀

        // 레이아웃 & 뷰파일 설정
        $this->theme    = "admin";
        $this->view     = "setting/basic";
        $this->active   = "setting/basic";
    }

    public function agreement()
    {
        // 메타태그 설정
        $this->site->meta_title = "약관 설정";            // 이 페이지의 타이틀

        // 레이아웃 & 뷰파일 설정
        $this->theme    = "admin";
        $this->view     = "setting/agreement";
        $this->active   = "setting/agreement";
    }

    public function member()
    {
        // 메타태그 설정
        $this->site->meta_title = "회원 설정";            // 이 페이지의 타이틀

        // 레이아웃 & 뷰파일 설정
        $this->theme    = "admin";
        $this->view     = "setting/member";
        $this->active   = "setting/member";
    }

    public function apis()
    {
        // 메타태그 설정
        $this->site->meta_title = "소셜/API 설정";            // 이 페이지의 타이틀

        // 레이아웃 & 뷰파일 설정
        $this->theme    = "admin";
        $this->view     = "setting/apis";
        $this->active   = "setting/apis";
    }

    public function localize($param="")
    {
        $this->load->library('form_validation');

        $this->form_validation->set_rules('mode',"mode", "required|trim");

        $accept_lang = $this->site->config('accept_languages');
        $this->data['accept_langs'] = explode(',', $accept_lang);

        if( $this->form_validation->run() != FALSE )
        {
            $loc_key = $this->input->post('loc_key', TRUE);
            foreach($this->data['accept_langs'] as $lang)
            {
                $lang_name = str_replace("-","_",$lang);
                $loc_value_{$lang_name} = $this->input->post('loc_value_'.$lang, TRUE);
            }

            $update = array();
            for($i=0; $i<count($loc_key); $i++)
            {
                $array['loc_key'] = $loc_key[$i];
                foreach($this->data['accept_langs'] as $lang)
                {
                    $lang_name = str_replace("-","_",$lang);
                    $array['loc_value_'.$lang] = $loc_value_{$lang_name}[$i];
                }

                $update[] = $array;
            }

            $this->db->update_batch("localize", $update, "loc_key");
            $this->cache->delete('site_language');
            alert('저장완료', base_url('admin/setting/localize/'.$param));
            exit;
        }
        else
        {
            if(empty($param))
            {
                $param = "공통";
            }

            $param = urldecode($param);

            $this->db->like("loc_key", $param, "after");
            $this->data['list'] = $this->db->order_by('loc_key ASC')->get('localize')->result_array();
            $this->data['active'] = $param;

            // 탭리스트
            $query = $this->db->query('SELECT SUBSTRING_INDEX(loc_key,"/",1) AS `keys` FROM wb_localize GROUP BY SUBSTRING_INDEX(loc_key,"/",1)');
            $this->data['tab_list'] = $query->result_array();

            // 메타태그 설정
            $this->site->meta_title = "다국어 설정";            // 이 페이지의 타이틀

            // 레이아웃 & 뷰파일 설정
            $this->theme    = "admin";
            $this->view     = "setting/localize";
            $this->active   = "setting/localize";
        }
    }

    public function localize_form()
    {
        $this->load->library('form_validation');

        $this->form_validation->set_rules("loc_key", "구분 키", "required|trim|max_length[60]|min_length[5]|callback_loc_key_check");
        $this->form_validation->set_rules("loc_value_ko", "한글", "required|trim");


        $accept_lang = $this->site->config('accept_languages');
        $this->data['accept_langs'] = explode(',', $accept_lang);

        if( $this->form_validation->run() != FALSE )
        {
            $data['loc_key'] = $this->input->post('loc_key', TRUE);

            foreach($this->data['accept_langs'] as $langs)
            {
                $data['loc_value_'.$langs] = $this->input->post('loc_value_'.$langs, TRUE);
            }

            $this->db->insert('localize', $data);

            $this->cache->delete('site_language');

            alert_modal_close('등록완료');
            exit;
        }
        else
        {
            // 레이아웃 & 뷰파일 설정
            $this->theme    = "admin";
            $this->theme_file = "iframe";
            $this->view     = "setting/localize_form";
        }
    }

    public function loc_key_check($str)
    {
        $this->db->where('loc_key', $str);
        $result = $this->db->get('localize');
        $loc = $result->row_array();

        if( $loc )
        {
            $this->form_validation->set_message('loc_key_check', "이미 사용중인 {field}입니다 : {$str}");
            return FALSE;
        }

        return true;
    }

    public function admin()
    {
        // 레이아웃 & 뷰파일 설정
        $this->theme    = "admin";
        $this->view     = "setting/admin";
        $this->active   = "setting/admin";
    }

    public function admin_add()
    {
        $this->data['scol'] = $this->input->get('scol', TRUE, '');
        $this->data['stxt'] = $this->input->get('stxt', TRUE, '');

        $this->data['lists'] = array();

        if(! empty($this->data['stxt']))
        {
            $result =
            $this->db
                ->select('M.*, MA.ath_type')
                ->from('member AS M')
                ->join('member_auth AS MA', 'MA.mem_idx=M.mem_idx','left')
                ->like( $this->data['scol'], $this->data['stxt'] )
                ->where('mem_status','Y')
                ->where('ath_type IS NULL',FALSE, FALSE)
                ->group_by('M.mem_idx')
                ->get();
            $this->data['lists'] = $result->result_array();
        }

        $this->theme    = "admin";
        $this->view     = "setting/admin_add";
        $this->theme_file   = "iframe";
    }

    public function update()
    {
        $reurl = $this->input->post('reurl', TRUE);
        $setting = $this->input->post('setting');

        // 수정할값을 저장하는 배열
        $update_data = array();
        foreach($setting as $key=>$val)
        {
            $update_data[] = array(
                "cfg_key" => $key,
                "cfg_value" => $val
            );
        }

        // 권한레벨 설정을 하였다면?
        if( $this->input->post('auth_name') )
        {
            $update_data[] = array(
                "cfg_key" => "name_auth_level",
                "cfg_value" => json_encode($this->post('auth_name'), JSON_UNESCAPED_SLASHES|JSON_UNESCAPED_UNICODE)
            );
        }

        if( $this->input->post('accept_language') )
        {
            $accept = $this->input->post('accept_language');
            if(count($accept) <= 0) {
                alert('최소한 하나의 언어를 선택하셔야 합니다.');
                exit;
            }

            $update_data[] = array(
                "cfg_key" => "accept_languages",
                "cfg_value" => implode(",", $accept)
            );
        }

        // 사이트 이미지 삭제가 되어있다면?
        if( $this->input->post('remove_site_meta_image') == 'Y' )
        {
            if( file_exists( FCPATH . $this->site->config('site_meta_image') ) )
            {
                @unlink ( FCPATH . $this->site->config('site_meta_image') );
            }
        }

        // 사이트 이미지 업로드가 있다면?
        if( isset($_FILES['site_meta_image']) && $_FILES['site_meta_image'] && $_FILES['site_meta_image']['tmp_name'] )
        {
            $up_dir = DIR_UPLOAD . DIRECTORY_SEPARATOR .  'common';
            make_dir($up_dir, FALSE);
            $config['upload_path']      =  FCPATH . $up_dir;
            $config['allowed_types']    = 'gif|jpg|png';
            //$config['max_width']        = 1200;
            //$config['min_width']        = 1200;
            //$config['max_height']       = 600;
            //$config['min_height']       = 600;
            $config['file_ext_tolower'] = TRUE;
            $config['encrypt_name']     = TRUE;

            $this->load->library("upload", $config);
            $this->upload->initialize($config);
            if( ! $this->upload->do_upload('site_meta_image') )
            {
                alert("업로드중 오류가 발생하였습니다.".$this->upload->display_errors('업로드 오류:', ' '));
            }
            else
            {
                $update_data[] = array(
                    "cfg_key" => "site_meta_image",
                    "cfg_value" =>  str_replace(DIRECTORY_SEPARATOR, "/", $up_dir) . "/" . $this->upload->data('file_name')
                );

                // 기존에 업로드 되었던 파일은 삭제한다.
                if( file_exists( FCPATH . $this->site->config('site_meta_image') ) )
                {
                    @unlink ( FCPATH . $this->site->config('site_meta_image') );
                }
            }
        }
        else {
            if( $this->input->post('remove_site_meta_image') == 'Y' )
            {
                $update_data[] = array(
                    "cfg_key" => "site_meta_image",
                    "cfg_value" =>  ''
                );
            }
        }

        // 수정할 값이 있다면 수정 실행
        if(  count($update_data)  > 0)
        {
            if( $this->db->update_batch( "config", $update_data, "cfg_key" ) )
            {
                // 저장된 캐시를 삭제
                $this->load->driver('cache', array('adapter' => 'apc', 'backup' => 'file', 'key_prefix' => PROJECT));
                $this->cache->delete('site_config');

                alert('수정내역이 반영되었습니다.', $reurl);
            }
            else
            {
                alert('수정된 내역이 없습니다.', $reurl);
            }
        }
        else
        {
            alert('수정된 내역이 없습니다.', $reurl);
        }
    }
}
