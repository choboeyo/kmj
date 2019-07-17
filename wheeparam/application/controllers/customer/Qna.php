<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * Q&A  페이지
 */
class Qna extends WB_Controller
{
    function index($qna_idx="")
    {
        $page = (int)$this->input->get('page', TRUE, 1) > 1 ? (int)$this->input->get('page', TRUE, 1) : 1;
        $page_rows = (int)$this->input->get('page_rows', TRUE, 15);
        $start = ($page - 1) * $page_rows;

        $sc = $this->input->get('sc', TRUE);
        $st = $this->input->get('st', TRUE);

        if(! empty($sc) && !empty($st)) {
            $st = explode(" ", $st);
            if(count($st) > 0) {
                foreach($st as $stxt) {
                    $this->db->like("qna_".$sc, $stxt);
                }
            }
        }

        // Q&A 목록 가져오기
        $this->db
            ->select("SQL_CALC_FOUND_ROWS Q.*, QC.qnc_title", FALSE)
            ->from('qna AS Q')
            ->join('qna_category AS QC','QC.qnc_idx=Q.qnc_idx','left')
            ->where('qna_status', 'Y')
            ->limit($page_rows, $start);

        $result = $this->db->get();
        $this->data['lists'] = $result->result_array();
        $this->data['total_count'] = (int)$this->db->query("SELECT FOUND_ROWS() AS cnt")->row(0)->cnt;

        foreach($this->data['lists'] as $i=>&$row)
        {
            $row['nums'] = $this->data['total_count'] - $i - $start;
            $row['is_answered'] = $row['qna_ans_status'] == 'Y';
        }

        // 페이지네이션 세팅
        $paging['page'] = $page;
        $paging['page_rows'] = $page_rows;
        $paging['total_rows'] = $this->data['total_count'];

        $this->load->library('paging', $paging);
        $this->data['pagination'] = $this->paging->create();

        $this->theme = $this->site->get_layout();
        $this->view = "customer/qna";
    }

    function write()
    {
        $this->load->library('form_validation');

        $this->form_validation->set_rules('qna_title', '제목', 'required|trim');

        if( $this->form_validation->run() != FALSE )
        {
            $data['qnc_idx'] = $this->input->post('qnc_idx', TRUE, 0);
            $data['qna_title'] = trim($this->input->post('qna_title', TRUE,''));
            if( $this->member->is_login() )
            {
                $data['qna_name'] = $this->member->info('nickname');
                $data['qna_password'] = $this->member->info('password');
            }
            else {
                $data['qna_name'] = trim($this->input->post('qna_name', TRUE,''));
                $data['qna_password'] = get_password_hash($data['qna_password']);
            }
            $data['qna_phone'] = trim($this->input->post('qna_phone', TRUE,''));
            $data['qna_email'] = trim($this->input->post('qna_email', TRUE,''));
            $data['qna_content'] = trim($this->input->post('qna_content', TRUE,''));
            $data['upd_user'] = $data['reg_user'] = $this->member->is_login();
            $data['upd_datetime'] = $data['reg_datetime'] = date('Y-m-d H:i:s');

            $data['qna_ans_status'] = 'N';
            $data['qna_ans_user'] = 0;
            $data['qna_ans_datetime'] = '0000-00-00 00:00:00';
            $data['qna_ans_upd_user'] = 0;
            $data['qna_ans_upd_datetime'] ='0000-00-00 00:00:00';
            $data['qna_ans_content'] = '';

            $upload_array = array();

            if( isset($_FILES) && isset($_FILES['userfile']) && count($_FILES['userfile']) > 0 )
            {
                $dir_path = DIR_UPLOAD . "/qna/".date('Y')."/".date('m');
                make_dir($dir_path,FALSE);

                $upload_config['upload_path'] = "./".$dir_path;
                $upload_config['file_ext_tolower'] = TRUE;
                $upload_config['allowed_types'] = FILE_UPLOAD_ALLOW;
                $upload_config['encrypt_name'] = TRUE;

                $this->load->library("upload", $upload_config);

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
                        $upload_array[] = array(
                            "att_target_type" => 'QNA',
                            "att_origin" => $filedata['orig_name'],
                            "att_filepath" => $dir_path . "/" . $filedata['file_name'],
                            "att_downloads" => 0,
                            "att_filesize" => $filedata['file_size'] * 1024,
                            "att_width" => $filedata['image_width'] ? $filedata['image_width'] : 0,
                            "att_height" => $filedata['image_height'] ? $filedata['image_height'] : 0,
                            "att_ext" => $filedata['file_ext'],
                            "att_is_image" => ($filedata['is_image'] == 1) ? 'Y' : 'N',
                            "reg_user" => $this->member->is_login(),
                            "reg_datetime" => date('Y-m-d H:i:s')
                        );
                    }
                }
            }

            $this->db->insert('qna', $data);
            $qna_idx = $this->db->insert_id();

            if( count($upload_array) > 0 )
            {
                foreach($upload_array as &$arr) {
                    $arr['att_target'] = $qna_idx;
                }

                $this->db->insert_batch('attach', $upload_array);
            }

            alert('등록이 완료되었습니다.', base_url('customer/qna'));
        }
        else
        {
            $this->data['qna_category'] = $this->db->where('qnc_status','Y')->order_by('sort')->get('qna_category')->result_array();

            $this->theme = $this->site->get_layout();
            $this->view = "customer/qna";
        }
    }

    function view($qna_idx) {

    }
}