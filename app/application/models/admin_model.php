<?php

class Admin_model extends CI_Model
{
    const WG_ENDUSER_TABLE = 'wg_enduser';

    public function __construct()
    {
        parent::__construct();
        $this->DB = $this->load->database('default', true);
    }
    /**
     * 得到后台用户信息
     * @param  array  $where [description]
     * @return [type]        [description]
     */
    public function getInfo($where = array())
    {
        $result = array();
        $this->DB->from(self::WG_ENDUSER_TABLE);
        if (!empty($where)) {
            $this->DB->where($where);
        }
        $query = $this->DB->get();
        if ($query && $query->num_rows() > 0) {
            $result = $query->result_array();
        }
        return $result;
    }
    /**
     * 添加后台用户信息
     * @param string $userType [description]
     * @param array  $data     [description]
     */
    public function addEndUser($userType = '', $data = array())
    {
        $result     = array();
        $isAdmin    = ($userType == 'teacher') ? 1 : 0;
        $detailData = array(
            'name'     => $data['mail'] ? $data['mail'] : '',
            'password' => md5($data['password']),
            'is_admin' => $isAdmin,
        );
        $this->DB->insert(self::WG_ENDUSER_TABLE, $detailData);
        if (!$this->DB->affected_rows()) {
            return false;
        }
        $result['insertId'] = $this->DB->insert_id();
        $result['status']   = ($result['insertId'] > 0) ? true : false;

        return $result;
    }
}
