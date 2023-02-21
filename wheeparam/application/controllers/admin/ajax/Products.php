<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require APPPATH . '/libraries/REST_Controller.php';

/**
 * @property Products_model $products_model
 */
class Products extends REST_Controller
{
    /**
     * 상품 분류
     */
    function category_post()
    {
        $cat_parent_id = $this->post('cat_parent_id', TRUE) ?? 0;
        $cat_id = $this->post('cat_id', TRUE) ?? 0;

        // 넘어온 데이타를 정리한다.
        $data['cat_title'] = trim($this->post('cat_title',TRUE) ?? '');
        $data['cat_skin'] = trim($this->post('cat_skin',TRUE) ?? '');
        $data['cat_skin_m'] = trim($this->post('cat_skin_m',TRUE) ?? '');
        $data['cat_use_paging'] = trim($this->post('cat_use_paging',TRUE) ?? 'T');
        $data['cat_page_rows'] = $this->post('cat_page_rows',TRUE)??0;
        $data['upd_user'] = $this->member->is_login();
        $data['upd_datetime'] = date('Y-m-d H:i:s');
        $data['cat_parent_id'] = $cat_parent_id;

        if(empty($data['cat_title'])) {
            $this->response(["message"=>"분류 이름이 설정되지 않았습니다."], 400);
            exit;
        }

        // 신규등록일 경우
        if(empty($cat_id)) {
            $data['reg_user'] = $data['upd_user'];
            $data['reg_datetime'] = $data['reg_user'];
            $data['cat_sort'] = ((int)$this->db->select_max('cat_sort', 'max')->where('cat_parent_id', $cat_parent_id)->where_in('cat_status',['Y','H'])->get('products_category')->row(0)->max)  + 1;

            if($this->db->insert('products_category', $data)) {
                $this->response(["message"=>"SUCCESS"]);
            }
            else {
                $this->response(["message"=>"DB 입력도중 오류가 발생하였습니다."], 500);
            }
        }
        else {
            if($this->db->where('cat_id', $cat_id)->update('products_category', $data))
            {
                $this->response(["message"=>"SUCCESS"]);
            }
            else {
                $this->response(["message"=>"DB 입력도중 오류가 발생하였습니다."], 500);
            }
        }

    }

    /**
     * TODO 상품 분류 삭제
     */
    function category_delete()
    {
        // 삭제할 분류 PK 가져오기
        $cat_id = trim($this->delete('cat_id', TRUE));

        if(empty($cat_id)) {
            $this->response(["message"=>"삭제할 상품분류가 올바르게 선택되지 않았습니다."], 400);
            exit;
        }
        
        // 존재하는 상품분류인지 확인
        if(! $category = $this->db
            ->where_in('cat_status' ,['Y','H'])
            ->where('cat_id', $cat_id)
            ->get('products_category_list')->row_array())
        {
            $this->response(["message"=>"상품분류가 존재하지 않거나 이미 삭제되었습니다."], 400);
            exit;
        }

        // 삭제할 분류 PK에 하위 분류가 존재하는지 확인, 존재한다면 삭제 취소
        $cnt = $this->db
            ->select('COUNT(*) AS cnt')
            ->like('node_path', $category['node_path'], 'after')
            ->where_in('cat_status',['Y','H'])
            ->where('cat_id <>', $cat_id)
            ->get('products_category_list')
            ->row(0)
            ->cnt;

        if( $cnt > 0) {
            $this->response(["message"=>"해당 상품분류에 속해있는 하위 분류가 존재합니다. 하위 분류를 먼저 삭제해주세요"], 400);
            exit;
        }

        // TODO 삭제할 분류 PK에 포함된 상품이 있는지 확인, 존재한다면 삭제 취소

        // 삭제 처리
        $this->db
            ->where('cat_id', $cat_id)
            ->set('cat_status','N')
            ->update('products_category');

        $this->response(["message"=>"SUCCESS"], 200);
    }

