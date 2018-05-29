<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * FAQ  페이지
 */
class Faq extends WB_Controller {

    /**********************************************************************************************
     * FAQ 목록
     ***********************************************************************************************/
    public function index($fac_idx="")
    {
        // 목록정보를 가져온다.
        $this->_get_common($fac_idx);

        // 메타태그 설정
        $this->site->meta_title = $this->site->config('faq_title');                 // 이 페이지의 타이틀
        $this->site->meta_description 	= $this->site->config('faq_description');   // 이 페이지의 요약 설명
        //$this->site->meta_keywords 		= "";   // 이 페이지에서 추가할 키워드 메타 태그
        //$this->site->meta_image			= "";   // 이 페이지에서 표시할 대표이미지

        // 레이아웃 & 뷰파일 설정
        $this->theme    = $this->site->get_layout();
        $this->view     = "customer/faq/lists";
        $this->active   = "/customer/faq";
    }

    /**********************************************************************************************
     * FAQ 내용보기
     ***********************************************************************************************/
    public function view($faq_idx, $fac_idx="")
    {
        // 목록정보를 가져온다.
        $this->_get_common($fac_idx);

        // FAQ 정보 가져오기
        $this->data['view'] = $this->faq_model->get_faq($faq_idx);
        $this->data['current_view'] = $faq_idx;
        $this->data['link_list'] = base_url('customer/faq') . ( $fac_idx ? '/' . $fac_idx : '' );

        // 메타태그 설정
        $this->site->meta_title = $this->data['view']['faq_title'] . " - ". $this->site->config('faq_title');                 // 이 페이지의 타이틀
        $this->site->meta_description 	= get_summary($this->data['view']['faq_content'], FALSE);   // 이 페이지의 요약 설명
        //$this->site->meta_keywords 		= "";   // 이 페이지에서 추가할 키워드 메타 태그
        //$this->site->meta_image			= "";   // 이 페이지에서 표시할 대표이미지

        // 레이아웃 & 뷰파일 설정
        $this->theme    = $this->site->get_layout();
        $this->view     = "customer/faq/view";
        $this->active   = "/customer/faq";
    }

    /**********************************************************************************************
     * FAQ 공통내용 가져오기
     ***********************************************************************************************/
    protected function _get_common($fac_idx)
    {
        // 모델 불러오기
        $this->load->model('faq_model');

        // FAQ 분류 목록을 불러옵니다.
        $faq_category = $this->faq_model->get_category_list();
        $this->data['faq_category'] = array();
        $this->data['faq_category_list'] = array();

        // 전체보기를 위한 데이타 세팅
        $this->data['current_category'] = trim($fac_idx);
        $this->data['total_count'] = 0;

        // FAQ 분류 데이타를 가공해준다.
        foreach($faq_category['list'] as $row)
        {
            $this->data['faq_category_list'][] = array(
                "idx"   => $row['fac_idx'],
                'title' => $row['fac_title'],
                'count' => $row['fac_count'],
                'link'  => base_url('customer/faq/' . $row['fac_idx']),
                "active" => ($row['fac_idx'] == $fac_idx) ? 'active' : ''
            );

            $this->data['total_count']+= $row['fac_count'];
        }

        // FAQ 목록을 가져온다.
        $this->data['faq_list'] = $this->faq_model->get_detail_list($fac_idx);

        // FAQ 목록을 가공한다.
        foreach($this->data['faq_list']['list'] as $i=>&$row)
        {
            $row['nums'] = ( $this->data['faq_list']['total_count'] - $i );
            $row['link'] = base_url('customer/faq') . ( $fac_idx ? '/' . $fac_idx : '' ) . '/' . $row['faq_idx'];
        }
    }
}
