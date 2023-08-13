<ul id="menuList" class="nav nav-tabs">
    <li class="nav-item dropdown">
        <button type="button" class="nav-link dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">관리</button>
        <ul class="dropdown-menu">
            <li><a class="dropdown-item menu" href="#dashboard">대시보드</a></li>
            <li><a class="dropdown-item menu" href="#code">코드관리</a></li>
            <li><a class="dropdown-item menu" href="#infomanage">정보관리</a></li>
            <li><a class="dropdown-item menu" href="#receipt_itemmanage">수납항목관리</a></li>
            <li><a class="dropdown-item menu" href="#center_notice_list">센터알림</a></li>
            <li><a class="dropdown-item menu" href="#week_check">주차정보</a></li>
        </ul>
    </li>
    <li class="nav-item dropdown">
        <button type="button" class="nav-link dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">원생관리</button>
        <ul class="dropdown-menu">
            <li><a class="dropdown-item menu" href="#student_list">원생목록</a></li>
            <li><a class="dropdown-item menu" href="#student_manage">원생관리</a></li>
        </ul>
    </li>
    <li class="nav-item dropdown">
        <button type="button" class="nav-link dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">직원관리</button>
        <ul class="dropdown-menu">
            <li><a class="dropdown-item menu" href="#employee">직원목록</a></li>
            <li><a class="dropdown-item menu" href="#workcheck">출근부확인</a></li>
            <li><a class="dropdown-item menu" href="#teacher_evaluation">근무평가</a></li>
            <li><a class="dropdown-item menu" href="#employee_edu">직원교육</a></li>
            <li><a class="dropdown-item menu" href="#businessreport">업무보고</a></li>
        </ul>
    </li>
    <li class="nav-item">
        <a class="nav-link menu" href="#counsel">상담관리</a>
    </li>
    <li class="nav-item dropdown">
        <button type="button" class="nav-link dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">수업관리</button>
        <ul class="dropdown-menu">
            <li><a class="dropdown-item menu" href="#lesson_schedule">시간표</a></li>
            <li><a class="dropdown-item menu" href="#lesson_list">수업리스트</a></li>
            <li><a class="dropdown-item menu" href="#lesson_schedule_batch">수업도서배치</a></li>
            <li><a class="dropdown-item menu" href="#student_evaluation">종합평가</a></li>
            <li><a class="dropdown-item menu" href="#activitypaper">활동지</a></li>
            <li><a class="dropdown-item menu" href="#newspaper_column">주제별신문칼럼</a></li>
        </ul>
    </li>
    <li class="nav-item dropdown">
        <button type="button" class="nav-link dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">도서&#47;독서관리</button>
        <ul class="dropdown-menu">
            <li><a class="dropdown-item menu" href="#bookmanage">도서관리</a></li>
            <li><a class="dropdown-item menu" href="#bookstatus">도서현황</a></li>
            <li><a class="dropdown-item menu" href="#bookrent">도서대출</a></li>
            <li><a class="dropdown-item menu" href="#curriculum">커리큘럼</a></li>
            <li><a class="dropdown-item menu" href="#student_note">원생쓴글</a></li>
        </ul>
    </li>
    <li class="nav-item">
        <a class="nav-link menu menu2" href="#send_sms">문자</a>
    </li>
    <li class="nav-item dropdown">
        <button type="button" class="nav-link dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">본사지원</button>
        <ul class="dropdown-menu">
            <li><a class="dropdown-item menu" href="#inquiry_request">문의요청</a></li>
            <li><a class="dropdown-item menu" href="#notice_list">공지사항</a></li>
            <li><a class="dropdown-item menu" href="#board_list">자료실</a></li>
            <li><a class="dropdown-item menu" href="#goods_order">물품주문</a></li>
            <li><a class="dropdown-item menu" href="#activity_error_report">활동지오류신고</a></li>
            <li><a class="dropdown-item menu" href="#magazine_produce">원고제출</a></li>
            <li><a class="dropdown-item menu" href="#magazine">리딩엠매거진</a></li>
        </ul>
    </li>
    <li class="nav-item dropdown">
        <button type="button" class="nav-link dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">매출</button>
        <ul class="dropdown-menu">
            <li><a class="dropdown-item menu" href="#student_feelist">원비수납</a></li>
            <li><a class="dropdown-item menu" href="#fee_refund">원비환불</a></li>
            <li><a class="dropdown-item menu" href="#sales_confirm">매출확정</a></li>
            <li><a class="dropdown-item menu" href="#sales_teacher_info">교사매출</a></li>
        </ul>
    </li>
    <li class="nav-item">
        <a class="nav-link menu menu2" href="#board_lessontip_list">수업TIP</a>
    </li>
    <li class="nav-item">
        <a class="nav-link" data-bs-toggle="modal" data-bs-target="#divModal">분할</a>
    </li>
