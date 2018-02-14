<?php

class Teacher_model extends CI_Model {
	const WG_TEACHERS_TABLE = 'wg_teachers';

	public function __construct() {
		parent::__construct();
		$this->DB = $this->load->database('default', true);
	}

	/**
	 * [getBasicInfo description]
	 * @param  array   $where  [筛选条件]
	 * @param  integer $offset [分页]
	 * @param  integer $limit  [条数]
	 * @param  string  $order  [排序方式]
	 * @return [array]          [description]
	 */
	public function getBasicInfo($where = array(), $offset = 0, $limit = 0, $order = 'id desc') {
		$result = array();
		$this->DB->from(self::WG_TEACHERS_TABLE);
		if (!empty($where)) {
			$this->DB->where($where);
		}

		$this->DB->where("flag >", 0);
		if ($limit) {
			$this->DB->limit($limit, $offset);
		}
		$this->DB->order_by($order);
		$query = $this->DB->get();
		if ($query && $query->num_rows() > 0) {
			$result['total'] = $query->num_rows();
			$result['list'] = $query->result_array();
		}
		return $result;
	}

	/**
	 * 添加教师信息
	 * @param array $data [description]
	 */
	public function addTeacherInfo($data = array()) {
		$result = array();

		if (isset($data['mobile'])) {
			$data['mobile'] = $data['mobile'] ? $data['mobile'] : 0;
		}
		$detailData = array(
			'realname' => $data['realname'],
			'mobile' => $data['mobile'],
			'createtime' => date('Y-m-d H:i:s'),
			'mail' => $data['mail'],
			'password' => md5($data['password']),
			'leavetime' => "0000-00-00 00:00:00",
			'flag' => 1,
		);
		$this->DB->insert(self::WG_TEACHERS_TABLE, $detailData);
		if (!$this->DB->affected_rows()) {
			return false;
		}
		if ($result['insertId'] = $this->DB->insert_id()) {
			$result['status'] = true;
		}
		return $result;
	}

	/**
	 * 更新教师信息
	 * @param  integer $teacher_id [更新的教师 id]
	 * @param  array   $data       [要更新的字段数据]
	 * @return [boolean]              [返回更新成功、失败]
	 */
	public function updateTeacherInfo($teacher_id = 0, $data = array()) {
		if (!$teacher_id) {
			return false;
		}

		$this->DB->where('id', $teacher_id);
		$this->DB->update(self::WG_TEACHERS_TABLE, $data);

		if ($this->DB->affected_rows() <= 0) {
			return false;
		}

		return true;
	}

	/**
	 * 删除教师信息
	 * 注意：不去删除教师信息，只是将flag改为-1（直接删除的话，就找不回来了）
	 * @param  integer $teacher_id [教师id]
	 * @return [Boolean]              [删除成功、失败]
	 */
	public function deleteTeacherInfo($teacher_id = 0) {
		if (!$teacher_id) {
			return false;
		}

		$data['flag'] = -1;
		$this->DB->where('id', $teacher_id);
		$this->DB->update(self::WG_TEACHERS_TABLE, $data);

		if ($this->DB->affected_rows() <= 0) {
			return false;
		}

		return true;
	}

}
?>