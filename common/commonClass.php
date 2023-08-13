<?php
class permissionCmp extends DBCmp
{
    var $admin_member_table = "member_employeeM"; //본사회원 테이블
    var $center_member_table = "member_centerM"; //센터 직원 테이블
    var $student_member_table = "member_studentM"; //회원 테이블

    var $access_log_table = "access_log"; //로그인 로그 테이블
    var $commute_table = "commute_logt"; //출퇴근 로그 테이블

    public function idCheck($user_id, $type)
    {
        if ($type == 'adm') {
            $sql = "SELECT count(0) cnt FROM " . $this->admin_member_table . " WHERE user_id = ?";
        } else if ($type == 'center') {
            $sql = "SELECT count(0) cnt FROM " . $this->center_member_table . " WHERE user_id = ?";
        } else if ($type == 'student') {
            $sql = "SELECT count(0) cnt FROM " . $this->student_member_table . " WHERE user_id = ?";
        } else {
            return false;
        }

        $result = sqlsrv_query($this->conn, $sql, array($user_id));
        return $result;
    }

    //로그인 처리
    public function getPermission($user_id, $password, $type, $center_idx = '1')
    {
        if ($type == 'adm') {
            $sql = "SELECT user_no, user_id, state, user_name, user_phone, email 
            FROM " . $this->admin_member_table . " WHERE user_id = ? and password = ? AND franchise_idx = ?";
        } else if ($type == 'center') {
            $sql = "SELECT user_no, user_id, state, user_name, user_phone, email, is_admin 
            FROM " . $this->center_member_table . " WHERE user_id = ? AND password = ? AND franchise_idx = ?";
        } else if ($type == 'student') {
            $sql = "SELECT user_no, user_id, state, user_name, user_phone, email 
            FROM " . $this->student_member_table . " WHERE user_id = ? AND password = ? AND franchise_idx = ?";
        } else {
            return false;
        }

        $result = sqlsrv_query($this->conn, $sql, array($user_id, $password, $center_idx));
        return $result;
    }

    //실패 로그 확인
    public function getLog()
    {
        $sql = "SELECT MAX(convert(varchar(19), reg_date, 120)) FROM {$this->access_log_table} WHERE remote_address = '" . $_SERVER['REMOTE_ADDR'] . "'";
        $result = $this->sqlRowOne($sql);

        return $result;
    }

    //로그 추가
    public function failLogInsert()
    {
        $sql = "INSERT INTO {$this->access_log_table} (remote_address) VALUES ('" . $_SERVER['REMOTE_ADDR'] . "')";

        $result = $this->execute($sql);
        return $result;
    }

    //나의 정보 확인
    public function getMyInfo($user_no, $type)
    {
        if ($type == 'adm') {
            $sql = "SELECT 
                        user_id, user_name, user_phone, emergencyhp, birth, gender, email, zipcode, address
                        , school, graduation_months, major, degree_number, career, career_year, certificate, bank_name
                        , CAST(DECRYPTBYPASSPHRASE('" . ACCOUNT_HASH . "',account_number) AS nvarchar(2000)) AS account_number
                    FROM {$this->admin_member_table} 
                    WHERE user_no = ?";
        } else if ($type == 'center') {
            $sql = "SELECT 
                        user_id, user_name, user_phone, emergencyhp, birth, gender, email, zipcode, address
                        , school, graduation_months, major, degree_number, career, career_year, certificate, bank_name
                        , CAST(DECRYPTBYPASSPHRASE('" . ACCOUNT_HASH . "',account_number) AS nvarchar(2000)) AS account_number
                    FROM {$this->center_member_table} 
                    WHERE user_no = ?";
        } else if ($type == 'student') {
            $sql = "SELECT 
                        user_id, user_name, user_phone, birth, gender, email, zipcode, address, school_name
                    FROM {$this->student_member_table} 
                    WHERE user_no = ?";
        } else {
            return false;
        }

        $result = sqlsrv_query($this->conn, $sql, array($user_no));
        return $result;
    }

