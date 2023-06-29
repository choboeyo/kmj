<?php
defined('BASEPATH') or exit('No direct script access allowed');

/**
 * Contact 페이지
 */
class Contact extends WB_Controller
{

  /**
   * Contact 페이지
   */
  public function index()
  {
    // 폼검증 라이브러리 호출
    $this->load->library('form_validation');

    // 필요한것만 주석 살려서 사용하세요
//        $this->form_validation->set_rules('con_name',"이름","required|trim");
//        $this->form_validation->set_rules('con_email',"이메일","required|trim|valid_email");
//        $this->form_validation->set_rules('con_phone',"연락처","required|trim");
//        $this->form_validation->set_rules('con_content', "문의 내용","required|trim");

    // 문의 요청에 관한 Parmeter가 넘어올 경우
    if ($this->form_validation->run() != FALSE) {
      $reurl = $this->input->post('reurl', TRUE, base_url());
      $complete_msg = $this->input->post('complete_msg', TRUE, '문의 작성이 완료되었습니다.');

      $data['con_name'] = $con_name = $this->input->post('con_name', TRUE);
      $data['con_phone'] = $con_phone = $this->input->post('con_phone', TRUE);
      $data['con_email'] = $con_email = $this->input->post('con_email', TRUE);
      $data['con_memo'] = $con_content = $this->input->post('con_memo', TRUE);
      $data['reg_datetime'] = date('Y-m-d H:i:s');

      $extra = $this->input->post('extra', TRUE);
      $extra_name = $this->input->post('extra_name', TRUE);

      $extra_content = "";
      $extra_content .= "이름 : {$con_name}" . PHP_EOL;
      $extra_content .= "연락처 : {$con_phone}" . PHP_EOL;
      $extra_content .= "이메일 : {$con_email}" . PHP_EOL;
      foreach ($extra as $key => $value) {
        $name = isset($extra_name[$key]) && $extra_name[$key] ? $extra_name[$key] : $key;
        $extra_content .= $name . " : " . $value . PHP_EOL;
      }

      $con_content = $extra_content . "문의 내용 : " . PHP_EOL . $con_content;

      // 이메일 라이브러리 로드
      $this->load->library('email');

      $email_config = array();
      if (SEND_EMAIL_SMTP_USE) {
        $email_config['protocol'] = "smtp";
        $email_config['smtp_host'] = SEND_EMAIL_SMTP_HOST;
        $email_config['smtp_user'] = SEND_EMAIL_SMTP_USER;
        $email_config['smtp_pass'] = SEND_EMAIL_SMTP_PASS;
        $email_config['smtp_port'] = SEND_EMAIL_SMTP_PORT;
        $email_config['smtp_crypto'] = SEND_EMAIL_SMTP_CRYP;
      } else {
        $email_config['protocol'] = "mail";
      }
      $this->email->initialize($email_config);
      $this->email->from(SEND_EMAIL, '홈페이지 문의');
      $this->email->to($this->site->config('email_send_address'));
      $this->email->reply_to($con_email);

      $this->email->subject('홈페이지 문의 메일 [' . $con_name . ']');
      $this->email->message($con_content);

      if ($this->db->insert('contact', $data)) {
        $this->email->send();
        alert($complete_msg, $reurl);
        exit;
      } else {
        alert('메일 발송 도중 오류가 발생하였습니다.');
        exit;
      }
    } else {
      // 메타태그 설정
      $this->site->meta_title = "문의하기";            // 이 페이지의 타이틀
      // $this->site->meta_description 	= "";   // 이 페이지의 요약 설명
      // $this->site->meta_keywords 		= "";   // 이 페이지에서 추가할 키워드 메타 태그
      // $this->site->meta_image			= "";   // 이 페이지에서 표시할 대표이미지

      // 레이아웃 & 뷰파일 설정
      $this->theme = $this->site->get_layout();
      $this->view = "contact/index";
      $this->active = "contact/index";
    }
  }
}
