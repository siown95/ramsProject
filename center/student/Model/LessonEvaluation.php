<?php
include $_SERVER['DOCUMENT_ROOT'] . "/center/student/Model/Model.php";

class LessonEvaluationInfo extends Model
{
    var $table_name = 'lesson_evaluationT';
    var $lesson_score_table_name = 'lesson_scoreT';
    var $lesson_table_name = 'lessonM';
    var $lesson_detail_table_name = 'lessonT';

    function __construct()
    {
        parent::__construct();
    }

    public function weekEvalSelect($franchise_idx, $student_idx, $eval_month)
    {
        $start_dt = $eval_month . '-01';
        $end_dt = date('Y-m-t', strtotime($eval_month . '-01'));

        $sql = "SELECT LM.lesson_date, LS.score_read, LS.score_debate1, LS.score_debate2, LS.score_debate3, LS.score_debate4, LS.score_write1, LS.score_write2, LS.score_write3, LS.score_write4
                FROM {$this->lesson_score_table_name} LS
                LEFT OUTER JOIN {$this->lesson_table_name} LM ON LS.schedule_idx = LM.schedule_idx
                WHERE LS.franchise_idx = '" . $franchise_idx . "' AND LS.student_idx = '" . $student_idx . "' AND LM.lesson_date BETWEEN '" . $start_dt . "' AND '" . $end_dt . "'";
        $result = $this->db->sqlRowArr($sql);
        return $result;
    }

    public function semiAnnualEvalSelect($franchise_idx, $student_idx, $eval_year, $eval_semiannual)
    {
        $sql = "SELECT eval_idx, eval_content, read_content, debate_content, write_content, lead_content, guide_content, parent_request, next_guide_content
        FROM lesson_evaluationT
        WHERE eval_year = '" . $eval_year . "' AND eval_semiannual = '" . $eval_semiannual . "' AND franchise_idx = '" . $franchise_idx . "' AND student_no = '" . $student_idx . "'";

        $result = $this->db->sqlRowArr($sql);
        return $result;
    }

    public function loadSemiAnnualEvaluationMain($franchise_idx, $student_idx)
    {
        $sql = "SELECT TOP(3) eval_idx, eval_year, (CASE WHEN eval_semiannual = '1' THEN '상반기' WHEN eval_semiannual = '2' THEN '하반기' ELSE '' END) eval_semiannual,
        read_score, debate_score, write_score, lead_score
        FROM lesson_evaluationT
        WHERE franchise_idx = '" . $franchise_idx . "' AND student_no = '" . $student_idx . "'
        ORDER BY eval_year DESC, eval_semiannual DESC";

        $result = $this->db->sqlRowArr($sql);
        return $result;
    }
}
