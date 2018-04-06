<?php

class Task_model extends CI_Model
{
    const WG_TASK_TABLE = 'wg_task';

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
        $this->DB->from(self::WG_TASK_TABLE);
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
     * 添加任务信息
     * @param array $data [description]
     */
    public function addTaskInfo($data = array())
    {
        $result = array();

        $detailData = array(
            'name'       => $data['name'],
            'desc'       => $data['desc'],
            'startime'   => $data['startime'],
            'endtime'    => $data['endtime'],
            'course_id'  => $data['course_id'],
            'createtime' => date('Y-m-d H:i:s'),
            'modifytime' => '0000-00-00 00:00:00',
            'flag'       => 1,
        );
        $this->DB->insert(self::WG_TASK_TABLE, $detailData);
        if (!$this->DB->affected_rows()) {
            return [];
        }
        $result['insertId'] = $this->DB->insert_id();
        $result['status']   = ($result['insertId'] > 0) ? true : false;

        return $result;
    }

    /**
     * 更新任务信息
     * @param  integer $task_id [更新的任务 id]
     * @param  array   $data       [要更新的字段数据]
     * @return [boolean]              [返回更新成功、失败]
     */
    public function updateTaskInfo($task_id = 0, $data = array())
    {
        if (!$task_id) {
            return false;
        }

        $this->DB->where('id', $task_id);
        $this->DB->update(self::WG_TASK_TABLE, $data);

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
    public function deleteTaskInfo($task_id = 0)
    {
        if (!$task_id) {
            return false;
        }

        $data['flag'] = -1;
        $this->DB->where('id', $task_id);
        $this->DB->update(self::WG_TASK_TABLE, $data);

        if ($this->DB->affected_rows() <= 0) {
            return false;
        }

        return true;
    }

}
