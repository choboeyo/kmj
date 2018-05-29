<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Search extends WB_Controller
{
    /**
     * 검색 결과 페이지
     */
    public function index()
    {
        // 검색어를 가져온다.
        $this->data['query'] = trim($this->input->get('query', TRUE));

        // 검색어에 공백이 두개 들어가있다면, 하나로 줄인다.
        $this->data['query'] = str_replace("  "," ", $this->data['query']);

        // 검색어가 비어있는지 체크
        if( empty($this->data['query']) )
        {
            alert(langs('공통/search/search_txt_empty', FALSE));
            exit;
        }

        // 어느검색인지 (통합/board)
        $this->data['board_key'] = $this->input->get('board_key', TRUE, "total");

        $this->data['page'] = $this->input->get('page', TRUE, 1);
        if( empty($this->data['board_key']) OR $this->data['board_key'] == 'total' ) {
            $this->data['page'] = 1;
        }

        // 검색결과와 각 카테고리별 검색수를 가져온다.
        $this->load->model('search_model');
        $this->data['search_result'] = $this->search_model->search_result( $this->data['query'], $this->data['board_key'], $this->data['page'] );

        // 검색어를 DB에 저장한다.
        $this->db->set('sea_query', $this->data['query'])->set('sea_regtime',date('Y-m-d H:i:s'))->insert("search");

        // 3개월 이상 된 검색어를 삭제한다.
        $this->db->where("sea_regtime < DATE_ADD(CURDATE(), INTERVAL '-3' MONTH)",NULL,FALSE)->delete('search');

        // 상세 검색일경우 페이지네이션 세팅
        $this->data['pagination'] = "";

        if( ! empty($this->data['board_key']) && $this->data['board_key'] != 'total' ) {
            $paging_config['page'] = $this->data['page'];
            $paging_config['total_rows'] = (int) $this->data['search_result']['count'][ $this->data['board_key'] ];
            $paging_config['page_rows'] = 5;
            $paging_config['fixed_page_num'] = 10;

            $this->load->library('paging');
            $this->paging->initialize( $paging_config);
            $this->data['pagination'] = $this->paging->create();
        }
        
        // 메타태그 설정
        $this->site->meta_title = "[{$this->data['query']}] ". langs('공통/search/search_result', FALSE);;            // 이 페이지의 타이틀
        // $this->site->meta_description 	= "";   // 이 페이지의 요약 설명
        // $this->site->meta_keywords 		= "";   // 이 페이지에서 추가할 키워드 메타 태그
        // $this->site->meta_image			= "";   // 이 페이지에서 표시할 대표이미지

        // 레이아웃 & 뷰파일 설정
        $this->theme = $this->site->get_layout();
        $this->view = "search/index";
    }
}