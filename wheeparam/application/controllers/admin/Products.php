<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * 상품 관련 메뉴
 *
 * @property Products_model $products_model
 */
class Products extends WB_Controller {

    public function __construct()
    {
        parent::__construct();

        $this->theme = "admin";
        
        $this->load->model('products_model');
    }

    /**
     * 상품 리뷰관리
     */
    public function reviews()
    {
        $this->load->model('shop_model');
        $this->data['page'] = $this->input->get('page', TRUE, 1);
        $this->data['page_rows'] = 5;
        $this->data['load_images'] = TRUE;
        $this->data['view_hidden'] = TRUE;

        $result = $this->shop_model->getProductReviews($this->data);
        $this->data['list'] = $result['list'];
        $this->data['totalCount'] = $result['totalCount'];

        // 페이지네이션 세팅
        $paging['page'] = $this->data['page'];
        $paging['page_rows'] = $this->data['page_rows'];
        $paging['total_rows'] = $this->data['totalCount'];

        $this->load->library('paging', $paging);
        $this->data['pagination'] = $this->paging->create();

        $this->active = $this->view = "products/reviews";
    }
    
    /**
     * 상품 문의관리
     */
    public function qna()
    {
        $this->load->model('shop_model');
        $this->data['page'] = $this->input->get('page', TRUE, 1);
        $this->data['page_rows'] = 5;
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

        $this->active = $this->view = "products/qna";
    }


    public function qna_form($qa_idx="")
    {
        $this->load->library('form_validation');

        $this->form_validation->set_rules("qa_a_content", '답변내용', 'required|trim');

        if($this->form_validation->run() != FALSE)
        {
            $data['qa_is_answer'] = 'Y';
            $data['qa_a_content'] = $this->input->post('qa_a_content', TRUE, '');
            $data['qa_a_mem_idx'] = $this->member->is_login();
            $data['qa_a_datetime'] = date('Y-m-d H:i:s');
            $data['qa_secret'] = $this->input->post('qa_secret', TRUE) === 'Y' ? 'Y' : 'N';

            $this->db->where('qa_idx', $qa_idx)->update('products_qa', $data);

            alert('답변 작성이 완료되었습니다.', base_url('admin/products/qna'));
        }
        else {
            if(! $this->data['view'] = $this->db
                ->select('QA.*, P.prd_name, PCL.parent_names, PCL.cat_title, M.mem_nickname AS nickname, M2.mem_nickname AS a_nickname')
                ->from('products_qa AS QA')
                ->join('products AS P','P.prd_idx=QA.prd_idx','left')
                ->join('products_category_list AS PCL','PCL.cat_id=P.cat_id','left')
                ->join('member AS M','M.mem_idx=QA.mem_idx','left')
                ->join('member AS M2','M.mem_idx=QA.qa_a_mem_idx','left')
                ->where('qa_idx', $qa_idx)
                ->get()
                ->row_array()) {
                alert('존재하지 않는 상품문의이거나 이미 삭제된 상품 문의 입니다.');
                exit;
            }
            $this->active =  "products/qna";
            $this->view ="products/qna_form";
        }
    }
    
    /*
     * 상품 분류 관리
     */
    public function categories()
    {
        $this->data['categoryList'] = $this->products_model->getCategoryList(TRUE);
        $this->active = $this->view = "products/categories";
    }

    /**
     * 상품 분류 등록/수정 폼
     * @param string $parent_id
     * @param string $id
     */
    public function categories_form($parent_id ="", $id = "")
    {
        // 부모 카테고리 정보를 가져온다.
        $this->data['parent'] = [];

        if(! empty($parent_id)) {
            if(! $this->data['parent'] = $this->db->where('cat_id', $parent_id)->get('products_category_list')->row_array() )
            {
                show_error('상위 분류정보를 가져올 수 없습니다.',400);
                exit;
            }
        }

        $this->data['view'] = [];

        if(! empty($id)) {
            if(! $this->data['view'] = $this->db
                ->where('cat_id', $id)
                ->get('products_category_list')
                ->row_array() )
            {
                show_error('해당 분류정보를 가져올 수 없습니다.\\n이미 삭제되었거나 존재하지 않습니다.',400);
                exit;
            }
        }
        $this->data['categoryList'] = $this->products_model->getCategoryList(TRUE);


        // 스킨 디렉토리 가져오기
        $this->data['skin_list'] = get_skin_list('shop_list');

        $this->view = "products/categories_form";
        $this->theme_file = "blank";
    }