    //아이디 찾기
    public function searchMemberInfo($user_no, $type)
    {
        if ($type == 'adm') {
            $sql = "SELECT 
                        user_id, user_name, email
                    FROM {$this->admin_member_table} 
                    WHERE user_no = ?";
        } else if ($type == 'center') {
            $sql = "SELECT 
                        user_id, user_name, email
                    FROM {$this->center_member_table} 
                    WHERE user_no = ?";
        } else if ($type == 'student') {
            $sql = "SELECT 
                        user_id, user_name, email
                    FROM {$this->student_member_table} 
                    WHERE user_no = ?";
        } else {
            return false;
        }

        $stmt = $this->conn->stmt_init();
        $stmt->prepare($sql);
        $stmt->bind_param("s", $user_no);
        $stmt->execute();

        $result = $stmt->get_result();
        return $result;
    }

    //메뉴 권한 확인
    public function myMenuList($user_no, $type)
    {
        if ($type == 'adm') {
            $sql = "SELECT menu_group FROM {$this->admin_member_table} WHERE user_no = '" . $user_no . "'";
        } else if ($type == 'center') {
            $sql = "SELECT menu_group FROM {$this->center_member_table} WHERE user_no = '" . $user_no . "'";
        } else {
            return false;
        }
        $result = $this->sqlRowOne($sql);

        return $result;
    }

    //당일 출퇴근 정보 확인
    public function selectCommuteLog($user_no, $franchise_idx, $type)
    {
        $date = date("Y-m-d");
        $member_table = '';

        if ($type == 'adm') {
            $member_table = $this->admin_member_table;
        } else if ($type == 'center') {
            $member_table = $this->center_member_table;
        } else {
            return false;
        }

        $sql = "SELECT TOP (1) L.state FROM {$this->commute_table} L
                LEFT OUTER JOIN {$member_table} M
                ON L.user_no = M.user_no
                WHERE L.user_no = '" . $user_no . "'
                AND L.franchise_idx = '" . $franchise_idx . "'
                AND L.reg_date BETWEEN '" . $date . " 00:00:00' AND '" . $date . " 23:59:59'
                ORDER BY L.reg_date DESC";

        $result = $this->sqlRowOne($sql);

        return $result;
    }

    //재직중인 인원만 추출
    public function selectEmployee($type, $franchise_idx = null)
    {
        if ($type == 'adm') {
            $sql = "SELECT user_no, user_name FROM {$this->admin_member_table} WHERE user_id <> 'admin' AND state = '00'";
        } else if ($type == 'center') {
            $sql = "SELECT user_no, user_name FROM {$this->center_member_table} WHERE user_id <> 'admin' AND state = '00' AND franchise_idx = '" . $franchise_idx . "'";
        } else if ($type == 'student') {
            $sql = "SELECT user_no, user_name FROM {$this->student_member_table} WHERE user_id <> 'admin' AND state = '00' AND franchise_idx = '" . $franchise_idx . "'";
        } else {
            return false;
        }
        $result = $this->sqlRowArr($sql);

        return $result;
    }

    //푸터에 들어가는 정보 추출
    public function selectInfomanage($franchise_idx)
    {
        $sql = "SELECT center_name, owner_name, biz_no, address, tel_num, fax_num, center_no FROM franchisem WHERE franchise_idx = '" . $franchise_idx . "'";
        $result = $this->sqlRow($sql);

        return $result;
    }
}

//정보 추출용 클래스
class infoClassCmp extends DBCmp
{
    var $franchisee_table = "franchisem"; //센터 리스트
    var $edu_table  = "employee_edum"; //직원 교육 테이블
    var $center_member_table = "member_centerm"; //선생님 호출 테이블
    var $student_member_table = "member_studentm"; //학생 호출 테이블
    var $banner_table = "bannerT"; // 배너 테이블

    public function searchEduInfo()
    {
        $sql = "SELECT edu_idx, edu_name, 
                CASE WHEN edu_type = 1 THEN '법정의무교육' 
                     WHEN edu_type = 2 THEN '사내직무교육' 
                     ELSE '' END 'edu_type' 
                FROM {$this->edu_table}";
        $result = $this->sqlRowArr($sql);

        return $result;
    }

    //프랜차이즈 명단
    public function searchFranchisee($type = null)
    {
        $where = "";
        if (!empty($type)) {
            $where = "AND franchise_type <> '00'";
        }
        $sql = "SELECT franchise_idx, center_name, address, tel_num FROM {$this->franchisee_table} WHERE useyn = 'Y'" . $where;
        $result = $this->sqlRowArr($sql);

        return $result;
    }

