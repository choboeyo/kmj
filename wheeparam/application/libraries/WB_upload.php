<?php
class WB_Upload extends CI_Upload {

    public function __construct()
    {
        parent::__construct();
    }
	function fixFilesArray($field="userfile")
	{
		// iterate over each uploaded file
		foreach ($_FILES[$field] as $key => $value) {
			foreach ($value as $noKey => $noValue) {
				$files[$noKey][$key] = $noValue;
			}
		}
		$_FILES[$field] = $files;
		return $files;
	}
	function do_multi_upload($field="userfile"){
		$data = array();
		$files = $this->fixFilesArray($field);
		unset($_FILES);
		foreach ($files as $file) {
			$_FILES['userfile'] = $file;
		
			if (!$this->do_upload('userfile') )
			{
				//업로드 실패
				$data[] = array('error' => $this->display_errors());
				
			}	
			else
			{
				$data[] = array('upload_data' => $this->data());
				
			}
		}
		return $data;
	}
}