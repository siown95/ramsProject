$(document).ready(function () {
    loadStudent();

    $("#txtcYear").datepicker({
        language: 'ko-Kr',
        format: 'yyyy'
    });

    $('#txtcYear').on('click', function () {
        $(".datepicker-container").css("z-index", "1055");
    });

    $("#studentListTable").on('page.dt', function () {
        $("#studentListTable > tbody > tr").removeClass('bg-light');
        $("#student_no").val('');

        $("#profileData").empty(); //기본정보 초기화

        //수업 결과 초기화
        $("#lessonTable").DataTable().destroy();
        $("#lessonData").empty();

        //대출도서 초기화
        $('#bookTable').DataTable().destroy();
        $("#bookData").empty();

        $("#_divDateControl").hide();
    });

    $("#btnPayCertificatePrint").click(function () {
        $("#PayCertificateModal").modal("hide");
        if ($("#student_no").val() == '') {
            alert("원생목록에서 원생을 선택해주세요.");
            $("#txtpay_sidx").val('');
            return false;
        } else {
            $("#txtpay_sidx").val($("#student_no").val());
        }
        payCertificatePost();
    });

    $("#studentList").on("click", ".tc", function (e) {
        getStudentInfo(e);
    });

    $("input[name=rdoType]").on("change", function () {
        loadStudent();
    });

    $("#studentManageMonth").change(function () {
        var _idx = $("#TabList li").find('.active').parent("li").index();
        var student_no = $("#student_no").val();

        if (student_no) {
            if (_idx == '1') {
                // getPayData(student_no);
                $("#_divDateControl").show();
            } else if (_idx == '2') {
                getLessonData(student_no);
                $("#_divDateControl").show();
            } else {
                $("#profileData").empty();
                $("#_divDateControl").hide();
                return false;
            }
        } else {
            $("#profileData").empty(); //기본정보 초기화

            //수업 결과 초기화
            $("#lessonTable").DataTable().destroy();
            $("#lessonData").empty();

            //대출도서 초기화
            $('#bookTable').DataTable().destroy();
            $("#bookData").empty();

            return false;
        }
    });

    $("#TabList li a").click(function () {
        var _idx = $(this).parent("li").index();
        var student_no = $("#student_no").val();

        if (student_no) {
            if (_idx == '0') {
                getProfileData(student_no);
                $("#_divDateControl").hide();
            } else if (_idx == '1') {
                // getPayData(student_no);
                $("#_divDateControl").show();
            } else if (_idx == '2') {
                getLessonData(student_no);
                $("#_divDateControl").show();
            } else if (_idx == '3') {
                getBookData(student_no);
                $("#_divDateControl").hide();
            } else {
                $("#profileData").empty();
                $("#_divDateControl").hide();
                return false;
            }
        } else {
            $("#profileData").empty();
            $("#_divDateControl").hide();
            return false;
        }
    });
});

function loadStudent() {
    var state = $("input[name=rdoType]:checked").val();
    var grade = $("#selGrade").val();
    var teacher_no = $("#selTeacher").val();

    $.ajax({
        url: '/center/ajax/studentControll.ajax.php',
        dataType: 'JSON',
        type: 'POST',
        async: false,
        data: {
            action: 'loadStudentDetail',
            center_idx: center_idx,
            state: state,
            grade: grade,
            teacher_no
        },
        success: function (result) {
            if (result.success && result.data) {
                $('#studentListTable').DataTable({
                    autoWidth: false,
                    destroy: true,
                    data: result.data,
                    stripeClasses: [],
                    dom: '<"col-sm-12"f>t<"col-sm-12"p>',
                    columns: [{
                        data: 'no'
                    },
                    {
                        data: 'teacher_name'
                    },
                    {
                        data: 'user_name'
                    },
                    {
                        data: 'age'
                    },
                    {
                        data: 'age'
                    }
                    ],
                    createdRow: function (row, data) {
                        $("th").addClass('text-center align-middle');
                        $(row).addClass('tc text-center align-middle');
                        $(row).attr('data-user-idx', data.user_no);
                    },
                    lengthChange: false,
                    info: false,
                    language: {
                        url: '/json/ko_kr.json'
                    }
                });

                $("#student_no").val('');
                $("#profileData").empty();
            } else {
                $('#studentListTable').DataTable().destroy();
                $("#studentList").empty();
            }
        }
    });
}

function getStudentInfo(e) {
    var targetClass = $(e.target).parents('.tc');

    if ($(e.target).parents('.bg-light').length) {
        targetClass.removeClass('bg-light');
        $("#student_no").val('');
        $("#profileData").empty(); //기본정보 초기화
        $("#payData").empty(); // 결제정보 초기화
        $("#color_tag_td").empty();
        $("#etc_td").empty();
        //수업 결과 초기화
        $("#lessonTable").DataTable().destroy();
        $("#lessonData").empty();

        //대출도서 초기화
        $('#bookTable').DataTable().destroy();
        $("#bookData").empty();
        $("#_divDateControl").hide();
    } else {
        $('.tc').removeClass('bg-light');
        targetClass.addClass('bg-light');

        var student_no = targetClass.data('user-idx');
        var _idx = $("#TabList li .active").parent('li').index();

        $("#student_no").val(student_no);
        getProfileData(student_no);
        getPayData(student_no);
        getLessonData(student_no);
        getBookData(student_no);
        if (_idx == '0') {
            $("#_divDateControl").hide();
        } else if (_idx == '1') {
            $("#_divDateControl").show();
        } else if (_idx == '2') {
            $("#_divDateControl").show();
        } else if (_idx == '3') {
            $("#_divDateControl").hide();
        } else {
            $("#profileData").empty();
            $("#_divDateControl").hide();
            return false;
        }
    }
}