    public function getFranchiseeDetail($franchise_idx)
    {
        $sql = "SELECT center_name, owner_name, biz_no, center_no, address, zipcode, tel_num, fax_num, email, franchisee_start, franchisee_end 
                FROM {$this->franchisee_table} WHERE franchise_idx = '{$franchise_idx}' ";
        $result = $this->sqlRow($sql);

        return $result;
    }

    //프랜차이즈 작업된 리스트
    public function getCenterList()
    {
        $sql = "SELECT center_eng_name FROM {$this->franchisee_table} WHERE center_eng_name <> '' AND useyn = 'Y'";
        $result = $this->sqlRowArr($sql);

        return $result;
    }

    public function getFranchiseeInfo($engName)
    {
        $sql = "SELECT franchise_idx FROM {$this->franchisee_table} WHERE center_eng_name = '{$engName}' ";
        $result = $this->sqlRowOne($sql);

        return $result;
    }

    public function getFranchiseeInfo2($user_id)
    {
        $sql = "SELECT center_eng_name FROM {$this->franchisee_table} WHERE franchise_idx = (SELECT franchise_idx from member_centerM WHERE user_id = '{$user_id}') ";
        $result = $this->sqlRowOne($sql);

        return $result;
    }

    public function getFranchiseeInfo3($user_id)
    {
        $sql = "SELECT center_eng_name FROM {$this->franchisee_table} WHERE franchise_idx = (SELECT franchise_idx from member_studentM WHERE user_id = '{$user_id}') ";
        $result = $this->sqlRowOne($sql);

        return $result;
    }

    public function getGoodsOrder()
    {
        $sql = "SELECT COUNT(0) FROM order_goodsT WHERE order_state = '02'";
        $result = $this->sqlRowOne($sql);
        return $result;
    }

    public function getErrorReport()
    {
        $sql = "SELECT COUNT(0) FROM activity_error_reportt WHERE state = '1'";
        $result = $this->sqlRowOne($sql);

        return $result;
    }

    //선생님 명단
    public function teacherList($center_idx)
    {
        $sql = "SELECT user_no, user_name FROM {$this->center_member_table} WHERE franchise_idx = '" . $center_idx . "' AND state = '00'";
        $result = $this->sqlRowArr($sql);

        return $result;
    }

    //색깔코드
    public function colorCodeList($center_idx)
    {
        $sql = "SELECT color_idx, color_detail, color_code FROM color_codet WHERE franchise_idx = '" . $center_idx . "' ORDER BY color_idx DESC";
        $result = $this->sqlRowArr($sql);

        return $result;
    }

    //선생님 평가항목
    public function teacherEvalList()
    {
        $sql = "SELECT code_num2, code_name FROM codem WHERE code_num1 = '21' AND code_num2 <> ''";
        $result = $this->sqlRowArr($sql);

        return $result;
    }

    //학생 리스트
    public function studentList($center_idx)
    {
        $sql = "SELECT user_no, user_name FROM {$this->student_member_table} WHERE franchise_idx = '" . $center_idx . "' AND state = '00'";
        $result = $this->sqlRowArr($sql);

        return $result;
    }

    //문의사항 확인
    public function getInquiryCnt()
    {
        $sql = "SELECT COUNT(0) FROM board_inquiryt I 
                LEFT OUTER JOIN board_inquiry_commentt C
                ON C.inquiry_idx = I.inquiry_idx 
                WHERE C.inquiry_comment IS NULL";
        $result = $this->sqlRowOne($sql);

        return $result;
    }

    //도서요청 확인
    public function getBookRequestCnt()
    {
        $sql = "SELECT COUNT(0) FROM bookRequestT
                WHERE request_state = '00'";
        $result = $this->sqlRowOne($sql);

        return $result;
    }

    public function getRefundRequestCnt()
    {
        $sql = "SELECT COUNT(0) AS cnt FROM franchise_feeM FF
        LEFT OUTER JOIN payment_logT PL1 ON FF.order_num = PL1.orderId WHERE FF.refund_date <> '' AND FF.refund_money > '0' AND PL1.cancels_cancelAmount = '0'
        UNION
        SELECT COUNT(0) AS cnt FROM invoice_refundT IR
        LEFT OUTER JOIN payment_logT PL2 ON IR.order_num = PL2.orderId WHERE IR.refund_date <> '' AND IR.refund_amount > '0' AND PL2.cancels_cancelAmount = '0'";
        $result = $this->sqlRowOne($sql);
        return $result;
    }