    /**
     * 상품
     */
    function category_sort_post()
    {
        $this->load->model('products_model');

        // 기존 상품목록 가져오기
        $temp = $this->db->get('products_category_list')->result_array();

        $data = $this->post('list', TRUE);

        $updateArray = [];
        foreach($data as $i=>$row) {
            $updateArray[] = [
                "cat_id" => $row['id'],
                "cat_parent_id" => $row['parent_id'] ?? 0,
                "cat_sort" => $i + 1
            ];
        }

        if(count($updateArray)) {
            $this->db->update_batch('products_category', $updateArray, 'cat_id');
        }

        // 변경된 상품 목록 가져오기
        $after = $this->db->get('products_category_list')->result_array();

        // 상품 NODE가 변경된 경우 포함된 상품수를 업데이트 해줘야 한다.
        $countUpdateArray = [];
        foreach($temp as $before_row) {
            foreach($after as $after_row) {
                if($before_row['cat_id'] === $after_row['cat_id'])
                {
                    if($before_row['node_path'] != $after_row['node_path']) {
                        $countUpdateArray[] = $before_row['cat_id'];
                        $countUpdateArray[] = $before_row['cat_parent_id'];
                    }
                    break;
                }
            }
        }

        if(count($countUpdateArray) >0) {
            foreach($countUpdateArray as $cat_id) {
                $this->products_model->updateCategoryCount($cat_id);
            }
        }
    }