    /**
     * 상품 관리
     */
    public function items()
    {
        $this->products_model->cleanTempItem();

        // 상품 분류 목록을 가져온다.
        $this->data['categoryList'] = $this->products_model->getCategoryList(TRUE);

        // 페이징 관련 매개변수 정리
        $this->data['page'] = $this->input->get('page', TRUE, 1);
        $this->data['pageRows'] = 15;

        // 필터관련 값 정리
        $this->data['prd_sell_status'] = $this->input->get('prd_sell_status', TRUE, ['Y','O','D']);
        $this->data['prd_status'] = $this->input->get('prd_status', TRUE, ['Y','H']);
        $this->data['cat_id'] = $this->input->get('cat_id', TRUE, '');
        $this->data['scol'] = $this->input->get('scol', TRUE, '');
        $this->data['stxt'] = $this->input->get('stxt', TRUE, '');

        $this->db
            ->select('SQL_CALC_FOUND_ROWS P.*, IFNULL(A.att_filepath, "") AS prd_thumbnail_path, PCL.parent_names, PCL.cat_title', FALSE)
            ->from('products AS P')
            ->join('products_category_list AS PCL','PCL.cat_id=P.cat_id','left')
            ->join('attach AS A','A.att_idx=P.prd_thumbnail', 'left')
            ->order_by('P.prd_sort ASC, P.prd_idx DESC')
            ->limit($this->data['pageRows'], ($this->data['page'] - 1) * $this->data['pageRows']);

        if(! empty($this->data['prd_status'])) {
            $this->db->where_in('P.prd_status', $this->data['prd_status']);
        }
        if(! empty($this->data['prd_sell_status'])) {
            $this->db->where_in('P.prd_sell_status', $this->data['prd_sell_status']);
        }
        if(! empty($this->data['cat_id']))
        {
            $this->db->where_in('P.cat_id', $this->data['cat_id']);
        }
        if(!empty($this->data['stxt'])) {
            $this->db->like('P.prd_name', $this->data['stxt']);
        }

        $result = $this->db->get();
        $this->data['list'] = $result->result_array();

        $this->data['totalCount'] = (int)$this->db->query("SELECT FOUND_ROWS() AS cnt")->row(0)->cnt;

        $paging['page'] = $this->data['page'];
        $paging['page_rows'] = $this->data['pageRows'];
        $paging['total_rows'] = $this->data['totalCount'];
        $this->load->library('paging', $paging);
        $this->data['pagination'] = $this->paging->create();

        $this->active = "products/items";
        $this->view = "products/items";
    }

    /**
     * 상품 진열장 관리
     * @return void
     */
    public function displays($dsp_idx="")
    {
        // 진열장 목록을 가져온다.
        $this->data['list'] = $this->db
            ->from('products_display')
            ->where('dsp_status','Y')
            ->order_by('dsp_title ASC')
            ->get()
            ->result_array();

        $this->data['dsp_idx'] = $dsp_idx;

        // 진열장 PK가 넘어온 경우 진열장에 포함된 아이템 목록도 가져온다.
        if(! empty($dsp_idx)) {

            if(! $this->data['display_info'] = $this->db->where('dsp_idx', $dsp_idx)->where('dsp_status','Y')->get('products_display')->row_array())
            {
                alert('삭제된 진열장이거나, 존재하지 않는 진열장입니다.');
            }

            $this->data['items_list'] = $this->db
                ->select('DI.*, P.prd_name, IFNULL(PA.att_filepath,"") AS thumbnail')
                ->select('CONCAT(PL.parent_names," ",PL.cat_title) AS category_name')
                ->from('products_display_items AS DI')
                ->join('products AS P','P.prd_idx=DI.prd_idx', 'left')
                ->join('attach AS PA','PA.att_idx=P.prd_thumbnail','left')
                ->join('products_category_list AS PL','PL.cat_id=P.cat_id', 'left')
                ->where('DI.dsp_idx', $dsp_idx)
                ->where_in('P.prd_status',['Y','H'])
                ->order_by('DI.dspi_sort ASC')
                ->get()
                ->result_array();
        }

        $this->active = "products/displays";
        $this->view = "products/displays";
    }

