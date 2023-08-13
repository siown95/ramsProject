$(document).ready(function () {
    rentBookLoad();
    rentListLoad();
    //학생 검색 모달
    $("#btnRentStudentSearch").click(function () {
        $("#RentStudentModal").modal('show');
    });

    //학생 검색
    $("#studentRent").click(function () {
        var student_name = $("#txtRentStudentName").val();

        if (!student_name || !student_name.trim()) {
            alert("학생이름을 입력하세요");
            return false;
        }

        if (student_name.length < 2) {
            alert("2글자 이상 입력하세요");
            return false;
        }

        $.ajax({
            url: '/center/ajax/bookRentControll.ajax.php',
            dataType: 'JSON',
            type: 'POST',
            
            data: {
                action: 'rentStudentSearch',
                center_idx: center_idx,
                student_name: student_name
            },
            success: function (result) {
                if (result.success && result.data.table) {
                    $("#studentRentList").html(result.data.table);
                    return false;
                } else {
                    alert(result.msg);
                    $("#studentRentList").empty();
                    return false;
                }
            },
            error: function (request, status, error) {
                alert("request : " + request + "\n" + "status : " + status + "\n" + "error : " + error);
            }
        });
    });

    $("#txtRentStudentName").keypress(function (e) {
        if (e.keyCode === 13) {
            $("#studentRent").trigger('click');
        }
    });

    $("#txtStudentName").click(function(){
        $("#btnRentStudentSearch").trigger('click');
    });

    //학생 검색 후 리스트 클릭
    $("#studentRentList").on("click", ".tc", function (e) {
        var student_no = $(e.target).parent('.tc').data("student-no");
        var student_name = $(e.target).parent('.tc').find("td:eq(0)").text();
        var student_grade = $(e.target).parent('.tc').find("td:eq(1)").text();
        var student_school = $(e.target).parent('.tc').find("td:eq(2)").text();

        $("#student_no").val(student_no);
        $("#txtStudentName").val(student_name);
        $("#lblGrade").text(student_grade);
        $("#lblSchool").text(student_school);
        $("#studentRentList").empty();
        $("#RentStudentModal").modal('hide');
    });


    $("#rentBookSearchTable").on('page.dt', function () {
        $("#rentBookSearchList").find('.tc').removeClass('bg-light');
        $("#rentBookIdx").val('');
        $("#rent_possible").val('');
        $("#status_idx").val('');
    });

    //도서 검색 후 리스트 클릭
    $("#rentBookSearchList").on("click", ".tc", function (e) {
        if ($(e.target).parents('.bg-light').length) {
            $(e.target).parent('.tc').removeClass('bg-light');

            $("#rentBookIdx").val('');
            $("#rent_possible").val('');
            $("#status_idx").val('');
        } else {
            $('.tc').removeClass('bg-light');
            $(e.target).parent('.tc').addClass('bg-light');

            var book_no = $(e.target).parent('.tc').data("book-id");
            var rent_possible = $(e.target).parent('.tc').data("rentyn");
            var status_idx = $(e.target).parent('.tc').data("status-idx");
            
            $("#rentBookIdx").val(book_no);
            $("#rent_possible").val(rent_possible);
            $("#status_idx").val(status_idx);
        }
    });

    //대출 입력
    $("#btnRentInsert").click(function () {
        var student_no = $("#student_no").val();
        var book_no = $("#rentBookIdx").val();
        var rent_possible = $("#rent_possible").val();
        var status_idx = $("#status_idx").val();

        if(rent_possible == '1'){
            alert('해당 교재는 대출중입니다');
            return false;
        }

        if (!student_no) {
            alert('학생을 선택하세요');
            return false;
        }

        if (!book_no) {
            alert('도서를 선택하세요');
            return false;
        }

        $.ajax({
            url: '/center/ajax/bookRentControll.ajax.php',
            dataType: 'JSON',
            type: 'POST',
            
            data: {
                action: 'rentBookInsert',
                center_idx: center_idx,
                student_no: student_no,
                teacher_no: userInfo.user_no,
                book_no: book_no,
                status_idx: status_idx
            },
            success: function (result) {
                if (result.success) {
                    alert(result.msg);
                    $("#contents").load('bookrent.php');
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
    });

    //반납 처리
    $("#btnReturnBook").click(function () {
        var rent_idx = $("#rent_idx").val();
        var status_idx = $("#rent_status_idx").val();
        var readYn = $("input[name='readYN']:checked").val();
        var teacher_no = userInfo.user_no;

        if(confirm("도서를 반납처리 하시겠습니까?")){
            $.ajax({
                url: '/center/ajax/bookRentControll.ajax.php',
                dataType: 'JSON',
                type: 'POST',
                
                data: {
                    action: 'rentBookReturn',
                    rent_idx: rent_idx,
                    teacher_no: teacher_no,
                    status_idx: status_idx,
                    readYn: readYn
                },
                success: function (result) {
                    if (result.success) {
                        alert(result.msg);
                        $("#contents").load('bookrent.php');
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
    });

    $("#rentListTable").on('page.dt', function () {
        $("#rentList").find('.tc').removeClass('bg-light');
        $("#rent_idx").val('');
        $("#rent_status_idx").val('');
        $("#txtStudentName2").val('');
        $("#txtBookName2").val('');
        $("#btnReturnBook").hide();
    });

    $("#rentList").on("click", ".tc", function (e) {
        rentSelect(e);
    });
});

//도서 불러오기
function rentBookLoad() {
    $.ajax({
        url: '/center/ajax/bookRentControll.ajax.php',
        dataType: 'JSON',
        type: 'POST',
        
        data: {
            action: 'rentBookSearch',
            center_idx: center_idx
        },
        success: function (result) {
            if (result.success && result.data) {
                $('#rentBookSearchTable').DataTable({
                    autoWidth: false,
                    destroy: true,
                    data: result.data,
                    stripeClasses: [],
                    dom: "<'col-sm-12'f>t<'col-sm-12'p>",
                    columns: [
                        {
                            data: 'no'
                        },
                        {
                            data: 'book_barcode'
                        },
                        {
                            data: 'book_name',
                            className: 'text-start'
                        },
                        {
                            data: 'book_writer'
                        },
                        {
                            data: 'book_publisher'
                        },
                        {
                            data: 'detail'
                        }
                        ,
                        {
                            data: 'rentTxt'
                        }
                    ],
                    createdRow: function (row, data) {
                        $("th").addClass('text-center align-middle');
                        $(row).addClass('tc text-center align-middle');
                        $(row).attr('data-book-id', data.book_idx);
                        $(row).attr('data-status-idx', data.status_idx);
                        $(row).attr('data-rentyn', data.rentYn);
                    },
                    order: [[0, 'desc']],
                    lengthChange: false,
                    info: false,
                    language: {
                        url: '/json/ko_kr.json'
                    }
                });
            } else {
                $('#rentBookSearchTable').DataTable().destroy();
                $("#rentBookSearchList").empty();
            }
        },
        error: function (request, status, error) {
            alert("request : " + request + "\n" + "status : " + status + "\n" + "error : " + error);
        }
    });
}

//대출 목록 불러오기
function rentListLoad() {
    var teacher_no = $("#selTeacher").val();
    var student_no = $("#selStudentName").val();

    $("#rent_idx").val('');
    $("#rent_status_idx").val('');
    $("#txtStudentName2").val('');
    $("#txtBookName2").val('');
    $("#btnReturnBook").hide();

    $.ajax({
        url: '/center/ajax/bookRentControll.ajax.php',
        dataType: 'JSON',
        type: 'POST',
        
        data: {
            action: 'rentListLoad',
            center_idx: center_idx,
            teacher_no: teacher_no,
            student_no: student_no,
        },
        success: function (result) {
            if (result.success && result.data) {
                $('#rentListTable').DataTable({
                    autoWidth: false,
                    destroy: true,
                    data: result.data,
                    stripeClasses: [],
                    dom: "<'col-sm-12'f>t<'col-sm-12'p>",
                    columns: [
                        {
                            data: 'no'
                        },
                        {
                            data: 'student_name'
                        },
                        {
                            data: 'book_barcode'
                        },
                        {
                            data: 'book_name',
                            className: 'text-start'
                        },
                        {
                            data: 'rent_date'
                        },
                        {
                            data: 'teacher_name'
                        },
                        {
                            data: 'ex_return_date'
                        },
                        {
                            data: 'return_date'
                        },
                        {
                            data: 'over_date'
                        }
                    ],
                    createdRow: function (row, data) {
                        $("th").addClass('text-center align-middle');
                        $(row).addClass('tc text-center align-middle');
                        $(row).attr('data-rent-idx', data.rent_idx);
                        $(row).attr('data-rent-status-idx', data.book_status_idx);
                    },
                    order: [[0, 'desc']],
                    lengthChange: false,
                    displayLength: 15,
                    info: false,
                    language: {
                        url: '/json/ko_kr.json'
                    }
                });
            } else {
                $('#rentListTable').DataTable().destroy();
                $("#rentList").empty();
            }
        },
        error: function (request, status, error) {
            alert("request : " + request + "\n" + "status : " + status + "\n" + "error : " + error);
        }
    });
}

function rentSelect(e) {
    if ($(e.target).parents('.bg-light').length) {
        $(e.target).parent('.tc').removeClass('bg-light');
        $("#rent_idx").val('');
        $("#rent_status_idx").val('');
        $("#txtStudentName2").val('');
        $("#txtBookName2").val('');
        $("#btnReturnBook").hide();
    } else {
        $('.tc').removeClass('bg-light');
        $(e.target).parent('.tc').addClass('bg-light');

        var rent_idx = $(e.target).parent('.tc').data("rent-idx");
        var rent_status_idx = $(e.target).parent('.tc').data("rent-status-idx");

        $.ajax({
            url: '/center/ajax/bookRentControll.ajax.php',
            dataType: 'JSON',
            type: 'POST',
            
            data: {
                action: 'rentSelect',
                rent_idx: rent_idx
            },
            success: function (result) {
                if (result.success) {
                    $("#rent_idx").val(rent_idx);
                    $("#rent_status_idx").val(rent_status_idx);
                    $("#txtStudentName2").val(result.data.student_name);
                    $("#txtBookName2").val(result.data.book_name);

                    if (result.data.mod_flag == 'Y') {
                        $("#btnReturnBook").show();
                    } else {
                        $("#btnReturnBook").hide();
                    }

                    if (result.data.readYn) {
                        $("#txtCheck" + result.data.readYn).prop('checked', true);
                    }
                }
            },
            error: function (request, status, error) {
                alert("request : " + request + "\n" + "status : " + status + "\n" + "error : " + error);
            }
        });
    }
}