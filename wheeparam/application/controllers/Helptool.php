<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Helptool extends WB_Controller {

    public function index()
    {
        show_404();
    }

    public function lang()
    {
        header('Content-Type: application/javascript; charset=UTF-8');
        header('cache-control: no-cache, must-revalidate');
        header('pragma: no-cache');

        echo "var LANG = {};".PHP_EOL;
        $list = $this->db->get('localize')->result_array();
        foreach($list as $row) {
            $key = str_replace( array("게시판","공통","회원","팝업"), array('board','common','member','popup'), $row['loc_key'] );
            echo "LANG." . str_replace("/", "_", $key)." = '".str_replace("\r\n","", nl2br($row['loc_value_'.LANG]))."';".PHP_EOL;
        }
        exit;
    }
}