    public function displays_form($dsp_idx="")
    {
        $this->load->library('form_validation');
        $this->form_validation->set_rules('dsp_key',  '진열장 고유키', 'required|trim');
        $this->form_validation->set_rules('dsp_title',  '진열장 이름', 'required|trim');

        if($this->form_validation->run() != FALSE)
        {
            $dsp_key = $this->input->post('dsp_key', TRUE,'');
            $data['dsp_title'] = trim($this->input->post('dsp_title', TRUE, ''));
            $data['dsp_skin'] =  trim($this->input->post('dsp_skin', TRUE, ''));
            $data['dsp_skin_m'] =  trim($this->input->post('dsp_skin_m', TRUE, ''));
            $data['upd_user'] = $this->member->is_login();
            $data['upd_datetime'] = date('Y-m-d H:i:s');

            if(empty($dsp_idx)) {
                $data['dsp_key'] = $dsp_key;
                $data['reg_user'] = $data['upd_user'];
                $data['reg_datetime'] = $data['upd_datetime'];

                // 중복되는 키가 있는지 확인한다.
                $cnt = (int)$this->db->select('COUNT(*) AS cnt')->where('dsp_key', $dsp_key)->get('products_display')->row(0)->cnt;

                if($cnt > 0) {
                    alert('이미 사용중인 고유 KEY 입니다');
                    return;
                }

                if( $this->db->insert('products_display', $data))
                {
                    alert_modal_close('신규 진열장이 등록되었습니다.');
                }
            }
            else {
                if($this->db->where('dsp_idx', $dsp_idx)->update('products_display', $data))
                {
                    alert_modal_close('진열장 정보가 수정되었습니다.');
                }
            }

            alert('데이터베이스에 진열장 정보를 저장하던중 오류가 발생하였습니다.');
        }
        else
        {
            // 스킨 디렉토리 가져오기
            $this->data['skin_list'] = get_skin_list('shop_list');

            $this->data['view'] = [];
            if(! empty($dsp_idx)) {
                if(! $this->data['view'] = $this->db->where('dsp_idx', $dsp_idx)->get('products_display')->row_array())
                {
                    alert_modal_close('존재하지 않거나 삭제된 진열장입니다.');
                }
            }

            $this->theme_file = "iframe";
            $this->view = "products/displays_form";
        }
    }

