<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require APPPATH . '/libraries/REST_Controller.php';
/**************************************************************
 * Products REST API
 *
 * @property Products_model $products_model
 *************************************************************/
class Products extends REST_Controller
{
    function __construct()
    {
        parent::__construct();

        if (!$this->input->is_ajax_request()) $this->response(array("result" => FALSE, "message" => langs('공통/msg/invalid_access')), 400);

        $this->load->model('products_model');
    }

    /**
     * 상품 카테고리 가져오기
     */
    function categories_get()
    {
        $list = $this->products_model->getCategoryList(TRUE);

        $this->response($list, 200);
    }

    /**
     * 상품 한개의 정보 가져오기
     */
    function items_get($prd_idx)
    {
        $data = $this->products_model->getItem($prd_idx);

        $this->response($data, 200);
    }

    /**
     * 리뷰 삭제
     * @param $prd_idx
     * @param $rev_idx
     */
    function reviews_delete($prd_idx, $rev_idx)
    {
        $mem_idx = $this->member->is_login();

        if(! $view = $this->db
            ->where('rev_idx', $rev_idx)
            ->where('prd_idx', $prd_idx)
            ->get('products_review')
            ->row_array())
        {
            $this->response(["message"=>"삭제하려는 리뷰를 찾을수 없습니다. 이미 삭제되었을 수 있습니다."], 400);
            exit;
        }

        if($view['mem_idx'] != $mem_idx) {
            $this->response(["message"=>"해당 리뷰를 삭제할 권한이 없습니다."], 400);
            exit;
        }

        if(! $this->db
            ->where('rev_idx', $rev_idx)
            ->set('rev_status','N')
            ->update('products_review'))
        {
            $this->response(["message"=>"리뷰 삭제에 실패하였습니다."], 500);
            exit;
        }
        else {
            $this->response(["message"=>"SUCCESS"], 200);
        }

    }

    /**
     * 리뷰 등록
     */
    function reviews_post()
    {
        $rev_idx = $this->post('rev_idx', TRUE);

        $data['prd_idx'] = $this->post('prd_idx', TRUE) ?? 0;
        $data['od_id'] = $this->post('od_id', TRUE) ?? 0;
        $data['rev_score'] = $this->post('rev_score', TRUE) ?? 0;
        $data['rev_content'] = trim($this->post('rev_content', TRUE)) ?? '';

        if($data['prd_idx'] < 0) {
            $this->response(["message"=>"올바른 경로로 리뷰를 작성해주세요"], 400);
            exit;
        }

        if($data['od_id'] < 0) {
            $this->response(["message"=>"리뷰를 남길 주문번호가 선택되지 않았습니다."], 400);
        }

        if($data['rev_score'] <=0) {
            $this->response(["message"=>"리뷰평점을 선택해주세요"], 400);
        }

        $data['upd_user'] = $data['mem_idx'];
        $data['upd_datetime'] = date('Y-m-d H:i:s');

        if(empty($rev_idx))
        {
            $data['mem_idx'] = $this->member->is_login();

            if(!$data['mem_idx']) {
                $this->response(["message"=>"리뷰는 회원만 작성할 수 있습니다."], 400);
            }

            $data['rev_status'] = 'Y';
            $data['reg_user'] = $data['mem_idx'];
            $data['reg_datetime'] = date('Y-m-d H:i:s');

            if(! $this->db->insert('products_review', $data)) {
                $this->response(["message"=>"상품 리뷰 저장도중 오류가 발생하였습니다."], 500);
            }

            $rev_idx = $this->db->insert_id();
        }
        else {
            if(! $this->db->where('rev_idx', $rev_idx)->update('products_review', $data)) {
                $this->response(["message"=>"상품 리뷰 저장도중 오류가 발생하였습니다."], 500);
            }
        }

        $images = $this->post('images', TRUE);

        if($images && count($images) > 0) {
            $this->db->where_in('att_idx', $images)->set('att_target', $rev_idx)->update('attach');
        }

        // 해당상품의 리뷰점수 업데이트
        $this->products_model->reviewScoreUpdate($data['prd_idx']);
    }

    /**
     * 리뷰 등록
     */
    function qna_post()
    {
        $data['prd_idx'] = $this->post('prd_idx', TRUE) ?? 0;
        $data['qa_content'] = trim($this->post('qa_content', TRUE)) ?? '';
        $data['mem_idx'] = $this->member->is_login();
        $data['qa_secret'] = $this->post('qa_secret', TRUE) === 'Y' ? 'Y' : 'N';

        if($data['prd_idx'] < 0) {
            $this->response(["message"=>"올바른 경로로 상품문의를 작성해주세요"], 400);
            exit;
        }

        if(!$data['mem_idx']) {
            $this->response(["message"=>"상품문의는 회원만 작성할 수 있습니다."], 400);
        }

        $data['reg_datetime'] = date('Y-m-d H:i:s');
        $data['qa_status'] = 'Y';

        if(! $this->db->insert('products_qa', $data)) {
            $this->response(["message"=>"문의 작성중 오류가 발생하였습니다."], 500);
        }
    }

