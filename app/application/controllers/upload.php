<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Upload extends CI_Controller
{
    public function index()
    {
        $this->load->helper('url');

        $config = array(
            'upload_path'   => './uploads/',
            'allowed_types' => './gif|png|jpg|jpeg',
            'file_name'     => uniqid(),
            'max_size'      => 1024 * 1024,
        );
        $this->load->library('upload', $config);
        $this->upload->initialize($config);

        $result = $this->upload->do_upload('file');
       
        if (!$result) {
            $error =  $this->upload->display_errors();
            
            ajax_fail($error, false);
        } else {
            // 如果上传成功，获取上传文件的信息
            $upload = $this->upload->data();
            //改成时间戳后的路径
            $originPath = $config['upload_path'] . $upload['raw_name'] . '.' . substr($upload['file_ext'], 1);
            //直接rename() 
            $newPath = $config['upload_path'] . date('Ymdhms') . '.' . substr($upload['file_ext'], 1);
            //将时间戳路径输出
            rename($originPath, $newPath);

            $uploadData['url']            = $newPath;
            $uploadData['origin_name']    = $upload['client_name'];
            $uploadData['image_width']    = $upload['image_width'];
            $uploadData['image_height']   = $upload['image_height'];
            $uploadData['image_size_str'] = $upload['image_size_str'];
            ajax_success($uploadData, '上传成功');
        }

    }
}