    public function displays_item_add($dsp_idx="")
    {
        // 상품 분류 목록을 가져온다.
        $this->data['categoryList'] = $this->products_model->getCategoryList(TRUE);

        $this->data['page'] = $this->input->get('page', TRUE, 1);
        $this->data['pageRows'] = 10;
        $this->data['stxt'] = $this->input->get('stxt', TRUE, '');
        $this->data['prd_sell_status'] = $this->input->get('prd_sell_status', TRUE, ['Y','O','D']);
        $this->data['prd_status'] = $this->input->get('prd_status', TRUE, ['Y','H']);
        $this->data['cat_id'] = $this->input->get('cat_id', TRUE, '');

        $this->db
            ->select('SQL_CALC_FOUND_ROWS P.*, IFNULL(A.att_filepath, "") AS prd_thumbnail_path, PCL.parent_names, PCL.cat_title', FALSE)
            ->from('products AS P')
            ->join('products_category_list AS PCL','PCL.cat_id=P.cat_id','left')
            ->join('products_display_items AS PDI',"PDI.dsp_idx='{$dsp_idx}' AND PDI.prd_idx=P.prd_idx", 'left')
            ->join('attach AS A','A.att_idx=P.prd_thumbnail', 'left')
            ->where('PDI.dsp_idx IS NULL',NULL, FALSE)
            ->order_by('P.prd_sort ASC, P.prd_idx DESC')
            ->limit($this->data['pageRows'], ($this->data['page'] - 1) * $this->data['pageRows']);

        if(! empty($this->data['prd_status'])) {
            $this->db->where_in('P.prd_status', $this->data['prd_status']);
        }
        if(! empty($this->data['prd_sell_status'])) {
            $this->db->where_in('P.prd_sell_status', $this->data['prd_sell_status']);
        }
        if(! empty($this->data['cat_id']))
        {
            $this->db->where_in('P.cat_id', $this->data['cat_id']);
        }
        if(!empty($this->data['stxt'])) {
            $this->db->like('P.prd_name', $this->data['stxt']);
        }

        $result = $this->db->get();
        $this->data['list'] = $result->result_array();

        $this->data['totalCount'] = (int)$this->db->query("SELECT FOUND_ROWS() AS cnt")->row(0)->cnt;

        $this->data['dsp_idx'] = $dsp_idx;

        $paging['page'] = $this->data['page'];
        $paging['page_rows'] = $this->data['pageRows'];
        $paging['total_rows'] = $this->data['totalCount'];
        $this->load->library('paging', $paging);
        $this->data['pagination'] = $this->paging->create();

        $this->theme_file = "iframe";
        $this->view = "products/displays_item_add";
    }