    /**
     * 리뷰 이미지 업로드
     */
    function reviews_images_post()
    {
        $this->load->library('upload');
        make_dir(DIR_UPLOAD . DIRECTORY_SEPARATOR . "review_image");
        $upload_path =  DIR_UPLOAD . '/review_image/' . date('Y') . '/' . date('m') . '/';

        $uploadconfig = array(
            'upload_path' => "./". $upload_path,
            'allowed_types' => 'jpg|jpeg|png|gif',
            'max_size' => 20 * 1024,
            'encrypt_name' => true,
        );

        if (isset($_FILES) && isset($_FILES['userfile']) && isset($_FILES['userfile']['name']) )
        {
            $this->upload->initialize($uploadconfig);

            if ($this->upload->do_upload()) {

                $filedata = $this->upload->data();

                $upload_data = [
                    "att_target_type" => 'PRODUCTS_REVIEW',
                    "att_target" => '',
                    "att_origin" => $filedata['orig_name'],
                    "att_filepath" => $upload_path . $filedata['file_name'],
                    "att_downloads" => 0,
                    "att_filesize" => $filedata['file_size'] * 1024,
                    "att_width" => $filedata['image_width'] ? $filedata['image_width'] : 0,
                    "att_height" => $filedata['image_height'] ? $filedata['image_height'] : 0,
                    "att_ext" => $filedata['file_ext'],
                    "att_is_image" => ($filedata['is_image'] == 1) ? 'Y' : 'N',
                    "reg_user" => $this->member->is_login(),
                    "reg_datetime" => date('Y-m-d H:i:s')
                ];
                if(! $this->db->insert('attach', $upload_data))
                {
                    @unlink(FCPATH . $upload_path.$filedata['file_name']);
                    $this->response(["message"=>"이미지 등록도중 오류가 발생하였습니다."], 400);
                }
                $att_idx = $this->db->insert_id();
                $file_path = base_url($upload_path.$filedata['file_name']);
                $this->response(["att_idx"=>$att_idx, "file_path"=>$file_path]);
            }
            else {
                $this->response(["message"=>"이미지 업로드 도중 오류가 발생하였습니다 :". $this->upload->display_errors(' ',' ')], 400);
            }
        }

    }

    /**
     * 상품 찜하기 토글
     */
    function wish_post()
    {
        $prd_idx = $this->post('prd_idx', TRUE);
        $mem_idx = $this->member->is_login();

        if(empty($prd_idx)) {
            $this->response(["message"=>"상품 정보가 올바르게 전달되지 않았습니다."], 400);
        }
        if(! $mem_idx) {
            $this->response(["message"=>"회원만 사용가능한 기능입니다."], 400);
        }

        $cnt =(int) $this->db
            ->select('COUNT(*) AS cnt')
            ->where('prd_idx', $prd_idx)
            ->where('mem_idx',$mem_idx)
            ->from('products_wish')
            ->get()
            ->row(0)
            ->cnt;

        if($cnt > 0) {
            $this->db->where('prd_idx', $prd_idx)->where('mem_idx',$mem_idx)->delete('products_wish');
        }
        else {
            $this->db->insert('products_wish', [
               "prd_idx" => $prd_idx,
               "mem_idx"=> $mem_idx
            ]);
        }

        $this->products_model->wishCountUpdate($prd_idx);
    }

    /**
     * 등록된 상품문의 삭제
     */
    function qna_delete($qa_idx)
    {
        $view = $this->db
            ->where('qa_idx', $qa_idx)
            ->get('products_qa')
            ->row_array();

        if(! $view) {
            $this->response(["message"=> "삭제하시려는 상품문의가 이미 삭제되었거나, 존재하지않습니다."], 400);
            exit;
        }

        $mem_idx = $this->member->is_login();

        if($view['mem_idx'] != $mem_idx) {
            $this->response(["message"=> "해당 문의를 삭제할 권한이 없습니다."], 400);
            exit;
        }

        if($view['qa_is_answer'] != 'N') {
            $this->response(["message"=> "답변이 달린 문의는 삭제할 수 없습니다."], 400);
            exit;
        }

        if(! $this->db
            ->where('qa_idx', $qa_idx)
            ->set('qa_status','N')
            ->update('products_qa'))
        {
            $this->response(["message"=> "처리도중 오류가 발생하였습니다."], 500);
            exit;
        } else {
            $this->response(["message"=>"SUCCESS"],200);
        }
    }
}