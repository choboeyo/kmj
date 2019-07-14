<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Board extends WB_Controller
{
    /**
     * 게시판 목록
     */
    public function lists()
    {
        $this->load->model('board_model');

        $this->data['board_list'] = $this->board_model->board_list();

        // 메타태그 설정
        $this->site->meta_title = "게시판 관리";

        // 레이아웃 & 뷰파일 설정
        $this->theme    = "admin";
        $this->view     = "board/lists";
        $this->active   = "board/lists";
    }

    /**
     * 게시판 등록/수정
     * @param string $brd_key
     */
    public function form($brd_key="")
    {
        $this->load->model('board_model');
        $this->load->library('form_validation');

        $this->form_validation->set_rules('brd_key','게시판 고유 키',"required|trim|min_length[3]|max_length[20]". (empty($brd_key)?"|callback_brd_key_check":""));

        if( $this->form_validation->run() != FALSE )
        {
            $data['brd_key'] = $this->input->post('brd_key', TRUE);
            $data['brd_title'] = $this->input->post('brd_title', TRUE);
            $data['brd_title_m'] = $this->input->post('brd_title_m', TRUE);
            $data['brd_keywords'] = $this->input->post('brd_keywords', TRUE);
            $data['brd_description'] = $this->input->post('brd_description', TRUE);
            $data['brd_skin_l'] = $this->input->post('brd_skin_l', TRUE);
            $data['brd_skin_l_m'] = $this->input->post('brd_skin_l_m', TRUE);
            $data['brd_skin_w'] = $this->input->post('brd_skin_w', TRUE);
            $data['brd_skin_w_m'] = $this->input->post('brd_skin_w_m', TRUE);
            $data['brd_skin_v'] = $this->input->post('brd_skin_v', TRUE);
            $data['brd_skin_v_m'] = $this->input->post('brd_skin_v_m', TRUE);
            $data['brd_skin_c'] = $this->input->post('brd_skin_c', TRUE);
            $data['brd_skin_c_m'] = $this->input->post('brd_skin_c_m', TRUE);
            $data['brd_search'] = $this->input->post('brd_search', TRUE, "N");
            $data['brd_sort'] = $this->input->post('brd_sort', TRUE);
            $data['brd_use_category'] = $this->input->post('brd_use_category', TRUE, "N");
            $data['brd_lv_list'] = $this->input->post('brd_lv_list', TRUE);
            $data['brd_lv_read'] = $this->input->post('brd_lv_read', TRUE);
            $data['brd_lv_write'] = $this->input->post('brd_lv_write', TRUE);
            $data['brd_lv_reply'] = $this->input->post('brd_lv_reply', TRUE);
            $data['brd_lv_comment'] = $this->input->post('brd_lv_comment', TRUE);
            $data['brd_lv_download'] = $this->input->post('brd_lv_download', TRUE);
            $data['brd_lv_upload'] = $this->input->post('brd_lv_upload', TRUE);
            $data['brd_use_list_thumbnail'] = $this->input->post('brd_use_list_thumbnail', TRUE);
            $data['brd_use_list_file'] = $this->input->post('brd_use_list_file', TRUE);
            $data['brd_use_view_list'] = $this->input->post('brd_use_view_list', TRUE);
            $data['brd_thumb_width'] = $this->input->post('brd_thumb_width', TRUE);
            $data['brd_thumb_height'] = $this->input->post('brd_thumb_height', TRUE);
            $data['brd_time_new'] = $this->input->post('brd_time_new', TRUE);
            $data['brd_hit_count'] = $this->input->post('brd_hit_count', TRUE);
            $data['brd_page_limit'] = $this->input->post('brd_page_limit', TRUE);
            $data['brd_page_rows'] = $this->input->post('brd_page_rows', TRUE);
            $data['brd_page_rows_m'] = $this->input->post('brd_page_rows_m', TRUE);
            $data['brd_fixed_num'] = $this->input->post('brd_fixed_num', TRUE);
            $data['brd_fixed_num_m'] = $this->input->post('brd_fixed_num_m', TRUE);
            $data['brd_display_time'] = $this->input->post('brd_display_time', TRUE);
            $data['brd_use_anonymous'] = $this->input->post('brd_use_anonymous', TRUE);
            $data['brd_use_secret'] = $this->input->post('brd_use_secret', TRUE);
            $data['brd_use_reply'] = $this->input->post('brd_use_reply', TRUE);
            $data['brd_use_comment'] = $this->input->post('brd_use_comment', TRUE);
            $data['brd_use_wysiwyg'] = $this->input->post('brd_use_wysiwyg', TRUE);
            $data['brd_use_attach'] = $this->input->post('brd_use_attach', TRUE);
            $data['brd_use_assign'] = $this->input->post('brd_use_assign', TRUE, 'N') == 'Y' ? 'Y' : 'N';
            $data['brd_point_read'] = $this->input->post('brd_point_read', TRUE);
            $data['brd_point_write'] = $this->input->post('brd_point_write', TRUE);
            $data['brd_point_comment'] = $this->input->post('brd_point_comment', TRUE);
            $data['brd_point_download'] =  $this->input->post('brd_point_download', TRUE);
            $data['brd_point_reply'] =  $this->input->post('brd_point_reply', TRUE);
            $data['brd_use_total_rss'] = $this->input->post('brd_use_total_rss', TRUE);
            $data['brd_use_rss'] = $this->input->post('brd_use_rss', TRUE);
            $data['brd_use_sitemap'] = $this->input->post('brd_use_sitemap', TRUE);

            if(empty($brd_key))
            {
                $tmp = (int)$this->db->select_max('brd_sort',"max")->from('board')->get()->row(0)->max;
                $data['brd_sort'] = $tmp +1;
                $data['brd_count_post'] = 0;

                if( $this->db->insert('board', $data) )
                {
                    alert('게시판 생성이 완료되었습니다.',base_url('/admin/board/lists'));
                    exit;
                }
            }
            else
            {
                $this->db->where('brd_key', $brd_key);
                if( $this->db->update('board', $data) ) {
                    $this->board_model->delete_cache($brd_key);
                    alert('게시판 정보 수정이 완료되었습니다.',base_url('/admin/board/lists'));
                    exit;
                }
            }

            alert('DB입력도중 오류가 발생하였습니다.');
            exit;
        }
        else
        {
            $this->data['view'] = (empty($brd_key)) ? array() : $this->board_model->get_board($brd_key, TRUE);
            $this->data['brd_key'] = $brd_key;
            $this->data['skin_list_l'] = get_skin_list('board/list');
            $this->data['skin_list_w'] = get_skin_list('board/write');
            $this->data['skin_list_v'] = get_skin_list('board/view');
            $this->data['skin_list_c'] = get_skin_list('board/comment');

            // 메타태그 설정
            $this->site->meta_title = "게시판 관리";

            // 레이아웃 & 뷰파일 설정
            $this->theme    = "admin";
            $this->view     = "board/form";
            $this->active   = "board/lists";
        }
    }

    /**
     * 게시판 삭제
     */
    public function remove($brd_key)
    {
        if(empty($brd_key))
        {
            alert('잘못된 접근입니다.');
            exit;
        }

        $this->db->where('brd_key', $brd_key)->delete('board');
        alert('게시판이 삭제되었습니다.');
        exit;
    }

    /**
     * 게시판 복사
     */
    public function board_copy($brd_key)
    {
        $this->load->model('board_model');
        $this->load->library('form_validation');

        $this->form_validation->set_rules('original', "원본 게시판", "required|trim");
        $this->form_validation->set_rules('brd_key','게시판 고유 키',"required|trim|min_length[3]|max_length[20]|callback_brd_key_check");

        if( $this->form_validation->run() != FALSE )
        {
            $data = $this->board_model->get_board( $this->input->post('original', TRUE) , TRUE);

            if(! $data || !isset($data['brd_key']) || !$data['brd_key'])
            {
                alert_modal_close('원본 게시판 설정을 찾을수 없습니다.');
                exit;
            }

            $data['brd_key'] = $this->input->post('brd_key', TRUE);
            $data['brd_title'] = $this->input->post('brd_title', TRUE);
            $data['brd_title_m'] = "";
            $tmp = (int)$this->db->select_max('brd_sort',"max")->from('board')->get()->row(0)->max;
            $data['brd_sort'] = $tmp +1;
            $data['brd_count_post'] = 0;

            $this->db->insert('board', $data);

            alert_modal_close('게시판 복사가 완료되었습니다.');
            exit;

        }
        else
        {
            $this->data['view'] = $this->board_model->get_board($brd_key, TRUE);
            if(! $this->data['view'] || !isset($this->data['view']['brd_key']) || ! $this->data['view']['brd_key'])
            {
                alert_modal_close('원본 게시판 설정을 찾을수 없습니다.');
                exit;
            }
            $this->data['brd_key'] = $brd_key;
            $this->data['skin_list'] = get_skin_list('board');

            $this->theme = "admin";
            $this->theme_file = "iframe";
            $this->view = "board/board_copy";
        }
    }

    /**
     * 게시판 키 중복여부 확인
     */
    function brd_key_check($str)
    {
        $this->load->model('board_model');

        if(! preg_match("/^[a-z][a-z0-9_]{2,19}$/", $str))
        {
            $this->form_validation->set_message('brd_key_check', "게시판 고유키는 영어 소문자로 시작하는 3~20 글자로 영어와 숫자만 사용가능합니다. : {$str}");
            return FALSE;
        }

        if( $board = $this->board_model->get_board($str, TRUE) ) {
            $this->form_validation->set_message('brd_key_check', "이미 사용중인 {field} 입니다 : {$str}");
            return FALSE;
        }

        return TRUE;
    }

    /**
     * 게시판 카테고리 관리
     * @param string $brd_key
     */
    function category($brd_key="")
    {
        $this->load->model('board_model');

        if(empty($brd_key))
        {
            alert('잘못된 접근입니다.');
            exit;
        }

        $this->data['board'] = $this->board_model->get_board($brd_key, FALSE);

        if( $this->data['board']['brd_use_category'] != 'Y' )
        {
            alert('게시판 카테고리 사용설정이 되어있지 않습니다.');
            exit;
        }

        // 메타태그 설정
        $this->site->meta_title = "게시판 관리";

        // 레이아웃 & 뷰파일 설정
        $this->theme    = "admin";
        $this->view     = "board/category";
        $this->active   = "board/lists";
    }

    /**
     * 카테고리 등록/수정 폼
     */
    function category_form()
    {
        $this->load->library('form_validation');
        $this->load->model('board_model');

        $this->form_validation->set_rules('brd_key', "게시판 고유키","required|trim");
        $this->form_validation->set_rules('bca_name', "카테고리 이름","required|trim");

        if( $this->form_validation->run() != FALSE )
        {
            $data['bca_idx'] = $this->input->post('bca_idx', TRUE);
            $data['bca_parent'] = $this->input->post('bca_parent', TRUE);
            $data['brd_key'] = $this->input->post('brd_key', TRUE);
            $data['bca_name'] = $this->input->post('bca_name', TRUE);

            if( empty($data['bca_idx']) )
            {
                $tmp = (int)$this->db->select_max('bca_sort','max')->where('brd_key',$data['brd_key'])->where('bca_parent', $data['bca_parent'])->get('board_category')->row(0)->max;
                $data['bca_sort'] = $tmp+1;

                if( $this->db->insert("board_category", $data) )
                {
                    $this->board_model->delete_cache($data['brd_key']);
                    alert_modal_close('새로운 카테고리가 추가되었습니다.');
                    exit;
                }
            }
            else
            {
                $this->db->where('bca_idx', $data['bca_idx']);
                $this->db->where('bca_parent', $data['bca_parent']);
                $this->db->where('brd_key', $data['brd_key']);
                $this->db->set('bca_name', $data['bca_name']);
                if( $this->db->update('board_category') )
                {
                    $this->board_model->delete_cache($data['brd_key']);
                    alert_modal_close('카테고리 이름을 변경하었습니다.');
                    exit;
                }
            }

            alert('DB 입력에 실패하였습니다');
            exit;

        }
        else
        {
            $this->data['brd_key']      = $this->input->get('brd_key', TRUE);
            $this->data['bca_parent']   = $this->input->get('bca_parent', TRUE);
            $this->data['bca_idx']      = $this->input->get('bca_idx', TRUE);

            $this->data['view'] = empty($this->data['bca_idx']) ? array() : $this->board_model->get_category($this->data['bca_idx']);

            $this->theme    = "admin";
            $this->theme_file = "iframe";
            $this->view     = "board/category_form";
        }
    }


    function board_common($brd_key)
    {
        $this->load->model('board_model');
        $this->data['board'] = $this->board_model->get_board($brd_key, FALSE);

        if(empty($this->data['board']) OR ! isset($this->data['board']['brd_key']) )
        {
            alert('존재하지 않는 게시판 또는 삭제된 게시판입니다.');
            exit;
        }

        $this->param['page'] = $this->data['page'] = (int)$this->input->get('page', TRUE) >= 1 ? $this->input->get('page', TRUE) : 1;
        $this->param['scol'] = $this->data['scol'] = $this->input->get('scol', TRUE);
        $this->param['stxt'] = $this->data['stxt'] = $this->input->get('stxt', TRUE);
        $this->param['category'] = $this->data['category'] = $this->input->get('category', TRUE);

        $this->data['use_wysiwyg'] = ($this->data['board']['brd_use_wysiwyg'] == 'Y');
        $this->data['use_secret'] = ($this->member->is_login() && $this->data['board']['brd_use_secret'] == 'Y');
        $this->data['use_notice'] = TRUE;
        $this->data['use_category'] = (($this->data['board']['brd_use_category'] == 'Y') && (count($this->data['board']['category']) > 0));
        $this->data['use_attach'] = ($this->data['board']['brd_use_attach'] == 'Y');
    }

    /**
     * 게시판 글 목록
     */
    function posts($brd_key)
    {
        $this->board_common($brd_key);

        $this->data['list'] = $this->board_model->post_list($this->data['board'], $this->param);

        $paging['page'] = $this->param['page'];
        $paging['page_rows'] = 20;
        $paging['total_rows'] = $this->data['list']['total_count'];
        $this->load->library('paging', $paging);
        $this->data['pagination'] = $this->paging->create();

        $this->active = "board/" . $brd_key;
        $this->theme = "admin";
        $this->view = "board/posts";
    }


    function read($brd_key, $post_idx="")
    {
        $this->board_common($brd_key);

        $this->data['view'] = $this->board_model->get_post($brd_key, $post_idx, FALSE);

        $this->active = "board/" . $brd_key;
        $this->theme = "admin";
        $this->view = "board/read";
    }

    function write($brd_key, $post_idx="")
    {
        $this->load->library('form_validation');
        $this->board_common($brd_key);

        $this->form_validation->set_rules('post_title', langs('게시판/form/post_title') ,'required|trim');
        $this->form_validation->set_rules('post_content', langs('게시판/form/post_content'),'required|trim');

        if( $this->form_validation->run()  != FALSE)
        {
            $this->load->library('upload');

            // 받아온 값을 정리한다.
            $data['post_title'] = $this->input->post('post_title', TRUE);
            $data['bca_idx'] = (int) $this->input->post('bca_idx', TRUE);
            $data['post_parent'] = $this->input->post('post_parent', TRUE, 0);
            $data['post_secret'] = $this->input->post('post_secret', TRUE, 'N') == 'Y' ? "Y":'N';
            $data['post_content'] = $this->input->post('post_content', FALSE);
            $data['brd_key'] = $brd_key;
            $data['post_modtime'] = date('Y-m-d H:i:s');
            $data['post_html'] = $this->data['use_wysiwyg'] ? 'Y' : 'N';
            $data['post_notice'] = $this->input->post('post_notice', TRUE) == 'Y' ? 'Y' : 'N';
            $data['post_ip'] = ip2long( $this->input->ip_address() );
            $data['post_mobile'] = $this->site->viewmode == DEVICE_MOBILE ? 'Y' : 'N';
            $data['post_keywords'] = $this->input->post('post_keywords', TRUE);
            for($i=1; $i<=9; $i++)
            {
                $data['post_ext'.$i] = $this->input->post('post_ext'.$i, TRUE,'');
            }

            $parent = array();
            if(! empty( $data['post_parent'] ) )
            {
                $parent = $this->board_model->get_post($brd_key, $data['post_parent'], FALSE);
            }

            // 게시판 설정을 이용해서 값 정리
            if( $this->data['board']['brd_use_secret'] == 'N' ) $data['post_secret'] = 'N';
            else if ( $this->data['board']['brd_use_secret'] == 'A' ) $data['post_secret'] = 'Y';
            // 답글인경우 원글이 비밀글이면 답글도 비밀글
            else if ( ! empty($data['post_parent']) && $parent['post_secret'] == 'Y' ) $data['post_secret'] = 'Y';

            // 파일 업로드가 있다면
            if( isset($_FILES) && isset($_FILES['userfile']) && count($_FILES['userfile']) > 0 )
            {
                $dir_path = DIR_UPLOAD . "/board/{$brd_key}/".date('Y')."/".date('m');
                make_dir($dir_path,FALSE);

                $upload_config['upload_path'] = "./".$dir_path;
                $upload_config['file_ext_tolower'] = TRUE;
                $upload_config['allowed_types'] = FILE_UPLOAD_ALLOW;
                $upload_config['encrypt_name'] = TRUE;

                $this->load->library("upload", $upload_config);
                $this->data['upload_array'] = array();

                // FOR문으로 업로드하기 위해 돌리기
                $files = NULL;
                foreach ($_FILES['userfile'] as $key => $value) {
                    foreach ($value as $noKey => $noValue) {
                        $files[$noKey][$key] = $noValue;
                    }
                }
                unset($_FILES);

                // FOR 문 돌면서 정리
                foreach ($files as $file) {
                    $_FILES['userfile'] = $file;
                    $this->upload->initialize($upload_config);
                    if( ! isset($_FILES['userfile']['tmp_name']) OR ! $_FILES['userfile']['tmp_name']) continue;
                    if (! $this->upload->do_upload('userfile') )
                    {
                        alert('파일 업로드에 실패하였습니다.\\n'.$this->upload->display_errors(' ',' '));
                        exit;
                    }
                    else
                    {
                        $filedata = $this->upload->data();
                        $this->data['upload_array'][] = array(
                            "brd_key" => $brd_key,
                            "att_origin" => $filedata['orig_name'],
                            "att_filename" => $dir_path . "/" . $filedata['file_name'],
                            "att_caption" => $filedata['orig_name'],
                            "att_downloads" => 0,
                            "att_filesize" => $filedata['file_size'] * 1024,
                            "att_image_width" => $filedata['image_width'] ? $filedata['image_width'] : 0,
                            "att_image_height" => $filedata['image_height'] ? $filedata['image_height'] : 0,
                            "att_ext" => $filedata['file_ext'],
                            "att_is_image" => ($filedata['is_image'] == 1) ? 'Y' : 'N',
                            "att_regtime" => date('Y-m-d H:i:s')
                        );
                    }
                }
            }

            // 첨부파일 삭제가 있다면 삭제한다.
            $del_file = $this->input->post("del_file", TRUE);
            if( $del_file && count($del_file) > 0 )
            {
                foreach($del_file as $att_idx) {
                    $this->board_model->attach_remove($att_idx);
                }
            }

            // 수정이냐 신규냐에 따라 값 설정
            if( empty($post_idx) )
            {
                $data['mem_userid'] = $this->member->info('userid');
                $data['mem_nickname'] = $this->member->info('nickname');
                $data['mem_password'] = $this->member->info('password');

                $data['post_regtime'] = date('Y-m-d H:i:s');
                $data['post_status'] = 'Y';
                $data['post_count_comment'] = 0;
                $data['post_hit'] = 0;

                // 답글인경우
                if(! empty($data['post_parent']))
                {
                    if( strlen($parent['post_reply']) >= 10 )
                    {
                        alert('더 이상 답변하실 수 없습니다.\\n답변은 10단계 까지만 가능합니다.');
                        exit;
                    }

                    $reply_len = strlen($parent['post_reply']) + 1;

                    $begin_reply_char = 'A';
                    $end_reply_char = 'Z';
                    $reply_number = +1;

                    $reply_char = "";

                    $this->db->select("MAX(SUBSTRING(post_reply, {$reply_len}, 1)) AS reply")->from('board_post')->where('post_num', $parent['post_num'])->where('brd_key', $brd_key)->where("SUBSTRING(post_reply, {$reply_len}, 1) <>", '');
                    if($parent['post_reply']) $this->db->like('post_reply', $parent['post_reply'],'after');
                    $row = $this->db->get()->row_array();

                    if(! $row['reply']) {
                        $reply_char = $begin_reply_char;
                    }
                    else if ($row['reply'] == $end_reply_char) {
                        alert("더 이상 답변하실 수 없습니다.\\n답변은 26개 까지만 가능합니다.");
                        exit;
                    }
                    else {
                        $reply_char = chr(ord($row['reply']) + $reply_number);
                    }

                    $data['post_reply'] = $parent['post_reply'] . $reply_char;

                    // 답변의 원글이 비밀글이라면, 비밀번호는 원글과 동일하게 넣는다.
                    if( $parent['post_secret'] == 'Y' ) {
                        $data['mem_password'] = $parent['mem_password'];
                    }

                    $data['post_num'] = $parent['post_num'];
                }
                else {
                    $tmp  = (int)$this->db->select_max('post_num','max')->from('board_post')->where('brd_key',$brd_key)->get()->row(0)->max;
                    $data['post_reply'] = "";
                    $data['post_num'] = $tmp+1;
                }

                if(! $this->db->insert('board_post', $data) )
                {
                    alert(langs('게시판/msg/write_failed'));
                    exit;
                }

                $post_idx = $this->db->insert_id();
            }
            else {
                $this->db->where('brd_key', $brd_key);
                $this->db->where('post_idx', $post_idx);

                if(! $this->db->update('board_post', $data))
                {
                    alert(langs('게시판/msg/write_failed'));
                    exit;
                }

            }

            // 업로드된 데이타가 있을경우에 DB에 기록
            if(isset($this->data['upload_array']) && count($this->data['upload_array']) >0 )
            {
                foreach($this->data['upload_array'] as &$arr) {
                    $arr['post_idx'] = $post_idx;
                }
                $this->db->insert_batch("board_attach", $this->data['upload_array']);
            }

            alert(langs('게시판/msg/write_success'), base_url("admin/board/read/{$brd_key}/{$post_idx}"));
            exit;
        }
        else {
            // 수정일경우를 대비해서 글 고유 pk 넘김
            $this->data['post_idx'] = (int)$post_idx;
            $this->data['post_parent'] = $this->input->get('post_parent', TRUE);
            $this->data['view'] = empty($post_idx) ? array() : $this->board_model->get_post($brd_key, $post_idx, FALSE);
            $this->data['parent'] = empty($this->data['post_parent']) ? array() : $this->board_model->get_post($brd_key, $this->data['post_parent'], FALSE);

            if( $this->data['post_idx'] && (! $this->data['view'] OR ! isset($this->data['view']['post_idx']) OR !$this->data['view']['post_idx'] ) )
            {
                alert('잘못된 접근입니다.');
                exit;
            }

            $hidden = array();
            // 답글작성일경우 부모 번호 넘겨주기
            if($this->data['post_parent']) {
                $hidden['post_parent'] = $this->data['post_parent'];
                $this->data['view']['post_title'] = "RE : ". $this->data['parent']['post_title'];
            }

            $write_url = base_url("admin/board/write/{$brd_key}" . ($post_idx ? '/'.$post_idx : ''), SSL_VERFIY ? "https":'http');

            $this->data['form_open'] = form_open_multipart($write_url, array("autocomplete"=>"off"), $hidden);
            $this->data['form_close'] = form_close();

            // 레이아웃 & 뷰파일 설정
            $this->active = "board/".$brd_key;
            $this->theme = "admin";
            $this->view     = "board/write";
        }
    }
}