    /**
     * 상품 복사
     * @param string|int $original_idx 원본 상품 PK
     */
    public function items_copy_form($original_idx)
    {
        $this->load->library('form_validation');

        $this->form_validation->set_rules('prd_idx','상품코드', 'required|trim');
        $this->form_validation->set_rules('prd_name', '상품명' ,'required|trim');


        $this->data['original_prd_idx'] = $original_idx;
        if(! $this->data['original'] = $this->products_model->getItem($original_idx))
        {
            alert_modal_close('복사하려는 상품의 원본이 삭제되었거나, 존재하지 않습니다');
            exit;
        }

        if($this->form_validation->run() !== FALSE)
        {
            $data['prd_idx'] = $this->input->post('prd_idx', TRUE, '');
            $data['prd_name'] = $this->input->post('prd_name', TRUE, '');

            // 이미 등록된 코드인지 확인한다.
            $count =(int) $this->db->select('COUNT(*) AS cnt')->from('products')->where('prd_idx', $data['prd_idx'])->get()->row(0)->cnt;
            if($count > 0) {
                alert('이미 등록된 상품 코드 입니다.');
                exit;
            }

            // 원본값 그대로 적용
            $data['prd_status'] = $this->data['original']['prd_status'];
            $data['prd_sell_status'] = $this->data['original']['prd_sell_status'];
            $data['cat_id'] = $this->data['original']['cat_id'];
            $data['prd_type'] = $this->data['original']['prd_type'];
            $data['prd_use_options'] = $this->data['original']['prd_use_options'];
            $data['prd_price'] = $this->data['original']['prd_price'];
            $data['prd_cust_price'] = $this->data['original']['prd_cust_price'];
            $data['prd_maker'] = $this->data['original']['prd_maker'];
            $data['prd_origin'] = $this->data['original']['prd_origin'];
            $data['prd_brand'] = $this->data['original']['prd_brand'];
            $data['prd_model'] = $this->data['original']['prd_model'];
            $data['prd_summary'] = $this->data['original']['prd_summary'];
            $data['prd_content'] = $this->data['original']['prd_content'];
            $data['prd_mobile_content'] = $this->data['original']['prd_mobile_content'];
            $data['prd_stock_qty'] = $this->data['original']['prd_stock_qty'];
            $data['prd_noti_qty'] = $this->data['original']['prd_noti_qty'];
            $data['prd_buy_min_qty'] = $this->data['original']['prd_buy_min_qty'];
            $data['prd_buy_max_qty'] = $this->data['original']['prd_buy_max_qty'];
            $data['prd_extra_info'] = json_encode($this->data['original']['prd_extra_info'], JSON_UNESCAPED_SLASHES|JSON_UNESCAPED_UNICODE|JSON_NUMERIC_CHECK);
            $data['prd_item_group'] = $this->data['original']['prd_item_group'];
            $data['prd_item_options'] = json_encode($this->data['original']['prd_item_options'], JSON_UNESCAPED_SLASHES|JSON_UNESCAPED_UNICODE|JSON_NUMERIC_CHECK);
            $data['prd_is_best'] = $this->data['original']['prd_is_best'];
            $data['prd_is_hit'] = $this->data['original']['prd_is_hit'];
            $data['prd_is_new'] = $this->data['original']['prd_is_new'];
            $data['prd_is_sale'] = $this->data['original']['prd_is_sale'];
            $data['prd_is_recommend'] = $this->data['original']['prd_is_recommend'];
            for($i=1; $i<=10; $i++) {
                $data['prd_extra_'.$i] = $this->data['original']['prd_extra_'.$i];
            }

            // 그외 값들
            $data['reg_user'] = $this->member->is_login();
            $data['reg_datetime'] = date('Y-m-d H:i:s');
            $data['upd_user'] = $data['reg_user'];
            $data['upd_datetime'] = $data['reg_datetime'];

            // 등록을 시작한다.
            $this->db->trans_begin();

            // 먼저 상품 옵션부분을 깨끗하게 지워준다.
            $this->db->where('prd_idx', $data['prd_idx'])->delete('products_options');

            // 상품 정보를 업데이트 한다.
            $this->db->insert('products', $data);

            // 상품 이미지들을 복사해준다.
            $dir_path = DIR_UPLOAD . "/products/{$data['prd_idx']}/";
            make_dir($dir_path,FALSE);
            $upload_config['upload_path'] = "./".$dir_path;
            $upload_config['file_ext_tolower'] = TRUE;
            $upload_config['allowed_types'] = FILE_UPLOAD_ALLOW;
            $upload_config['encrypt_name'] = TRUE;

            $this->load->library("upload", $upload_config);
            $this->load->helper('file');

            $image_list = $this->db->where('att_target_type',"PRODUCTS")->where('att_target', $original_idx)->get('attach')->result_array();
            foreach($image_list as $i=>$row)
            {
                if(is_file(FCPATH.$row['att_filepath']))
                {
                    $file_info = get_file_info(FCPATH.$row['att_filepath']);

                    @copy(FCPATH.$row['att_filepath'], FCPATH.DIRECTORY_SEPARATOR.$dir_path.$file_info['name']);

                    $this->db->insert("attach", [
                        "att_target_type" => "PRODUCTS",
                        "att_target" => $data['prd_idx'],
                        "att_sort" => $row['att_sort'],
                        "att_is_image" => $row['att_is_image'],
                        "att_origin" => $row['att_origin'],
                        "att_filepath" => $dir_path.$file_info['name'],
                        "att_ext" => $row['att_ext'],
                        "att_filesize" => $row['att_filesize'],
                        "att_width" => $row['att_width'],
                        "att_height" => $row['att_height'],
                        "reg_user" => $data['reg_user'],
                        "reg_datetime" => $data['reg_datetime']
                    ]);

                    if($i == 0) {
                        $this->db->where('prd_idx', $data['prd_idx'])->set('prd_thumbnail', $this->db->insert_id())->update('products');
                    }
                }
            }

            // 필수 선택 옵션을 INSERT 한다
            $options_array = [];
            if($data['prd_use_options'] === 'Y') {
                foreach($this->data['original']['options'] as $row)
                {
                    $options_array[] = [
                        "prd_idx" => $data['prd_idx'],
                        "opt_code" => $row['opt_code'],
                        "opt_subject" => $row['opt_subject'],
                        "opt_status" => $row['opt_status'],
                        "opt_type" => 'detail',
                        "opt_add_price" => $row['opt_add_price'],
                        "opt_stock_qty" => $row['opt_stock_qty'],
                        "opt_noti_qty" => $row['opt_noti_qty']
                    ];
                }
            }

            // 추가 선택 옵션을 INSERT 한다
            foreach($this->data['original']['options2'] as $row)
            {
                $options_array[] = [
                    "prd_idx" => $data['prd_idx'],
                    "opt_code" => $row['opt_code'],
                    "opt_subject" => $row['opt_subject'],
                    "opt_status" => $row['opt_status'],
                    "opt_type" => 'addition',
                    "opt_add_price" => $row['opt_add_price'],
                    "opt_stock_qty" => $row['opt_stock_qty'],
                    "opt_noti_qty" => $row['opt_noti_qty']
                ];
            }
            if(count($options_array) >0) {
                $this->db->insert_batch('products_options', $options_array);
            }

            $this->products_model->updateCategoryCount($data['cat_id']);

            if ($this->db->trans_status() === FALSE)
            {
                $this->db->trans_rollback();
                alert('상품 정보 입력에 실패하였습니다. 관리자에게 문의하세요');
                exit;
            }
            else
            {
                $this->db->trans_commit();

                $temp ='parent.location.href="' . base_url('admin/products/items_form/'.$data['prd_idx']) . '";';

                alert_modal_close('상품 복사가 완료되었습니다.', $temp);
                exit;
            }
        }
        else
        {
            $this->data['categoryList'] = $this->products_model->getCategoryList(TRUE);

            $this->theme_file = "iframe";
            $this->view = "products/items_copy_form";
        }
    }

