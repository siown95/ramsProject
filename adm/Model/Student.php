<?php
include $_SERVER['DOCUMENT_ROOT'] . "/adm/Model/Model.php";

class StudentInfo extends Model
{
    var $table_name = 'member_studentm';

    function __construct()
    {
        parent::__construct();
    }

    public function getStudentData($center_type)
    {
        $year = date("Y");
        $last_year = date('Y', strtotime("-1 year"));

        if ($center_type == 'all') {
            $type_where = "a";
        } else if ($center_type == '01') {
            $type_where = "d"; //직영
        } else {
            $type_where = "f"; //가맹
        }

        $sql = "exec sp_student_statistic_month '" . $last_year . "-01-01 00:00:00','" . $year . "-12-31 23:59:59','" . $type_where . "'";

        $result = $this->db->sqlRowArr($sql);
        return $result;
    }

    public function getFranchiseList($center_type)
    {
        $where_qry = "";
        if ($center_type == '01') {
            $where_qry = "AND franchise_type = '01'";
        } else if ($center_type == '02') {
            $where_qry = "AND franchise_type = '02'";
        } else {
            return false;
        }

        $sql = "SELECT franchise_idx, center_name FROM franchiseM WHERE useyn = 'Y' " . $where_qry;

        $result = $this->db->sqlRowArr($sql);
        return $result;
    }

    public function getAnalysisStudentData1($from_date, $center_type, $center_idx)
    {
        $where_center_type = '';
        $where_center_idx = '';

        if ($center_type == 'all') {
            $where_center_type = " AND franchise_type IN ('01', '02')";
        } else if ($center_type == '01') {
            $where_center_type = " AND franchise_type = '01'";
        } else if ($center_type == '02') {
            $where_center_type = " AND franchise_type ='02'";
        } else {
            return false;
        }

        if ($center_idx != 'all') {
            $where_center_idx = "AND mss.franchise_idx = '" . $center_idx . "'";
            $where_center_type .= " AND franchise_idx = '" . $center_idx . "' ";
        }

        $sql = "SELECT 
                (SELECT COUNT(mss.user_no) cnt FROM member_studentM mss 
                WHERE mss.state = '00' AND dbo.fn_birth2Grade(mss.birth) = dbo.fn_birth2Grade(ms.birth) " . $where_center_idx . "
                GROUP BY dbo.fn_birth2Grade(mss.birth)) totcnt,
                dbo.fn_birth2Grade(ms.birth) grade, 
                lm.lesson_type, 
                COUNT(distinct ms.user_no) cnt 
                FROM member_studentM ms
                LEFT OUTER JOIN lessonM lm ON ms.franchise_idx = lm.franchise_idx AND lm.franchise_idx = ms.franchise_idx
                LEFT OUTER JOIN lessonT lt ON ms.user_no = lt.student_idx
                WHERE ms.franchise_idx in (SELECT franchise_idx FROM franchiseM WHERE useyn = 'Y' " . $where_center_type . ")
                AND lm.lesson_date BETWEEN '" . $from_date . "-01' AND '" . date("Y-m-t", strtotime($from_date)) . "'
                AND ms.state = '00' AND lm.schedule_idx = lt.schedule_idx
                GROUP BY dbo.fn_birth2Grade(ms.birth), lm.lesson_type";
        $result = $this->db->sqlRowArr($sql);

        return $result;
    }

    public function getAnalysisStudentData2($from_date, $center_type, $center_idx)
    {
        $where_center_type = '';
        $where_center_idx = '';

        if ($center_type == 'all') {
            $where_center_type = " AND franchise_type IN ('01', '02')";
        } else if ($center_type == '01') {
            $where_center_type = " AND franchise_type = '01'";
        } else if ($center_type == '02') {
            $where_center_type = " AND franchise_type ='02'";
        } else {
            return false;
        }

        if ($center_idx != 'all') {
            $where_center_idx = " AND mss.franchise_idx = '" . $center_idx . "' ";
            $where_center_type .= " AND franchise_idx = '" . $center_idx . "' ";
        }

        $sql = "SELECT 
                (SELECT COUNT(mss.user_no) cnt FROM member_studentM mss 
                WHERE mss.state = '00' AND dbo.fn_birth2Grade(mss.birth) = dbo.fn_birth2Grade(ms.birth) " . $where_center_idx . "
                GROUP BY dbo.fn_birth2Grade(mss.birth)) totcnt,
                dbo.fn_birth2Grade(ms.birth) grade, 
                lm.lesson_type, 
                COUNT(distinct ms.user_no) cnt 
                FROM member_studentM ms
                LEFT OUTER JOIN lessonM lm ON ms.franchise_idx = lm.franchise_idx AND lm.franchise_idx = ms.franchise_idx
                LEFT OUTER JOIN lessonT lt ON ms.user_no = lt.student_idx
                WHERE ms.franchise_idx in (SELECT franchise_idx FROM franchiseM WHERE useyn = 'Y' " . $where_center_type . ")
                AND lm.lesson_date BETWEEN '" . $from_date . "-01' AND '" . date("Y-m-t", strtotime($from_date)) . "'
                AND ms.state = '00' AND lm.schedule_idx = lt.schedule_idx
                GROUP BY dbo.fn_birth2Grade(ms.birth), lm.lesson_type";
        $result = $this->db->sqlRowArr($sql);

        return $result;
    }
}