    //강의실 수 확인
    public function getClassRoom($franchise_idx)
    {
        $sql = "SELECT class_no FROM {$this->franchisee_table} WHERE franchise_idx = '{$franchise_idx}' ";
        $result = $this->sqlRowOne($sql);

        return $result;
    }

    // 메인 페이지 배너 확인
    public function getBanner()
    {
        $sql = "SELECT TOP(10) banner_image, banner_link FROM {$this->banner_table} 
        WHERE from_date <= '" . date('Y-m-d') . "' AND to_date <= '" . date('Y-m-d') . "' AND banner_visible = '' 
        ORDER BY mainYn DESC, orders ";
        $result = $this->sqlRowArr($sql);

        return $result;
    }

    public function getMainNotice()
    {
        $sql = "SELECT board_idx, title, CONVERT(VARCHAR(10), reg_date, 23) AS reg_date FROM boardM WHERE category1 = '' AND category2 = '' ORDER BY board_idx DESC";
        $result = $this->sqlRowArr($sql);
        return $result;
    }

    public function getMainNoticeRecent()
    {
        $sql = "SELECT TOP(5) board_idx, title, CONVERT(VARCHAR(10), reg_date, 23) AS reg_date FROM boardM WHERE category1 = '' AND category2 = '' ORDER BY board_idx DESC";
        $result = $this->sqlRowArr($sql);
        return $result;
    }

    //관리자가 원장님 아이디 찾기
    public function getOwnerId($center_idx)
    {
        if (!empty($center_idx)) {
            $sql = "SELECT owner_id FROM {$this->franchisee_table} WHERE franchise_idx = '{$center_idx}'";
            $result = $this->sqlRowOne($sql);

            return $result;
        } else {
            return false;
        }
    }

    //관리자가 원장님으로 로그인 접근
    public function getFranchiseeLogin($user_id, $center_idx)
    {
        if (!empty($user_id) || !empty($center_idx)) {
            $sql = "SELECT user_no, user_id, state, user_name, user_phone, email, is_admin 
            FROM " . $this->center_member_table . " WHERE user_id = ? AND franchise_idx = ? AND state = '00'";

            $result = sqlsrv_query($this->conn, $sql, array($user_id, $center_idx));
        } else {
            return false;
        }

        return $result;
    }
}

//코드 관련 공용함수
class codeInfoCmp extends DBCmp
{
    public function getCodeInfo($code_num1, $code_num2 = null, $code_num3 = null)
    {
        $sql = "SELECT code_num2, code_name FROM codem WHERE code_num1 = '" . $code_num1 . "' AND code_num2 <> ''";
        $result = $this->sqlRowArr($sql);

        return $result;
    }

    public function getFileBoardCodeInfo($code_num1, $code_num2 = null, $code_num3 = null)
    {
        $sql = "SELECT code_num2, code_name FROM codem WHERE code_num1 = '{$code_num1}' AND code_num2 <> '' GROUP BY code_num2, code_name";
        $result = $this->sqlRowArr($sql);

        return $result;
    }
}

//보고서 출력용
class reportCmp extends DBCmp
{
    var $report_table = "reportt";