    /**
     * 상품 추가
     */
    public function items_add_form()
    {
        $this->load->library('form_validation');

        $this->form_validation->set_rules('prd_idx','상품코드', 'required|trim');

        $this->data['categoryList'] = $this->products_model->getCategoryList(TRUE);

        if(count($this->data['categoryList']) === 0) {
            alert_modal_close('상품분류가 등록되어있지 않습니다. 상품메뉴를 먼저 등록하세요');
            exit;
        }

        if($this->form_validation->run() !== FALSE)
        {
            $data['prd_idx'] = $this->input->post('prd_idx', TRUE, '');

            // 이미 등록된 코드인지 확인한다.
            $count =(int) $this->db->select('COUNT(*) AS cnt')->from('products')->where('prd_idx', $data['prd_idx'])->get()->row(0)->cnt;
            if($count > 0) {
                alert('이미 등록된 상품 코드 입니다.');
                exit;
            }

            // Default 값이 필요한값들 넣기
            $data['prd_content'] = '';
            $data['prd_mobile_content'] = '';
            $data['reg_user'] = $this->member->is_login();
            $data['reg_datetime'] = date('Y-m-d H:i:s');
            $data['upd_user'] = $data['reg_user'];
            $data['upd_datetime'] = $data['reg_datetime'];
            $data['prd_status'] = 'T';
            $data['prd_extra_info'] = '{}';
            $data['prd_item_options'] = '[]';

            if(! $this->db->insert('products', $data)) {
                alert('상품 생성도중 오류가 발생하였습니다.');
                exit;
            }
            else {
                $temp ='parent.location.href="' . base_url('admin/products/items_form/'.$data['prd_idx']) . '";';
                alert_modal_close('상품 신규등록이 완료되었습니다.',$temp);
                exit;
            }
        }
        else
        {
            $this->theme_file = "iframe";
            $this->view = "products/items_add_form";
        }
    }

    /**
     * 상품 등록 / 수정 폼
     */
    public function items_form($prd_idx = "")
    {
        // 폼검증 라이브러리 불러오기
        $this->load->library('form_validation');

        $this->form_validation->set_rules('prd_name','상품명', 'required|trim');

        $this->data['product_id'] = $prd_idx;

        $this->site->add_js('/assets/js/vue' . ( IS_TEST ? '' :'.min') . '.js');
        $this->site->add_js('https://cdn.jsdelivr.net/npm/sortablejs@1.8.4/Sortable.min.js');
        $this->site->add_js('https://cdnjs.cloudflare.com/ajax/libs/Vue.Draggable/2.20.0/vuedraggable.umd.min.js');

        $this->active = "products/items_form";
        $this->view = "products/items_form";
    }