function getProfileData(student_no) {
    $.ajax({
        url: '/center/ajax/studentControll.ajax.php',
        dataType: 'JSON',
        type: 'POST',
        async: false,
        data: {
            action: 'getStudentProfile',
            center_idx: center_idx,
            user_no: student_no
        },
        success: function (result) {
            if (result.success) {
                $("#profileData").html(result.data.table);
                return false;
            } else {
                alert(result.msg);
                return false;
            }
        },
        error: function (request, status, error) {
            alert("request : " + request + "\n" + "status : " + status + "\n" + "error : " + error);
        }
    });
}

function getPayData(student_no) {
    var months = $("#studentManageMonth").val();
    $.ajax({
        url: '/center/ajax/studentFeeControll.ajax.php',
        dataType: 'JSON',
        type: 'POST',
        async: false,
        data: {
            action: 'getPayData',
            center_idx: center_idx,
            student_idx: student_no,
            months: months
        },
        success: function (result) {
            if (result.success) {
                if (result.data && (result.data).length != 0) {
                    $("#payData").html(result.data.tbl);
                    $("#color_tag_td").html(result.data.color_tag);
                    $("#etc_td").html(result.data.memo);
                } else {
                    $("#payData").empty();
                    $("#color_tag_td").empty();
                    $("#etc_td").empty();
                }
                return false;
            } else {
                alert(result.msg);
                $("#payData").empty();
                $("#color_tag_td").empty();
                $("#etc_td").empty();
                return false;
            }
        },
        error: function (request, status, error) {
            alert("request : " + request + "\n" + "status : " + status + "\n" + "error : " + error);
        }
    });
}

function getLessonData(student_no) {
    var studentManageMonth = $("#studentManageMonth").val();

    $.ajax({
        url: '/center/ajax/lessonListControll.ajax.php',
        dataType: 'JSON',
        type: 'POST',
        async: false,
        data: {
            action: 'getLessonStudentData',
            center_idx: center_idx,
            student_idx: student_no,
            studentManageMonth: studentManageMonth
        },
        success: function (result) {
            if (result.success && result.data) {
                $('#lessonTable').DataTable({
                    autoWidth: false,
                    destroy: true,
                    data: result.data,
                    stripeClasses: [],
                    dom: "<'col-sm-12'f>t<'col-sm-12'p>",
                    columns: [
                        {
                            data: 'lesson_date'
                        },
                        {
                            data: 'lesson_type'
                        },
                        {
                            data: 'score_read'
                        },
                        {
                            data: 'score_debate'
                        },
                        {
                            data: 'score_write'
                        },
                        {
                            data: 'attend_yn'
                        },
                        {
                            data: 'score_memo'
                        }
                    ],
                    createdRow: function (row) {
                        $("th").addClass('text-center align-middle');
                        $(row).addClass('text-center align-middle');
                    },
                    lengthChange: false,
                    info: false,
                    searching: false,
                    language: {
                        url: '/json/ko_kr.json'
                    }
                });
            } else {
                $('#lessonTable').DataTable().destroy();
            }
        },
        error: function (request, status, error) {
            alert("request : " + request + "\n" + "status : " + status + "\n" + "error : " + error);
        }
    });
}

function getBookData(student_no) {
    $.ajax({
        url: '/center/ajax/bookRentControll.ajax.php',
        dataType: 'JSON',
        type: 'POST',
        async: false,
        data: {
            action: 'rentBookManageList',
            center_idx: center_idx,
            student_no: student_no,
        },
        success: function (result) {
            if (result.success && result.data) {
                $('#bookTable').DataTable({
                    autoWidth: false,
                    destroy: true,
                    data: result.data,
                    stripeClasses: [],
                    dom: "<'col-sm-12'f>t<'col-sm-12'p>",
                    columns: [
                        {
                            data: 'book_barcode'
                        },
                        {
                            data: 'book_name'
                        },
                        {
                            data: 'rent_date'
                        },
                        {
                            data: 'ex_return_date'
                        },
                        {
                            data: 'over_date'
                        }
                    ],
                    createdRow: function (row, data) {
                        $("th").addClass('text-center align-middle');
                        $(row).addClass('tc text-center align-middle');
                        $(row).attr('data-rent-idx', data.rent_idx);
                    },
                    order: [[0, 'desc']],
                    lengthChange: false,
                    info: false,
                    searching: false,
                    language: {
                        url: '/json/ko_kr.json'
                    }
                });
            } else {
                $('#bookTable').DataTable().destroy();
            }
        },
        error: function (request, status, error) {
            alert("request : " + request + "\n" + "status : " + status + "\n" + "error : " + error);
        }
    });
}

function payCertificatePost() {
    var form = document.getElementById("payCertificateForm");
    form.submit();
}