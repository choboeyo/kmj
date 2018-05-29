<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Help extends WB_Controller {

    function document($provider)
    {
        $this->theme = "admin";
        $this->theme_file = "iframe";
        $this->view = "help/".$provider;
    }

}