    /**
     * 상품 재고 관리
     */
    public function stocks()
    {
        // 상품 분류 목록을 가져온다.
        $this->data['categoryList'] = $this->products_model->getCategoryList(TRUE);

        // 페이징 관련 매개변수 정리
        $this->data['page'] = $this->input->get('page', TRUE, 1);
        $this->data['pageRows'] = 15;

        // 필터관련 값 정리
        $this->data['prd_sell_status'] = $this->input->get('prd_sell_status', TRUE, ['Y','O','D']);
        $this->data['prd_status'] = $this->input->get('prd_status', TRUE, ['Y','H']);
        $this->data['cat_id'] = $this->input->get('cat_id', TRUE, '');
        $this->data['scol'] = $this->input->get('scol', TRUE, '');
        $this->data['stxt'] = $this->input->get('stxt', TRUE, '');

        $this->db
            ->select('SQL_CALC_FOUND_ROWS P.*, IFNULL(A.att_filepath, "") AS prd_thumbnail_path, PCL.parent_names, PCL.cat_title', FALSE)
            ->from('products AS P')
            ->join('products_category_list AS PCL','PCL.cat_id=P.cat_id','left')
            ->join('attach AS A','A.att_idx=P.prd_thumbnail', 'left')
            ->where('prd_use_options', 'N')
            ->order_by('P.prd_sort ASC, P.prd_idx DESC')
            ->limit($this->data['pageRows'], ($this->data['page'] - 1) * $this->data['pageRows']);

        if(! empty($this->data['prd_status'])) {
            $this->db->where_in('P.prd_status', $this->data['prd_status']);
        }
        if(! empty($this->data['prd_sell_status'])) {
            $this->db->where_in('P.prd_sell_status', $this->data['prd_sell_status']);
        }
        if(! empty($this->data['cat_id']))
        {
            $this->db->where_in('P.cat_id', $this->data['cat_id']);
        }
        if(!empty($this->data['stxt'])) {
            $this->db->like('P.prd_name', $this->data['stxt']);
        }

        $result = $this->db->get();
        $this->data['list'] = $result->result_array();

        $this->data['totalCount'] = (int)$this->db->query("SELECT FOUND_ROWS() AS cnt")->row(0)->cnt;

        $paging['page'] = $this->data['page'];
        $paging['page_rows'] = $this->data['pageRows'];
        $paging['total_rows'] = $this->data['totalCount'];
        $this->load->library('paging', $paging);
        $this->data['pagination'] = $this->paging->create();

        $this->active = "products/stocks";
        $this->view = "products/stocks";
    }

    /**
     * 상품 옵션 재고 관리
     */
    public function options_stocks()
    {
        // 상품 분류 목록을 가져온다.
        $this->data['categoryList'] = $this->products_model->getCategoryList(TRUE);

        // 페이징 관련 매개변수 정리
        $this->data['page'] = $this->input->get('page', TRUE, 1);
        $this->data['pageRows'] = 15;

        // 필터관련 값 정리
        $this->data['prd_sell_status'] = $this->input->get('prd_sell_status', TRUE, ['Y','O','D']);
        $this->data['prd_status'] = $this->input->get('prd_status', TRUE, ['Y','H']);
        $this->data['cat_id'] = $this->input->get('cat_id', TRUE, '');
        $this->data['scol'] = $this->input->get('scol', TRUE, '');
        $this->data['stxt'] = $this->input->get('stxt', TRUE, '');

        $this->db
            ->select('SQL_CALC_FOUND_ROWS PO.*,P.prd_item_options, P.prd_name, IFNULL(A.att_filepath, "") AS prd_thumbnail_path, PCL.parent_names, PCL.cat_title', FALSE)
            ->from('products_options AS PO')
            ->join('products AS P', 'P.prd_idx=PO.prd_idx','left')
            ->join('products_category_list AS PCL','PCL.cat_id=P.cat_id','left')
            ->join('attach AS A','A.att_idx=P.prd_thumbnail', 'left')
            ->order_by('P.prd_sort ASC, P.prd_idx DESC')
            ->limit($this->data['pageRows'], ($this->data['page'] - 1) * $this->data['pageRows']);

        if(! empty($this->data['prd_status'])) {
            $this->db->where_in('P.prd_status', $this->data['prd_status']);
        }
        if(! empty($this->data['prd_sell_status'])) {
            $this->db->where_in('P.prd_sell_status', $this->data['prd_sell_status']);
        }
        if(! empty($this->data['cat_id']))
        {
            $this->db->where_in('P.cat_id', $this->data['cat_id']);
        }
        if(!empty($this->data['stxt'])) {
            $this->db->like('P.prd_name', $this->data['stxt']);
        }

        $result = $this->db->get();
        $this->data['list'] = $result->result_array();

        foreach($this->data['list'] as &$row) {
            $row['prd_item_options'] = json_decode($row['prd_item_options'], TRUE);
            $row['optNamesArray'] = [];

            if($row['opt_type'] === 'addition') {
                $row['optNamesArray'][] = $row['opt_code'];
            } else {
                $row['optNamesArray'] = explode(SEPERATE_CHARSET, $row['opt_code']);
            }
        }

        $this->data['totalCount'] = (int)$this->db->query("SELECT FOUND_ROWS() AS cnt")->row(0)->cnt;

        $paging['page'] = $this->data['page'];
        $paging['page_rows'] = $this->data['pageRows'];
        $paging['total_rows'] = $this->data['totalCount'];
        $this->load->library('paging', $paging);
        $this->data['pagination'] = $this->paging->create();

        $this->active = "products/options-stocks";
        $this->view = "products/options-stocks";
    }


