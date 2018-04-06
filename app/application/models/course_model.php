<?php

class Course_model extends CI_Model
{
    const WG_COURSE_TABLE = 'wg_course';

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
        $this->DB->from(self::WG_COURSE_TABLE);
        if (!empty($where)) {
            $this->DB->where($where);
        }

        $this->DB->where("flag >", 0);
        if ($limit) {
            $this->DB->limit($limit, $offset); //从$offset开始查$limit条
        }
        $this->DB->order_by($order);
        $query = $this->DB->get();
        if ($query && $query->num_rows() > 0) {
            $result = $query->result_array();
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
        $this->DB->from(self::WG_COURSE_TABLE);
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
     * 添加课程信息
     * @param array $data [description]
     */
    public function addCourseInfo($data = array())
    {
        $result = array();

        $detailData = array(
            'title'      => $data['title'] ? $data['title'] : '',
            'desc'       => $data['desc'] ? $data['desc'] : '',
            'createtime' => date('Y-m-d H:i:s'),
            'modifytime' => '0000-00-00 00:00:00',
            'thumb'      => $data['thumb'] ? $data['thumb'] : '',
            'teacher_id' => $data['teacher_id'] ? $data['teacher_id'] : 0,
            'task_id'    => $data['task_id'] ? $data['task_id'] : '',
            'grade_id'   => $data['grade_id'] ? $data['grade_id'] : 0,
            'flag'       => 1,
        );
        $this->DB->insert(self::WG_COURSE_TABLE, $detailData);
        if (!$this->DB->affected_rows()) {
            return [];
        }
        $result['insertId'] = $this->DB->insert_id();
        $result['status']   = ($result['insertId'] > 0) ? true : false;

        return $result;
    }

    /**
     * 更新课程信息
     * @param  integer $course_id [更新的课程 id]
     * @param  array   $data       [要更新的字段数据]
     * @return [boolean]              [返回更新成功、失败]
     */
    public function updateCourseInfo($course_id = 0, $data = array())
    {
        if (!$course_id) {
            return false;
        }

        $this->DB->where('id', $course_id);
        $this->DB->update(self::WG_COURSE_TABLE, $data);

        if ($this->DB->affected_rows() <= 0) {
            return false;
        }

        return true;
    }

    /**
     * 删除课程信息
     * 注意：不去删除课程信息，只是将flag改为-1（直接删除的话，就找不回来了）
     * @param  integer $course_id [课程id]
     * @return [Boolean]              [删除成功、失败]
     */
    public function deleteCourseInfo($course_id = 0)
    {
        if (!$course_id) {
            return false;
        }

        $data['flag'] = -1;
        $this->DB->where('id', $course_id);
        $this->DB->update(self::WG_COURSE_TABLE, $data);

        if ($this->DB->affected_rows() <= 0) {
            return false;
        }

        return true;
    }

    /**
     * 根据课程id获取任务列表
     * @param  [type] $id [课程id]
     * @return [type]     [description]
     */
    public function getTaskByCourseId($id)
    {
        if (!$id) {
            return [];
        }
        $courseInfo = $this->getBasicInfo($id);
        $taskArr    = $courseInfo['task_id'];
        if (!$taskArr) {
            return [];
        }
        $taskId = explode(',', $taskArr);
        $this->load->model("task_model");
        $result = array();
        foreach ($taskId as $key => $value) {
            $result[$key]['task_info'] = $this->task_model->getBasicInfo(array('id' => $value));
        }

        return $result;

    }

    /**
     * 对某个字段值分组
     * @param  string  $field  [字段]
     * @param  array   $where  [筛选条件]
     * @param  integer $limit  [条数]
     * @param  string  $order  [排序方式]
     * @return [type]          [description]
     */
    public function courseGroupBy($field = '*', $where = array(), $limit = 0, $order = '')
    {
        $result = null;
        $this->DB->select($field);
        $this->DB->from(self::WG_COURSE_TABLE);
        if ($where) {
            $this->DB->where($where);
        }
        $this->DB->where("flag >", 0);
        $this->DB->group_by($field);
        if ($limit) {
            $this->DB->limit($limit);
        }
        $this->DB->order_by($order);
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
     * 得到学生已有课程
     * @param  [type] $ids [课程id]
     * @return [type]      [description]
     */
    public function getCourseByIds($ids = array(), $field = 'id')
    {
        $result = array();
        $this->DB->from(self::WG_COURSE_TABLE);
        $this->DB->where('flag >', 0);
        $this->DB->where_in($field, $ids);
        $this->DB->order_by('id desc');
        $query = $this->DB->get();
        if ($query && $query->num_rows() > 0) {
            $result = $query->result_array();
        }
        return $result;
    }
    /**
     * 得到学生所在年级的课程
     * @param  array  $ids     [description]
     * @param  string $field   [字段]
     * @param  [type] $gradeId [年级id]
     * @return [type]          [description]
     */
    public function getNotCourseByIds($ids = array(), $field = 'id',$gradeId)
    {
        $result = array();
        $this->DB->from(self::WG_COURSE_TABLE);
        $this->DB->where('flag >', 0);
        $this->DB->where('grade_id', $gradeId);
        $this->DB->where_not_in($field, $ids);
        $this->DB->order_by('id desc');
        $query = $this->DB->get();
        if ($query && $query->num_rows() > 0) {
            $result = $query->result_array();
        }
        return $result;
    }

    
    /**
     * 根据老师id和班级id 得到课程信息
     * @param  [type] $teacherId [教师id]
     * @param  [type] $gradeId   [班级id]
     * @return [type]            [description]
     */
    public function getCourseByTeaIdAndGradeId($teacherId, $gradeId)
    {
        if (!$teacherId && !$gradeId) {
            return [];
        }

        $this->DB->select();
        $this->DB->from(self::WG_COURSE_TABLE);
        $this->DB->where('teacher_id', $teacherId);
        $this->DB->where('grade_id', $gradeId);
        $query = $this->DB->get();
        if ($query && $query->num_rows() > 0) {
            $result = $query->result_array();
        }
        return $result;
    }

}