    public function getReport($report_idx, $division, $user_no, $franchise_idx)
    {
        $member_table = ($division == 'adm') ? 'member_employeem' : 'member_centerm';

        $sql = "SELECT R.title1, R.title2, R.title3, R.title4, R.title5, R.title6, R.title7, R.title8, R.title9, R.title10,
        R.content1, R.content2, R.content3, R.content4, R.content5, R.content6, R.content7, R.content8, R.content9, R.content10,
        CONVERT(varchar(19), R.reg_date, 120) reg_date, M.user_name
        FROM {$this->report_table} R
        LEFT OUTER JOIN " . $member_table . " M 
        ON R.user_no = M.user_no
        WHERE R.report_idx = '" . $report_idx . "'
        AND R.user_no = '" . $user_no . "'
        AND R.franchise_idx = '" . $franchise_idx . "'";

        $result['report_data'] = $this->sqlRow($sql);

        $sql = "SELECT TOP(1) R.title1, R.title2, R.title3, R.title4, R.title5, R.title6, R.title7, R.title8, R.title9, R.title10,
        R.content1, R.content2, R.content3, R.content4, R.content5, R.content6, R.content7, R.content8, R.content9, R.content10,
        CONVERT(varchar(19), R.reg_date, 120) reg_date, M.user_name
        FROM {$this->report_table} R
        LEFT OUTER JOIN " . $member_table . " M 
        ON R.user_no = M.user_no
        WHERE R.report_idx < '" . $report_idx . "' 
        AND R.user_no = '" . $user_no . "'
        AND R.franchise_idx = '" . $franchise_idx . "'
        ORDER BY R.reg_date DESC";

        $result['pre_report_data'] = $this->sqlRow($sql);

        return $result;
    }
}
//결제 클래스
class paymentCmp extends DBCmp
{
    var $franchisee_table = "franchiseM";
    public function getSecretKey($franchise_idx)
    {
        $sql = "SELECT shop_id FROM {$this->franchisee_table} WHERE franchise_idx = '{$franchise_idx}' AND useyn = 'Y'";
        $result = $this->sqlRowOne($sql);

        return $result;
    }

    public function getClientKey($franchise_idx)
    {
        $sql = "SELECT shop_key FROM {$this->franchisee_table} WHERE franchise_idx = '{$franchise_idx}' AND useyn = 'Y'";
        $result = $this->sqlRowOne($sql);

        return $result;
    }
}
//메뉴 클래스
class menuClassCmp extends DBCmp
{
    var $menu_table = "menu_link";
    var $meta_table = "meta_tagm";

    public function getMenu($type)
    {
        $sql = "SELECT menu_idx, menu_name, link FROM {$this->menu_table} WHERE type = '" . $type . "' AND useyn = 'Y'";
        $result = $this->sqlRowArr($sql);

        return $result;
    }

    public function getMetaInfo($og_url)
    {
        $sql = "SELECT og_title, og_image, og_url FROM {$this->meta_table} WHERE og_url = '" . $og_url . "'";
        $result = $this->sqlRow($sql);

        return $result;
    }
}

class analysisClassCmp extends DBCmp
{
    public function getMonthStudent($franchise_idx = null)
    {
        $where_qry = '';

        if (!empty($franchise_idx)) {
            $where_qry = " AND franchise_idx = '" . $franchise_idx . "'";
        }
        $sql = "SELECT COUNT(0) FROM member_studentm WHERE reg_date BETWEEN '" . date("Y-m") . "-01' AND '" . date("Y-m-t") . "' AND state = '00'" . $where_qry;

        $result = $this->sqlRowOne($sql);
        return $result;
    }

    public function getAllStudent($franchise_idx = null)
    {
        $where_qry = '';

        if (!empty($franchise_idx)) {
            $where_qry = " AND franchise_idx = '" . $franchise_idx . "'";
        }

        $sql = "SELECT COUNT(0) FROM member_studentm WHERE state = '00'" . $where_qry;

        $result = $this->sqlRowOne($sql);
        return $result;
    }

    public function getNotReturnBook($franchise_idx)
    {
        $sql = "SELECT COUNT(0) FROM book_rentt WHERE franchise_idx = '" . $franchise_idx . "' AND return_date = ''";
        $result = $this->sqlRowOne($sql);

        return $result;
    }

    public function getMonthSales($franchise_idx = null)
    {
        $where_qry = "";
        if (!empty($franchise_idx)) {
            $where_qry = " AND I.franchise_idx = '" . $franchise_idx . "'";
        }
        $sql = "SELECT (SUM(I.order_money) - SUM(I.refund_money)) FROM invoiceM I
        LEFT OUTER JOIN receiptT R ON I.receipt_idx = R.receipt_item_idx AND I.franchise_idx = R.franchise_idx
        WHERE I.order_ym = '" . date('Y-m') . "' AND I.order_state IN ('02', '04') AND R.receipt_lesson_type <> ''" . $where_qry;
        $result = $this->sqlRowOne($sql);
        return $result;
    }

