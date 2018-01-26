<?php 

class Student_model extends CI_Model {
	const WG_STUDENTS_TABLE 		= 'wg_students';

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
	public function getBasicInfo($where = array(),$offset= 0,$limit=0, $order='id desc')
	{
		$result = array();
		$this->DB->from(self::WG_STUDENTS_TABLE);
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
			$result = $query->result_array();
		}
		return $result;	
	}

	/**
	 * 添加学生信息
	 * @param array $data [description]
	 */
	public function addStudentInfo($data = array())
	{
		$result = array();

 		$detailData = array(
			'realname'      => $data['realname'],
			'github_account'=> $data['github_account'],
			'github_info'	=> $data['github_info'],
			'createtime' 	=> date('Y-m-d H:i:s'),
			'grade'	        => $data['grade'],
			'class'	        => $data['class'],
			'teacher_id'	=> $data['teacher_id'],
			'course_id'	    => $data['course_id'],
			'flag'          => 1
		);
		$this->DB->insert(self::WG_STUDENTS_TABLE, $detailData);
		if (!$this->DB->affected_rows()) {
			return false;
		}
		if($result['insertId'] = $this->DB->insert_id()){
			$result['status'] = true;
		}
		return $result;
	}

	/**
	 * 更新学生信息
	 * @param  integer $student_id [更新的学生 id]
	 * @param  array   $data       [要更新的字段数据]
	 * @return [boolean]              [返回更新成功、失败]
	 */
	public function updateStudentInfo($student_id = 0,$data = array())
	{
		if(!$student_id){
			return false;
		}

		$this->DB->where('id',$student_id);
		$this->DB->update(self::WG_STUDENTS_TABLE, $data);

		if ($this->DB->affected_rows() <= 0) {
			return false;
		}

		return true;
	}

	/**
	 * 删除学生信息
	 * 注意：不去删除学生信息，只是将flag改为-1（直接删除的话，就找不回来了）
	 * @param  integer $student_id [学生id]
	 * @return [Boolean]              [删除成功、失败]
	 */
	public function deleteStudentInfo($student_id = 0)
	{
		if(!$student_id){
			return false;
		}

		$data['flag'] = -1;
		$this->DB->where('id',$student_id);
		$this->DB->update(self::WG_STUDENTS_TABLE, $data);

		if ($this->DB->affected_rows() <= 0) {
			return false;
		}

		return true;
	}

}
 ?>