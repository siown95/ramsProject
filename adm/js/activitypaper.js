$(document).ready(function () {
    loadCurriculum(); //커리큘럼 지정된 도서 로드

    $('#activityList').on("click", ".booktc", function (e) {
        activityListSelect(e);
    });

    $('#btnSelect').click(function () {
        $('#BookNo').val($('#SearchBookNo').val());
        $('#txtBookName').val($('#txtSearchBookName').val());
        $('#SearchBookNo').val('');
        $('#txtSearchBookName').val('');
        $('#BookModal').modal('hide');
    });

    $("#btnSave").click(function () {
        activityListUpdate();
    });

    $('#btnActivityUpload1').click(function () {
        $('#fileStudentActivity1').trigger('click');
    });

    $('#fileStudentActivity1').change(function () {
        var fileNm = $('#fileStudentActivity1')[0].files[0];

        if (fileNm) {
            $("#fileStudentActivityName1").val(fileNm.name);
        } else {
            $("#fileStudentActivityName1").val('');
            $('#fileStudentActivity1').val('');
        }
    });

    $('#btnActivityUpload2').click(function () {
        $('#fileStudentActivity2').trigger('click');
    });

    $('#fileStudentActivity2').change(function () {
        var fileNm = $('#fileStudentActivity2')[0].files[0];

        if (fileNm) {
            $("#fileStudentActivityName2").val(fileNm.name);
        } else {
            $("#fileStudentActivityName2").val('');
            $('#fileStudentActivity2').val('');
        }
    });

    $('#btnActivityUpload3').click(function () {
        $('#fileTeacherActivity1').trigger('click');
    });

    $('#fileTeacherActivity1').change(function () {
        var fileNm = $('#fileTeacherActivity1')[0].files[0];

        if (fileNm) {
            $("#fileTeacherActivityName1").val(fileNm.name);
        } else {
            $("#fileTeacherActivityName1").val('');
            $('#fileTeacherActivity1').val('');
        }
    });

    $('#btnActivityUpload4').click(function () {
        $('#fileTeacherActivity2').trigger('click');
    });

    $('#fileTeacherActivity2').change(function () {
        var fileNm = $('#fileTeacherActivity2')[0].files[0];

        if (fileNm) {
            $("#fileTeacherActivityName2").val(fileNm.name);
        } else {
            $("#fileTeacherActivityName2").val('');
            $('#fileTeacherActivity2').val('');
        }
    });

    $(".file-del").click(function (e) {
        var activitypaper_idx = $("#activitypaper_idx").val();
        var file_name = $(e.target).parent(".form-inline").find('.file-name').val();
        var file_number = $(e.target).parent(".form-inline").find('.file-name').attr('id');

        if (confirm('파일을 삭제하시겠습니까?')) {
            $.ajax({
                url: '/adm/ajax/activityPaperControll.ajax.php',
                dataType: 'JSON',
                type: 'POST',
                data: {
                    action: 'fileDelete',
                    activitypaper_idx: activitypaper_idx,
                    file_name: file_name,
                    file_number: file_number
                },
                success: function (result) {
                    if (result.success) {
                        alert(result.msg);
                        location.reload();
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
});

function loadCurriculum() {
    $.ajax({
        url: '/adm/ajax/activityPaperControll.ajax.php',
        dataType: 'JSON',
        type: 'POST',
        
        data: {
            action: 'activityListLoad'
        },
        success: function (result) {
            if (result.success && result.data) {
                $('#dataTable').DataTable({
                    autoWidth: false,
                    destroy: true,
                    data: result.data,
                    stripeClasses: [],
                    dom: "<'col-sm-12'f>t<'col-sm-12'p>",
                    displayLength: 5,
                    columns: [
                        {
                            data: 'no',
                            className: 'booktc align-middle'
                        },
                        
                        {
                            data: 'code_name',
                            className: 'booktc align-middle'
                        },
                        {
                            data: 'months',
                            className: 'booktc align-middle'
                        },
                        {
                            data: 'book_isbn',
                            className: 'booktc align-middle'
                        },
                        {
                            data: 'img_link',
                            className: 'booktc align-middle'
                        },
                        {
                            data: 'book_name',
                            className: 'booktc align-middle text-start'
                        },
                        {
                            data: 'book_writer',
                            className: 'booktc align-middle'
                        },
                        {
                            data: 'book_publisher',
                            className: 'booktc align-middle'
                        },
                        {
                            data: 'detail',
                            className: 'booktc align-middle'
                        },
                        {
                            data: 'download',
                            className: 'align-middle'
                        }
                        ,
                        {
                            data: 'view',
                            className: 'align-middle'
                        }
                    ],
                    createdRow: function (row, data) {
                        $(row).attr('data-curriculum-idx', data.curriculum_idx);
                        $(row).attr('data-book-idx', data.book_idx);
                        $(row).attr('data-activitypaper-idx', data.activitypaper_idx);
                        $("th").addClass('text-center align-middle');

                        if (!data.download) {
                            $(row).addClass('bg-nodata');
                        }
                    },
                    order: [[0, 'desc']],
                    lengthChange: false,
                    info: false,
                    language: {
                        url: "/json/ko_kr.json",
                    }
                });
            } else {
                $('#dataTable').DataTable().destroy();
            }
        },
        error: function (request, status, error) {
            alert("request : " + request + "\n" + "status : " + status + "\n" + "error : " + error);
        }
    });
}

function activityListSelect(e) {
    var targetClass = $(e.target).parents('tr');
    var curriculum_idx = targetClass.data('curriculum-idx');
    var book_idx = targetClass.data('book-idx');
    var activitypaper_idx = targetClass.data('activitypaper-idx');
    var book_name = targetClass.find('td:eq(5)').text();

    if ($(e.target).parent('.selectList').length) {
        targetClass.removeClass('selectList');
        $("#btnSave").hide();
        $("#txtBookName").val('');
        $("#curriculum_idx").val('');
        $("#book_idx").val('');
        $("#activitypaper_idx").val('');

        $("#exfile1").addClass("d-none");
        $("#exfile1").attr("href", "");
        $("#imgdel1").addClass("d-none");
        $("#activity_student1").val('');
        $("#fileStudentActivityName1").val('');
        $("#fileStudentActivity1").val('');

        $("#exfile2").addClass("d-none");
        $("#exfile2").attr("href", "");
        $("#imgdel2").addClass("d-none");
        $("#activity_student2").val('');
        $("#fileStudentActivityName2").val('');
        $("#fileStudentActivity2").val('');

        $("#exfile3").addClass("d-none");
        $("#exfile3").attr("href", "");
        $("#imgdel3").addClass("d-none");
        $("#activity_teacher1").val('');
        $("#fileTeacherActivityName1").val('');
        $("#fileTeacherActivity1").val('');

        $("#exfile4").addClass("d-none");
        $("#exfile4").attr("href", "");
        $("#imgdel4").addClass("d-none");
        $("#activity_teacher2").val('');
        $("#fileTeacherActivityName2").val('');
        $("#fileTeacherActivity2").val('');

    } else {
        $('.booktc').removeClass('selectList');
        targetClass.addClass('selectList');

        $("#fileStudentActivity1").val('');
        $("#fileStudentActivity2").val('');
        $("#fileTeacherActivity1").val('');
        $("#fileTeacherActivity2").val('');

        $.ajax({
            url: '/adm/ajax/activityPaperControll.ajax.php',
            dataType: 'JSON',
            type: 'POST',
            
            data: {
                action: 'activityListSelect',
                activitypaper_idx: activitypaper_idx
            },
            success: function (result) {
                if (result.success) {
                    $("html").animate({scrollTop:$("#activityForm").offset().top},1);
                    $("#txtBookName").val(book_name);
                    $("#curriculum_idx").val(curriculum_idx);
                    $("#book_idx").val(book_idx);
                    $("#activitypaper_idx").val(activitypaper_idx);
                    $("#btnSave").show();

                    if (result.data.activity_student1) {
                        $("#exfile1").removeClass("d-none");
                        $("#exfile1").attr("href", "/files/activitypaper/" + result.data.activity_student1);
                        $("#exfile1").html("<i class=\"fa-solid fa-file-arrow-down\"></i>" + result.data.activity_student1);
                        $("#imgdel1").removeClass("d-none");
                        $("#activity_student1").val(result.data.activity_student1);
                        $("#fileStudentActivityName1").val(result.data.activity_student1);
                    } else {
                        $("#exfile1").addClass("d-none");
                        $("#exfile1").attr("href", "");
                        $("#imgdel1").addClass("d-none");
                        $("#activity_student1").val('');
                        $("#fileStudentActivityName1").val('');
                    }

                    if (result.data.activity_student2) {
                        $("#exfile2").removeClass("d-none");
                        $("#exfile2").attr("href", "/files/activitypaper/" + result.data.activity_student1);
                        $("#exfile2").html("<i class=\"fa-solid fa-file-arrow-down\"></i>" + result.data.activity_student1);
                        $("#imgdel2").removeClass("d-none");
                        $("#activity_student2").val(result.data.activity_student2);
                        $("#fileStudentActivityName2").val(result.data.activity_student2);
                    } else {
                        $("#exfile2").addClass("d-none");
                        $("#exfile2").attr("href", "");
                        $("#imgdel2").addClass("d-none");
                        $("#activity_student2").val('');
                        $("#fileStudentActivityName2").val('');
                    }

                    if (result.data.activity_teacher1) {
                        $("#exfile3").removeClass("d-none");
                        $("#exfile3").attr("href", "/files/activitypaper/" + result.data.activity_teacher1);
                        $("#exfile3").html("<i class=\"fa-solid fa-file-arrow-down\"></i>" + result.data.activity_teacher1);
                        $("#imgdel3").removeClass("d-none");
                        $("#activity_teacher1").val(result.data.activity_teacher1);
                        $("#fileTeacherActivityName1").val(result.data.activity_teacher1);
                    } else {
                        $("#exfile3").addClass("d-none");
                        $("#exfile3").attr("href", "");
                        $("#imgdel3").addClass("d-none");
                        $("#activity_teacher1").val('');
                        $("#fileTeacherActivityName1").val('');
                    }

                    if (result.data.activity_teacher2) {
                        $("#exfile4").removeClass("d-none");
                        $("#exfile4").attr("href", "/files/activitypaper/" + result.data.activity_teacher2);
                        $("#exfile4").html("<i class=\"fa-solid fa-file-arrow-down\"></i>" + result.data.activity_teacher2);
                        $("#imgdel4").removeClass("d-none");
                        $("#activity_teacher2").val(result.data.activity_teacher2);
                        $("#fileTeacherActivityName2").val(result.data.activity_teacher2);
                    } else {
                        $("#exfile4").addClass("d-none");
                        $("#exfile4").attr("href", "");
                        $("#imgdel4").addClass("d-none");
                        $("#activity_teacher2").val('');
                        $("#fileTeacherActivityName2").val('');
                    }
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
}

function activityListUpdate() {
    var action = '';
    var curriculum_idx = $("#curriculum_idx").val();
    var book_idx = $("#book_idx").val();
    var activitypaper_idx = $("#activitypaper_idx").val();

    if (!curriculum_idx) {
        alert('활동지를 선택 후 업로드해주세요.');
        return false;
    }

    datas = new FormData();
    datas.append('book_idx', book_idx);
    datas.append('activity_student1', $('#fileStudentActivity1')[0].files[0]);
    datas.append('activity_student2', $('#fileStudentActivity2')[0].files[0]);
    datas.append('activity_teacher1', $('#fileTeacherActivity1')[0].files[0]);
    datas.append('activity_teacher2', $('#fileTeacherActivity2')[0].files[0]);

    if (activitypaper_idx) {
        action = 'activityListUpdate';
        datas.append('activitypaper_idx', activitypaper_idx);

        datas.append('activity_student1_file', $("#activity_student1").val());
        datas.append('activity_student2_file', $("#activity_student2").val());
        datas.append('activity_teacher1_file', $("#activity_teacher1").val());
        datas.append('activity_teacher2_file', $("#activity_teacher2").val());
    } else {
        action = 'activityListInsert';
    }

    datas.append('action', action);

    $.ajax({
        url: '/adm/ajax/activityPaperControll.ajax.php',
        contentType: 'multipart/form-data',
        dataType: 'JSON',
        type: 'POST',
        mimeType: 'multipart/form-data',
        data: datas,
        cache: false,
        contentType: false,
        processData: false,
        success: function (result) {
            if (result.success) {
                alert(result.msg);
                location.reload();
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

