<?php
class Install extends CI_Controller {

    private $password = "tjsrmslove0614*";

    function index()
    {
        $this->load->library('form_validation');

        $this->form_validation->set_rules('userpass', '비밀번호','required|trim');

        if( $this->form_validation->run() )
        {
            if( $this->input->post('userpass') != $this->password )
            {
                $this->load->helper('common');
                alert('비밀번호가 일치하지 않습니다.');
                exit;
            }
            $this->db_init();
        }
        else {
            $this->load->view('tools/install');
        }

    }

    function db_init()
    {
        $sql = file_get_contents(BASEPATH . "..". DIRECTORY_SEPARATOR . "config".DIRECTORY_SEPARATOR."wheeparam.sql");
        $sqls = explode(';', $sql);
        array_pop($sqls);

        // DB 기존구조 넣기
        $this->load->database();
        foreach($sqls as $statement)
        {
            $this->db->query($statement);
        }

        $this->load->helper('common');

        // 관리자 아이디 생성
        $data['mem_status'] = "Y";
        $data['mem_userid'] =  $this->input->post('admin_id', TRUE, "admin@wheeparam.com");
        $data['mem_password'] = get_password_hash($this->input->post('admin_pass', TRUE, $this->password) );
        $data['mem_nickname'] = $this->input->post('admin_nick', TRUE, "휘파람");
        $data['mem_email'] = $this->input->post('admin_email', TRUE, "admin@wheeparam.com");
        $data['mem_gender'] = "U";
        $data['mem_recv_email'] = "N";
        $data['mem_recv_sms'] = "N";
        $data['mem_auth']   = 10;
        $data['mem_point'] = 0;
        $data['mem_regtime'] = date('Y-m-d H:i:s');
        $data['mem_regip'] = ip2long($this->input->ip_address());
        $data['mem_logcount'] = 0;
        $this->db->insert('member', $data);
        unset($data);

        // 관리자 아이디를 관리자 목록에 추가
        $data['mem_idx'] = $this->db->insert_id();
        $data['ath_type'] = "SUPER";
        $data['ath_key'] = "";
        $this->db->insert('member_auth',$data);

        $data['ath_type'] = 'MASTER';
        $this->db->insert('member_auth',$data);


        unset($data);
        
        // 인스톨 파일 삭제
        unlink( APPPATH . '..'.DIRECTORY_SEPARATOR.'config'.DIRECTORY_SEPARATOR.'no_install.php' );
        $this->load->helper('url');
        redirect( BASE_URL );
    }
}