<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Course extends CI_Controller
{
    const LIMIT = 12;

    public function __construct()
    {
        parent::__construct();
        $this->DB = $this->load->database("default", true);
        $this->load->model("course_model");
    }
    public function getGradeByConfig()
    {
        $this->config->load("classids", true);
        $ids = $this->config->item('classids');
        $i     = 0;
        $grade = [];
        foreach ($ids as $key => $value) {
            $grade[$i]['key']   = $i;
            $grade[$i]['value'] = $key;
            $i++;
        }
        return $grade;
    }
    /**
     * 获取教师课程列表
     * @return [type] [description]
     */
    public function getTeacherList()
    {
        $where = [];
        $email = $_COOKIE['userid'];
        $this->load->model('teacher_model');
        $teacherInfo = $this->teacher_model->getBasicInfo(array('mail' => $email));
        $teacherId   = $teacherInfo['list'][0]['id'];

        if (!$teacherId) {
            header('Location:/login');
        }
        $gradeGroup = $this->getGradeGroupByTeacherId($teacherId);
        if (!$gradeGroup) {
            $gradeGroup = $this->course_model->courseGroupBy('grade_id', '', 4, 'grade_id desc');
            // $gradeGroup = $this->getGradeByConfig();
        }
            // $gradeGroup = $this->getGradeByConfig();

        $grade = $this->input->post('grade') ? $this->input->post('grade') : $gradeGroup[0]['value'];

        $where = array(
            'teacher_id' => $teacherId,
            'grade_id'   => $grade,
        );
        $page   = $this->input->post('page') ? $this->input->post('page') : 1;
        $offset = ($page - 1) * self::LIMIT;

        $total = $this->course_model->getTotalNum($where);
        $data  = $this->course_model->getBasicInfo($where, $offset, self::LIMIT);

        $this->load->model("student_model");
        foreach ($data as $key => $value) {
            $data[$key]['stuNumber'] = $this->student_model->getStudentNumByCourseId($value['id']);
        }

        $result['total']        = $total;
        $result['pageSize']     = self::LIMIT;
        $result['gradeGroup']   = $gradeGroup;
        $result['currentGrade'] = $grade;
        $result['list']         = $data;
        $result['list'] ? ajax_success($result, "加载成功") : ajax_success($result, '快来添加课程吧');
    }
    /**
     * 添加课程
     */
    public function addCourse()
    {
        $data   = array();
        $id     = $this->input->post('id');
        $status = false;
        if ($id) {
            // 修改
            $courseInfo       = $this->course_model->getBasicInfo(array('id' => $id));
            $data             = $courseInfo['list'];
            $data['title']    = isset($_POST['name']) ? $_POST['name'] : $data['title'];
            $data['desc']     = isset($_POST['desc']) ? $_POST['desc'] : $data['desc'];
            $data['thumb']    = isset($_POST['thumb']) ? $_POST['thumb'] : $data['thumb'];
            $data['grade_id'] = isset($_POST['grade_id']) ? $_POST['grade_id'] : $data['grade_id'];
            $status           = $this->course_model->updateCourseInfo($id, $data);
            !$status && ajax_fail(false, '没有修改呦！');
        } else {
            //添加
            $data['title']      = isset($_POST['name']) ? $_POST['name'] : '';
            $data['desc']       = isset($_POST['desc']) ? $_POST['desc'] : '';
            $data['thumb']      = isset($_POST['thumb']) ? $_POST['thumb'] : '';
            $data['teacher_id'] = isset($_POST['teacher_id']) ? $_POST['teacher_id'] : '';
            $data['grade_id']   = isset($_POST['grade_id']) ? $_POST['grade_id'] : '';
            $data['repos']      = isset($_POST['repos']) ? $_POST['repos'] : '';
            $status             = $this->course_model->addCourseInfo($data);
            $status             = $status['status'];
            !$status && ajax_fail(false, '操作失败');
        }
        $status && ajax_success($status, '操作成功');
    }

    /**
     * 获取年级
     * @return [type] [description]
     */
    public function getGradeGroupByTeacherId($teacher_id)
    {
        if (!$teacher_id) {
            return [];
        }
        $where = array(
            'teacher_id' => $teacher_id,
        );
        $gradeGroup = $this->course_model->courseGroupBy('grade_id', $where, 4, 'grade_id desc');
        return $gradeGroup;
    }

    /**
     * 获取学生课程列表
     * 展示规则：先展示已加入的课程，后显示该年级且学生所属老师的课程（未加入）
     * 数据获取规则：获取年级所有课程,再用not_in查询
     * @return [type] [description]
     */
    public function getStudentList()
    {
        $courseList = array();
        $userId     = $_COOKIE['userid'];
        if (!$userId) {
            header('Location:/login');
        }
        $userWhere = array(
            'id' => $userId,
        );
        $this->load->model("student_model");
        $userInfo      = $this->student_model->getBasicInfo($userWhere);
        $userCourseIds = $userInfo['list'][0]['course_id'];

        $page   = $this->input->post('page') ? $this->input->post('page') : 1;
        $offset = ($page - 1) * self::LIMIT;

        //获取学生所在年级的课程
        if (!$userCourseIds) {
            $courseWhere = array(
                'grade_id' => $userInfo['list'][0]['grade'],
            );
            $courseList = $this->course_model->getBasicInfo($courseWhere);
        } else {
            // 获取已加入课程
            $courseIdArr = explode(',', $userInfo['list'][0]['course_id']);
            $courseList  = $this->course_model->getCourseByIds($courseIdArr, 'id');
            foreach ($courseList as $key => $value) {
                $courseList[$key]['addStatus'] = 1;
            }
            $courseNotList = $this->course_model->getNotCourseByIds($courseIdArr, 'id', $userInfo['list'][0]['grade']);
            $courseList    = array_merge($courseList, $courseNotList);
        }
        $this->load->model('teacher_model');
        foreach ($courseList as $key => $value) {
            $teacherInfo                     = $this->teacher_model->getBasicInfo(array('id' => $value['teacher_id']));
            $courseList[$key]['teacherInfo'] = $teacherInfo['list'][0];
            $courseList[$key]['stuNumber']   = $this->student_model->getStudentNumByCourseId($value['id']);
        }
        $data['total']    = count($courseList);
        $data['pageSize'] = self::LIMIT;
        $data['list']     = array_slice($courseList, $offset, self::LIMIT);
        ajax_success($data, '加载成功');
    }
    /**
     * 获取课程列表年级的分组（取前4）
     * @return [type] [description]
     */
    public function getGrade()
    {
        $gradeGroup = $this->course_model->courseGroupBy('grade_id', $where, 4, 'grade_id desc');
        ajax_success($gradeGroup);
    }

    /**
     * 获取课程信息
     * @return [type] [description]
     */
    public function getCourseInfo()
    {
        $data     = array();
        $courseId = $this->input->get('course_id');
        if ($_COOKIE['userrole'] == 'teacher') {
            $teacherId  = $this->input->get('teacher_id');
            $gradeGroup = $this->getGradeGroupByTeacherId($teacherId);
        }
        if (!$courseId) {
            ajax_fail(false, '暂无该课程');
        }
        $where = array(
            'id' => $courseId,
        );
        $courseInfo                 = $this->course_model->getBasicInfo($where);
        $courseInfo[0]['reposdesc'] = '注意：1、请同学们务必使用&nbsp;&nbsp;<h3 class="dil font-red">' . $courseInfo[0]['repos'] . '</h3>&nbsp;&nbsp;作为仓库名称；2、仓库下文件夹命名格式：task_01、task_02、task_12';
        $data['course']             = $courseInfo[0];
        $data['grade']              = $gradeGroup ? $gradeGroup : [];

        ajax_success($data);
    }
}