</ul>

<div class="modal fade" id="ArrowModal" tabindex="-1" aria-labelledby="ModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="ModalLabel">분할 화면 위치 선택</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="text-center">
                    <button type="button" id="btnLeft" class="btn btn-lg btn-outline-primary"><i class="fa-solid fa-caret-left"></i></button>
                    <button type="button" id="btnRight" class="btn btn-lg btn-outline-primary"><i class="fa-solid fa-caret-right"></i></button>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" id="btnModalClose2" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="divModal" tabindex="-1" aria-labelledby="ModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="ModalLabel">화면 분할</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <select id="selDivide" class="form-select">
                    <option value="1">전체</option>
                    <option value="2">분할</option>
                </select>
            </div>
            <div class="modal-footer">
                <button type="button" id="btnModalClose" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<script>
    var link;
    var lmenu = '';
    var rmenu = '';

    document.addEventListener("DOMContentLoaded", function() {
        document.querySelectorAll('.dropdown').forEach(function(ei) {
            ei.addEventListener("mouseover", function(e) {
                let el = this.querySelector("button[data-bs-toggle]");
                if (el != null) {
                    let nel = el.nextElementSibling;
                    el.classList.add("show");
                    nel.classList.add("show");
                }
            });
            ei.addEventListener("mouseleave", function(e) {
                let el = this.querySelector("button[data-bs-toggle]");
                if (el != null) {
                    let nel = el.nextElementSibling;
                    el.classList.remove("show");
                    nel.classList.remove("show");
                }
            });
        });
    });

    $('#selDivide').change(function() {
        if ($('#selDivide').val() == '1') {
            $('#contents_div1').hide();
            $('#contents_div2').hide();
            $('#contents').empty();
            $('#contents_div1').empty();
            $('#contents_div2').empty();
        } else {
            $('#contents_div1').show();
            $('#contents_div2').show();
            $('#contents').empty();
            $('#contents_div1').empty();
            $('#contents_div2').empty();
        }
        $('#btnModalClose').trigger('click');
    });

    $('.menu').click(function() {
        link = $(this).attr('href');
        if (link == '' || link == null || link == 'undefined') {
            return false;
        } else {
            if ($('#selDivide').val() == '1') {
                link = link.replace('#', '');
                $('#contents').empty();
                $('#contents').load(link + '.php');
            } else {
                $("#ArrowModal").modal("show");
            }
            $(".menu").removeClass("text-danger", true);
            $("button").removeClass("text-danger", true);
            if ($(this).hasClass("dropdown-item")) {
                $(this).parents("li").children("button").addClass("text-danger");
                $(this).addClass("text-danger");
            } else {
                $(this).addClass("text-danger");
            }
            return false;
        }
    });

    $("#btnLeft").on("click", function() {
        link = link.replace('#', '');
        if (lmenu != link && rmenu != link) {
            lmenu = link;
            $('#contents').empty();
            $('#contents_div1').empty();
            $('#contents_div1').load(link + '.php');
        }
        $("#ArrowModal").modal("hide");
        return false;
    });

    $("#btnRight").on("click", function() {
        link = link.replace('#', '');
        if (lmenu != link && rmenu != link) {
            rmenu = link;
            $('#contents').empty();
            $('#contents_div2').empty();
            $('#contents_div2').load(link + '.php');
        }
        $("#ArrowModal").modal("hide");
        return false;
    });
</script>