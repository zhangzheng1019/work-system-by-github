<?php

class Email_model extends CI_Model
{
    /**
     * 发送邮件类
     * @param  string $to          [地址]
     * @param  [type] $subject     [邮件主题]
     * @param  [type] $body        [邮件主体]
     * @return [type]              [description]
     */
    public function sendEmail($to = '', $subject = '', $body = '')
    {
        if (!$to) {
            return false;
        }
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

        $this->load->library('email'); //加载CI的email类
        $this->email->initialize($emailConf);

        $emailSubject = $subject;
        $emailMessage = '<html><head><title>' . $subject . '</title></head><body>';
        $emailMessage .= $body;
        $emailMessage .= '<p><font color="gray">基于github作业统计系统</font></p>';
        $emailMessage .= '<p><font color="gray">©github三人组, 2018.</font></p>';
        $emailMessage .= '<p><font color="gray">此邮件为系统自动发送，请勿回复。</font></p>';
        $emailMessage .= '</body></html>';
        $emailAttach = '';

        //以下设置Email内容
        $this->email->from($this->config->item('email_path'), $this->config->item('smtp_user'));
        $this->email->to($to); // 邮件收件人
        $this->email->subject($emailSubject); // 邮件主题
        $this->email->message($emailMessage); // 邮件内容
        if ($emailAttach) {
            $this->email->attach($emailAttach); // 附件信息
        }

        $sendStatus = $this->email->send();
        return $sendStatus;
    }
}
