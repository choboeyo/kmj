<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Download extends WB_Controller
{
    function attach($att_idx="") {
        if(empty($att_idx))
        {
            alert('잘못된 접근입니다.');
        }

        if(! $att = $this->db->where('att_idx', $att_idx)->get('attach')->row_array())
        {
            alert('잘못된 접근입니다.');
            exit;
        }

        $this->db->where('att_idx', $att['att_idx'])->set('att_downloads', 'att_downloads + 1', FALSE)->update('attach');

        $this->load->helper('download');
        $data = file_get_contents(FCPATH.$att['att_filepath']);
        $name = urlencode($att['att_origin']);
        force_download($name, $data);
    }
}