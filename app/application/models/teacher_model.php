<?php 

class Teacher_model extends CI_Model {
	const WG_TEACHERS_TABLE 		= 'wg_teachers';

	public function __construct() {
		parent::__construct();
		$this->DB = $this->load->database('default', true);
	}

	/**
	 * 查询数据
	 * @param  [type] $id [description]
	 * @return [type]     [description] 
	 */
	public function getBasicInfoById($where = array(),$offset= 0,$limit=20, $order='id desc')
	{
		$result = array();
		$this->DB->from(self::WG_TEACHERS_TABLE);
		if (!empty($where)) {
			$this->DB->where($where);
		}
		if ($limit) {
			$this->DB->limit($limit, $offset);
		}
		$this->DB->order_by($order);
		$query = $this->DB->get();
		if ($query && $query->num_rows() > 0) {
			$result = $query->result_array();
		}
		return $result;	
	}

	public function addTeacherInfo($data = array())
	{
		if(isset($data['mobile'])){
			$data['mobile'] = $data['mobile'] ? $data['mobile'] : 0;
		}
 		$detailData = array(
			'realname'      => $data['realname'],
			'mobile'		=> $data['mobile'],
			'createtime' 	=> date('Y-m-d H:i:s'),
			'mail'			=> $data['mail'],
			'password'	    => md5($data['password']),
			'leavetime'		=> "0000-00-00 00:00:00",
			'flag'          => 1
		);
		$this->DB->insert(self::WG_TEACHERS_TABLE, $detailData);
		if (!$this->DB->affected_rows()) {
			return false;
		}
		return true;
	}
}

 ?>