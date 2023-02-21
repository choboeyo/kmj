<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * 일반 페이지
 */
class Content extends WB_Controller {

    public function index($page="")
    {
        // Page가 지정되지 않았다면?
        if(empty($page))
        {
            show_404();
            exit;
        }

        // 레이아웃 & 뷰파일 설정
        $this->theme    = $this->site->get_layout();

        if(! is_file( VIEWPATH . DIR_THEME . DIRECTORY_SEPARATOR . $this->theme . DIRECTORY_SEPARATOR . "content" . DIRECTORY_SEPARATOR . $page . ".php" ))
        {
            show_404();
            exit;
        }

        $this->view     = "content/" . $page;
    }
}