    public function getYearSales($franchise_idx = null)
    {
        $where_qry = "";
        if (!empty($franchise_idx)) {
            $where_qry = " AND I.franchise_idx = '" . $franchise_idx . "'";
        }
        $sql = "SELECT (SUM(I.order_money) - SUM(I.refund_money)) FROM invoiceM I
        LEFT OUTER JOIN receiptT R ON I.receipt_idx = R.receipt_item_idx AND I.franchise_idx = R.franchise_idx
        WHERE I.order_ym BETWEEN '" . date('Y') . "-01' AND '" . date('Y') . "-12' AND I.order_state IN ('02', '04') AND R.receipt_lesson_type <> ''" . $where_qry;
        $result = $this->sqlRowOne($sql);
        return $result;
    }
}

//마스킹용
class PrintDataCmp extends DBCmp
{
    public function getWildcard($value, $type)
    {
        if (!empty($value)) {
            switch (strtoupper($type)) {
                case 'ID':
                    return self::setWildcardId($value);
                case 'NAME':
                    return self::setWildcardName($value);
                case 'TEL':
                case 'PHONE':
                    return self::setWildcardTel($value);
                case 'EMAIL':
                    return self::setWildcardEmail($value);
                case 'ADDRESS':
                    return self::setWildcardAddress($value);
                case 'BIRTH':
                    return self::setWildcardBirth($value);
                default:
                    return '*';
            }
        }
        return $value;
    }

    static public function setWildcardBirth($str)
    {
        if (empty($str)) {
            return '';
        }
        $len = mb_strlen($str);
        if ($len <= 1) {
            return '*';
        } else if ($len <= 2) {
            return mb_substr($str, 0, 1) . '*';
        }
        return mb_substr($str, 0, -4) . str_repeat('*', 4);
    }

    static public function setWildcardEmail($email)
    {
        $ids = explode('@', $email);
        if (count($ids) < 2) {
            return '';
        }
        return mb_substr($ids[0], 0, -5) . str_repeat('*', mb_strlen(substr($ids[0], -5))) . '@' . $ids[1];
    }

    static public function setWildcardName($name)
    {
        if (empty($name)) {
            return '***';
        }
        $len = mb_strlen($name);
        if ($len <= 1) {
            return '*';
        } else if ($len <= 2) {
            return mb_substr($name, 0, 1) . '*';
        }
        return mb_substr($name, 0, 1) . str_repeat('*', $len - 2) . mb_substr($name, -1, 1);
    }

    static public function setWildcardId($id)
    {

        if (empty($id)) {
            return '';
        }
        $len = mb_strlen($id);
        if ($len <= 1) {
            return '*';
        } else if ($len <= 2) {
            return mb_substr($id, 0, 1) . '*';
        }
        return mb_substr($id, 0, -5) . str_repeat('*', mb_strlen(substr($id, -5)));
    }

    static public function setWildcardTel($tel)
    {
        if (empty($tel)) {
            return '';
        }
        if (!self::getValidTelNumber($tel)) {
            return '잘못된 전화번호';
        }
        $result = '';
        $hp = preg_replace("/[^0-9]/", "", trim($tel));
        if (preg_match("/^01([0|1|6|7|8|9]?)-?([0-9]{3,4})-?([0-9]{4})$/", $hp)) {
            $result = $hp;
            if (!empty($result)) {
                $result = preg_replace("/(^01.{1}|[0-9]{3})([0-9]+)([0-9]{4})/", "$1-****-$3", $result);
            }
            return $result;
        } else {
            $result = '잘못된 전화번호';
        }
        return $result;
    }

    static public function setWildcardAddress($str)
    {
        if (empty($str)) {
            return '';
        }
        $len = mb_strlen($str);
        if ($len <= 1) {
            return '*';
        } else if ($len <= 2) {
            return mb_substr($str, 0, 1) . '*';
        }
        return mb_substr($str, 0, 5) . str_repeat('*', $len - 7) . mb_substr($str, -1, 2);
    }

    static public function getValidTelNumber($tel)
    {
        $ret = false;
        if (preg_match("/01([0|1|6|7|8|9]?)+[-]?[0-9]{3,4}+[-]?[0-9]{4}/", $tel)) {
            $ret = true;
        }
        if (preg_match("/[070|02|031|032|033|041|042|043|051|052|053|054|055|061|062|063|064]+[-]?[0-9]{3,4}+[-]?[0-9]{4}/", $tel)) {
            $ret = true;
        }
        if (preg_match("/[0-9]{3,4}+[-]?[0-9]{4}/", $tel)) {
            $ret = true;
        }
        return $ret;
    }
}
