<?php

class Email_model extends CI_Model
{
	/**
	 * 发送邮件类
	 * @param  string $emailAccept [接收的邮箱]
	 * @param  string $veriCode    [生成的验证码]
	 * @return [type]              [description]
	 */
	public function sendEmail($emailAccept='',$veriCode='')
    {
    	if(!emailAccept){
    		return false;
    	}
    	if(!$veriCode){
    		return false;
    	}
        $this->load->library('email'); //加载CI的email类
        $this->config->load('email');
        //以下设置Email参数
        $emailConf['protocol']  = $this->config->item('protocol');
        $emailConf['smtp_host'] = $this->config->item('smtp_host');
        $emailConf['smtp_user'] = $this->config->item('smtp_user');
        $emailConf['smtp_pass'] = $this->config->item('smtp_pass');
        $emailConf['smtp_port'] = $this->config->item('smtp_port');
        $emailConf['charset']   = $this->config->item('charset');
        $emailConf['wordwrap']  = $this->config->item('wordwrap');
        $emailConf['mailtype']  = $this->config->item('mailtype');

        $this->email->initialize($emailConf);

        $emailSubject = '您的验证码'.$veriCode;
        $emailMessage = '<font style="font-size:20px; font-weight:bold;">欢迎使用作业统计系统</font>';
        $emailAttach  = '';

        //以下设置Email内容
        $this->email->from($this->config->item('email_path'), $this->config->item('smtp_user'));
        $this->email->to($emailAccept); // 邮件收件人
        $this->email->subject($emailSubject); // 邮件主题
        $this->email->message($emailMessage); // 邮件内容
        if($emailAttach){
            $this->email->attach($emailAttach); // 附件信息
        }

        $sendStatus = $this->email->send();
dump($sendStatus);
        return $sendStatus;
    }
}
