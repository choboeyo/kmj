<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**************************************************************************************
 *
 * Class Upload
 *
 * 업로드 관련 컨트롤러
 *
 * @author Jang Seongeun <songwritersg@nexvation.com>
 * @date 2016.11.07
 *************************************************************************************/
class Editor extends WB_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->load->library('upload');
        $this->theme = FALSE;
    }

    public function smarteditor($ed_nonce='')
    {
        make_dir(DIR_UPLOAD . DIRECTORY_SEPARATOR . "editor");

        $upload_path =  DIR_UPLOAD . '/editor/' . date('Y') . '/' . date('m') . '/';

        if (isset($_FILES)
            && isset($_FILES['files'])
            && isset($_FILES['files']['name'])
            && isset($_FILES['files']['name'][0])) {

            $uploadconfig = array(
                'upload_path' => "./" . $upload_path,
                'allowed_types' => 'jpg|jpeg|png|gif',
                'max_size' => 10 * 1024,
                'encrypt_name' => true,
            );

            $this->upload->initialize($uploadconfig);
            $upload = isset($_FILES['files']) ? $_FILES['files'] : null;
            if( is_array( $upload['tmp_name'] ) ){
                $_FILES['userfile']['name'] = $upload['name'][0];
                $_FILES['userfile']['type'] = $upload['type'][0];
                $_FILES['userfile']['tmp_name'] = $upload['tmp_name'][0];
                $_FILES['userfile']['error'] = $upload['error'][0];
                $_FILES['userfile']['size'] = $upload['size'][0];
            } else {
                if($upload['type'] == "application/octet-stream"){
                    $imageMime = getimagesize($upload['tmp_name']); // get temporary file REAL info
                    $upload['type'] = $imageMime['mime']; //set in our array the correct mime
                }
                $_FILES['userfile']['name'] = $upload['name'];
                $_FILES['userfile']['type'] = $upload['type'];
                $_FILES['userfile']['tmp_name'] = $upload['tmp_name'];
                $_FILES['userfile']['error'] = $upload['error'];
                $_FILES['userfile']['size'] = $upload['size'];
            }

            if ($this->upload->do_upload()) {

                $filedata = $this->upload->data();
                $image_url = base_url(DIR_UPLOAD . '/editor/' . date('Y') . '/' . date('m') . '/' . element('file_name', $filedata));
                $info = new stdClass();
                $info->oriname = element('orig_name', $filedata);
                $info->name = element('file_name', $filedata);
                $info->size = intval(element('file_size', $filedata) * 1024);
                $info->type = 'image/' . str_replace('.', '', element('file_ext', $filedata));
                $info->url = $image_url;
                $info->width = element('image_width', $filedata) ? element('image_width', $filedata) : 0;
                $info->height = element('image_height', $filedata) ? element('image_height', $filedata) : 0;

                $return['files'][0] = $info;

                exit(json_encode($return));

            } else {
                exit($this->upload->display_errors());
            }
        } elseif ($this->input->get('file')) {
            unlink($upload_path . $this->input->get('file'));
        }
    }

    /**
     * CK 에디터를 통해 이미지를 업로드하는 컨트롤러입니다.
     */
    public function ckeditor($type="")
    {
        make_dir(DIR_UPLOAD . DIRECTORY_SEPARATOR . "editor");

        $upload_path =  DIR_UPLOAD . '/editor/' . date('Y') . '/' . date('m') . '/';

        $uploadconfig = array(
            'upload_path' => "./". $upload_path,
            'allowed_types' => 'jpg|jpeg|png|gif',
            'max_size' => 10 * 1024,
            'encrypt_name' => true,
        );

        $CKEditorFuncNum =  (int)$this->input->get('CKEditorFuncNum', null, 0);

        if (isset($_FILES)
            && isset($_FILES['upload'])
            && isset($_FILES['upload']['name'])) {

            $this->upload->initialize($uploadconfig);
            $_FILES['userfile']['name'] = $_FILES['upload']['name'];
            $_FILES['userfile']['type'] = $_FILES['upload']['type'];
            $_FILES['userfile']['tmp_name'] = $_FILES['upload']['tmp_name'];
            $_FILES['userfile']['error'] = $_FILES['upload']['error'];
            $_FILES['userfile']['size'] = $_FILES['upload']['size'];

            if ($this->upload->do_upload()) {

                $filedata = $this->upload->data();
                $image_url = base_url(DIR_UPLOAD . '/editor/' . date('Y') . '/' . date('m') . '/' . element('file_name', $filedata));

                if(empty($type))
                {
                    exit ("<script>window.parent.CKEDITOR.tools.callFunction({$CKEditorFuncNum}, '{$image_url}', '업로드완료');</script>");
                }
                else if (strtolower($type) == 'json')
                {
                    $return = array(
                        "fileName" => $_FILES['upload']['name'],
                        "uploaded" => 1,
                        "url" => $image_url
                    );
                    exit(json_encode($return, JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES));
                }

            } else {
                if(empty($type)) {
                    echo $this->upload->display_errors();
                }
                else if (strtolower($type) == 'json')
                {
                    $return = array(
                        "fileName" => $_FILES['upload']['name'],
                        "uploaded" => 0,
                        "url" => "",
                        "error" => array(
                            "number" => 201,
                            "message" => $this->upload->display_errors(' ',' ')
                        )
                    );
                    exit(json_encode($return, JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES));
                }
            }
        }
    }

}