<?php

class Student_model extends CI_Model
{
    const WG_STUDENTS_TABLE = 'wg_students';

    public function __construct()
    {
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
    public function getBasicInfo($where = array(), $offset = 0, $limit = 0, $order = 'id desc')
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
            $result['list'] = $query->result_array();
        }
        return $result;
    }
    /**
     * 记录总数
     * @param  array   $where  [description]
     * @param  integer $offset [description]
     * @param  integer $limit  [description]
     * @param  string  $order  [description]
     * @return [type]          [description]
     */
    public function getTotalNum($where = array(), $order = 'id desc')
    {
        $total = 0;
        $this->DB->from(self::WG_STUDENTS_TABLE);
        if (!empty($where)) {
            $this->DB->where($where);
        }

        $this->DB->where("flag >", 0);

        $this->DB->order_by($order);
        $query = $this->DB->get();
        if ($query && $query->num_rows() > 0) {
            $total = $query->num_rows();
        }
        return $total;
    }
    /**
     * 添加学生信息
     * @param array $data [description]
     */
    public function addStudentInfo($data = array())
    {
        $result = array();

        // 需要修改teacher_id 和 courser_id
        $detailData = array(
            'realname'    => $data['realname'] ? $data['realname'] : '',
            'github_info' => $data['github_info'] ? $data['github_info'] : '',
            'createtime'  => date('Y-m-d H:i:s'),
            'grade'       => $data['grade'] ? $data['grade'] : '',
            'class'       => $data['class'] ? $data['class'] : '',
            'thumb'       => $data['thumb'] ? $data['thumb'] : '',
            'desc'        => $data['desc'] ? $data['desc'] : '',
            'teacher_id'  => $data['teacher_id'] ? $data['teacher_id'] : 0,
            'course_id'   => $data['course_id'] ? $data['course_id'] : 0,
            'github_id'   => $data['github_id'] ? $data['github_id'] : 0,
            'flag'        => 1,
        );
        $this->DB->insert(self::WG_STUDENTS_TABLE, $detailData);
        if (!$this->DB->affected_rows()) {
            return [];
        }
        $result['insertId'] = $this->DB->insert_id();
        $result['status']   = ($result['insertId'] > 0) ? true : false;

        return $result;
    }

    /**
     * 更新学生信息
     * @param  integer $student_id [更新的学生 id]
     * @param  array   $data       [要更新的字段数据]
     * @return [boolean]              [返回更新成功、失败]
     */
    public function updateStudentInfo($student_id = 0, $data = array())
    {
        if (!$student_id) {
            return false;
        }

        $this->DB->where('id', $student_id);
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
        if (!$student_id) {
            return false;
        }

        $data['flag'] = -1;
        $this->DB->where('id', $student_id);
        $this->DB->update(self::WG_STUDENTS_TABLE, $data);

        if ($this->DB->affected_rows() <= 0) {
            return false;
        }

        return true;
    }

    /**
     * 根据学生id 得到课程信息
     * @param  [int] $id [学生id]
     * @return [array]     [课程信息]
     */
    public function getCourseByStuId($id)
    {
        if (!$id) {
            return [];
        }
        $stuInfo   = $this->getBasicInfo(array('id' => $id));
        $courseArr = $stuInfo['course_id'];
        if (!$courseArr) {
            return [];
        }

        $courseId = explode(',', $courseArr);
        $this->load->model("course_model");
        $result = array();
        foreach ($courseId as $key => $value) {
            $result[$key]['course_info'] = $this->course_model->getBasicInfo(array('id' => $value));
        }

        return $result;
    }

    /**
     * 根据学生id获取教师列表
     * @param  [int] $id [学生id]
     * @return [type]     [教师列表]
     */
    public function getTeacherByStuId($id)
    {
        if (!$id) {
            return [];
        }
        $stuInfo    = $this->getBasicInfo(array('id' => $id));
        $teacherArr = $stuInfo['teacher_id'];
        if (!$teacherArr) {
            return [];
        }
        $teacherId = explode(',', $teacherArr);
        $this->load->model("teacher_model");
        $result = array();
        foreach ($teacherId as $key => $value) {
            $result[$key]['teacher_info'] = $this->teacher_model->getBasicInfo(array('id' => $value));
        }

        return $result;
    }
    /**
     * 对某个字段值分组
     * @param  [type] $field [字段]
     * @return [type]        [description]
     */
    public function studentGroupBy($field = '*')
    {
        $result = null;
        $this->DB->select($field);
        $this->DB->from(self::WG_STUDENTS_TABLE);
        $this->DB->where("flag >", 0);
        $this->DB->group_by($field);
        $query = $this->DB->get();
        if ($query && $query->num_rows() > 0) {
            $data = $query->result_array();
        }
        foreach ($data as $key => $value) {
            $result[$key]['key']   = $key;
            $result[$key]['value'] = $value[$field];
        }
        return $result;
    }
    /**
     * 根据课程id 更新学生列表(学生加入课程操作)
     * @param  [type] $gradeId [description]
     * @return [type]          [description]
     */
    public function updateStuByStuIdAndCourseId($studentId, $courseId)
    {
        if (!$studentId) {
            return false;
        }
        $stuInfo   = $this->getBasicInfo(array('id' => $studentId));
        $courseIds = $stuInfo['list'][0]['course_id'];

        if (empty($courseIds)) {
            $courseIds = $courseId;
        } else {
            $courseArr = explode(',', $courseIds);
            if (inArray($courseId, $courseArr)) {
                return false;
            }
            array_push($courseArr, $courseId);
            $courseIds = implode(',', $courseArr);
        }
        $data['course_id'] = $courseIds;
        $this->DB->where('id', $studentId);
        $this->DB->update(self::WG_STUDENTS_TABLE, $data);

        if ($this->DB->affected_rows() <= 0) {
            return false;
        }

        return true;
    }
    /**
     * 根据课程Id获取该课程人数
     * @param  [type] $courseId [description]
     * @return [type]           [description]
     */
    public function getStudentNumByCourseId($courseId)
    {
        if (!$courseId) {
            return 0;
        }
        $allStudent = $this->getBasicInfo();
        return count(filterData($allStudent['list'], $courseId, 'course_id'));
    }
}
