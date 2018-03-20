<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Upload extends CI_Controller
{
    public function index()
    {
        echo "<PRE>";
        var_dump($_FILES);
        $config = array(
            'upload_path'   => './upload',
            'allowed_types' => './gif|png|jpg|jpeg',
            'file_name'     => uniqid(),
            'max_size'      => 1024 * 1024,
        );
        $this->load->library('upload', $config);
        $this->upload->initialize($config);

        $result = $this->upload->do_upload('pic');
        // 如果上传成功，获取上传文件的信息
        var_dump($result);
        if ($result) {
            var_dump($this->upload->data());
        }

    }
}