    /**
     * 상품 라벨 관리
     */
    public function labels()
    {
        $this->products_model->cleanTempItem();

        // 상품 분류 목록을 가져온다.
        $this->data['categoryList'] = $this->products_model->getCategoryList(TRUE);

        // 페이징 관련 매개변수 정리
        $this->data['page'] = $this->input->get('page', TRUE, 1);
        $this->data['pageRows'] = 15;

        // 필터관련 값 정리
        $this->data['prd_sell_status'] = $this->input->get('prd_sell_status', TRUE, ['Y','O','D']);
        $this->data['prd_status'] = $this->input->get('prd_status', TRUE, ['Y','H']);
        $this->data['cat_id'] = $this->input->get('cat_id', TRUE, '');
        $this->data['scol'] = $this->input->get('scol', TRUE, '');
        $this->data['stxt'] = $this->input->get('stxt', TRUE, '');

        $this->db
            ->select('SQL_CALC_FOUND_ROWS P.*, IFNULL(A.att_filepath, "") AS prd_thumbnail_path, PCL.parent_names, PCL.cat_title', FALSE)
            ->from('products AS P')
            ->join('products_category_list AS PCL','PCL.cat_id=P.cat_id','left')
            ->join('attach AS A','A.att_idx=P.prd_thumbnail', 'left')
            ->order_by('P.prd_sort ASC, P.prd_idx DESC')
            ->limit($this->data['pageRows'], ($this->data['page'] - 1) * $this->data['pageRows']);

        if(! empty($this->data['prd_status'])) {
            $this->db->where_in('P.prd_status', $this->data['prd_status']);
        }
        if(! empty($this->data['prd_sell_status'])) {
            $this->db->where_in('P.prd_sell_status', $this->data['prd_sell_status']);
        }
        if(! empty($this->data['cat_id']))
        {
            $this->db->where_in('P.cat_id', $this->data['cat_id']);
        }
        if(!empty($this->data['stxt'])) {
            $this->db->like('P.prd_name', $this->data['stxt']);
        }

        $result = $this->db->get();
        $this->data['list'] = $result->result_array();

        $this->data['totalCount'] = (int)$this->db->query("SELECT FOUND_ROWS() AS cnt")->row(0)->cnt;

        $paging['page'] = $this->data['page'];
        $paging['page_rows'] = $this->data['pageRows'];
        $paging['total_rows'] = $this->data['totalCount'];
        $this->load->library('paging', $paging);
        $this->data['pagination'] = $this->paging->create();

        $this->active = "products/labels";
        $this->view = "products/labels";
    }
}