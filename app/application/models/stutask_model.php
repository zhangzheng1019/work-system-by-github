<?php

class Stutask_model extends CI_Model
{
    const WG_STU_TASK_TABLE = 'wg_stu_task';

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
     * @return [array]         [description]
     */
    public function getBasicInfo($where = array(), $offset = 0, $limit = 0, $order = 'id desc')
    {
        $result = array();
        $this->DB->from(self::WG_STU_TASK_TABLE);
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
    /**
     * 统计
     * @param  array  $where [description]
     * @param  string $order [description]
     * @return [type]        [description]
     */
    public function getTotalNum($where = array(), $order = 'id desc')
    {
        $total = 0;
        $this->DB->from(self::WG_STU_TASK_TABLE);
        if (!empty($where)) {
            $this->DB->where($where);
        }
        $this->DB->order_by($order);
        $query = $this->DB->get();
        if ($query && $query->num_rows() > 0) {
            $total = $query->num_rows();
        }
        return $total;
    }
    /**
     * 添加任务信息
     * @param array $data [description]
     */
    public function addStuTaskInfo($data = array())
    {
        $result = array();

        $detailData = array(
            'student_id'   => $data['student_id'] ? $data['student_id'] : '',
            'task_id'      => $data['task_id'] ? $data['task_id'] : '',
            'course_id'    => $data['course_id'] ? $data['course_id'] : '',
            'tea_response' => $data['tea_response'] ? $data['tea_response'] : '',
            'createtime'   => date('Y-m-d H:i:s'),
            'modifytime'   => '0000-00-00 00:00:00',
            'flag'         => -1,
        );
        $this->DB->insert(self::WG_STU_TASK_TABLE, $detailData);
        if (!$this->DB->affected_rows()) {
            return [];
        }
        $result['insertId'] = $this->DB->insert_id();
        $result['status']   = ($result['insertId'] > 0) ? true : false;

        return $result;
    }

    /**
     * 更新学生任务信息
     * @param  integer $id [更新 id]
     * @param  array   $data       [要更新的字段数据]
     * @return [boolean]              [返回更新成功、失败]
     */
    public function updateStuTaskInfo($id = 0, $data = array())
    {
        if (!$id) {
            return false;
        }

        $this->DB->where('id', $id);
        $this->DB->update(self::WG_STU_TASK_TABLE, $data);

        if ($this->DB->affected_rows() <= 0) {
            return false;
        }

        return true;
    }

    /**
     * 删除任务信息
     * 注意：不去删除任务信息，只是将flag改为-1（直接删除的话，就找不回来了）
     * @param  integer $task_id [任务id]
     * @return [Boolean]              [删除成功、失败]
     */
    public function deleteStuTaskInfo($task_id = 0)
    {
        if (!$task_id) {
            return false;
        }

        $data['flag'] = -1;
        $this->DB->where('task_id', $task_id);
        $this->DB->update(self::WG_STU_TASK_TABLE, $data);

        if ($this->DB->affected_rows() <= 0) {
            return false;
        }

        return true;
    }

    /**
     * 根据任务id 获取该学生的任务状态
     * @return [type] [description]
     */
    public function getTaskInfoByTaskIdAndStuId($taskId, $stuId)
    {
        if (!$taskId && !$stuId) {
            return [];
        }

        $this->DB->select();
        $this->DB->from(self::WG_STU_TASK_TABLE);
        $this->DB->where('student_id', $stuId);
        $this->DB->where('task_id', $taskId);
        $query = $this->DB->get();
        if ($query && $query->num_rows() > 0) {
            $result = $query->result_array();
        }
        return $result;
    }
}
