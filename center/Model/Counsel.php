<?php
include $_SERVER['DOCUMENT_ROOT'] . "/center/Model/Model.php";

class CounselInfo extends Model
{
    var $counsel_table_name = "counselt";
    var $counsel_new_table_name = "counsel_newt";
    var $table_name = '';

    function __construct()
    {
        parent::__construct();
    }

    //정규상담 함수 목록
    public function studentSearch($franchise_idx, $student_name)
    {
        $sql = "SELECT user_no, user_name, school_name, (YEAR(getdate()) - LEFT(birth, 4) + 1) user_age FROM member_studentm 
                WHERE state = '00' AND  franchise_idx = '" . $franchise_idx . "' AND user_name LIKE ('%" . $student_name . "%')";
        $result = $this->db->sqlRowArr($sql);

        return $result;
    }

    public function counselUpdate($params)
    {
        $this->table_name = $this->counsel_table_name;
        $this->where_qry = " counsel_idx = '" . $params['counsel_idx'] . "' ";

        $result = $this->update($params);
        return $result;
    }

    public function counselDelete($counsel_idx)
    {
        $this->table_name = $this->counsel_table_name;
        $this->where_qry = " counsel_idx = '" . $counsel_idx . "' ";

        $result = $this->delete();
        return $result;
    }

    public function counselLoad($franchise_idx, $counsel_month)
    {
        $sql = "SELECT C.counsel_idx, S.user_name student_name, M.user_name teacher_name, convert(varchar(19), C.counsel_date, 120) counsel_date
                , convert(varchar(19), C.reg_date, 120) reg_date
                FROM {$this->counsel_table_name} C
                LEFT OUTER JOIN member_centerm M
                ON C.writer_no = M.user_no
                LEFT OUTER JOIN member_studentm S
                ON C.student_no = S.user_no 
                WHERE C.franchise_idx = '" . $franchise_idx . "'
                AND C.reg_date BETWEEN '" . date("Y-m", strtotime($counsel_month)) . "-01' AND '" . date("Y-m-t", strtotime($counsel_month)) . "'";
        $result = $this->db->sqlRowArr($sql);

        return $result;
    }

    public function counselSelect($counsel_idx)
    {
        $sql = "SELECT S.user_name student_name,(YEAR(getdate()) - LEFT(S.birth, 4) + 1) student_age, S.school_name
                , convert(varchar(19), C.counsel_date, 120) counsel_date, C.counsel_kind, C.counsel_method, C.counsel_contents
                , C.counsel_followup, C.counsel_discharge_reason, C.counsel_discharge_contents, C.counsel_request
                , C.counsel_open
                FROM {$this->counsel_table_name} C
                LEFT OUTER JOIN member_studentm S
                ON C.student_no = S.user_no 
                WHERE C.counsel_idx = '" . $counsel_idx . "'";

        $result = $this->db->sqlRow($sql);
        return $result;
    }
    //정규상담 종료

    //신규상담 함수 목록
    public function counselNewLoad($franchise_idx, $counsel_month)
    {
        $sql = "SELECT C.counsel_idx, C.counselee_name student_name, M.user_name teacher_name, convert(varchar(19), C.counsel_date, 120) counsel_date
                , convert(varchar(19), C.reg_date, 120) reg_date
                FROM {$this->counsel_new_table_name} C
                LEFT OUTER JOIN member_centerm M
                ON C.writer_no = M.user_no
                WHERE C.franchise_idx = '" . $franchise_idx . "'
                AND C.reg_date BETWEEN '" . date("Y-m", strtotime($counsel_month)) . "-01' AND '" . date("Y-m-t", strtotime($counsel_month)) . "'";
        $result = $this->db->sqlRowArr($sql);

        return $result;
    }

    public function counselNewInsert($params)
    {
        $this->table_name = $this->counsel_new_table_name;
        $result = $this->insert($params);

        return $result;
    }

    public function counselNewSelect($counsel_idx)
    {
        $sql = "SELECT counselee_name, gender, counsel_teacher_no, counsel_phone, school_name, counsel_grade, convert(varchar(19), counsel_date, 120) counsel_date, counsel_regist
                , counsel_class, counsel_result, counsel_contents, counsel_know
                FROM {$this->counsel_new_table_name} WHERE counsel_idx = '" . $counsel_idx . "'";

        $result = $this->db->sqlRow($sql);
        return $result;
    }

    public function counselNewDelete($counsel_idx)
    {
        $this->table_name = $this->counsel_new_table_name;
        $this->where_qry = " counsel_idx = '" . $counsel_idx . "' ";

        $result = $this->delete();
        return $result;
    }

    public function msgInfoSelect($franchise_idx, $months, $flag)
    {
        $where_arr = array(
            "C.franchise_idx = '" . $franchise_idx . "'"
        );

        if (!empty($months)) {
            $where_arr[] .= " C.counsel_date BETWEEN '". date('Y-m-d', strtotime($months . '-01')) ."' AND '". date('Y-m-t', strtotime($months)) ."' ";
        }
        $where_query = "WHERE " . implode(" AND ", $where_arr);
        if (!empty($flag) == 'n') {
            $sql = "SELECT counselee_name as user_name, counsel_phone as user_phone FROM counsel_newT C " . $where_query;
        } else if (!empty($flag) == 'o') {
            $sql = "SELECT MS.user_name, MS.user_phone FROM member_studentM MS
                    LEFT OUTER JOIN counselT C ON MS.franchise_idx = C.franchise_idx AND MS.user_no = C.student_no" . $where_query;
        } else {
            return false;
        }

        $result = $this->db->sqlRowArr($sql);
        return $result;
    }
}
