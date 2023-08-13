$(document).ready(function () {
    getSalesData();

    $("#btnSaleTeacherInfoSearch").click(function () {
        var salesmonth = $("#dtSalesYearMonth").val();
        var user_idx = $("#selSalesTeacher").val();
        var lesson_class_type = $("#selLessonClassType").val();

        if (!salesmonth) {
            alert("조회 기간을 선택해주세요.");
            return false;
        }
        if (!user_idx) {
            alert("담당 대상을 선택해주세요.");
            return false;
        }
        if (!lesson_class_type) {
            alert("수업 종류를 선택해주세요.");
            return false;
        }

        getSalesData();
    });
});

function getSalesData() {
    var salesmonth = $("#dtSalesYearMonth").val();
    var user_idx = $("#selSalesTeacher").val();
    var lesson_class_type = $("#selLessonClassType").val();
    $.ajax({
        url: '/center/ajax/salesTeacherInfoControll.ajax.php',
        dataType: 'JSON',
        type: 'POST',
        async: false,
        data: {
            action: 'getSalesData',
            center_idx: center_idx,
            user_idx: user_idx,
            salesmonth: salesmonth,
            lesson_class_type: lesson_class_type
        },
        success: function (result) {
            if (result.success) {
                if (result.data && (result.data).length != 0) {
                    $("#TeacherSaleList").html(result.data.tbl);
                } else {
                    $("#TeacherSaleList").empty();
                }
                return false;
            } else {
                alert(result.msg);
                $("#TeacherSaleList").empty();
                return false;
            }
        },
        error: function (request, status, error) {
            alert("request : " + request + "\n" + "status : " + status + "\n" + "error : " + error);
        }
    });
}