    /**
     * 상품 정보 수정
     * @return void
     */
    function item_post($prd_idx = "")
    {
        $this->load->model('products_model');

        // 상품코드가 제대로 넘어왔는지 확인
        if(empty($prd_idx)) {
            $this->response(["message"=> "수정하려는 상품이 이미 삭제되었거나, 존재하지 않습니다."], 400);
        }

        // 수정전 기존 데이타 가져옴
        $temp_data = $this->db->where('prd_idx', $prd_idx)->get('products')->row_array();

        $data['prd_status'] = $this->post('prd_status', TRUE) ?? 'Y';
        $data['prd_sell_status'] = $this->post('prd_sell_status', TRUE) ?? 'Y';
        $data['cat_id'] = $this->post('cat_id', TRUE) ?? 0;
        $data['prd_type'] = 'H'; // 상품유형 H:현물 / C:컨텐츠.. 컨텐츠는 추후 추가
        $data['prd_use_options'] = $this->post('prd_use_options', TRUE) === 'Y' ? 'Y' : 'N';
        $data['prd_price'] = $this->post('prd_price', TRUE) ?? 0;
        $data['prd_cust_price'] = $this->post('prd_cust_price', TRUE) ?? 0;
        $data['prd_name'] = trim($this->post('prd_name', TRUE) ?? '');
        $data['prd_maker'] = trim($this->post('prd_maker', TRUE) ?? '');
        $data['prd_origin'] = trim($this->post('prd_origin', TRUE) ?? '');
        $data['prd_brand'] = trim($this->post('prd_brand', TRUE) ?? '');
        $data['prd_model'] = trim($this->post('prd_model', TRUE) ?? '');
        $data['prd_summary'] = trim($this->post('prd_summary', TRUE) ?? '');
        $data['prd_thumbnail'] = $this->post('prd_thumbnail', TRUE) ?? 0;
        $data['prd_content'] = $this->post('prd_content', FALSE) ?? 0;
        $data['prd_mobile_content'] = $this->post('prd_mobile_content', FALSE) ?? 0;
        $data['prd_stock_qty'] = $this->post('prd_stock_qty', FALSE) ?? 0;
        $data['prd_noti_qty'] = $this->post('prd_noti_qty', FALSE) ?? 0;
        $data['prd_buy_min_qty'] = $this->post('prd_buy_min_qty', FALSE) ?? 0;
        $data['prd_buy_max_qty'] = $this->post('prd_buy_max_qty', FALSE) ?? 0;
        $prd_extra_info = $this->post('prd_extra_info', TRUE) ?? [];
        $data['prd_extra_info'] = json_encode($prd_extra_info, JSON_UNESCAPED_SLASHES|JSON_UNESCAPED_UNICODE|JSON_NUMERIC_CHECK);
        $data['prd_item_group'] = $this->post('prd_item_group', TRUE);
        $prd_item_options = $this->post('prd_item_options', TRUE) ?? [];
        $data['prd_item_options'] = json_encode($prd_item_options, JSON_UNESCAPED_SLASHES|JSON_UNESCAPED_UNICODE|JSON_NUMERIC_CHECK);
        $options = $this->post('options', TRUE) ?? [];
        $options2 = $this->post('options2', TRUE) ?? [];
        $data['prd_is_best'] = $this->post('prd_is_best', TRUE) === 'Y' ? 'Y' : 'N';
        $data['prd_is_hit'] = $this->post('prd_is_hit', TRUE) === 'Y' ? 'Y' : 'N';
        $data['prd_is_new'] = $this->post('prd_is_new', TRUE) === 'Y' ? 'Y' : 'N';
        $data['prd_is_sale'] = $this->post('prd_is_sale', TRUE) === 'Y' ? 'Y' : 'N';
        $data['prd_is_recommend'] = $this->post('prd_is_recommend', TRUE) === 'Y' ? 'Y' : 'N';
        $data['prd_sc_method'] = $this->post('prd_sc_method', TRUE);
        $data['prd_sc_type'] = $this->post('prd_sc_type', TRUE);
        $data['prd_sc_price'] =str_replace("-","", $this->post('prd_sc_price', TRUE)) ?? 0;
        $data['prd_sc_minimum'] =str_replace("-","", $this->post('prd_sc_minimum', TRUE)) ?? 0;
        $data['prd_sc_qty'] =str_replace("-","", $this->post('prd_sc_qty', TRUE)) ?? 0;

        for($i=1; $i<=10; $i++) {
            $data['prd_extra_'.$i] = $this->post('prd_extra_'.$i, TRUE, '');
        }


        /**
         * 폼검증 시작
         */

        // 상품명
        if(empty($data['prd_name'])) {
            $this->response(["message"=>"상품명을 입력하셔야 합니다."], 400);
            exit;
        }

        // 상품분류
        if((int)$data['cat_id'] <= 0) {
            $this->response(["message"=>"상품 분류를 선택하셔야 합니다."], 400);
            exit;
        }

        // 선택한 상품분류가 존재하는 상품분류인지 확인한다.
        $cnt = (int)$this->db->select('COUNT(*) AS cnt')->from('products_category')->where('cat_id', $data['cat_id'])->where_in('cat_status', ['Y','H'])->get()->row(0)->cnt;

        if($cnt <= 0 ) {
            $this->response(["message"=>"선택하신 상품분류가 존재하지 않거나, 삭제된 상품분류입니다."], 400);
            exit;
        }

        if(! in_array($data['prd_status'] ,['Y','H']) ) {
            $this->response(["message"=>"상품 표시상태가 올바르지 않습니다."], 400);
            exit;
        }

        if(! in_array($data['prd_sell_status'], ['Y','O','D']))
        {
            $this->response(["message"=>"상품 판매상태가 올바르지 않습니다."], 400);
            exit;
        }

        if((int)$data['prd_price'] < 0) {
            $this->response(["message"=>"판매금액은 음수로 선택할 수 없습니다."], 400);
            exit;
        }

        if(empty($data['prd_item_group'])) {
            $this->response(["message"=>"품목 그룹이 제대로 설정되어 있지 않습니다."], 400);
            exit;
        }

        if(empty($prd_extra_info)) {
            $this->response(["message"=>"품목 그룹이 제대로 설정되어 있지 않습니다."], 400);
            exit;
        }

        /**
         * 옵션에 따른 자동처리
         */
        // 판매상태가 정상이고, 현재 재고가 0이면 품절상태로 처리
        if((int)$data['prd_stock_qty'] <= 0 && $data['prd_sell_status'] === 'Y') {
            $data['prd_sell_status'] = 'O';
        }

        // 필수 선택옵션 사용이 'Y' 인데 등록된 필수옵션이 하나도 없다면
        if($data['prd_use_options'] === 'Y' && count($options) <= 0) {
            $this->response(["message"=>"필수 선택옵션을 등록하셔야 합니다."], 400);
            exit;
        }

        // 필수 선택옵션 사용이 'N' 일경우 필수옵션을 전부삭제
        $opt_subject_temp = [];
        if($data['prd_use_options'] === 'N') {
            $options = [];
            $data['prd_item_options'] = '[]';
        }
        else {
            foreach($prd_item_options as $t) {
                $opt_subject_temp[] = $t['title'];
            }
        }

        $opt_subject = implode(SEPERATE_CHARSET, $opt_subject_temp);


        // 등록을 시작한다.
        $this->db->trans_begin();

        // 먼저 상품 옵션부분을 깨끗하게 지워준다.
        $this->db->where('prd_idx', $prd_idx)->delete('products_options');

        // 상품 정보를 업데이트 한다.
        $this->db->where('prd_idx', $prd_idx)->update('products', $data);

        // 필수 선택 옵션을 INSERT 한다
        $options_array = [];
        if($data['prd_use_options'] === 'Y') {
            foreach($options as $row)
            {
                if(empty($row)) continue;
                if(empty($row['opt_code'])) continue;

                $options_array[] = [
                  "prd_idx" => $prd_idx,
                  "opt_code" => $row['opt_code'],
                  "opt_subject"=> $opt_subject,
                  "opt_status" => $row['opt_status'],
                  "opt_type" => 'detail',
                  "opt_add_price" => $row['opt_add_price'],
                  "opt_stock_qty" => $row['opt_stock_qty'],
                  "opt_noti_qty" => $row['opt_noti_qty']
                ];
            }
        }

        // 추가 선택 옵션을 INSERT 한다
        foreach($options2 as $row)
        {
            if(empty($row)) continue;
            if(empty($row['opt_code'])) continue;

            $options_array[] = [
                "prd_idx" => $prd_idx,
                "opt_code" => $row['opt_code'],
                "opt_subject" => "",
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

        // 해당 상품이 소속된 분류의 상품개수를 업데이트 해준다.
        $this->products_model->updateCategoryCount($data['cat_id']);

        // 만약 카테고리가 변경된경우라면, 기존 상품의 상품개수도 업데이트 해준다.
        if($data['cat_id'] != $temp_data['cat_id'])
        {
            $this->products_model->updateCategoryCount($temp_data['cat_id']);
        }

        $this->load->driver('cache', array('adapter' => 'apc', 'backup' => 'file', 'key_prefix' => PROJECT));
        $this->cache->delete('products/'.$prd_idx);

        if ($this->db->trans_status() === FALSE)
        {
            $this->db->trans_rollback();
            $this->response(["message"=>'상품 정보 입력에 실패하였습니다. 관리자에게 문의하세요'], 500);
            exit;
        }
        else
        {
            $this->db->trans_commit();

            $this->response(["success"=>TRUE], 200);
            exit;
        }
    }

    /**
     * 이미지 업로드
     * @return object
     */
    function images_post()
    {
        $this->load->model('products_model');
        $prd_idx = $this->post('prd_idx', TRUE);

        if(empty($prd_idx))
        {
            $this->response(["message"=>"이미지 업로드할 상품정보가 제대로 전달되지 않았습니다."], 400);
        }

        if(! $product = $this->products_model->getItem($prd_idx))
        {
            $this->response(["message"=>"이미지 업로드할 상품이 삭제되었거나, 존재하지 않습니다."], 400);
        }

        if($product['prd_status'] === 'N')
        {
            $this->response(["message"=>"이미지 업로드할 상품이 삭제되었거나, 존재하지 않습니다."], 400);
        }

        if( isset($_FILES) && isset($_FILES['userfile']) && count($_FILES['userfile']) > 0 )
        {
            // 업로드 라이브러리를 위한 세팅
            $dir_path = DIR_UPLOAD . "/products/{$prd_idx}/";
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

            $upload_array = [];

            // 해당 이미지의 정렬순서를 구해온다
            $sort = (int)$this->db
                ->select_max('att_sort','sort')
                ->where('att_target', $prd_idx)
                ->where('att_target_type', 'PRODUCTS')
                ->get('attach')
                ->row(0)
                ->sort;

            // FOR 문 돌면서 정리
            foreach ($files as $i=>$file) {
                $_FILES['userfile'] = $file;
                $this->upload->initialize($upload_config);
                if( ! isset($_FILES['userfile']['tmp_name']) OR ! $_FILES['userfile']['tmp_name']) continue;

                if (! $this->upload->do_upload('userfile') )
                {
                    $this->response(["message"=> '파일 업로드에 실패하였습니다.\\n'.$this->upload->display_errors(' ',' ')], 500);
                    exit;
                }
                else
                {
                    $filedata = $this->upload->data();
                    $upload_data = [
                        "att_target_type" => 'PRODUCTS',
                        "att_target" => $prd_idx,
                        "att_sort" => $sort + $i +1,
                        "att_origin" => $filedata['orig_name'],
                        "att_filepath" => $dir_path . $filedata['file_name'],
                        "att_downloads" => 0,
                        "att_filesize" => $filedata['file_size'] * 1024,
                        "att_width" => $filedata['image_width'] ? $filedata['image_width'] : 0,
                        "att_height" => $filedata['image_height'] ? $filedata['image_height'] : 0,
                        "att_ext" => $filedata['file_ext'],
                        "att_is_image" => ($filedata['is_image'] == 1) ? 'Y' : 'N',
                        "reg_user" => $this->member->is_login(),
                        "reg_datetime" => date('Y-m-d H:i:s')
                    ];

                    $this->db->insert('attach', $upload_data);

                    $upload_array[] = $this->db->insert_id();
                }
            }

            $uploaded_list = $this->db->where_in('att_idx', $upload_array)->get('attach')->result_array();

            $this->response($uploaded_list, 200);
        }
        else
        {
            $this->response(["message"=>"이미지 업로드가 제대로 처리되지 않았습니다."], 400);
        }
    }

    /**
     * 이미지 삭제
     * @return void
     */
    function images_delete()
    {
        $id = $this->delete('id', TRUE);

        if(empty($id))
            $this->response(["message"=>"잘못된 접근입니다.".$id], 400);

        if(! $att = $this->db->where('att_idx', $id)->get('attach')->row_array())
        {
            $this->response(["message"=>"존재하지 않는 이미지이거나, 이미 삭제된 이미지입니다."], 400);
        }

        if(file_exists(FCPATH.$att['att_filepath'])) {
            @unlink(FCPATH . $att['att_filepath']);
        }

        $this->db->where('att_idx', $id)->delete('attach');
    }

    /**
     * 필수 선택옵션의 등록된 옵션을 기준으로 처리
     */
    function options_generate_get()
    {
        $this->load->model('products_model');
        $return = [];

        $prd_idx = $this->get('prd_idx', TRUE);
        $prd_item_options = $this->get('prd_item_options', TRUE);

        $opt1_title = isset($prd_item_options[0]) ? preg_replace($this->products_model->option_id_filter,'', trim(stripslashes($prd_item_options[0]['title']))) : '';
        $opt2_title = isset($prd_item_options[1]) ? preg_replace($this->products_model->option_id_filter,'', trim(stripslashes($prd_item_options[1]['title']))) : '';
        $opt3_title = isset($prd_item_options[2]) ? preg_replace($this->products_model->option_id_filter,'', trim(stripslashes($prd_item_options[2]['title']))) : '';

        $opt1_content = isset($prd_item_options[0]) && isset($prd_item_options[0]['items']) ? $prd_item_options[0]['items'] : [];
        $opt2_content = isset($prd_item_options[1]) && isset($prd_item_options[1]['items']) ? $prd_item_options[1]['items'] : [];
        $opt3_content = isset($prd_item_options[2]) && isset($prd_item_options[2]['items']) ? $prd_item_options[2]['items'] : [];

        foreach($opt1_content as $i=>&$row1) {
            $row1 = preg_replace($this->products_model->option_id_filter,'',trim(stripslashes($row1)));
            if(empty($row1)) {
                unset($opt1_content[$i]);
            }
        }

        foreach($opt2_content as $k=>&$row2) {
            $row2 = preg_replace($this->products_model->option_id_filter,'',trim(stripslashes($row2)));
            if(empty($row2)) {
                unset($opt2_content[$k]);
            }
        }
        foreach($opt3_content as $t=>&$row3) {
            $row3 = preg_replace($this->products_model->option_id_filter,'',trim(stripslashes($row3)));
            if(empty($row3)) {
                unset($opt3_content[$t]);
            }
        }


        if(empty($opt1_title) OR empty($opt1_content) ) {
            $this->response([], 200);
            exit;
        }

        $opt1_count = count($opt1_content);
        $opt2_count = count($opt2_content);
        $opt3_count = count($opt3_content);

        for($i=0; $i<$opt1_count; $i++) {
            $j= 0 ;
            do {
                $k = 0;
                do {
                    $opt1 = isset($opt1_content[$i]) ? strip_tags(trim($opt1_content[$i])) : '';
                    $opt2 = isset($opt2_content[$j]) ? strip_tags(trim($opt2_content[$j])) : '';
                    $opt3 = isset($opt3_content[$k]) ? strip_tags(trim($opt3_content[$k])) : '';

                    $opt_2_len = strlen($opt2);
                    $opt_3_len = strlen($opt3);

                    $opt_name = [];

                    $opt_name[] = $opt1;
                    if($opt_2_len) {
                        $opt_name[] = $opt2;
                    }
                    if($opt_3_len) {
                        $opt_name[] = $opt3;
                    }
                    $opt_code = implode(SEPERATE_CHARSET, $opt_name);

                    $opt_add_price = 0;
                    $opt_stock_qty = 9999;
                    $opt_noti_qty = 100;
                    $opt_status = 'Y';

                    $k++;

                    // 기존에 설정된 값이 있는지 체크해서 값 불러오기
                    $row = $this->db
                        ->where('opt_code', $opt_code)
                        ->where('prd_idx', $prd_idx)
                        ->where('opt_type', 'detail')
                        ->get('products_options')
                        ->row_array();

                    if($row) {
                        $opt_add_price = $row['opt_add_price'];
                        $opt_stock_qty = $row['opt_stock_qty'];
                        $opt_noti_qty = $row['opt_noti_qty'];
                        $opt_status = $row['opt_status'];
                    }

                    $return[] = [
                        "opt_code" => $opt_code,
                        "opt_add_price" => $opt_add_price,
                        "opt_status" => $opt_status,
                        "opt_stock_qty" => $opt_stock_qty,
                        "opt_noti_qty" => $opt_noti_qty,
                        "opt_name" => $opt_name
                    ];
                }
                while ($k < $opt3_count);

                $j++;
            }
            while ($j < $opt2_count);
        }

        $this->response($return, 200);
    }

    /**
     * 상품 재고 일괄적용 처리
     * @return void
     */
    function stocks_post()
    {
        $prd_idx = $this->post('prd_idx', TRUE);
        $prd_stock_qty = $this->post('prd_stock_qty', TRUE);
        $prd_noti_qty = $this->post('prd_noti_qty', TRUE);
        $prd_sell_status = $this->post('prd_sell_status', TRUE);

        if(
            count($prd_idx) != count($prd_stock_qty)
            OR count($prd_idx) != count($prd_noti_qty)
            OR count($prd_idx) != count($prd_sell_status)
        ) {
            $this->response(["message"=>"잘못된 접근입니다.", 400]);
        }

        $updateArray =[];

        for($i=0; $i<count($prd_idx); $i++) {
            $updateArray[] = [
              "prd_idx" => $prd_idx[$i],
              "prd_stock_qty" => str_replace(",","",$prd_stock_qty[$i]),
              "prd_noti_qty" => str_replace(",","",$prd_noti_qty[$i]),
              "prd_sell_status" => $prd_sell_status[$i]
            ];
        }

        if(count($updateArray) > 0) {
            $this->db->update_batch('products', $updateArray, 'prd_idx');
        }

        $this->response(["message"=>"SUCCESS"]);
    }

    /**
     * 상품 옵션 재고 일괄적용 처리
     * @return void
     */
    function options_stocks_post()
    {
        $opt_idx = $this->post('opt_idx', TRUE);
        $opt_stock_qty = $this->post('opt_stock_qty', TRUE);
        $opt_noti_qty = $this->post('opt_noti_qty', TRUE);
        $opt_add_price = $this->post('opt_add_price', TRUE);

        if(
            count($opt_idx) != count($opt_stock_qty)
            OR count($opt_idx) != count($opt_noti_qty)
            OR count($opt_idx) != count($opt_add_price)
        ) {
            $this->response(["message"=>"잘못된 접근입니다.", 400]);
        }

        $updateArray =[];

        for($i=0; $i<count($opt_idx); $i++) {
            $updateArray[] = [
                "opt_idx" => $opt_idx[$i],
                "opt_stock_qty" => str_replace(",","",$opt_stock_qty[$i]),
                "opt_noti_qty" => str_replace(",","",$opt_noti_qty[$i]),
                "opt_add_price" => str_replace(",","",$opt_add_price[$i])
            ];
        }

        if(count($updateArray) > 0) {
            $this->db->update_batch('products_options', $updateArray, 'opt_idx');
        }

        $this->response(["message"=>"SUCCESS"]);
    }

    /**
     * 상품 라벨 일괄적용 처리
     * @return void
     */
    function labels_post()
    {
        $prd_idx = $this->post('prd_idx', TRUE);
        $prd_is_best = $this->post('prd_is_best', TRUE);
        $prd_is_hit = $this->post('prd_is_hit', TRUE);
        $prd_is_new = $this->post('prd_is_new', TRUE);
        $prd_is_recommend = $this->post('prd_is_recommend', TRUE);
        $prd_is_sale = $this->post('prd_is_sale', TRUE);

        $updateArray =[];

        for($i=0; $i<count($prd_idx); $i++) {
            $updateArray[] = [
                "prd_idx" => $prd_idx[$i],
                "prd_is_best" => isset($prd_is_best[$prd_idx[$i]]) && $prd_is_best[$prd_idx[$i]] == 'Y' ? 'Y' : 'N',
                "prd_is_hit" => isset($prd_is_hit[$prd_idx[$i]]) && $prd_is_hit[$prd_idx[$i]] == 'Y' ? 'Y' : 'N',
                "prd_is_new" => isset($prd_is_new[$prd_idx[$i]]) && $prd_is_new[$prd_idx[$i]] == 'Y' ? 'Y' : 'N',
                "prd_is_recommend" => isset($prd_is_recommend[$prd_idx[$i]]) && $prd_is_recommend[$prd_idx[$i]] == 'Y' ? 'Y' : 'N',
                "prd_is_sale" => isset($prd_is_sale[$prd_idx[$i]]) && $prd_is_sale[$prd_idx[$i]] == 'Y' ? 'Y' : 'N',
            ];
        }

        if(count($updateArray) > 0) {
            $this->db->update_batch('products', $updateArray, 'prd_idx');
        }

        $this->response(["message"=>"SUCCESS"]);
    }

    /**
     * 진열장에 상품을 추가합니다.
     * @return void
     */
    function display_items_post()
    {
        $dsp_idx = $this->post('dsp_idx', TRUE);
        $prd_idx = $this->post('prd_idx', TRUE);

        if(empty($dsp_idx)) {
            $this->response(["message"=>"잘못된 접근입니다."], 400);
        }

        if(! is_array($prd_idx) OR count($prd_idx) == 0) {
            $this->response(["message"=>"진열장에 추가할 품목을 선택해주세요"], 400);
        }

        // 진열장 순서를 결정하기 위해 가장마지막 sort를 구해온ㄷ,
        $sort = (int)$this->db
            ->select_max('dspi_sort')
            ->from('products_display_items')
            ->where('dsp_idx', $dsp_idx)
            ->get()
            ->row(0)
            ->cnt;
        $sort +=1 ;

        $mem_idx = $this->member->is_login();
        $reg_datetime = date('Y-m-d H:i:s');
        $insert_array = [];
        foreach($prd_idx as $idx) {
            $insert_array[]= [
                "dspi_sort" => $sort,
                "dsp_idx" => $dsp_idx,
                "prd_idx" => $idx,
                "reg_user" => $mem_idx,
                "reg_datetime" => $reg_datetime
            ];

            $sort++;
        }

        if(count($insert_array)) {
            $this->db->insert_batch('products_display_items', $insert_array);
        }
    }

    /**
     * 진열장에서 상품을 제외시킵니다.
     * @return void
     */
    function display_items_delete()
    {
        $dsp_idx = $this->delete('dsp_idx', TRUE);
        $prd_idx = $this->delete('prd_idx', TRUE);

        if(empty($dsp_idx)) {
            $this->response(["message"=>"진열장 정보가 선택되지 않았습니다."], 400);
        }
        if(empty($prd_idx)) {
            $this->response(["message"=>"제외시킬 상품 정보가 선택되지 않았습니다."], 400);
        }

        $this->db->where('dsp_idx', $dsp_idx)->where('prd_idx', $prd_idx)->delete('products_display_items');

    }

    function review_status_post()
    {
        $rev_idx = $this->post('rev_idx', TRUE);
        $rev_status = $this->post('rev_status', TRUE);

        if(empty($rev_idx)) {
            $this->response(["message"=>"잘못된 접근입니다."]);
        }

        if(!in_array($rev_status, ["Y","H","N"])) {
            $this->response(["message"=>"리뷰 노출 상태 변경에 실패하였습니다."]);
        }

        $this->db
            ->where('rev_idx', $rev_idx)
            ->set('rev_status', $rev_status)
            ->update('products_review');

        $rev = $this->db->where('rev_idx', $rev_idx)->get('products_review')->row_array();

        $this->load->model('products_model');
        $this->products_model->reviewScoreUpdate($rev['prd_idx']);
    }
}