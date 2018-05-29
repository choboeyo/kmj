<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * Q&A  페이지
 */
class Qna extends WB_Controller
{
    function index($qna_idx="")
    {
        if(!$this->member->is_login())
        {
            alert_login();
            exit;
        }

        $this->theme = $this->site->get_layout();
        $this->view = "customer/qna/lists";
    }
}