<?php
include $_SERVER['DOCUMENT_ROOT'] . "/center/Model/Model.php";

class ActivityPaperInfo extends Model
{
    var $table_name = "activitypapert";

    function __construct()
    {
        parent::__construct();
    }

    public function activityListLoad()
    {
        $sql = "SELECT 
                      cu.months
                    , b.img_link
                    , b.book_name
                    , b.book_writer
                    , b.book_publisher
                    , c.detail 
                    , c1.code_name
                    , a.activity_student1
                    , a.activity_student2
                    , a.activity_teacher1
                    , a.activity_teacher2
                FROM curriculumm cu
                LEFT OUTER JOIN bookm b
                    ON cu.book_idx = b.book_idx
                LEFT OUTER JOIN {$this->table_name} a
                    ON a.book_idx = b.book_idx
                LEFT OUTER JOIN codem c
                    ON (b.book_category1 = c.code_num1 AND b.book_category2 = c.code_num2 AND b.book_category3 = c.code_num3)
                LEFT OUTER JOIN codem c1
                    ON (cu.grade = c1.code_num2 AND c1.code_num1 = '02')    
                WHERE cu.months BETWEEN '" . date('m', strtotime("-1 month")) . "' AND '" . date('m', strtotime("+1 month")) . "'
                AND cu.franchise_idx = '1'
                ORDER BY cu.months, c1.code_num2";

        $result = $this->db->sqlRowArr($sql);
        return $result;
    }
}